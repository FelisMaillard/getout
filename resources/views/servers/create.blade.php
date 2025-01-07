@extends('layouts.app')

@section('title', 'Créer un serveur')

@section('meta_description', 'Créez un nouveau serveur sur GetOut')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 md:pt-0">
        <div class="bg-black rounded-lg border border-gray-800 p-6">
            <h1 class="text-2xl font-bold text-white mb-6">Créer un serveur</h1>

            <form action="{{ route('servers.store') }}" method="POST">
                @csrf

                {{-- Nom du serveur --}}
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-white mb-2">
                        Nom du serveur
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           class="w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                           value="{{ old('name') }}"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-white mb-2">
                        Description
                        <span class="text-gray-400">(optionnel)</span>
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="3"
                              class="w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Type de confidentialité --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-white mb-2">
                        Confidentialité
                    </label>
                    <div class="space-y-4">
                        <label class="flex items-center">
                            <input type="radio"
                                   name="privacy_type"
                                   value="public"
                                   class="w-4 h-4 text-purple-600 border-gray-800 focus:ring-purple-600 focus:ring-offset-gray-900"
                                   {{ old('privacy_type', 'public') === 'public' ? 'checked' : '' }}>
                            <span class="ml-2 text-white">Public</span>
                            <span class="ml-2 text-sm text-gray-400">- Tout le monde peut rejoindre</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio"
                                   name="privacy_type"
                                   value="private"
                                   class="w-4 h-4 text-purple-600 border-gray-800 focus:ring-purple-600 focus:ring-offset-gray-900"
                                   {{ old('privacy_type') === 'private' ? 'checked' : '' }}>
                            <span class="ml-2 text-white">Privé</span>
                            <span class="ml-2 text-sm text-gray-400">- Sur invitation uniquement</span>
                        </label>
                    </div>
                    @error('privacy_type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Limite de membres --}}
                <div class="mb-6">
                    <label for="max_members" class="block text-sm font-medium text-white mb-2">
                        Limite de membres
                    </label>
                    <input type="number"
                           name="max_members"
                           id="max_members"
                           min="2"
                           max="1000"
                           class="w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                           value="{{ old('max_members', 100) }}"
                           required>
                    <p class="mt-1 text-sm text-gray-400">Minimum 2, maximum 1000</p>
                    @error('max_members')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Boutons --}}
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('servers.index') }}"
                       class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
                        Créer le serveur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
