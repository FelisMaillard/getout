<div>
    <!-- Input de recherche -->
    <div class="relative mb-4">
        <input
            wire:model.live.debounce.300ms="search"
            type="text"
            placeholder="Rechercher un ami par tag ou nom..."
            class="w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
        >
        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
            @if($search !== '')
                <button wire:click="$set('search', '')" class="text-gray-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            @else
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            @endif
        </div>
    </div>

    <!-- Résultats de recherche -->
    <div class="space-y-2 max-h-60 overflow-y-auto">
        @forelse($friends as $friendship)
            <div class="flex items-center justify-between p-2 hover:bg-gray-900 rounded-lg transition duration-150">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center">
                        @if($friendship->friend->profile_photo_url)
                            <img src="{{ Storage::url($friendship->friend->profile_photo_url) }}"
                                 alt="{{ $friendship->friend->prenom }}"
                                 class="w-10 h-10 rounded-full object-cover">
                        @else
                            <span class="text-white font-medium">
                                {{ substr($friendship->friend->prenom, 0, 1) }}{{ substr($friendship->friend->nom, 0, 1) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <p class="text-white font-medium">
                            {{ $friendship->friend->prenom }} {{ $friendship->friend->nom }}
                        </p>
                        <p class="text-gray-400 text-sm">
                            @{{ $friendship->friend->tag }}
                        </p>
                    </div>
                </div>
                <button
                    wire:click="invite({{ $friendship->friend->id }})"
                    class="px-3 py-1 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors duration-200"
                >
                    Inviter
                </button>
            </div>
        @empty
            @if(strlen($search) >= 0)
                <div class="text-center py-4 text-gray-400">
                    Aucun ami trouvé
                </div>
            @else
                <div class="text-center py-4 text-gray-400">
                    Commencez à taper pour rechercher vos amis
                </div>
            @endif
        @endforelse
    </div>

    <!-- Notification de succès -->
    <div
        x-data="{ show: false }"
        x-on:invite-sent.window="show = true; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition
        class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg"
        style="display: none;"
    >
        Invitation envoyée avec succès
    </div>
</div>
