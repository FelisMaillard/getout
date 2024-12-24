@extends('layouts.app')

@section('content')

@php
    // Récupérer la relation existante pour l'utilisateur cible
    $existingRelation = Auth::user()->sentRelations()
        ->withTrashed()
        ->where('friend_id', $user->id)
        ->first();
@endphp


<div class="min-h-screen bg-gray-950 py-24 md:py-10">
    <div class="max-w-md md:max-w-2xl mx-auto px-4">
        <!-- Carte de profil -->
        <div class="bg-black rounded-lg p-6 border border-gray-800">
            <!-- Photo de profil mobile -->
            @if($user->profile_photo_url)
                <div class="md:hidden flex items-center justify-center mb-6 md:mb-10">
                    <img src="{{ Storage::url($user->profile_photo_url) }}"
                         alt="Photo de profil"
                         class="w-32 h-32 rounded-full object-cover">
                </div>
            @else
                <div class="md:hidden flex items-center justify-center mb-6 md:mb-10">
                    <div class="w-32 h-32 md:w-72 md:h-72 rounded-full overflow-hidden bg-purple-600 flex items-center justify-center text-white text-xl md:text-3xl font-bold shrink-0">
                        {{ substr($user->prenom, 0, 1) . substr($user->nom, 0, 1) }}
                    </div>
                </div>
            @endif

            <!-- Ligne séparatrice mobile -->
            <div class="md:hidden flex justify-center items-center h-10">
                <div class="w-full max-w-md h-[1px] bg-gray-800 mb-6"></div>
            </div>

            <div class="flex gap-2 md:gap-6">
                <!-- Photo de profil desktop -->
                @if($user->profile_photo_url)
                    <div class="hidden md:flex md:w-32 md:h-32 rounded-full overflow-hidden">
                        <img src="{{ Storage::url($user->profile_photo_url) }}"
                             alt="Photo de profil"
                             class="w-32 h-32 object-cover">
                    </div>
                @else
                    <div class="hidden md:flex md:w-32 md:h-32 rounded-full overflow-hidden bg-purple-600 items-center justify-center text-white text-xl md:text-3xl font-bold shrink-0">
                        {{ substr($user->prenom, 0, 1) . substr($user->nom, 0, 1) }}
                    </div>
                @endif

                <!-- Informations -->
                <div class="flex-1 space-y-4">
                    <!-- Nom et actions -->
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-xl font-medium text-white">{{ '@' . $user->tag }}</h1>
                            <div class="text-white font-medium">{{ $user->prenom }} {{ $user->nom }}</div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            @if(!$isOwnProfile)
                                <div class="flex gap-2">
                                    <!-- Boutons de relation -->
                                    <div class="flex-shrink-0">
                                        @include('components.relation-button', ['targetUser' => $user])
                                    </div>

                                    <!-- Bouton de blocage -->
                                    @if(!$existingRelation || $existingRelation->status !== 'blocked')
                                    <div class="flex-shrink-0">
                                        @include('components.block-button', ['targetUser' => $user])
                                    </div>
                                    @endif
                                </div>
                            @else
                                <a href="{{ route('profile.edit') }}"
                                   class="px-2 py-2 md:px-4 md:py-2 bg-gray-800 hover:bg-gray-700 text-white text-sm rounded-lg transition">
                                    Modifier
                                </a>
                                <a href="{{ route('notifications.index') }}"
                                    class="px-2 py-2 md:px-4 md:py-2 bg-gray-800 hover:bg-gray-700 text-white text-sm rounded-lg transition">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                          <path fill-rule="evenodd" d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zm-2 14a2 2 0 104 0H8z" clip-rule="evenodd" />
                                     </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Bio -->
                    @if($user->bio)
                        <div class="text-gray-300">
                            {{ $user->bio }}
                        </div>
                    @endif

                    <!-- Stats -->
                    <div class="flex gap-6">
                        <div class="text-center">
                            <span class="block text-white font-bold">{{ $user->followersCount() }}</span>
                            <span class="text-gray-400 text-sm">Abonnés</span>
                        </div>
                        <div class="text-center">
                            <span class="block text-white font-bold">{{ $user->followingCount() }}</span>
                            <span class="text-gray-400 text-sm">Abonnements</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
