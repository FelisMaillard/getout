{{-- resources/views/messages/single.blade.php --}}
<div class="flex items-start group" id="message-{{ $message->id }}">
    {{-- Avatar --}}
    <div class="flex-shrink-0 mr-4">
        @if($message->user->profile_photo_url)
            <img src="{{ Storage::url($message->user->profile_photo_url) }}"
                 alt="Photo de profil de {{ $message->user->prenom }} {{ $message->user->nom }}"
                 class="w-10 h-10 rounded-full object-cover">
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
            <h4 class="text-white font-medium">{{ $message->user->prenom }} {{ $message->user->nom }}</h4>
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
                    <a href="{{ Storage::url($message->metadata['path'] ?? '') }}"
                       target="_blank"
                       class="text-purple-400 hover:text-purple-300 truncate">
                        {{ $message->metadata['filename'] ?? 'Fichier' }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    @if(Gate::allows('update-message', $message))
        <div class="flex-shrink-0 ml-4 opacity-0 group-hover:opacity-100 transition-opacity">
            <div class="flex items-center space-x-2">
                <button onclick="editMessage('{{ $message->id }}', {{ json_encode($message->content) }})"
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
