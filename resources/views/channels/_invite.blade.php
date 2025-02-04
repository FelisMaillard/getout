<!-- Modal d'invitation de membre -->
<div
    id="inviteModal"
    class="fixed inset-0 bg-gray-800 bg-opacity-50 z-50 hidden"
>
    <div class="min-h-screen px-4 text-center">
        <!-- Overlay pour fermer en cliquant en dehors -->
        <div
            class="fixed inset-0 transition-opacity"
            aria-hidden="true"
            onclick="toggleInviteModal(false)"
        >
            <div class="absolute inset-0 opacity-75"></div>
        </div>

        <!-- Modal -->
        <div
            class="inline-block align-middle bg-black rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
        >
            <div class="bg-black px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-white">Inviter un ami</h3>
                    <button
                        onclick="toggleInviteModal(false)"
                        class="text-gray-400 hover:text-white"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Composant Livewire -->
                @livewire('server-invite-search', ['server' => $server])
            </div>

            <div
                class="bg-black px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
            >
                <button
                    onclick="toggleInviteModal(false)"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-800 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Fonction pour ouvrir/fermer la modal
    function toggleInviteModal(show = true) {
        const modal = document.getElementById('inviteModal');
        if (show) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }

    // Fermer la modal en appuyant sur la touche "Échap"
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            toggleInviteModal(false);
        }
    });
</script>
