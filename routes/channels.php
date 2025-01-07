<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('channel.{channelId}', function ($user, $channelId) {
    $channel = \App\Models\Channel::find($channelId);
    if (!$channel) return false;

    // Vérifie si l'utilisateur est membre du serveur
    return $channel->server->members()
        ->where('user_id', $user->id)
        ->exists();
});
