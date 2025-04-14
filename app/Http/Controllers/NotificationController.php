<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRelation;
use App\Models\ServerInvite;

class NotificationController extends Controller
{
    /**
     * Affiche la vue des notifications
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Demandes d'abonnement existantes
        $pendingRequests = Auth::user()
            ->receivedRelations()
            ->with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Nouvelles relations (followers) - ajout de cette partie
        $newFollowers = Auth::user()
            ->receivedRelations()
            ->with('user')
            ->where('status', 'accepted')
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Ajout des invitations aux serveurs
        $serverInvites = ServerInvite::where('invitee_id', Auth::id())
            ->whereNull('accepted_at')
            ->whereNull('rejected_at')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->with(['server', 'inviter'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('notifications.index', [
            'pendingRequests' => $pendingRequests,
            'pendingRequestsCount' => $pendingRequests->total(),
            'newFollowers' => $newFollowers,
            'newFollowersCount' => $newFollowers->total(),
            'serverInvites' => $serverInvites,
            'serverInvitesCount' => $serverInvites->total()
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        try {
            $notification = UserRelation::findOrFail($id);

            \Log::info('Tentative de marquer comme lu', [
                'notification_id' => $id,
                'user_id' => Auth::id(),
                'friend_id' => $notification->friend_id
            ]);

            if ($notification->friend_id !== Auth::id()) {
                \Log::warning('Tentative non autorisée de marquer une notification comme lue', [
                    'notification_id' => $id,
                    'user_id' => Auth::id()
                ]);
                return back()->with('error', 'Action non autorisée');
            }

            $notification->update(['read_at' => now()]);

            \Log::info('Notification marquée comme lue avec succès', [
                'notification_id' => $id
            ]);

            return back()->with('status', 'Notification marquée comme lue');
        } catch (\Exception $e) {
            \Log::error('Erreur lors du marquage comme lu', [
                'notification_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    /**
     * Marque tous les nouveaux followers comme lus
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllFollowersAsRead()
    {
        // Utilisation de la requête directe pour s'assurer que la mise à jour est exécutée
        Auth::user()
            ->receivedRelations()
            ->where('status', 'accepted')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Débug pour vérifier que des lignes ont été affectées
        $affectedRows = Auth::user()
            ->receivedRelations()
            ->where('status', 'accepted')
            ->whereNull('read_at')
            ->count();

        \Log::info('Marquage de tous les followers comme lus', ['affected_rows' => $affectedRows]);

        return back()->with('status', 'Toutes les notifications ont été marquées comme lues');
    }
}
