{{-- Messages Channel Content --}}
<div class="flex flex-col h-full bg-black">
    {{-- En-tête du channel --}}
    <div class="flex-shrink-0 flex items-center px-4 py-3 bg-black border-b border-gray-800">
        <div class="flex items-center flex-1">
            <div class="ml-4">
                <div class="flex items-center">
                    <span class="text-gray-400 mr-2">#</span>
                    <h2 class="text-lg font-medium text-white">{{ $channel->name }}</h2>
                </div>
                @if($channel->description)
                    <p class="text-sm text-gray-400">{{ $channel->description }}</p>
                @endif
            </div>
        </div>

        @if(Gate::allows('update-channel', $channel))
            <button onclick="document.getElementById('edit-channel-modal').classList.remove('hidden')"
                    class="p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-800 ml-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </button>
        @endif
    </div>

    {{-- Zone des messages --}}
    <div class="flex-1 min-h-0 relative">
        <div class="absolute inset-0 overflow-y-auto px-4 py-6 space-y-6" id="messages-container">
            @if(isset($messages) && $messages->isNotEmpty())
                @foreach($messages->reverse() as $message)
                    @include('messages.single', [
                        'message' => $message,
                        'server' => $server,
                        'channel' => $channel
                    ])
                @endforeach
            @else
                <div class="flex items-center justify-center h-full text-gray-400">
                    <p>Aucun message dans ce channel. Soyez le premier à écrire !</p>
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
            <div class="flex-1">
                <input type="text"
                       name="content"
                       id="message-input"
                       class="w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                       placeholder="Envoyer un message..."
                       autocomplete="off">
            </div>

            <div class="flex-shrink-0 flex space-x-2">
                <!-- File upload button -->
                <label class="p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-800 cursor-pointer">
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

                <!-- Send button -->
                <button type="submit"
                        id="send-message"
                        class="p-2 text-purple-400 hover:text-purple-300 transition-colors rounded-lg hover:bg-gray-800 disabled:opacity-50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
