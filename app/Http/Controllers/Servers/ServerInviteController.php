<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\ServerInvite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ServerInviteController extends Controller
{
    /**
     * Recherche les amis pour les inviter
     */
    public function searchFriends(Request $request, Server $server)
    {
        $search = $request->input('search');

        return Auth::user()->friends()
            ->whereHas('friend', function($query) use ($search) {
                $query->where('tag', 'LIKE', "%{$search}%")
                      ->orWhere('nom', 'LIKE', "%{$search}%")
                      ->orWhere('prenom', 'LIKE', "%{$search}%");
            })
            ->whereDoesntHave('memberServers', function($query) use ($server) {
                $query->where('server_id', $server->id);
            })
            ->with('friend')
            ->get();
    }

    /**
     * Envoie une invitation à un utilisateur
     */
    public function store(Request $request, Server $server)
    {
        Gate::authorize('update-server', $server);

        $validated = $request->validate([
            'invitee_id' => ['required', 'exists:users,id'],
        ]);

        // Vérifier si l'utilisateur n'est pas déjà membre
        if ($server->members()->where('user_id', $validated['invitee_id'])->exists()) {
            return back()->with('error', 'Cet utilisateur est déjà membre du serveur.');
        }

        // Vérifier si une invitation en cours existe déjà
        $existingInvite = $server->invites()
            ->where('invitee_id', $validated['invitee_id'])
            ->whereNull('accepted_at')
            ->whereNull('rejected_at')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->exists();

        if ($existingInvite) {
            return back()->with('error', 'Une invitation est déjà en cours pour cet utilisateur.');
        }

        // Créer l'invitation
        $invite = $server->invites()->create([
            'inviter_id' => Auth::id(),
            'invitee_id' => $validated['invitee_id'],
            'expires_at' => now()->addDays(30), // Expire après 30 jours
        ]);

        return back()->with('success', 'Invitation envoyée avec succès.');
    }

    /**
     * Accepte une invitation
     */
    public function accept(Server $server, ServerInvite $invite)
    {
        if (Auth::id() !== $invite->invitee_id) {
            return back()->with('error', 'Cette invitation ne vous est pas destinée.');
        }

        if (!$invite->isPending()) {
            return back()->with('error', 'Cette invitation n\'est plus valide.');
        }

        // Accepter l'invitation
        $invite->accept();

        // Ajouter l'utilisateur comme membre
        $server->members()->create([
            'user_id' => Auth::id(),
            'role' => 'member',
            'privacy_consent' => true,
            'privacy_consent_date' => now(),
        ]);

        return redirect()
            ->route('servers.show', $server)
            ->with('success', 'Vous avez rejoint le serveur.');
    }

    /**
     * Refuse une invitation
     */
    public function reject(ServerInvite $invite)
    {
        if (Auth::id() !== $invite->invitee_id) {
            return back()->with('error', 'Cette invitation ne vous est pas destinée.');
        }

        if (!$invite->isPending()) {
            return back()->with('error', 'Cette invitation n\'est plus valide.');
        }

        $invite->reject();

        return back()->with('success', 'Invitation refusée.');
    }

    /**
     * Annule une invitation (pour l'inviteur)
     */
    public function cancel(Server $server, ServerInvite $invite)
    {
        Gate::authorize('update-server', $server);

        if (!$invite->isPending()) {
            return back()->with('error', 'Cette invitation n\'est plus valide.');
        }

        $invite->delete();

        return back()->with('success', 'Invitation annulée.');
    }
}
