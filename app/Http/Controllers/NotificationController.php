<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRelation;
use App\Models\ServerInvite;
use Illuminate\Support\Collection;

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
        // Récupération des demandes d'abonnement
        $pendingRequests = Auth::user()
            ->receivedRelations()
            ->with('user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Récupération des invitations aux serveurs
        $serverInvites = ServerInvite::where('invitee_id', Auth::id())
            ->whereNull('accepted_at')
            ->whereNull('rejected_at')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->with(['server', 'inviter'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Fusion des notifications dans une seule collection
        $allNotifications = new Collection();

        // Ajouter les demandes d'abonnement
        foreach ($pendingRequests as $request) {
            $allNotifications->push([
                'id' => 'friend_' . $request->id,
                'type' => 'friend_request',
                'data' => $request,
                'created_at' => $request->created_at,
                'sender' => $request->user,
                'content' => 'souhaite vous suivre'
            ]);
        }

        // Ajouter les invitations aux serveurs
        foreach ($serverInvites as $invite) {
            $allNotifications->push([
                'id' => 'server_' . $invite->id,
                'type' => 'server_invite',
                'data' => $invite,
                'created_at' => $invite->created_at,
                'sender' => $invite->inviter,
                'content' => 'vous invite à rejoindre ' . $invite->server->name
            ]);
        }

        // Trier toutes les notifications par date (les plus récentes d'abord)
        $sortedNotifications = $allNotifications->sortByDesc('created_at');

        // Compter les notifications non lues
        $notificationCount = $sortedNotifications->count();

        return view('notifications.index', [
            'notifications' => $sortedNotifications,
            'notificationCount' => $notificationCount,
            'pendingRequests' => $pendingRequests, // Gardons les données originales pour la rétrocompatibilité
            'serverInvites' => $serverInvites // Gardons les données originales pour la rétrocompatibilité
        ]);
    }

    /**
     * Marque une notification comme lue
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Request $request, $id)
    {
        $notification = UserRelation::findOrFail($id);

        if ($notification->friend_id !== Auth::id()) {
            return back()->with('error', 'Action non autorisée');
        }

        $notification->update(['read_at' => now()]);

        return back()->with('status', 'Notification marquée comme lue');
    }
}
