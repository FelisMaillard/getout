<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $canChangeTag = !$user->last_tag_change ||
            Carbon::parse($user->last_tag_change)->addMonth()->isPast();

        return view('profile.edit', [
            'user' => $user,
            'canChangeTag' => $canChangeTag,
            'nextTagChange' => $user->last_tag_change ?
                Carbon::parse($user->last_tag_change)->addMonth() :
                null
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($user->profile_photo_url) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->profile_photo_url));
            }

            // Générer un nom unique
            $filename = Str::uuid() . '.' . $request->file('photo')->getClientOriginalExtension();

            // Redimensionner et optimiser l'image
            $image = Image::make($request->file('photo'))
                ->fit(400, 400) // Taille fixe pour optimiser le stockage
                ->encode('jpg', 80); // Compression raisonnable

            // Sauvegarder
            Storage::disk('public')->put('profile-photos/' . $filename, $image);

            $validated['profile_photo_url'] = '/storage/profile-photos/' . $filename;
        }

        // On gère private séparément
        $user->private = (bool)$request->input('private', false);

        // Si le tag est changé, mettre à jour la date de dernier changement
        if (isset($validated['tag']) && $validated['tag'] !== $user->tag) {
            $user->last_tag_change = now();
        }

        // On enlève private des données validées car on l'a déjà géré
        unset($validated['private']);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);

        try {
            $user = $request->user();

            // Supprimer l'ancienne photo si elle existe
            if ($user->profile_photo_url) {
                if (file_exists(public_path($user->profile_photo_url))) {
                    unlink(public_path($user->profile_photo_url));
                }
            }

            $filename = Str::uuid() . '.' . $request->file('photo')->getClientOriginalExtension();

            // Créer le dossier s'il n'existe pas
            $uploadPath = public_path('profile-photos');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Sauvegarder directement dans public
            $request->file('photo')->move($uploadPath, $filename);

            // Mettre à jour l'URL dans la base de données
            $user->profile_photo_url = '/profile-photos/' . $filename;
            $user->save();

            return back()->with('status', 'Photo mise à jour avec succès');

        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'upload de la photo', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['photo' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }

    public function deletePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->profile_photo_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->profile_photo_url));
            $user->profile_photo_url = null;
            $user->save();

            return back()->with('status', 'photo-deleted');
        }

        return back();
    }
}
