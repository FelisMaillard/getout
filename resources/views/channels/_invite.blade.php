<!-- Modal d'invitation de membre -->
<div id="invite-member-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
    <div class="min-h-screen px-4 text-center">
        <div class="inline-block align-middle bg-black rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-black px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-white mb-4">Inviter un ami</h3>
                    <button
                        onclick="document.getElementById('invite-member-modal').classList.add('hidden')"
                        class="text-gray-400 hover:text-white"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Composant Livewire -->
                @livewire('server-invite-search', ['server' => $server])
            </div>

            <div class="bg-black px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button
                    onclick="document.getElementById('invite-member-modal').classList.add('hidden')"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-800 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>
