<div class="flex-1 flex flex-col bg-gray-950">
    {{-- En-tête du channel --}}
    <div class="flex items-center px-4 py-3 bg-black border-b border-gray-800">
        <div class="flex-1">
            <div class="flex items-center">
                <span class="text-gray-400 mr-2">#</span>
                <h2 class="text-lg font-medium text-white">{{ $channel->name }}</h2>
            </div>
            @if($channel->description)
                <p class="text-sm text-gray-400">{{ $channel->description }}</p>
            @endif
        </div>

        @if(Gate::allows('update-channel', $channel))
            <div class="flex items-center space-x-2">
                <button onclick="document.getElementById('edit-channel-modal').classList.remove('hidden')"
                        class="p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>
                <form action="{{ route('servers.channels.destroy', [$server, $channel]) }}"
                      method="POST"
                      class="inline"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce channel ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="p-2 text-red-400 hover:text-red-500 transition-colors rounded-lg hover:bg-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        @endif
    </div>

    {{-- Liste des messages --}}
    <div class="flex-1 overflow-y-auto px-4 py-6 space-y-6">
        @foreach($messages as $message)
            <div class="flex items-start group" id="message-{{ $message->id }}">
                {{-- Avatar --}}
                <div class="flex-shrink-0 mr-4">
                    @if($message->user->profile_photo_url)
                        <img src="{{ Storage::url($message->user->profile_photo_url) }}"
                             alt="Photo de profil de {{ $message->user->name }}"
                             class="w-10 h-10 rounded-full">
                    @else
                        <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center">
                            <span class="text-white text-sm font-medium">
                                {{ substr($message->user->prenom, 0, 1) . substr($message->user->nom, 0, 1) }}
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Contenu --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        <h4 class="text-white font-medium">{{ $message->user->nom }}</h4>
                        <span class="ml-2 text-xs text-gray-400">
                            {{ $message->created_at->format('d/m/Y H:i') }}
                        </span>
                        @if($message->edited_at)
                            <span class="ml-2 text-xs text-gray-500">(modifié)</span>
                        @endif
                    </div>

                    <div class="mt-1 text-gray-300 break-words">
                        @if($message->type === 'text')
                            {{ $message->content }}
                        @elseif($message->type === 'system')
                            <em class="text-gray-400">{{ $message->content }}</em>
                        @elseif($message->type === 'file')
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <a href="#" class="text-purple-400 hover:text-purple-300 truncate">
                                    {{ $message->metadata['filename'] ?? 'Fichier' }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                @if(Gate::allows('update-message', $message))
                    <div class="flex-shrink-0 ml-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <div class="flex space-x-2">
                            <button onclick="editMessage('{{ $message->id }}', '{{ $message->content }}')"
                                    class="p-1 text-gray-400 hover:text-white transition-colors rounded hover:bg-gray-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                            <form action="{{ route('servers.channels.messages.delete', [$server, $channel, $message]) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="p-1 text-red-400 hover:text-red-500 transition-colors rounded hover:bg-gray-800">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach

        {{-- Indicateur de chargement pour les nouveaux messages --}}
        <div id="message-end" class="h-4"></div>
    </div>

    {{-- Zone de saisie --}}
    <div class="px-4 py-4 bg-black border-t border-gray-800">
        <form action="{{ route('servers.channels.messages.store', [$server, $channel]) }}"
              method="POST"
              class="flex space-x-4"
              id="message-form">
            @csrf
            <div class="flex-1">
                <input type="text"
                       name="content"
                       id="message-input"
                       class="w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                       placeholder="Envoyer un message..."
                       required
                       autocomplete="off">
                <input type="hidden" name="type" value="text">
            </div>

            <div class="flex-shrink-0 flex space-x-2">
                {{-- Upload de fichier --}}
                <label class="p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-800 cursor-pointer">
                    <input type="file" class="hidden" id="file-upload">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </label>

                {{-- Envoi --}}
                <button type="submit"
                        class="p-2 text-purple-400 hover:text-purple-300 transition-colors rounded-lg hover:bg-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal d'édition du channel --}}
<div id="edit-channel-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
    <div class="min-h-screen px-4 text-center">
        <div class="inline-block align-middle bg-black rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('servers.channels.update', [$server, $channel]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-black px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-white" id="modal-title">
                                Modifier le channel
                            </h3>
                            <div class="mt-4 space-y-4">
                                {{-- Nom --}}
                                <div>
                                    <label for="edit-name" class="block text-sm font-medium text-white">
                                        Nom du channel
                                    </label>
                                    <input type="text"
                                           name="name"
                                           id="edit-name"
                                           value="{{ $channel->name }}"
                                           class="mt-1 w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                                           required>
                                </div>

                                {{-- Description --}}
                                <div>
                                    <label for="edit-description" class="block text-sm font-medium text-white">
                                        Description
                                    </label>
                                    <textarea name="description"
                                              id="edit-description"
                                              rows="3"
                                              class="mt-1 w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">{{ $channel->description }}</textarea>
                                </div>

                                {{-- Privé --}}
                                <div class="flex items-center">
                                    <input type="checkbox"
                                           name="is_private"
                                           id="edit-is-private"
                                           {{ $channel->is_private ? 'checked' : '' }}
                                           class="h-4 w-4 text-purple-600 bg-gray-900 border-gray-800 rounded focus:ring-purple-600 focus:ring-offset-gray-900">
                                    <label for="edit-is-private" class="ml-2 block text-sm text-white">
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

{{-- JavaScript pour la gestion des messages --}}
@push('scripts')
<script>
    // Fonction pour faire défiler vers le bas
    function scrollToBottom() {
        const messageEnd = document.getElementById('message-end');
        if (messageEnd) {
            messageEnd.scrollIntoView({ behavior: 'smooth' });
        }
    }

    // Fonction pour éditer un message
    function editMessage(messageId, content) {
        const messageInput = document.getElementById('message-input');
        const messageForm = document.getElementById('message-form');

        messageInput.value = content;
        messageInput.focus();

        // Changer l'action du formulaire pour une mise à jour
        messageForm.action = `{{ route('servers.channels.messages.store', [$server, $channel]) }}/${messageId}`;
        messageForm.method = 'POST';

        // Ajouter la méthode PUT
        let methodInput = messageForm.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            messageForm.appendChild(methodInput);
        }
        methodInput.value = 'PUT';
    }

    // Gestionnaire de soumission du formulaire
    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        fetch(form.action, {
            method: form.method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            }
        })
        .then(response => response.json())
        .then(data => {
            // Réinitialiser le formulaire
            form.reset();
            form.action = `{{ route('servers.channels.messages.store', [$server, $channel]) }}`;
            form.method = 'POST';

            // Supprimer la méthode PUT si elle existe
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) {
                methodInput.remove();
            }

            // Ajouter le nouveau message à la fin
            const messagesContainer = document.getElementById('message-end').parentElement;
            messagesContainer.insertAdjacentHTML('beforeend', data.message);

            // Faire défiler vers le bas
            scrollToBottom();
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    });

    // Gestionnaire d'upload de fichiers
    document.getElementById('file-upload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', 'file');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        fetch(`{{ route('servers.channels.messages.store', [$server, $channel]) }}`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Ajouter le nouveau message à la fin
            const messagesContainer = document.getElementById('message-end').parentElement;
            messagesContainer.insertAdjacentHTML('beforeend', data.message);

            // Faire défiler vers le bas
            scrollToBottom();
        })
        .catch(error => {
            console.error('Erreur:', error);
        });

        // Réinitialiser l'input file
        e.target.value = '';
    });

    // Faire défiler vers le bas au chargement
    document.addEventListener('DOMContentLoaded', scrollToBottom);

    // Écouter les événements de Pusher pour les nouveaux messages
    window.Echo?.private('channel.{{ $channel->id }}')
        .listen('MessageSent', (e) => {
            const messagesContainer = document.getElementById('message-end').parentElement;
            messagesContainer.insertAdjacentHTML('beforeend', e.message);
            scrollToBottom();
        });
