<?php

namespace App\Policies;

use App\Models\ServerInvite;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServerInvitePolicy
{
    use HandlesAuthorization;

    public function accept(User $user, ServerInvite $invite): bool
    {
        return $invite->invitee_id === $user->id &&
               $invite->isPending() &&
               !$invite->isExpired();
    }

    public function reject(User $user, ServerInvite $invite): bool
    {
        return $invite->invitee_id === $user->id &&
               $invite->isPending() &&
               !$invite->isExpired();
    }

    public function delete(User $user, ServerInvite $invite): bool
    {
        if ($invite->inviter_id === $user->id) {
            return true;
        }

        $member = $invite->server->members()->where('user_id', $user->id)->first();
        return $member && in_array($member->role, ['owner', 'admin']);
    }
}
