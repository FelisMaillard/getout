<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Message $message): bool
    {
        // L'auteur du message peut le modifier
        if ($message->user_id === $user->id) {
            // Vérifie si le message n'a pas plus de 24h
            return $message->created_at->addHours(24)->isFuture();
        }

        // Les admins et modérateurs peuvent aussi modifier les messages
        $member = $message->channel->server->members()->where('user_id', $user->id)->first();
        return $member && in_array($member->role, ['owner', 'admin', 'moderator']);
    }

    public function delete(User $user, Message $message): bool
    {
        // L'auteur du message peut le supprimer
        if ($message->user_id === $user->id) {
            return true;
        }

        // Les admins et modérateurs peuvent aussi supprimer les messages
        $member = $message->channel->server->members()->where('user_id', $user->id)->first();
        return $member && in_array($member->role, ['owner', 'admin', 'moderator']);
    }
}