</script>
@endpush

{{-- Vue partielle pour un seul message (utilisée pour l'AJAX) --}}
{{-- resources/views/messages/single.blade.php --}}
<div class="flex items-start group" id="message-{{ $message->id }}">
    {{-- Avatar --}}
    <div class="flex-shrink-0 mr-4">
        @if($message->user->profile_photo_url)
            <img src="{{ Storage::url($message->user->profile_photo_url) }}"
                 alt="Photo de profil de {{ $message->user->name }}"
                 class="w-10 h-10 rounded-full">
        @else
            <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center">
                <span class="text-white text-sm font-medium">
                    {{ substr($message->user->prenom, 0, 1) . substr($message->user->nom, 0, 1) }}
                </span>
            </div>
        @endif
    </div>

    {{-- Contenu --}}
    <div class="flex-1 min-w-0">
        <div class="flex items-center">
            <h4 class="text-white font-medium">{{ $message->user->nom }}</h4>
            <span class="ml-2 text-xs text-gray-400">
                {{ $message->created_at->format('d/m/Y H:i') }}
            </span>
            @if($message->edited_at)
                <span class="ml-2 text-xs text-gray-500">(modifié)</span>
            @endif
        </div>

        <div class="mt-1 text-gray-300 break-words">
            @if($message->type === 'text')
                {{ $message->content }}
            @elseif($message->type === 'system')
                <em class="text-gray-400">{{ $message->content }}</em>
            @elseif($message->type === 'file')
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    <a href="#" class="text-purple-400 hover:text-purple-300 truncate">
                        {{ $message->metadata['filename'] ?? 'Fichier' }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    @if(Gate::allows('update-message', $message))
        <div class="flex-shrink-0 ml-4 opacity-0 group-hover:opacity-100 transition-opacity">
            <div class="flex space-x-2">
                <button onclick="editMessage('{{ $message->id }}', '{{ $message->content }}')"
                        class="p-1 text-gray-400 hover:text-white transition-colors rounded hover:bg-gray-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>
                <form action="{{ route('servers.channels.messages.delete', [$server, $channel, $message]) }}"
                      method="POST"
                      class="inline"
                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="p-1 text-red-400 hover:text-red-500 transition-colors rounded hover:bg-gray-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
