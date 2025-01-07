@extends('layouts.server')

@section('content')
<div class="flex flex-col h-screen w-full">
    <!-- Header -->
    <div class="flex items-center px-4 py-3 bg-black border-b border-gray-800">
        <a href="{{ route('servers.index') }}"
           class="p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="ml-3 text-white font-medium flex-1">{{ $server->name }}</h1>
        <button onclick="toggleMembersList()"
                class="md:hidden p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </button>
    </div>

    <div class="flex flex-1 overflow-hidden">
        <!-- Liste des channels -->
        <div class="w-full md:w-64 bg-black border-r border-gray-800 flex flex-col overflow-hidden">
            <!-- Description du serveur (desktop) -->
            <div class="hidden md:block p-4 border-b border-gray-800">
                <p class="text-sm text-gray-400">{{ $server->description }}</p>
            </div>

            <!-- Liste des channels -->
            <div class="flex-1 overflow-y-auto p-2">
                <!-- Channels textuels -->
                <div class="mb-4">
                    <div class="flex items-center justify-between px-2 mb-2">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase">Channels textuels</h2>
                        @if(Gate::allows('update-server', $server))
                            <button onclick="document.getElementById('create-channel-modal').classList.remove('hidden')"
                                    class="p-1 text-gray-400 hover:text-white transition-colors rounded-full hover:bg-gray-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        @endif
                    </div>

                    <div class="space-y-0.5">
                        @foreach($channels->where('type', 'text') as $channel)
                            <a href="{{ route('servers.channels.show', [$server, $channel]) }}"
                               class="flex items-center px-2 py-1.5 rounded-md group hover:bg-gray-900">
                                <span class="text-gray-400 group-hover:text-white mr-2">#</span>
                                <span class="text-gray-400 group-hover:text-white truncate">{{ $channel->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Channels vocaux -->
                <div>
                    <div class="flex items-center justify-between px-2 mb-2">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase">Channels vocaux</h2>
                    </div>

                    <div class="space-y-0.5">
                        @foreach($channels->where('type', 'voice') as $channel)
                            <a href="#" class="flex items-center px-2 py-1.5 rounded-md group hover:bg-gray-900">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                </svg>
                                <span class="text-gray-400 group-hover:text-white truncate">{{ $channel->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Zone principale -->
        <div class="hidden md:flex flex-1 bg-black flex-col items-center justify-center text-gray-400">
            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <p>Sélectionnez un channel pour commencer à discuter</p>
        </div>

        <!-- Liste des membres -->
        <div id="members-sidebar"
             class="fixed inset-y-0 right-0 w-64 bg-black transform translate-x-full transition-transform duration-300 ease-in-out md:relative md:translate-x-0 border-l border-gray-800">
            <div class="p-4 border-b border-gray-800">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-white">Membres</h2>
                    <button onclick="toggleMembersList()" class="md:hidden p-2 text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <p class="text-sm text-gray-400">{{ $members->count() }} membres</p>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-2">
                @foreach($members as $member)
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
                        </div>
                        <div>
                            <p class="text-white text-sm font-medium">{{ $member->user->prenom }} {{ $member->user->nom }}</p>
                            <p class="text-gray-400 text-xs">{{ $member->role }}</p>
                        </div>
                    </div>
                @endforeach
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
                    <h3 class="text-lg font-medium text-white mb-4">Créer un nouveau channel</h3>

                    <div class="space-y-4">
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

@push('scripts')
<script>
function toggleMembersList() {
    const sidebar = document.getElementById('members-sidebar');
    sidebar.classList.toggle('translate-x-full');
}
</script>
@endpush
@endsection
