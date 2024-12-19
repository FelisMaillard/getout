<?php

namespace App\Http\Controllers\Pages;

use App\Models\UserRelation;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserRelationController extends Controller
{
    public function sendRequest(Request $request, $userTag)
    {
        // Récupérer l'utilisateur par son tag
        $user = User::where('tag', $userTag)->firstOrFail();

        // Vérifier que l'utilisateur n'envoie pas une demande à lui-même
        $existingRelation = Auth::user()->sentRelations()
            ->where('friend_id', $user->id)
            ->first();

        if (!$existingRelation) {
            // Créer une nouvelle relation
            $relation = UserRelation::create([
                'user_id' => Auth::id(),
                'friend_id' => $user->id,
                'status' => 'pending',
                'privacy_consent' => true,
                'privacy_consent_date' => now()
            ]);

            return redirect()->back()->with('status', 'Demande d\'abonnement envoyée');
        } else if ($existingRelation->status === 'accepted') {
            // Supprimer la relation existante (unfollow)
            $existingRelation->delete();
            return redirect()->back()->with('status', 'Vous ne suivez plus cet utilisateur');
        }

        return redirect()->back()->with('status', 'Une demande est déjà en cours');
    }

    public function acceptRequest(UserRelation $relation, Request $request)
    {
        // Vérifier que l'utilisateur connecté est bien le destinataire
        if ($relation->friend_id !== Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        // Valider le consentement RGPD
        $request->validate([
            'privacy_consent' => 'required|boolean'
        ]);

        $relation->update([
            'status' => 'accepted',
            'privacy_consent' => $request->privacy_consent,
            'privacy_consent_date' => now()
        ]);

        return response()->json([
            'message' => 'Demande acceptée',
            'relation' => $relation
        ]);
    }

    public function blockUser(User $user)
    {
        $relation = UserRelation::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'friend_id' => $user->id
            ],
            [
                'status' => 'blocked'
            ]
        );

        if ($relation->status !== 'blocked') {
            $relation->update(['status' => 'blocked']);
        }

        return response()->json(['message' => 'Utilisateur bloqué']);
    }

    public function removeRelation(UserRelation $relation)
    {
        // Vérifier que l'utilisateur est autorisé
        if ($relation->user_id !== Auth::id() && $relation->friend_id !== Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $relation->delete();

        return response()->json(['message' => 'Relation supprimée']);
    }
}
