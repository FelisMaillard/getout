@extends('layouts.app')

@section('title', 'Signaler ' . $user->prenom . ' ' . $user->nom)

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 md:pt-0">
        <div class="bg-black rounded-lg border border-gray-800 p-6">
            <h1 class="text-2xl font-bold text-white mb-6">Signaler {{ $user->prenom }} {{ $user->nom }}</h1>

            <form action="{{ route('reports.user.store', $user) }}" method="POST">
                @csrf

                <!-- Type de signalement -->
                <div class="mb-6">
                    <label for="type_report_id" class="block text-sm font-medium text-white mb-2">
                        Motif du signalement
                    </label>
                    <select
                        name="type_report_id"
                        id="type_report_id"
                        class="w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                        required
                    >
                        <option value="">Sélectionnez un motif</option>
                        @foreach($reportTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }} - {{ $type->description }}</option>
                        @endforeach
                    </select>
                    @error('type_report_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-white mb-2">
                        Description détaillée
                    </label>
                    <textarea
                        name="description"
                        id="description"
                        rows="5"
                        class="w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                        placeholder="Veuillez décrire en détail le problème. Plus vous fournirez d'informations, plus notre équipe pourra répondre efficacement."
                        required
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Avertissement -->
                <div class="mb-6 p-4 bg-red-900/20 border border-red-800 rounded-lg">
                    <p class="text-sm text-gray-300">
                        <span class="font-semibold text-red-400">Important :</span>
                        Tout signalement abusif ou non fondé pourra entraîner des sanctions contre votre compte.
                        Nos équipes examinent attentivement chaque cas signalé.
                    </p>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('profile.show', $user->tag) }}"
                       class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
                        Soumettre le signalement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
