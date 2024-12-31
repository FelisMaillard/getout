<?php

namespace App\Policies;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChannelPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Channel $channel): bool
    {
        if ($channel->is_private) {
            return $channel->members()->where('user_id', $user->id)->exists();
        }

        return $channel->server->members()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Channel $channel): bool
    {
        $member = $channel->server->members()->where('user_id', $user->id)->first();
        return $member && in_array($member->role, ['owner', 'admin']);
    }

    public function delete(User $user, Channel $channel): bool
    {
        $member = $channel->server->members()->where('user_id', $user->id)->first();
        return $member && in_array($member->role, ['owner', 'admin']);
    }

    public function createMessage(User $user, Channel $channel): bool
    {
        // Vérifie si l'utilisateur est membre du serveur et si le channel n'est pas en lecture seule
        $member = $channel->server->members()->where('user_id', $user->id)->first();

        if (!$member) {
            return false;
        }

        if ($channel->type === 'announcement') {
            return in_array($member->role, ['owner', 'admin']);
        }

        return true;
    }
}
