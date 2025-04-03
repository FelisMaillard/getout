@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('meta_description', 'Votre tableau de bord personnel GetOut - statistiques, activités récentes et suggestions')

@section('content')
<div class="min-h-screen bg-black flex flex-col">
    <div class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-12 w-full">
        <!-- Entête personnalisé -->
        <header class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-white">
                Bonjour, {{ $user->prenom }} 👋
            </h1>
            <p class="text-gray-400 mt-2">
                {{ now()->format('l j F Y') }} — Voici votre activité récente
            </p>
        </header>

        <!-- Cartes de statistiques -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-black border border-gray-800 rounded-lg p-4 hover:border-purple-500 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Abonnements</p>
                        <h3 class="text-xl md:text-2xl font-bold text-white">{{ $stats['following_count'] }}</h3>
                    </div>
                    <div class="bg-purple-600/10 rounded-lg p-2">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-black border border-gray-800 rounded-lg p-4 hover:border-purple-500 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Abonnés</p>
                        <h3 class="text-xl md:text-2xl font-bold text-white">{{ $stats['followers_count'] }}</h3>
                    </div>
                    <div class="bg-green-600/10 rounded-lg p-2">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-black border border-gray-800 rounded-lg p-4 hover:border-purple-500 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Serveurs</p>
                        <h3 class="text-xl md:text-2xl font-bold text-white">{{ $stats['servers_count'] }}</h3>
                    </div>
                    <div class="bg-blue-600/10 rounded-lg p-2">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-black border border-gray-800 rounded-lg p-4 hover:border-purple-500 transition-colors">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-sm">Messages</p>
                        <h3 class="text-xl md:text-2xl font-bold text-white">{{ $stats['messages_count'] }}</h3>
                    </div>
                    <div class="bg-yellow-600/10 rounded-lg p-2">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Conteneur principal en deux colonnes -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Colonne principale (2/3) -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Activité récente -->
                <section class="bg-black border border-gray-800 rounded-lg">
                    <div class="border-b border-gray-800 p-4">
                        <h2 class="text-xl text-white font-semibold">Activité récente</h2>
                    </div>
                    <div class="divide-y divide-gray-800">
                        @forelse($recentActivity as $message)
                            <div class="p-4 hover:bg-gray-900 transition-colors">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($message->user->profile_photo_url)
                                            <img src="{{ Storage::url($message->user->profile_photo_url) }}" alt="{{ $message->user->prenom }}" class="w-10 h-10 rounded-full">
                                        @else
                                            <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center">
                                                <span class="text-white text-sm font-medium">
                                                    {{ substr($message->user->prenom, 0, 1) . substr($message->user->nom, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-white">
                                            <a href="{{ route('profile.show', $message->user->tag) }}" class="font-medium text-purple-400 hover:underline">
                                                {{ $message->user->prenom }} {{ $message->user->nom }}
                                            </a>
                                            a posté dans
                                            <a href="{{ route('servers.channels.show', [$message->channel->server, $message->channel]) }}" class="font-medium text-blue-400 hover:underline">
                                                #{{ $message->channel->name }}
                                            </a>
                                        </p>
                                        <p class="mt-1 text-sm text-gray-300 truncate">
                                            {{ Str::limit($message->content, 80) }}
                                        </p>
                                        <div class="mt-1 flex items-center">
                                            <p class="text-xs text-gray-500">
                                                {{ $message->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <p class="text-gray-400">Aucune activité récente à afficher</p>
                                <a href="{{ route('servers.index') }}" class="inline-block mt-4 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                    Rejoindre un serveur
                                </a>
                            </div>
                        @endforelse
                    </div>
                    @if($recentActivity->count() > 0)
                        <div class="border-t border-gray-800 p-4 text-center">
                            <a href="{{ route('servers.index') }}" class="text-purple-400 hover:text-purple-300 text-sm">
                                Voir tous vos serveurs →
                            </a>
                        </div>
                    @endif
                </section>

                <!-- Serveurs actifs -->
                <section class="bg-black border border-gray-800 rounded-lg">
                    <div class="border-b border-gray-800 p-4">
                        <h2 class="text-xl text-white font-semibold">Vos serveurs actifs</h2>
                    </div>
                    <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @forelse($activeServers as $server)
                            <a href="{{ route('servers.show', $server) }}" class="block bg-black border border-gray-800 hover:border-purple-500 rounded-lg p-4 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-medium">
                                            {{ substr($server->name, 0, 2) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-medium truncate">{{ $server->name }}</h3>
                                        <p class="text-gray-400 text-sm">{{ $server->messages_count }} messages cette semaine</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="col-span-2 p-6 text-center">
                                <p class="text-gray-400">Vous ne faites partie d'aucun serveur actif</p>
                                <a href="{{ route('servers.create') }}" class="inline-block mt-4 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                    Créer un serveur
                                </a>
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <!-- Colonne secondaire (1/3) -->
            <div class="space-y-8">

                <!-- Profil rapide -->
                <section class="bg-black border border-gray-800 rounded-lg overflow-hidden">
                    <div class="border-b border-gray-800 p-4">
                        <h2 class="text-xl text-white font-semibold">Votre profil</h2>
                    </div>
                    <div class="p-6 flex items-center space-x-4">
                        @if($user->profile_photo_url)
                            <img src="{{ Storage::url($user->profile_photo_url) }}" alt="{{ $user->prenom }}" class="w-16 h-16 rounded-full object-cover">
                        @else
                            <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-xl font-medium">
                                    {{ substr($user->prenom, 0, 1) . substr($user->nom, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-white font-semibold">{{ $user->prenom }} {{ $user->nom }}</h3>
                            <p class="text-gray-400">{{ "@" . $user->tag }}</p>
                        </div>
                    </div>
                    <div class="px-6 pb-6 flex space-x-2">
                        <a href="{{ route('profile.show', $user->tag) }}" class="flex-1 px-4 py-2 bg-purple-600 text-white text-center rounded-lg hover:bg-purple-700 transition-colors">
                            Voir profil
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex-1 px-4 py-2 bg-gray-800 text-white text-center rounded-lg hover:bg-gray-700 transition-colors">
                            Modifier
                        </a>
                    </div>
                </section>

                <!-- Raccourcis rapides -->
                <section class="bg-black border border-gray-800 rounded-lg">
                    <div class="border-b border-gray-800 p-4">
                        <h2 class="text-xl text-white font-semibold">Raccourcis</h2>
                    </div>
                    <div class="p-4 grid grid-cols-2 gap-4">
                        <a href="{{ route('servers.create') }}" class="flex flex-col items-center p-4 bg-black border border-gray-800 hover:border-purple-500 rounded-lg transition-colors">
                            <svg class="w-8 h-8 text-purple-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span class="text-white text-sm">Nouveau serveur</span>
                        </a>
                        <a href="{{ route('search.index') }}" class="flex flex-col items-center p-4 bg-black border border-gray-800 hover:border-purple-500 rounded-lg transition-colors">
                            <svg class="w-8 h-8 text-purple-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span class="text-white text-sm">Rechercher</span>
                        </a>
                        <a href="{{ route('notifications.index') }}" class="flex flex-col items-center p-4 bg-black border border-gray-800 hover:border-purple-500 rounded-lg transition-colors">
                            <svg class="w-8 h-8 text-purple-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="text-white text-sm">Notifications</span>
                        </a>
                        <a href="{{ route('servers.index') }}" class="flex flex-col items-center p-4 bg-black border border-gray-800 hover:border-purple-500 rounded-lg transition-colors">
                            <svg class="w-8 h-8 text-purple-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2H5z" />
                            </svg>
                            <span class="text-white text-sm">Mes serveurs</span>
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
