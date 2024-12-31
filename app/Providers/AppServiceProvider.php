<?php

namespace App\Providers;

use App\Models\Server;
use App\Models\Channel;
use App\Models\Message;
use App\Models\ServerInvite;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\Response;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // View composer existant
        View::composer('layouts.app', function ($view) {
            $user = Auth::user();
            if ($user) {
                $pendingRequests = $user->receivedRelations()
                    ->where('status', 'pending')
                    ->orderBy('created_at', 'desc')
                    ->get();

                $pendingRequestsCount = $pendingRequests->count();

                $view->with([
                    'user' => $user,
                    'pendingRequests' => $pendingRequests,
                    'pendingRequestsCount' => $pendingRequestsCount
                ]);
            }
        });

        // Serveur Gates
        Gate::define('view-server', function (User $user, Server $server) {
            if ($server->privacy_type === 'public') {
                return true;
            }
            return $server->members()->where('user_id', $user->id)->exists();
        });

        Gate::define('update-server', function (User $user, Server $server) {
            $member = $server->members()->where('user_id', $user->id)->first();
            return $member && in_array($member->role, ['owner', 'admin']);
        });

        Gate::define('delete-server', function (User $user, Server $server) {
            return $server->owner_id === $user->id;
        });

        Gate::define('create-server-member', function (User $user, Server $server) {
            if ($server->members()->count() >= $server->max_members) {
                return Response::deny('Le serveur a atteint sa limite de membres.');
            }

            $member = $server->members()->where('user_id', $user->id)->first();
            return $member && in_array($member->role, ['owner', 'admin']);
        });

        // Channel Gates
        Gate::define('view-channel', function (User $user, Channel $channel) {
            if ($channel->is_private) {
                return $channel->members()->where('user_id', $user->id)->exists();
            }
            return $channel->server->members()->where('user_id', $user->id)->exists();
        });

        Gate::define('update-channel', function (User $user, Channel $channel) {
            $member = $channel->server->members()->where('user_id', $user->id)->first();
            return $member && in_array($member->role, ['owner', 'admin']);
        });

        Gate::define('delete-channel', function (User $user, Channel $channel) {
            $member = $channel->server->members()->where('user_id', $user->id)->first();
            return $member && in_array($member->role, ['owner', 'admin']);
        });

        // Message Gates
        Gate::define('create-message', function (User $user, Channel $channel) {
            $member = $channel->server->members()->where('user_id', $user->id)->first();

            if (!$member) {
                return false;
            }

            if ($channel->type === 'announcement') {
                return in_array($member->role, ['owner', 'admin']);
            }

            return true;
        });

        Gate::define('update-message', function (User $user, Message $message) {
            if ($message->user_id === $user->id) {
                return $message->created_at->addHours(24)->isFuture();
            }

            $member = $message->channel->server->members()->where('user_id', $user->id)->first();
            return $member && in_array($member->role, ['owner', 'admin', 'moderator']);
        });

        Gate::define('delete-message', function (User $user, Message $message) {
            if ($message->user_id === $user->id) {
                return true;
            }

            $member = $message->channel->server->members()->where('user_id', $user->id)->first();
            return $member && in_array($member->role, ['owner', 'admin', 'moderator']);
        });

        // Server Invite Gates
        Gate::define('accept-invite', function (User $user, ServerInvite $invite) {
            return $invite->invitee_id === $user->id &&
                   $invite->isPending() &&
                   !$invite->isExpired();
        });

        Gate::define('reject-invite', function (User $user, ServerInvite $invite) {
            return $invite->invitee_id === $user->id &&
                   $invite->isPending() &&
                   !$invite->isExpired();
        });
    }
}
