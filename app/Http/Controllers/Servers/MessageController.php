<?php

namespace App\Http\Controllers\Servers;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function store(Request $request, Channel $channel)
    {
        Gate::authorize('create-message', $channel);

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
            'type' => ['required', 'in:text,system,file'],
            'metadata' => ['nullable', 'array'],
        ]);

        $message = $channel->messages()->create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'type' => $validated['type'],
            'metadata' => $validated['metadata'],
        ]);

        // Déclencher l'événement
        broadcast(new MessageSent($message))->toOthers();

        if ($request->ajax()) {
            return response()->json([
                'message' => view('messages.single', [
                    'message' => $message,
                    'server' => $channel->server,
                    'channel' => $channel
                ])->render(),
            ]);
        }

        return back();
    }

    public function update(Request $request, Channel $channel, Message $message)
    {
        Gate::authorize('update-message', $message);

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $message->edit($validated['content']);

        if ($request->ajax()) {
            return response()->json([
                'message' => view('messages.single', compact('message'))->render(),
            ]);
        }

        return back()->with('success', 'Message mis à jour');
    }

    public function destroy(Channel $channel, Message $message)
    {
        Gate::authorize('delete-message', $message);

        $message->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Message supprimé');
    }
}
