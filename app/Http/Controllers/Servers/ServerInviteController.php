<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\ServerInvite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ServerInviteController extends Controller
{
    public function store(Request $request, Server $server)
    {
        Gate::authorize('update-server', $server);

        $validated = $request->validate([
            'invitee_id' => ['required', 'exists:users,id'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ]);

        $invite = $server->invites()->create([
            'inviter_id' => Auth::id(),
            'invitee_id' => $validated['invitee_id'],
            'expires_at' => $validated['expires_at'],
        ]);

        return back()->with('success', 'Invitation envoyée');
    }

    public function accept(ServerInvite $invite)
    {
        Gate::authorize('accept-invite', $invite);

        if ($invite->isPending()) {
            $invite->accept();

            // Ajouter l'utilisateur comme membre du serveur
            $invite->server->members()->create([
                'user_id' => Auth::id(),
                'role' => 'member',
                'privacy_consent' => true,
                'privacy_consent_date' => now(),
            ]);

            return redirect()
                ->route('servers.show', $invite->server)
                ->with('success', 'Invitation acceptée');
        }

        return back()->with('error', 'Cette invitation n\'est plus valide');
    }

    public function reject(ServerInvite $invite)
    {
        Gate::authorize('reject-invite', $invite);

        if ($invite->isPending()) {
            $invite->reject();
            return back()->with('success', 'Invitation refusée');
        }

        return back()->with('error', 'Cette invitation n\'est plus valide');
    }
}
