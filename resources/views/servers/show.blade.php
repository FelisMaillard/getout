@extends('layouts.app')

@section('title', $server->name)

@section('content')
<div class="min-h-screen bg-gray-950">
    <div class="flex h-[calc(100vh-4rem)]">
        <!-- Sidebar des channels -->
        <div class="hidden md:flex flex-col w-64 bg-black border-r border-gray-800">
            <!-- En-tête du serveur -->
            <div class="p-4 border-b border-gray-800">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-bold text-white truncate">{{ $server->name }}</h1>
                    @if(Gate::allows('update-server', $server))
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('servers.edit', $server) }}"
                               class="p-2 text-gray-400 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <button onclick="document.getElementById('invite-modal').classList.remove('hidden')"
                                    class="p-2 text-gray-400 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
                <p class="mt-1 text-sm text-gray-400">{{ $server->privacy_type === 'public' ? 'Public' : 'Privé' }} • {{ $members->count() }} membres</p>
            </div>

            <!-- Liste des channels -->
            <div class="flex-1 overflow-y-auto">
                <!-- Channels textuels -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase">Channels textuels</h2>
                        @if(Gate::allows('update-server', $server))
                            <button onclick="document.getElementById('create-channel-modal').classList.remove('hidden')"
                                    class="p-1 text-gray-400 hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        @endif
                    </div>
                    <div class="space-y-1">
                        @foreach($channels->where('type', 'text') as $channel)
                            <a href="{{ route('servers.channels.show', [$server, $channel]) }}"
                               class="flex items-center px-2 py-1 rounded hover:bg-gray-900 {{ request()->route('channel')?->id === $channel->id ? 'bg-gray-900' : '' }}">
                                <span class="text-gray-400 group-hover:text-white">#</span>
                                <span class="ml-2 text-gray-400 group-hover:text-white">{{ $channel->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Channels vocaux -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase">Channels vocaux</h2>
                    </div>
                    <div class="space-y-1">
                        @foreach($channels->where('type', 'voice') as $channel)
                            <a href="#" class="flex items-center px-2 py-1 rounded hover:bg-gray-900">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                </svg>
                                <span class="ml-2 text-gray-400">{{ $channel->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Zone principale -->
        <div class="flex-1 flex flex-col">
            @if(isset($channel))
                <!-- Contenu du channel -->
                @include('channels.show')
            @else
                <!-- Message de bienvenue -->
                <div class="flex-1 flex items-center justify-center">
                    <div class="text-center">
                        <h2 class="text-xl font-bold text-white mb-2">Bienvenue sur {{ $server->name }}</h2>
                        <p class="text-gray-400">Sélectionnez un channel pour commencer à discuter</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar des membres -->
        <div class="hidden lg:flex flex-col w-64 bg-black border-l border-gray-800">
            <div class="p-4 border-b border-gray-800">
                <h2 class="text-lg font-medium text-white">Membres</h2>
                <p class="text-sm text-gray-400">{{ $members->count() }} membres</p>
            </div>

            <div class="flex-1 overflow-y-auto p-4">
                <!-- En ligne -->
                <div class="mb-6">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase mb-2">En ligne</h3>
                    <div class="space-y-2">
                        @foreach($members->take(5) as $member)
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    @if($member->user->profile_photo_url)
                                        <img src="{{ Storage::url($member->user->profile_photo_url) }}"
                                             alt="{{ $member->user->name }}"
                                             class="w-8 h-8 rounded-full">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">
                                                {{ substr($member->user->prenom, 0, 1) . substr($member->user->nom, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-black rounded-full"></span>
                                </div>
                                <div>
                                    <p class="text-white text-sm font-medium">{{ $member->user->nom }}</p>
                                    <p class="text-gray-400 text-xs">{{ $member->role }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Hors ligne -->
                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase mb-2">Hors ligne</h3>
                    <div class="space-y-2">
                        @foreach($members->slice(5) as $member)
                            <div class="flex items-center space-x-3 opacity-50">
                                <div class="relative">
                                    @if($member->user->profile_photo_url)
                                        <img src="{{ Storage::url($member->user->profile_photo_url) }}"
                                             alt="{{ $member->user->name }}"
                                             class="w-8 h-8 rounded-full grayscale">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center">
                                            <span class="text-white text-sm font-medium">
                                                {{ substr($member->user->prenom, 0, 1) . substr($member->user->nom, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-white text-sm font-medium">{{ $member->user->nom }}</p>
                                    <p class="text-gray-400 text-xs">{{ $member->role }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'invitation -->
<div id="invite-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
    <div class="min-h-screen px-4 text-center">
        <div class="inline-block align-middle bg-black rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-black px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                            Inviter des membres
                        </h3>
                        <div class="mt-2">
                            <input type="text"
                                   class="w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                                   placeholder="Rechercher un utilisateur...">
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-black px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Inviter
                </button>
                <button type="button"
                        onclick="document.getElementById('invite-modal').classList.add('hidden')"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-800 shadow-sm px-4 py-2 bg-black text-base font-medium text-gray-400 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de création de channel -->
<div id="create-channel-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
    <div class="min-h-screen px-4 text-center">
        <div class="inline-block align-middle bg-black rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('servers.channels.store', $server) }}" method="POST">
                @csrf
                <div class="bg-black px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                                Créer un nouveau channel
                            </h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-white">
                                        Nom du channel
                                    </label>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           class="mt-1 w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                                           required>
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-white">
                                        Type de channel
                                    </label>
                                    <select name="type"
                                            id="type"
                                            class="mt-1 w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                                        <option value="text">Textuel</option>
                                        <option value="voice">Vocal</option>
                                        <option value="announcement">Annonces</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium text-white">
                                        Description
                                    </label>
                                    <textarea name="description"
                                              id="description"
                                              rows="3"
                                              class="mt-1 w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"></textarea>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox"
                                           name="is_private"
                                           id="is_private"
                                           class="h-4 w-4 text-purple-600 bg-gray-900 border-gray-800 rounded focus:ring-purple-600 focus:ring-offset-gray-900">
                                    <label for="is_private" class="ml-2 block text-sm text-white">
                                        Channel privé
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-black px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Créer
                    </button>
                    <button type="button"
                            onclick="document.getElementById('create-channel-modal').classList.add('hidden')"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-800 shadow-sm px-4 py-2 bg-black text-base font-medium text-gray-400 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
