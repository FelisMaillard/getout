<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRelation;

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
        // Récupérer les demandes d'abonnement en attente
        $pendingRequests = Auth::user()
            ->receivedRelations()
            ->with('user') // Eager loading pour éviter N+1
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Pagination pour de meilleures performances

        // Rendre le nombre de pendingRequests accessible à l'application
        $pendingRequestsCount = $pendingRequests->total();

        return view('notifications.index', [
            'pendingRequests' => $pendingRequests,
            'pendingRequestsCount' => $pendingRequestsCount,
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
