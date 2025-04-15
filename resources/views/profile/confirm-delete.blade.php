@extends('layouts.app')

@section('title', 'Confirmer la suppression du compte')

@section('content')
<div class="min-h-screen bg-black py-24 md:py-10">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-black rounded-lg p-6 border border-red-800">
            <h1 class="text-2xl font-bold text-white mb-6 text-center">Confirmer la suppression</h1>

            <div class="mb-6 text-center">
                <svg class="w-16 h-16 text-red-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>

                <p class="text-lg text-white">Êtes-vous sûr de vouloir supprimer votre compte ?</p>
                <p class="text-sm text-gray-400 mt-2">Cette action est irréversible. Toutes vos données, conversations et contenus seront définitivement supprimés.</p>
            </div>

            <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-6">
                @csrf
                @method('DELETE')

                <div>
                    <label for="password" class="block text-sm font-medium text-white mb-2">
                        Veuillez saisir votre mot de passe pour confirmer
                    </label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white focus:ring-2 focus:ring-red-600 focus:border-transparent"
                           required>

                    @if($errors->userDeletion->has('password'))
                        <p class="mt-2 text-sm text-red-500">{{ $errors->userDeletion->first('password') }}</p>
                    @endif
                </div>

                <div class="flex justify-between mt-8">
                    <a href="{{ route('profile.edit') }}"
                       class="px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors duration-200">
                        Annuler
                    </a>

                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors duration-200">
                        Supprimer définitivement mon compte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
