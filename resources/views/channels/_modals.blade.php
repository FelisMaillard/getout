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

<!-- Modal de modification de channel -->
<div id="edit-channel-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
    <div class="min-h-screen px-4 text-center">
        <div class="inline-block align-middle bg-black rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('servers.channels.update', [$server, $channel]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-black px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-white mb-4">Modifier le channel</h3>

                    <div class="space-y-4">
                        <div>
                            <label for="edit_name" class="block text-sm font-medium text-white">
                                Nom du channel
                            </label>
                            <input type="text"
                                   name="name"
                                   id="edit_name"
                                   value="{{ $channel->name }}"
                                   class="mt-1 w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                                   required>
                        </div>

                        <div>
                            <label for="edit_description" class="block text-sm font-medium text-white">
                                Description
                            </label>
                            <textarea name="description"
                                     id="edit_description"
                                     rows="3"
                                     class="mt-1 w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">{{ $channel->description }}</textarea>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox"
                                   name="is_private"
                                   id="edit_is_private"
                                   {{ $channel->is_private ? 'checked' : '' }}
                                   class="h-4 w-4 text-purple-600 bg-gray-900 border-gray-800 rounded focus:ring-purple-600 focus:ring-offset-gray-900">
                            <label for="edit_is_private" class="ml-2 block text-sm text-white">
                                Channel privé
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-black px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Enregistrer
                    </button>
                    <button type="button"
                            onclick="document.getElementById('edit-channel-modal').classList.add('hidden')"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-800 shadow-sm px-4 py-2 bg-black text-base font-medium text-gray-400 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
