@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 md:pt-0">
        <h1 class="text-2xl font-bold text-white mb-8">Notifications</h1>

        <!-- Tableau unifié des notifications -->
        <div class="bg-black overflow-hidden border border-gray-800 rounded-lg">
            <div class="p-4 border-b border-gray-800">
                <h2 class="text-lg font-medium text-white">Toutes vos notifications</h2>
            </div>

            @if($notifications->isEmpty())
                <div class="p-8 text-center">
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <p class="text-gray-400">Aucune notification à afficher</p>
                    </div>
                </div>
            @else
                <div class="divide-y divide-gray-800">
                    @foreach($notifications as $notification)
                        <div class="p-4 hover:bg-gray-900 transition-colors duration-200">
                            <div class="flex items-start">
                                <!-- Avatar -->
                                <div class="flex-shrink-0 mr-4">
                                    @if($notification['sender']->profile_photo_url)
                                        <img src="{{ Storage::url($notification['sender']->profile_photo_url) }}"
                                            alt="{{ $notification['sender']->prenom }} {{ $notification['sender']->nom }}"
                                            class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-purple-600 flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">
                                                {{ substr($notification['sender']->prenom, 0, 1) . substr($notification['sender']->nom, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Contenu de la notification -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between">
                                        <p class="text-white font-medium">
                                            {{ $notification['sender']->prenom }} {{ $notification['sender']->nom }}
                                            <span class="text-gray-400 font-normal">{{ $notification['content'] }}</span>
                                        </p>
                                        <span class="text-xs text-gray-500">
                                            {{ $notification['created_at']->diffForHumans() }}
                                        </span>
                                    </div>

                                    @if($notification['type'] === 'server_invite')
                                        <p class="text-sm text-gray-400 mt-1">
                                            Serveur: <span class="text-purple-400">{{ $notification['data']->server->name }}</span>
                                        </p>
                                    @endif

                                    <!-- Actions -->
                                    <div class="mt-3 flex">
                                        @if($notification['type'] === 'friend_request')
                                            <form action="{{ route('relations.accept', $notification['data']->id) }}" method="POST" class="mr-2">
                                                @csrf
                                                <button type="submit"
                                                        class="px-3 py-1 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                                    Accepter
                                                </button>
                                            </form>

                                            <form action="{{ route('relations.remove', $notification['data']->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                                    Refuser
                                                </button>
                                            </form>
                                        @elseif($notification['type'] === 'server_invite')
                                            <form action="{{ route('servers.invites.accept', ['server' => $notification['data']->server_id, 'invite' => $notification['data']->id]) }}" method="POST" class="mr-2">
                                                @csrf
                                                <button type="submit"
                                                        class="px-3 py-1 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                                    Rejoindre
                                                </button>
                                            </form>

                                            <form action="{{ route('servers.invites.reject', ['server' => $notification['data']->server_id, 'invite' => $notification['data']->id]) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                        class="px-3 py-1 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                                    Refuser
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
