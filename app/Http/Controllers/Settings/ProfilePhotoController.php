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

        // Récupérer l'utilisateur
        $user = $request->user();

        // Supprimer l'ancienne photo si elle existe
        if ($user->profile_photo_url) {
            Storage::disk('public')->delete($user->profile_photo_url);
        }

        // Générer un nom unique pour le fichier
        $filename = 'profiles/' . $user->tag . Str::uuid() . '.' . $request->file('photo')->extension();

        // Sauvegarder l'image
        Storage::disk('public')->put($filename , $request->file('photo')->get());

        // Mettre à jour l'URL dans la base de données
        $user->profile_photo_url = $filename;
        $user->save();

        Log::info('Photo de profil mise à jour', [
            'user_id' => $user->id,
            'filename' => $filename
        ]);

        return back()->with('status', 'Photo de profil mise à jour avec succès.');
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        // Supprimer le fichier
        if ($user->profile_photo_url) {
            Storage::disk('public')->delete($user->profile_photo_url);
        }

        // Réinitialiser l'URL
        $user->profile_photo_url = null;
        $user->save();

        Log::info('Photo de profil supprimée', [
            'user_id' => $user->id
        ]);

        return back()->with('status', 'Photo de profil supprimée.');
    }
}
