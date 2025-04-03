<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Message;
use App\Models\Server;
use App\Models\Channel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Utilisateur connecté
        $user = Auth::user();

        // Statistiques de l'utilisateur
        $stats = [
            'following_count' => $user->followingCount(),
            'followers_count' => $user->followersCount(),
            'servers_count' => $user->memberServers()->count(),
            'messages_count' => Message::where('user_id', $user->id)->count()
        ];

        // Activité récente (derniers messages dans les channels)
        $recentActivity = Message::whereHas('channel.server.members', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['user', 'channel.server'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Serveurs récemment actifs
        $servers = Server::whereHas('members', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get();

        // Calculer l'activité pour chaque serveur manuellement
        $activeServers = $servers->map(function($server) {
            // Compter les messages récents à travers tous les channels du serveur
            $messageCount = 0;
            foreach ($server->channels as $channel) {
                $messageCount += $channel->messages()
                    ->where('created_at', '>', now()->subDays(7))
                    ->count();
            }

            // Ajouter le compteur comme propriété
            $server->messages_count = $messageCount;
            return $server;
        })
        ->sortByDesc('messages_count')
        ->take(5);

        // Suggestions d'amis (utilisateurs que l'utilisateur ne suit pas encore)
        $suggestedFriends = User::whereNotIn('id', function ($query) use ($user) {
                $query->select('friend_id')
                    ->from('users_relations')
                    ->where('user_id', $user->id);
            })
            ->where('id', '!=', $user->id)
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('pages.home', compact(
            'user',
            'stats',
            'recentActivity',
            'activeServers',
        ));
    }
}
