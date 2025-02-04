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
            'serverInvites' => $serverInvites
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
