<?php
// app/Events/MessageSent.php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $html;

    public function __construct(Message $message)
    {
        $this->message = $message;
        // On génère le HTML du message à partir de la vue partielle
        $this->html = view('messages.single', [
            'message' => $message,
            'server' => $message->channel->server,
            'channel' => $message->channel
        ])->render();
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel.' . $this->message->channel_id)
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->html
        ];
    }
}
