<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\ServerMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ServerMemberController extends Controller
{
    public function store(Request $request, Server $server)
    {
        Gate::authorize('create-server-member', $server);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['required', 'in:admin,moderator,member'],
        ]);

        // Vérifier si l'utilisateur n'est pas déjà membre
        $user = User::findOrFail($validated['user_id']);
        if ($server->isMember($user)) {
            return back()->with('error', 'Cet utilisateur est déjà membre du serveur.');
        }

        $server->members()->create([
            'user_id' => $validated['user_id'],
            'role' => $validated['role'],
            'privacy_consent' => true,
            'privacy_consent_date' => now(),
        ]);

        return back()->with('success', 'Membre ajouté avec succès');
    }

    public function update(Request $request, Server $server, ServerMember $member)
    {
        Gate::authorize('update-server', $server);

        $validated = $request->validate([
            'role' => ['required', 'in:admin,moderator,member'],
        ]);

        $member->update($validated);

        return back()->with('success', 'Rôle mis à jour avec succès');
    }

    public function destroy(Server $server, ServerMember $member)
    {
        Gate::authorize('update-server', $server);

        $member->delete();

        return back()->with('success', 'Membre retiré avec succès');
    }
}
