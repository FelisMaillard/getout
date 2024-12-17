<x-app-layout>
    <h1>test</h1>
    <div class="max-w-2xl mx-auto p-4">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white mb-2">Rechercher des utilisateurs</h1>
            <p class="text-gray-400">Recherchez des utilisateurs par leur @tag ou leur nom</p>
        </div>

        <!-- Barre de recherche -->
        <div class="relative">
            <input
                type="text"
                id="search"
                class="w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent text-white"
                placeholder="Rechercher..."
                x-data
                x-on:input.debounce.300ms="handleSearch($el.value)"
            >
            <div
                id="results"
                class="absolute w-full mt-2 bg-gray-900 border border-gray-700 rounded-lg shadow-lg hidden"
            >
                <!-- Les résultats seront injectés ici -->
            </div>
        </div>

        <!-- Notice RGPD -->
        <p class="mt-4 text-sm text-gray-500">
            Les résultats montrent uniquement les utilisateurs qui ont choisi d'être trouvables dans la recherche.
            Vous pouvez gérer vos préférences de recherche dans vos paramètres de confidentialité.
        </p>
    </div>

    @push('scripts')
    <script>
        function handleSearch(query) {
            if (query.length < 3) {
                document.getElementById('results').innerHTML = '';
                document.getElementById('results').classList.add('hidden');
                return;
            }

            fetch(`/search?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    const results = document.getElementById('results');
                    if (data.results.length === 0) {
                        results.innerHTML = `
                            <div class="p-4 text-gray-400">
                                Aucun résultat trouvé
                            </div>
                        `;
                    } else {
                        results.innerHTML = data.results.map(user => `
                            <a href="/profile/${user.id}" class="flex items-center p-4 hover:bg-gray-800 transition">
                                <img src="${user.profile_photo_url || '/default-avatar.png'}" class="w-10 h-10 rounded-full">
                                <div class="ml-4">
                                    <div class="text-white">${user.prenom} ${user.nom}</div>
                                    <div class="text-gray-400">@${user.tag}</div>
                                </div>
                            </a>
                        `).join('');
                    }
                    results.classList.remove('hidden');
                });
        }
    </script>
    @endpush
</x-app-layout>
