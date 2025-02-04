@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 md:pt-0">
        <h1 class="text-2xl font-bold text-white mb-8">Notifications</h1>

        <!-- Sections des demandes d'abonnement -->
        <div class="bg-black overflow-hidden">
            <div class="p-4 border-b border-gray-800">
                <h2 class="text-lg font-medium text-white">Demandes d'abonnement</h2>
            </div>

            @if($pendingRequests->isEmpty())
                <div class="p-4 text-gray-400 text-center">
                    Aucune demande d'abonnement en attente
                </div>
            @else
                <div class="divide-y divide-gray-800">
                    @foreach($pendingRequests as $request)
                        <div class="p-4 hover:bg-gray-900 transition-colors duration-200">
                            <div class="flex items-center justify-between">
                                <!-- Info utilisateur -->
                                <div class="flex items-center space-x-4">
                                    <a href="{{ route('profile.show', $request->user->tag) }}"
                                       class="flex items-center space-x-4 group">
                                        <!-- Avatar -->
                                        <div class="w-12 h-12 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold">
                                            {{ substr($request->user->prenom, 0, 1) . substr($request->user->nom, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-white font-medium group-hover:text-purple-400 transition">
                                                {{ $request->user->prenom }} {{ $request->user->nom }}
                                            </p>
                                            <p class="text-gray-400 text-sm">{{ '@' . $request->user->tag }}</p>
                                            <p class="text-gray-500 text-xs mt-1">
                                                {{ $request->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </a>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('relations.accept', $request->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                            Accepter
                                        </button>
                                    </form>

                                    <form action="{{ route('relations.remove', $request->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                            Refuser
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($pendingRequests->hasPages())
                    <div class="p-4 border-t border-gray-800">
                        {{ $pendingRequests->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Section des invitations aux serveurs -->
        <div class="bg-black overflow-hidden mt-8">
            <div class="p-4 border-b border-gray-800">
                <h2 class="text-lg font-medium text-white">Invitations aux serveurs</h2>
            </div>

            @if($serverInvites->isEmpty())
                <div class="p-4 text-gray-400 text-center">
                    Aucune invitation en attente
                </div>
            @else
                <div class="divide-y divide-gray-800">
                    @foreach($serverInvites as $invite)
                        <div class="p-4 hover:bg-gray-900 transition-colors duration-200">
                            <div class="flex items-center justify-between">
                                <!-- Info du serveur -->
                                <div class="flex-1">
                                    <h3 class="text-white font-medium">{{ $invite->server->name }}</h3>
                                    <p class="text-gray-400 text-sm">
                                        Invité par {{ $invite->inviter->prenom }} {{ $invite->inviter->nom }}
                                    </p>
                                    @if($invite->expires_at)
                                        <p class="text-gray-500 text-xs mt-1">
                                            Expire {{ $invite->expires_at->diffForHumans() }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('servers.invites.accept', ['server' => $invite->server_id, 'invite' => $invite->id]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                            Rejoindre
                                        </button>
                                    </form>

                                    <form action="{{ route('servers.invites.reject', ['server' => $invite->server_id, 'invite' => $invite->id]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors duration-200">
                                            Refuser
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($serverInvites->hasPages())
                    <div class="p-4 border-t border-gray-800">
                        {{ $serverInvites->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
