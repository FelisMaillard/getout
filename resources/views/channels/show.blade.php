@extends('layouts.server')

@section('content')
<div class="flex flex-col h-screen w-full">
    <!-- Header -->
    <div class="flex items-center px-4 py-3 bg-black border-b border-gray-800">
        <a href="{{ route('servers.show', $server) }}"
           class="p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
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

    <div class="flex flex-1 h-[calc(100vh-8rem)]">
        {{-- Messages --}}
        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-6" id="messages-container">
            @if(isset($messages) && $messages->isNotEmpty())
                @foreach($messages as $message)
                    @include('messages.single', ['message' => $message, 'server' => $server, 'channel' => $channel])
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
                <label class="p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-800 cursor-pointer">
                    <input type="file" class="hidden" id="file-upload">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                </label>
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

@push('scripts')
<script>
    // Fonction pour faire défiler vers le bas
    function scrollToBottom() {
        const messagesContainer = document.getElementById('messages-container');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    // Gestionnaire de soumission du formulaire
    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            form.reset();
            const messagesEnd = document.getElementById('message-end');
            messagesEnd.insertAdjacentHTML('beforebegin', data.message);
            scrollToBottom();
        })
        .catch(error => console.error('Erreur:', error));
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
            const messagesEnd = document.getElementById('message-end');
            messagesEnd.insertAdjacentHTML('beforebegin', data.message);
            scrollToBottom();
        })
        .catch(error => console.error('Erreur:', error));

        e.target.value = '';
    });

    // Faire défiler vers le bas au chargement
    document.addEventListener('DOMContentLoaded', scrollToBottom);
</script>
@endpush
@endsection
