<div>
    <div class="relative">
        <input
            wire:model.live="query"
            type="text"
            class="w-full px-6 py-4 bg-black border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent text-lg"
            placeholder="Rechercher un utilisateur..."
            autocomplete="off"
        >
        <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <div class="mt-6 space-y-4">
            @if($users->isEmpty())
                <p class="text-gray-400 text-center">Aucun résultat trouvé</p>
            @else
                @foreach($users as $user)
                    <div class="bg-black border border-gray-800 rounded-lg p-4 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($user->prenom, 0, 1) . substr($user->nom, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-white font-semibold">{{ $user->prenom }} {{ $user->nom }}</h3>
                                <p class="text-gray-400">{{ "@" . $user->tag }}</p>
                            </div>
                        </div>
                        <a href="#" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                            Voir le profil
                        </a>
                    </div>
                @endforeach
            @endif
    </div>
</div>
