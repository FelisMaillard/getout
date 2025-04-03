@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 md:pt-0">
        <h1 class="text-2xl font-bold text-white mb-8">Notifications</h1>

        <!-- Tableau unifié des notifications -->
        <div class="bg-black overflow-hidden border border-gray-800 rounded-lg">
            <div class="p-4 border-b border-gray-800">
                <h2 class="text-lg font-medium text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    Notifications
                </h2>
            </div>

            @if($pendingRequests->isEmpty() && $serverInvites->isEmpty())
                <div class="p-4 text-gray-400 text-center">
                    Aucune notification en attente
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Type</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">De</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Détails</th>
                                <th class="px-4 py-3 text-left text-sm font-medium text-gray-300">Date</th>
                                <th class="px-4 py-3 text-right text-sm font-medium text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            <!-- Demandes d'abonnement -->
                            @forelse($pendingRequests as $request)
                                <tr class="hover:bg-gray-900 transition-colors duration-200">
                                    <td class="px-4 py-4 text-sm text-white">
                                        <span class="px-2 py-1 bg-purple-600 bg-opacity-20 text-purple-400 rounded-full">
                                            Abonnement
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <a href="{{ route('profile.show', $request->user->tag) }}" class="flex items-center group">
                                            <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold mr-2">
                                                {{ substr($request->user->prenom, 0, 1) . substr($request->user->nom, 0, 1) }}
                                            </div>
                                            <span class="text-white group-hover:text-purple-400 transition">
                                                {{ $request->user->prenom }} {{ $request->user->nom }}
                                            </span>
                                        </a>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-300">
                                        Souhaite s'abonner à votre profil
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-400">
                                        {{ $request->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <form action="{{ route('relations.accept', $request->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-purple-600 text-white text-xs rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                                    Accepter
                                                </button>
                                            </form>
                                            <form action="{{ route('relations.remove', $request->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-1 bg-gray-800 text-white text-xs rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                                    Refuser
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <!-- Pas de demandes d'abonnement -->
                            @endforelse

                            <!-- Invitations aux serveurs -->
                            @forelse($serverInvites as $invite)
                                <tr class="hover:bg-gray-900 transition-colors duration-200">
                                    <td class="px-4 py-4 text-sm text-white">
                                        <span class="px-2 py-1 bg-blue-600 bg-opacity-20 text-blue-400 rounded-full">
                                            Serveur
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <a href="{{ route('profile.show', $invite->inviter->tag) }}" class="flex items-center group">
                                            <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold mr-2">
                                                {{ substr($invite->inviter->prenom, 0, 1) . substr($invite->inviter->nom, 0, 1) }}
                                            </div>
                                            <span class="text-white group-hover:text-purple-400 transition">
                                                {{ $invite->inviter->prenom }} {{ $invite->inviter->nom }}
                                            </span>
                                        </a>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-300">
                                        Vous invite à rejoindre <span class="font-medium text-white">{{ $invite->server->name }}</span>
                                        @if($invite->expires_at)
                                            <span class="block text-xs text-gray-500 mt-1">
                                                Expire {{ $invite->expires_at->diffForHumans() }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm text-gray-400">
                                        {{ $invite->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <form action="{{ route('servers.invites.accept', ['server' => $invite->server_id, 'invite' => $invite->id]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-purple-600 text-white text-xs rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                                    Rejoindre
                                                </button>
                                            </form>
                                            <form action="{{ route('servers.invites.reject', ['server' => $invite->server_id, 'invite' => $invite->id]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-gray-800 text-white text-xs rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                                    Refuser
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <!-- Pas d'invitations aux serveurs -->
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-4 border-t border-gray-800 flex justify-between items-center">
                    <div class="text-sm text-gray-400">
                        {{ ($pendingRequests->total() ?? 0) + ($serverInvites->total() ?? 0) }} notification(s) au total
                    </div>
                    <div class="flex space-x-4">
                        @if($pendingRequests->hasPages())
                            <div>{{ $pendingRequests->links() }}</div>
                        @endif
                        @if($serverInvites->hasPages())
                            <div>{{ $serverInvites->links() }}</div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
