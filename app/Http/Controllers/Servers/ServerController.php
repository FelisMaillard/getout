<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ServerController extends Controller
{
    public function index()
    {
        $servers = Auth::user()
            ->memberServers()
            ->withCount('members')
            ->latest()
            ->paginate(10);

        return view('servers.index', compact('servers'));
    }

    public function create()
    {
        return view('servers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:servers,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'privacy_type' => ['required', 'in:public,private'],
            'max_members' => ['required', 'integer', 'min:2', 'max:1000'],
        ]);

        $server = Auth::user()->ownedServers()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'privacy_type' => $validated['privacy_type'],
            'max_members' => $validated['max_members'],
        ]);

        // Créer automatiquement un membre pour le propriétaire
        $server->members()->create([
            'user_id' => Auth::id(),
            'role' => 'owner',
            'privacy_consent' => true,
            'privacy_consent_date' => now(),
        ]);

        // Créer un channel général par défaut
        $server->channels()->create([
            'name' => 'general',
            'type' => 'text',
            'description' => 'Canal général',
        ]);

        return redirect()
            ->route('servers.show', $server)
            ->with('success', 'Serveur créé avec succès');
    }

    public function show(Server $server, Request $request)
    {
        Gate::authorize('view-server', $server);

        $channels = $server->channels()
            ->withCount('members')
            ->latest()
            ->get();

        $members = $server->members()
            ->with('user')
            ->get();

        $currentChannel = null;
        $messages = null;  // Initialisation de $messages

        if ($request->has('currentChannel')) {
            $currentChannel = $server->channels()->findOrFail($request->currentChannel);
            $messages = $currentChannel->messages()
                ->with('user')
                ->latest()
                ->paginate(50);
        }

        return view('servers.show', compact('server', 'channels', 'members', 'currentChannel', 'messages'));
    }

    public function edit(Server $server)
    {
        Gate::authorize('update-server', $server);

        return view('servers.edit', compact('server'));
    }

    public function update(Request $request, Server $server)
    {
        Gate::authorize('update-server', $server);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:servers,name,' . $server->id],
            'description' => ['nullable', 'string', 'max:1000'],
            'privacy_type' => ['required', 'in:public,private'],
            'max_members' => ['required', 'integer', 'min:2', 'max:1000'],
        ]);

        $server->update($validated);

        return redirect()
            ->route('servers.show', $server)
            ->with('success', 'Serveur mis à jour avec succès');
    }

    public function destroy(Server $server)
    {
        Gate::authorize('delete-server', $server);

        $server->delete();

        return redirect()
            ->route('servers.index')
            ->with('success', 'Serveur supprimé avec succès');
    }
}
