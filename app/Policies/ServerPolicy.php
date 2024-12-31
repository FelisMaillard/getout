<?php
// app/Policies/ServerPolicy.php

namespace App\Policies;

use App\Models\Server;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // Tout utilisateur authentifié peut voir la liste des serveurs
    }

    public function view(User $user, Server $server): bool
    {
        if ($server->privacy_type === 'public') {
            return true;
        }

        return $server->members()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return true; // Tout utilisateur authentifié peut créer un serveur
    }

    public function update(User $user, Server $server): bool
    {
        $member = $server->members()->where('user_id', $user->id)->first();
        return $member && in_array($member->role, ['owner', 'admin']);
    }

    public function delete(User $user, Server $server): bool
    {
        return $server->owner_id === $user->id;
    }

    public function createMember(User $user, Server $server): bool
    {
        if ($server->members()->count() >= $server->max_members) {
            return false;
        }

        $member = $server->members()->where('user_id', $user->id)->first();
        return $member && in_array($member->role, ['owner', 'admin']);
    }

    public function createChannel(User $user, Server $server): bool
    {
        $member = $server->members()->where('user_id', $user->id)->first();
        return $member && in_array($member->role, ['owner', 'admin']);
    }

    public function createInvite(User $user, Server $server): bool
    {
        $member = $server->members()->where('user_id', $user->id)->first();
        return $member && in_array($member->role, ['owner', 'admin', 'moderator']);
    }
}
