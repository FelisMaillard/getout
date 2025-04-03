@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 md:pt-0">
        <h1 class="text-2xl font-bold text-white mb-8">Notifications</h1>

        <!-- Tableau unifié des notifications -->
        <div class="bg-black overflow-hidden border border-gray-800 rounded-t-lg">
            <div class="p-4 border-b border-gray-800">
                <h2 class="text-lg font-medium text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    Notifications
                </h2>
            </div>
        </div>

        <div class="bg-gray-950 overflow-hidden border border-gray-800 rounded-b-lg">
            <div class="p-4 border-b border-gray-800">

                @if($serverInvites->isEmpty() && $newFollowers->isEmpty() && $pendingRequests->isEmpty())
                <div class="p-4 text-gray-400 text-center">
                    Aucune notifications
                </div>
                @endif


                @if($newFollowers->isNotEmpty())
                    <form action="{{ route('notifications.followers.read') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-purple-400 hover:text-purple-300">
                            Tout marquer comme lu
                        </button>
                    </form>
                @endif

                @if($newFollowers->isNotEmpty())
                    <div class="divide-y divide-gray-800">
                        @foreach($newFollowers as $follower)
                            <div class="p-4 hover:bg-gray-900 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <!-- Info utilisateur -->
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ route('profile.show', $follower->user->tag) }}"
                                        class="flex items-center space-x-4 group">
                                            <!-- Avatar -->
                                            @if($follower->user->profile_photo_url)
                                                <img src="{{ Storage::url($follower->user->profile_photo_url) }}"
                                                    alt="{{ $follower->user->prenom }}"
                                                    class="w-12 h-12 rounded-full object-cover">
                                            @else
                                                <div class="w-12 h-12 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold">
                                                    {{ substr($follower->user->prenom, 0, 1) . substr($follower->user->nom, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-white font-medium group-hover:text-purple-400 transition">
                                                    {{ $follower->user->prenom }} {{ $follower->user->nom }}
                                                </p>
                                                <p class="text-gray-400 text-sm">{{ '@' . $follower->user->tag }}</p>
                                                <p class="text-gray-500 text-xs mt-1">
                                                    {{ $follower->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2">
                                        <form action="{{ route('notifications.markAsRead', $follower->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                                Marquer comme lu
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($pendingRequests->isNotEmpty())
                    <div class="divide-y divide-gray-800">
                        @foreach($pendingRequests as $request)
                            <div class="p-4 hover:bg-gray-900 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <!-- Info utilisateur -->
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ route('profile.show', $request->user->tag) }}"
                                        class="flex items-center space-x-4 group">
                                            <!-- Avatar -->
                                            <div class="w-12 h-12 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold">
                                                {{ substr($request->user->prenom, 0, 1) . substr($request->user->nom, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-white font-medium group-hover:text-purple-400 transition">
                                                    {{ $request->user->prenom }} {{ $request->user->nom }}
                                                </p>
                                                <p class="text-gray-400 text-sm">{{ '@' . $request->user->tag }}</p>
                                                <p class="text-gray-500 text-xs mt-1">
                                                    {{ $request->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </a>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2">
                                        <form action="{{ route('relations.accept', $request->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                                Accepter
                                            </button>
                                        </form>

                                        <form action="{{ route('relations.remove', $request->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                                Refuser
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($serverInvites->isNotEmpty())
                    <div class="divide-y divide-gray-800">
                        @foreach($serverInvites as $invite)
                            <div class="p-4 hover:bg-gray-900 transition-colors duration-200">
                                <div class="flex items-center justify-between">
                                    <!-- Info du serveur -->
                                    <div class="flex-1">
                                        <h3 class="text-white font-medium">{{ $invite->server->name }}</h3>
                                        <p class="text-gray-400 text-sm">
                                            Invité par {{ $invite->inviter->prenom }} {{ $invite->inviter->nom }}
                                        </p>
                                        @if($invite->expires_at)
                                            <p class="text-gray-500 text-xs mt-1">
                                                Expire {{ $invite->expires_at->diffForHumans() }}
                                            </p>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2">
                                        <form action="{{ route('servers.invites.accept', ['server' => $invite->server_id, 'invite' => $invite->id]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                                Rejoindre
                                            </button>
                                        </form>

                                        <form action="{{ route('servers.invites.reject', ['server' => $invite->server_id, 'invite' => $invite->id]) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                                Refuser
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
