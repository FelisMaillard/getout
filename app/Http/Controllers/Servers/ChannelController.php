<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Models\Server;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ChannelController extends Controller
{
    public function store(Request $request, Server $server)
    {
        Gate::authorize('update-server', $server);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'type' => ['required', 'in:text,voice,announcement'],
            'is_private' => ['boolean'],
        ]);

        $channel = $server->channels()->create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
            'type' => $validated['type'],
            'is_private' => $validated['is_private'] ?? false,
        ]);

        return back()->with('success', 'Canal créé avec succès');
    }

    public function show(Server $server, Channel $channel)
    {
        Gate::authorize('view-channel', $channel);

        $messages = $channel->messages()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        if (request()->ajax()) {
            $view = view('channels._messages', compact('server', 'channel', 'messages'))->render();
            return response()->json(['html' => $view]);
        }

        return view('servers.show', compact('server', 'channel', 'messages'));
    }

    public function update(Request $request, Server $server, Channel $channel)
    {
        Gate::authorize('update-channel', $channel);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_private' => ['boolean'],
        ]);

        $channel->update($validated);

        return back()->with('success', 'Canal mis à jour avec succès');
    }

    public function destroy(Server $server, Channel $channel)
    {
        Gate::authorize('delete-channel', $channel);

        $channel->delete();

        return redirect()
            ->route('servers.show', $server)
            ->with('success', 'Canal supprimé avec succès');
    }
}
