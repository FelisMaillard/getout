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

        // Vérifier s'il existe déjà une relation (incluant les soft deleted)
        $existingRelation = Auth::user()->sentRelations()
            ->withTrashed()  // Ajouter cette ligne pour inclure les relations soft deleted
            ->where('friend_id', $user->id)
            ->first();

        // Vérifier que l'utilisateur n'envoie pas une demande à lui-même
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous abonner à vous-même.');
        }

        // Si aucune relation n'existe (même soft deleted)
        if (!$existingRelation) {
            // Vérifier si l'utilisateur a un compte privé
            if (!$user->private) {
                // Créer une relation acceptée directement
                UserRelation::create([
                    'user_id' => Auth::id(),
                    'friend_id' => $user->id,
                    'status' => 'accepted',
                    'privacy_consent' => true,
                    'privacy_consent_date' => now()
                ]);

                return redirect()->back()->with('status', 'Vous suivez maintenant cet utilisateur');
            }

            // Créer une nouvelle relation dans le cas contraire
            UserRelation::create([
                'user_id' => Auth::id(),
                'friend_id' => $user->id,
                'status' => 'pending',
                'privacy_consent' => true,
                'privacy_consent_date' => now()
            ]);

            return redirect()->back()->with('status', 'Demande d\'abonnement envoyée');
        }
        // Si la relation existe et n'est pas soft deleted
        else if (!$existingRelation->trashed() && $existingRelation->status === 'accepted') {
            // Supprimer la relation existante (unfollow)
            $existingRelation->delete();
            return redirect()->back()->with('status', 'Vous ne suivez plus cet utilisateur');
        }
        // Si la relation existe mais est soft deleted
        else if ($existingRelation->trashed()) {
            // Restaurer la relation
            $existingRelation->restore();
            // Mettre à jour son statut
            $existingRelation->update([
                'status' => !$user->private ? 'accepted' : 'pending',
                'privacy_consent' => true,
                'privacy_consent_date' => now()
            ]);

            $message = !$user->private ?
                'Vous suivez maintenant cet utilisateur' :
                'Demande d\'abonnement envoyée';

            return redirect()->back()->with('status', $message);
        }

        return redirect()->back()->with('status', 'Une demande est déjà en cours');
    }

    /**
     * Accepte une demande d'abonnement
     *
     * @param UserRelation $relation La relation à accepter
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptRequest(UserRelation $relation)
    {
        // Vérifier que l'utilisateur connecté est bien le destinataire
        if ($relation->friend_id !== Auth::id()) {
            return back()->with('error', 'Vous n\'êtes pas autorisé à effectuer cette action.');
        }

        try {
            // Mettre à jour le statut de la relation
            $relation->update([
                'status' => 'accepted',
                'privacy_consent' => true, // L'utilisateur accepte le partage de données
                'privacy_consent_date' => now() // Date du consentement RGPD
            ]);

            return back()->with('status', 'Demande d\'abonnement acceptée');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de l\'acceptation de la demande.');
        }
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

        // Supprimer la relation
        $relation->delete();

        return response()->json(['message' => 'Relation supprimée']);
    }
}
