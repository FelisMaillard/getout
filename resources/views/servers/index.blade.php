{{-- resources/views/servers/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes serveurs')

@section('meta_description', 'Liste de vos serveurs sur GetOut')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 md:pt-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-white">Mes serveurs</h1>
            <a href="{{ route('servers.create') }}"
               class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
                Créer un serveur
            </a>
        </div>

        @if($servers->isEmpty())
            <div class="bg-black rounded-lg border border-gray-800 p-8 text-center">
                <div class="flex flex-col items-center justify-center space-y-4">
                    <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-lg font-medium text-white">Aucun serveur</h3>
                    <p class="text-gray-400">Vous n'avez pas encore créé ou rejoint de serveur.</p>
                    <a href="{{ route('servers.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Créer mon premier serveur
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($servers as $server)
                    <div class="bg-black rounded-lg border border-gray-800 hover:border-purple-500 transition-colors duration-200">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-white truncate">{{ $server->name }}</h3>
                                <span class="px-2 py-1 text-xs rounded-full {{ $server->privacy_type === 'public' ? 'bg-green-500/10 text-green-500' : 'bg-yellow-500/10 text-yellow-500' }}">
                                    {{ $server->privacy_type === 'public' ? 'Public' : 'Privé' }}
                                </span>
                            </div>

                            @if($server->description)
                                <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $server->description }}</p>
                            @endif

                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center text-gray-400">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <span class="text-sm">{{ $server->members_count }} membres</span>
                                    </div>
                                </div>

                                <a href="{{ route('servers.show', $server) }}"
                                   class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors duration-200">
                                    Accéder
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($servers->hasPages())
                <div class="mt-6">
                    {{ $servers->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
