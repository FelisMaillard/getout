<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProfilePhotoController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,webp|max:2048'
        ]);

        $user = $request->user();

        // Approche plus directe pour l'upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            // Nettoyage de l'ancienne photo
            if ($user->profile_photo_url && file_exists(public_path('uploads/' . $user->profile_photo_url))) {
                unlink(public_path('uploads/' . $user->profile_photo_url));
            }

            // Création du dossier si nécessaire
            if (!file_exists(public_path('uploads/profiles'))) {
                mkdir(public_path('uploads/profiles'), 0755, true);
            }

            // Génération d'un nom de fichier unique
            $filename = 'profiles/' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Déplacement du fichier directement (sans Storage)
            $file->move(public_path('uploads'), $filename);

            // Mise à jour en base de données
            $user->profile_photo_url = $filename;
            $user->save();

            // Log pour le débogage
            Log::info('Photo de profil mise à jour avec la méthode alternative', [
                'user_id' => $user->id,
                'filename' => $filename,
                'path' => public_path('uploads/' . $filename)
            ]);
        }

        return back()->with('status', 'Photo de profil mise à jour avec succès.');
    }

    public function destroy(Request $request)
    {
        // Récupérer l'utilisateur
        $user = $request->user();

        // Supprimer l'ancienne photo si elle existe
        if ($user->profile_photo_url) {
            Storage::disk('public')->delete($user->profile_photo_url);
        }

        // Mettre à jour l'URL dans la base de données
        $user->profile_photo_url = null;
        $user->save();

        Log::info('Photo de profil supprimée', [
            'user_id' => $user->id
        ]);

        return back()->with('status', 'Photo de profil supprimée.');
    }
}
