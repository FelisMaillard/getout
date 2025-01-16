@extends('layouts.server')

@section('content')
<div class="flex flex-col h-screen w-full">
    <!-- Header -->
    <div class="flex items-center px-4 py-3 bg-black border-b border-gray-800">
        <a href="{{ route('servers.index') }}"
           class="p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <h1 class="ml-3 text-white font-medium flex-1">{{ $server->name }}</h1>
        <button onclick="toggleMembersList()"
                class="md:hidden p-2 text-gray-400 hover:text-white transition-colors rounded-lg hover:bg-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </button>
    </div>

    <div class="flex flex-1 overflow-hidden">
        <!-- Liste des channels -->
        <div class="w-full md:w-64 bg-black border-r border-gray-800 flex flex-col overflow-hidden">
            <!-- Description du serveur (desktop) -->
            <div class="hidden md:block p-4 border-b border-gray-800">
                <p class="text-sm text-gray-400">{{ $server->description }}</p>
            </div>

            <!-- Liste des channels -->
            <div class="flex-1 overflow-y-auto p-2">
                <!-- Channels textuels -->
                <div class="mb-4">
                    <div class="flex items-center justify-between px-2 mb-2">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase">Channels textuels</h2>
                        @if(Gate::allows('update-server', $server))
                            <button onclick="document.getElementById('create-channel-modal').classList.remove('hidden')"
                                    class="p-1 text-gray-400 hover:text-white transition-colors rounded-full hover:bg-gray-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        @endif
                    </div>

                    <div class="space-y-0.5">
                        @foreach($channels->where('type', 'text') as $channel)
                            <button onclick="loadChannel({{ $channel->id }})"
                                   class="w-full flex items-center px-2 py-1.5 rounded-md group hover:bg-gray-900 {{ Request::is('*/channels/'.$channel->id) ? 'bg-gray-900' : '' }}">
                                <span class="text-gray-400 group-hover:text-white mr-2">#</span>
                                <span class="text-gray-400 group-hover:text-white truncate">{{ $channel->name }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Channels vocaux -->
                <div>
                    <div class="flex items-center justify-between px-2 mb-2">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase">Channels vocaux</h2>
                    </div>

                    <div class="space-y-0.5">
                        @foreach($channels->where('type', 'voice') as $channel)
                            <button class="w-full flex items-center px-2 py-1.5 rounded-md group hover:bg-gray-900">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-white mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                </svg>
                                <span class="text-gray-400 group-hover:text-white truncate">{{ $channel->name }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Zone principale -->
        <div id="main-content" class="flex-1 bg-black flex flex-col">
            @isset($currentChannel)
                @include('channels._messages', ['channel' => $currentChannel])
            @else
                <div class="flex-1 flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <svg class="w-16 h-16 mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p>Sélectionnez un channel pour commencer à discuter</p>
                    </div>
                </div>
            @endisset
        </div>

        <!-- Liste des membres -->
        <div id="members-sidebar"
             class="hidden md:flex w-64 bg-black border-l border-gray-800 flex-col">
            <div class="p-4 border-b border-gray-800">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-white">Membres</h2>
                </div>
                <p class="text-sm text-gray-400">{{ $members->count() }} membres</p>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-2">
                @foreach($members as $member)
                    <div class="flex items-center space-x-3">
                        @if($member->user->profile_photo_url)
                            <img src="{{ Storage::url($member->user->profile_photo_url) }}"
                                 alt="{{ $member->user->name }}"
                                 class="w-8 h-8 rounded-full">
                        @else
                            <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center">
                                <span class="text-white text-sm font-medium">
                                    {{ substr($member->user->prenom, 0, 1) . substr($member->user->nom, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <p class="text-white text-sm font-medium">{{ $member->user->prenom }} {{ $member->user->nom }}</p>
                            <p class="text-gray-400 text-xs">{{ $member->role }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@include('channels._modals') {{-- Inclure les modaux ici --}}

@push('scripts')
<script>
function scrollToBottom() {
    const messagesContainer = document.getElementById('messages-container');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
}

function loadChannel(channelId) {
    fetch(`/servers/{{ $server->id }}/channels/${channelId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('main-content').innerHTML = data.html;
        history.pushState({}, '', `/servers/{{ $server->id }}?currentChannel=${channelId}`);
        initializeMessageHandlers(); // Réinitialisation des gestionnaires après le chargement
    });
}

function initializeMessageHandlers() {
    const messageForm = document.getElementById('message-form');
    const fileUpload = document.getElementById('file-upload');

    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                }
            })
            .then(response => response.json())
            .then(data => {
                form.reset();
                const messagesEnd = document.getElementById('message-end');
                messagesEnd.insertAdjacentHTML('beforebegin', data.message);
                scrollToBottom();
            });
        });
    }

    if (fileUpload) {
        fileUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', 'file');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            fetch(window.location.href + '/messages', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const messagesEnd = document.getElementById('message-end');
                messagesEnd.insertAdjacentHTML('beforebegin', data.message);
                scrollToBottom();
                e.target.value = '';
            });
        });
    }

    scrollToBottom();
}

// Initialiser les gestionnaires au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('main-content').querySelector('#messages-container')) {
        initializeMessageHandlers();
    }
});
</script>
@endpush
@endsection
