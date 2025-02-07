@extends('layouts.server')

@section('content')
<div class="flex flex-col h-screen w-full">
    <!-- Header principal -->
    <header class="flex items-center justify-between px-4 py-3 bg-black border-b border-gray-800">
        <div class="flex items-center">
            <!-- Bouton menu channels (mobile) -->
            <button onclick="toggleChannelsSidebar()"
                    class="md:hidden p-2 mr-2 text-gray-400 hover:text-white rounded-lg hover:bg-gray-800 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Bouton retour (visible sur tous les écrans) -->
            <a href="{{ route('servers.index') }}"
               class="flex p-2 text-gray-400 hover:text-white rounded-lg hover:bg-gray-800 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>

            <!-- Titre du serveur -->
            <h1 class="ml-3 text-white font-medium truncate">{{ $server->name }}</h1>
        </div>

        <!-- Bouton liste membres (mobile) -->
        <button onclick="toggleMembersSidebar()"
                class="md:hidden p-2 text-gray-400 hover:text-white rounded-lg hover:bg-gray-800 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </button>
    </header>

    <!-- Container principal -->
    <div class="flex flex-1 overflow-hidden relative pt-0 md:pt-0 pb-16 md:pb-0">
        <!-- Overlay mobile avec z-index inférieur aux sidebars -->
        <div id="mobile-overlay"
             class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"
             onclick="closeAllSidebars()">
        </div>

        <!-- Sidebar Channels avec z-index plus élevé -->
        <aside id="channels-sidebar"
               class="fixed md:static inset-y-0 left-0 w-64 bg-black border-r border-gray-800 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-50 flex flex-col">

            <!-- En-tête du sidebar channels -->
            <div class="p-4 border-b border-gray-800">
                <p class="text-sm text-gray-400">{{ $server->description }}</p>
            </div>

            <!-- Liste des channels -->
            <div class="flex-1 overflow-y-auto p-2">
                <div class="mb-4">
                    <div class="flex items-center justify-between px-2 mb-2">
                        <h2 class="text-xs font-semibold text-gray-400 uppercase">Channels textuels</h2>
                        @if(Gate::allows('update-server', $server))
                            <button onclick="document.getElementById('create-channel-modal').classList.remove('hidden')"
                                    class="p-1 text-gray-400 hover:text-white rounded-full hover:bg-gray-800 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        @endif
                    </div>

                    <!-- Liste des channels -->
                    <div class="space-y-0.5">
                        @foreach($channels->where('type', 'text') as $channel)
                            <button onclick="loadChannel({{ $channel->id }})"
                                    class="w-full flex items-center px-2 py-1.5 rounded-md group hover:bg-gray-900
                                           {{ Request::is('*/channels/'.$channel->id) ? 'bg-gray-900' : '' }}">
                                <span class="text-gray-400 group-hover:text-white mr-2">#</span>
                                <span class="text-gray-400 group-hover:text-white truncate">{{ $channel->name }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Bouton profil utilisateur -->
            <div class="border-t border-gray-800 p-4">
                <a href="{{ route('profile.show', ['tag' => Auth::user()->tag]) }}"
                   class="flex items-center space-x-3 hover:bg-gray-900 p-2 rounded-lg transition-colors duration-200">
                    <!-- Avatar -->
                    @if(Auth::user()->profile_photo_url)
                        <img src="{{ Storage::url(Auth::user()->profile_photo_url) }}"
                             alt="{{ Auth::user()->prenom }}"
                             class="w-10 h-10 rounded-full object-cover">
                    @else
                        <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center">
                            <span class="text-white text-sm font-medium">
                                {{ substr(Auth::user()->prenom, 0, 1) }}{{ substr(Auth::user()->nom, 0, 1) }}
                            </span>
                        </div>
                    @endif

                    <!-- Informations utilisateur -->
                    <div class="min-w-0">
                        <div class="text-white font-medium truncate">
                            {{ Auth::user()->prenom }} {{ Auth::user()->nom }}
                        </div>
                        <div class="text-gray-400 text-sm truncate">
                            {{ '@' . Auth::user()->tag }}
                        </div>
                    </div>
                </a>
            </div>
        </aside>

        <!-- Contenu principal -->
        <main id="main-content" class="flex-1 bg-black flex flex-col">
            @isset($currentChannel)
                @include('channels._messages', ['channel' => $currentChannel])
            @else
                <div class="flex-1 flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <svg class="w-16 h-16 mb-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p>Sélectionnez un channel pour commencer à discuter</p>
                    </div>
                </div>
            @endisset
        </main>

        <!-- Sidebar Membres avec z-index plus élevé -->
        <aside id="members-sidebar"
               class="fixed md:static inset-y-0 right-0 w-64 bg-black border-l border-gray-800 transform translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-50 flex flex-col">

            <!-- En-tête du sidebar membres -->
            <div class="p-4 border-b border-gray-800">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-white">Membres</h2>
                    @if(Gate::allows('update-server', $server))
                        <button onclick="toggleInviteModal(true)"
                                class="p-1 text-gray-400 hover:text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                    @endif
                </div>
                <p class="text-sm text-gray-400">{{ $members->count() }} membres</p>
            </div>

            <!-- Liste des invitations en attente -->
            @if($pendingInvites && $pendingInvites->count() > 0)
                <div class="p-4 border-b border-gray-800">
                    <h3 class="text-sm font-medium text-gray-400 mb-2">Invitations en attente</h3>
                    <div class="space-y-2">
                        @foreach($pendingInvites as $invite)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div class="text-sm">
                                        <p class="text-white">{{ $invite->invitee->email }}</p>
                                        <p class="text-xs text-gray-400">
                                            Expire {{ $invite->expires_at ? $invite->expires_at->diffForHumans() : 'dans 30 jours' }}
                                        </p>
                                    </div>
                                </div>
                                @if(Gate::allows('update-server', $server))
                                    <form action="{{ route('servers.invites.cancel', [$server, $invite]) }}"
                                          method="POST"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-400 hover:text-red-300"
                                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette invitation ?')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Liste des membres -->
            <div class="flex-1 overflow-y-auto p-4 space-y-2">
                @foreach($members as $member)
                    <div class="flex items-center space-x-3">
                        @if($member->user->profile_photo_url)
                            <img src="{{ Storage::url($member->user->profile_photo_url) }}"
                                 alt="{{ $member->user->prenom }} {{ $member->user->nom }}"
                                 class="w-8 h-8 rounded-full object-cover">
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
        </aside>
    </div>
</div>

@include('channels._modals')

@push('scripts')
<script>
let channelsSidebarOpen = false;
let membersSidebarOpen = false;

function toggleChannelsSidebar() {
    const sidebar = document.getElementById('channels-sidebar');
    const overlay = document.getElementById('mobile-overlay');

    channelsSidebarOpen = !channelsSidebarOpen;
    membersSidebarOpen = false;

    sidebar.style.transform = channelsSidebarOpen ? 'translateX(0)' : 'translateX(-100%)';
    document.getElementById('members-sidebar').style.transform = 'translateX(100%)';

    if (channelsSidebarOpen) {
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Empêche le défilement du body
    } else {
        overlay.classList.add('hidden');
        document.body.style.overflow = ''; // Restaure le défilement
    }
}

function toggleMembersSidebar() {
    const sidebar = document.getElementById('members-sidebar');
    const overlay = document.getElementById('mobile-overlay');

    membersSidebarOpen = !membersSidebarOpen;
    channelsSidebarOpen = false;

    sidebar.style.transform = membersSidebarOpen ? 'translateX(0)' : 'translateX(100%)';
    document.getElementById('channels-sidebar').style.transform = 'translateX(-100%)';

    if (membersSidebarOpen) {
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Empêche le défilement du body
    } else {
        overlay.classList.add('hidden');
        document.body.style.overflow = ''; // Restaure le défilement
    }
}

function closeAllSidebars() {
    channelsSidebarOpen = false;
    membersSidebarOpen = false;

    document.getElementById('channels-sidebar').style.transform = 'translateX(-100%)';
    document.getElementById('members-sidebar').style.transform = 'translateX(100%)';
    document.getElementById('mobile-overlay').classList.add('hidden');
    document.body.style.overflow = ''; // Restaure le défilement
}

function loadChannel(channelId) {
    fetch(`/servers/{{ $server->id }}/channels/${channelId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.html) {
            document.getElementById('main-content').innerHTML = data.html;
            history.pushState({}, '', `/servers/{{ $server->id }}?currentChannel=${channelId}`);
            initializeMessageHandlers();
            scrollToBottom();
            closeAllSidebars();
        }
    })
    .catch(error => console.error('Error loading channel:', error));
}

function initializeMessageHandlers() {
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const fileUpload = document.getElementById('file-upload');
    const messagesContainer = document.getElementById('messages-container');

    if (messageForm) {
        messageForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            if (!messageInput.value.trim()) return;

            const formData = new FormData(this);

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.message) {
                    const messagesEnd = document.getElementById('message-end');
                    messagesEnd.insertAdjacentHTML('beforebegin', data.message);
                    messageForm.reset();
                    scrollToBottom();
                }
            } catch (error) {
                console.error('Error sending message:', error);
                showNotification('Erreur lors de l\'envoi du message', 'error');
            }
        });
    }

    if (fileUpload) {
        fileUpload.addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', 'file');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            try {
                showNotification('Upload en cours...', 'info');

                const response = await fetch(window.location.pathname + '/messages', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.message) {
                    const messagesEnd = document.getElementById('message-end');
                    messagesEnd.insertAdjacentHTML('beforebegin', data.message);
                    scrollToBottom();
                    showNotification('Fichier envoyé avec succès', 'success');
                }
            } catch (error) {
                console.error('Error uploading file:', error);
                showNotification('Erreur lors de l\'upload du fichier', 'error');
            }

            e.target.value = '';
        });
    }
}

function scrollToBottom() {
    const messagesContainer = document.getElementById('messages-container');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300
        ${type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-purple-600'} text-white`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function toggleInviteModal(show = true) {
    const modal = document.getElementById('inviteModal');
    if (!modal) return;

    if (show) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    } else {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('main-content').querySelector('#messages-container')) {
        initializeMessageHandlers();
        scrollToBottom();
    }

    // Gestion de la fermeture des sidebars avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllSidebars();
        }
    });

    // Redimensionnement de la fenêtre
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 768) { // 768px est le breakpoint md de Tailwind
            document.getElementById('channels-sidebar').style.transform = 'translateX(0)';
            document.getElementById('members-sidebar').style.transform = 'translateX(0)';
            document.getElementById('mobile-overlay').classList.add('hidden');
        } else {
            if (!channelsSidebarOpen && !membersSidebarOpen) {
                closeAllSidebars();
            }
        }
    });
});
</script>
@endpush
@endsection
