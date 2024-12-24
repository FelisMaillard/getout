<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Affiche le profil public d'un utilisateur
     */
    public function show(Request $request, $tag)
    {
        // Récupérer l'utilisateur par son tag
        $user = User::where('tag', $tag)->firstOrFail();

        // Vérifier si c'est le profil de l'utilisateur connecté
        $isOwnProfile = Auth::id() === $user->id;

        // Récupérer la relation existante pour l'utilisateur cible
        $existingRelation = null;
        if (!$isOwnProfile) {
            $existingRelation = Auth::user()->sentRelations()
                ->withTrashed()
                ->where('friend_id', $user->id)
                ->first();
        }

        return view('profile.show', [
            'user' => $user,
            'isOwnProfile' => $isOwnProfile,
            'existingRelation' => $existingRelation
        ]);
    }
}
