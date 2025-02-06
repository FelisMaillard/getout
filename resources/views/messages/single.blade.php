{{-- Message Container --}}
<div class="flex items-start {{ $message->user_id === auth()->id() ? 'justify-end' : '' }} group p-2 rounded-lg transition-colors duration-200"
    id="message-{{ $message->id }}">

    {{-- Message Content Container --}}
    <div class="flex max-w-[80%] {{ $message->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
        @if(!$previousMessage || $message->user_id !== $previousMessage->user_id || $message->created_at->diffInMinutes($previousMessage->created_at) >= 5)
            <div class="flex-shrink-0 mr-3">
                @if($message->user->profile_photo_url)
                    <img src="{{ Storage::url($message->user->profile_photo_url) }}"
                         alt="Photo de profil de {{ $message->user->prenom }} {{ $message->user->nom }}"
                         class="w-8 h-8 rounded-full object-cover">
                @else
                    <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center">
                        <span class="text-white text-sm font-medium">
                            {{ substr($message->user->prenom, 0, 1) . substr($message->user->nom, 0, 1) }}
                        </span>
                    </div>
                @endif
            </div>
        @endif

        {{-- Message Bubble --}}
        <div class="flex flex-col {{ $message->user_id === auth()->id() ? 'items-end' : '' }}">
            @if(!$previousMessage || $message->user_id !== $previousMessage->user_id || $message->created_at->diffInMinutes($previousMessage->created_at) >= 5)
                {{-- Username and Timestamp --}}
                <div class="flex items-center space-x-2 mb-1 {{ $message->user_id === auth()->id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                    <span class="text-sm font-medium text-gray-300">
                        {{ $message->user->prenom }} {{ $message->user->nom }}
                    </span>
                    <span class="text-xs text-gray-500">
                        {{ $message->created_at->format('H:i') }}
                    </span>
                    @if($message->edited_at)
                        <span class="text-xs text-gray-500">(modifié)</span>
                    @endif
                </div>
            @endif

            {{-- Message Content --}}
            <div class="flex items-end group">
                {{-- Message Body --}}
                <div class="text-gray-100
                    rounded-2xl px-4 py-2 max-w-full break-words">

                    @if($message->type === 'text')
                        <p class="whitespace-pre-wrap">{{ $message->content }}</p>
                    @elseif($message->type === 'file')
                        @if($message->file_type === 'image')
                            <div class="relative group/image">
                                <img src="{{ Storage::url($message->file_path) }}"
                                     alt="{{ $message->file_name }}"
                                     class="max-w-lg rounded-lg max-h-96 object-contain"
                                     loading="lazy">
                                <a href="{{ Storage::url($message->file_path) }}"
                                   target="_blank"
                                   class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 opacity-0 group-hover/image:opacity-100 transition-opacity duration-200">
                                    <span class="text-white bg-black bg-opacity-75 px-4 py-2 rounded-lg">
                                        Voir l'original
                                    </span>
                                </a>
                            </div>
                        @elseif($message->file_type === 'video')
                            <video controls class="max-w-lg rounded-lg">
                                <source src="{{ Storage::url($message->file_path) }}" type="{{ $message->mime_type }}">
                                Votre navigateur ne supporte pas la lecture de vidéos.
                            </video>
                        @else
                            <div class="flex items-center space-x-3 bg-gray-800/50 p-3 rounded-lg">
                                <div class="text-white">
                                    @if(Str::endsWith($message->file_name, ['.pdf']))
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    @elseif(Str::endsWith($message->file_name, ['.xlsx', '.xls']))
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-white text-sm font-medium truncate">{{ $message->file_name }}</p>
                                    <p class="text-xs text-gray-300">{{ human_filesize($message->file_size) }}</p>
                                </div>
                                <a href="{{ Storage::url($message->file_path) }}"
                                   target="_blank"
                                   class="text-white hover:text-gray-200 transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    @endif
                </div>

                {{-- Message Actions --}}
                <div class="flex items-center opacity-0 group-hover:opacity-100 transition-opacity duration-200
                    {{ $message->user_id === auth()->id() ? 'order-first ml-2' : 'ml-2' }}">
                    @if($message->type === 'text' && $message->user_id === auth()->id())
                        <button onclick="editMessage('{{ $message->id }}', {{ json_encode($message->content) }})"
                                class="p-1 text-gray-400 hover:text-white transition-colors rounded-full hover:bg-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                    @endif

                    @if($message->user_id === auth()->id() || auth()->user()->can('moderate', $channel))
                        <form action="{{ route('servers.channels.messages.delete', [$server, $channel, $message]) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="p-1 text-gray-400 hover:text-red-500 transition-colors rounded-full hover:bg-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
