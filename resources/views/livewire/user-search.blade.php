<div>
    <!-- Barre de recherche -->
    <div class="relative">
        <input
            wire:model.live="query"
            type="text"
            class="w-full px-6 py-4 bg-black border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent text-lg"
            placeholder="Rechercher un utilisateur..."
            value="{{ old('query', session('query', '')) }}"
        >
        <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Liste des résultats -->
    <div class="mt-6">
        @if($users->isEmpty())
            <div class="text-center space-y-4">
                <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-400">Aucun résultat trouvé</p>
            </div>
        @else
            @foreach($users as $user)
                <div class="bg-black border border-gray-800 hover:bg-gray-950 transition-colors duration-200
                    @if($loop->first) rounded-t-lg border-b-0
                    @elseif($loop->last) rounded-b-lg border-t-0
                    @else border-y-0
                    @endif">
                    <div class="flex items-center justify-between p-4">
                        <!-- Information utilisateur -->
                        <a href="{{ "@" . $user->tag }}" class="flex items-center space-x-4 flex-1">
                            <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($user->prenom, 0, 1) . substr($user->nom, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-white font-semibold">{{ $user->prenom }} {{ $user->nom }}</h3>
                                <p class="text-gray-400">{{ "@" . $user->tag }}</p>
                            </div>
                        </a>

                        <!-- Bouton de relation -->
                        <div>
                            @include('components.relation-button', ['targetUser' => $user])
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
