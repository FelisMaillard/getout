{{-- Messages Channel Content --}}
<div class="flex flex-col h-full bg-black">
    {{-- En-tête du channel --}}
    <div class="flex-shrink-0 flex items-center px-4 py-3 bg-black border-b border-gray-800 sticky top-0 z-10 backdrop-blur-sm bg-opacity-80">
        <div class="flex items-center flex-1">
            <div class="ml-2">
                <div class="flex items-center">
                    <span class="text-gray-400 mr-2">#</span>
                    <h2 class="text-lg font-medium text-white">{{ $channel->name }}</h2>
                </div>
                @if($channel->description)
                    <p class="text-sm text-gray-400">{{ $channel->description }}</p>
                @endif
            </div>
        </div>

        <div class="flex space-x-2">
            {{-- Info du channel --}}
            <button
                class="p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-800"
                title="Information sur le canal"
                onclick="toggleChannelInfo()">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </button>

            {{-- Bouton d'édition pour les administrateurs --}}
            @if(Gate::allows('update-channel', $channel))
                <button
                    onclick="document.getElementById('edit-channel-modal').classList.remove('hidden')"
                    class="p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-800"
                    title="Modifier le canal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>
            @endif
        </div>
    </div>

    {{-- Info panel (masqué par défaut) --}}
    <div id="channel-info-panel" class="hidden bg-gray-900 border-b border-gray-800 p-4 animate-fade-in">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-lg font-medium text-white">À propos de #{{ $channel->name }}</h3>
            <button onclick="toggleChannelInfo()" class="text-gray-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="text-gray-300 text-sm">
            <p><strong>Description:</strong> {{ $channel->description ?: 'Aucune description' }}</p>
            <p><strong>Créé le:</strong> {{ $channel->created_at->format('d/m/Y') }}</p>
            <p><strong>Type:</strong> {{ ucfirst($channel->type) }}</p>
            <p><strong>Visibilité:</strong> {{ $channel->is_private ? 'Privé' : 'Public' }}</p>
        </div>
    </div>

    {{-- Zone des messages --}}
    <div class="flex-1 min-h-0 relative overflow-hidden">
        {{-- Date indicator (flottant) --}}
        <div id="date-indicator" class="hidden absolute top-2 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-3 py-1 rounded-full shadow-md z-10">
            Aujourd'hui
        </div>

        <div class="absolute inset-0 overflow-y-auto px-4 py-2 messages-container" id="messages-container">
            {{-- Loading spinner --}}
            <div id="messages-loading" class="flex justify-center items-center py-8 hidden">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
            </div>

            {{-- Messages groupés par date --}}
            @if(isset($messages) && $messages->isNotEmpty())
                @php
                    $currentDate = null;
                    $reversedMessages = $messages->reverse();
                @endphp

                @foreach($reversedMessages as $message)
                    @php
                        $messageDate = $message->created_at->format('Y-m-d');
                    @endphp

                    @if($currentDate !== $messageDate)
                        <div class="flex justify-center my-4">
                            <div class="bg-gray-800 text-gray-300 text-xs px-3 py-1 rounded-full">
                                @if($message->created_at->isToday())
                                    Aujourd'hui
                                @elseif($message->created_at->isYesterday())
                                    Hier
                                @else
                                    {{ $message->created_at->format('d/m/Y') }}
                                @endif
                            </div>
                        </div>
                        @php
                            $currentDate = $messageDate;
                        @endphp
                    @endif

                    @include('messages.single', [
                        'message' => $message,
                        'server' => $server,
                        'channel' => $channel
                    ])
                @endforeach
            @else
                <div class="flex flex-col items-center justify-center h-full text-gray-400 py-16">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="text-center">Aucun message dans ce channel.</p>
                    <p class="text-center text-gray-500 mt-2">Soyez le premier à écrire !</p>
                </div>
            @endif
            <div id="message-end" class="h-4"></div>
        </div>
    </div>

    {{-- Zone de saisie --}}
    <div class="flex-shrink-0 px-4 py-4 bg-black border-t border-gray-800">
        <form action="{{ route('servers.channels.messages.store', ['server' => $server, 'channel' => $channel]) }}"
              method="POST"
              id="message-form"
              enctype="multipart/form-data"
              class="flex space-x-4">
            @csrf

            {{-- Upload button --}}
            <label class="p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-800 cursor-pointer flex items-center justify-center">
                <input type="file"
                       name="file"
                       id="file-upload"
                       class="hidden"
                       accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.csv,.sql">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
            </label>

            <div class="flex-1 relative">
                <textarea
                    name="content"
                    id="message-input"
                    rows="1"
                    class="w-full px-4 py-2 max-h-32 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent resize-none overflow-auto"
                    placeholder="Envoyer un message..."
                    autocomplete="off"></textarea>

                <div id="emoji-button" class="absolute right-2 bottom-2 text-gray-400 hover:text-white cursor-pointer p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            {{-- Send button --}}
            <button type="submit"
                    id="send-message"
                    class="p-2 text-purple-400 hover:text-purple-300 transition-colors rounded-lg hover:bg-gray-800 disabled:opacity-50 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </form>

        {{-- Typing indicator --}}
        <div id="typing-indicator" class="text-xs text-gray-500 mt-1 h-4 pl-2 hidden">
            <span class="animate-pulse">Un utilisateur est en train d'écrire...</span>
        </div>
    </div>
</div>

<script>
function toggleChannelInfo() {
    const panel = document.getElementById('channel-info-panel');
    if (panel.classList.contains('hidden')) {
        panel.classList.remove('hidden');
    } else {
        panel.classList.add('hidden');
    }
}

// Auto-resize textarea
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('message-input');

    function autoResize() {
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 128) + 'px';
    }

    textarea.addEventListener('input', autoResize);

    // Handle enter key
    textarea.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('message-form').dispatchEvent(new Event('submit'));
        }
    });
});
</script>
