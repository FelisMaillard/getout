@extends('layouts.app')

@section('title', 'Recherche')

@section('meta_description', 'Rechercher des utilisateurs sur GetOut')

@section('content')
<div class="min-h-screen bg-gray-950 flex flex-col">
    <div class="flex-1 flex flex-col items-center justify-start pt-20 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-white mb-8">Rechercher</h1>

        <div class="w-full max-w-3xl">
            <livewire:user-search />
        </div>
    </div>

    <div class="fixed bottom-0 left-0 right-0 bg-purple-900/90 backdrop-blur-sm p-4 text-center">
        <p class="text-sm text-white">
            Conformément au RGPD, les résultats de recherche sont limités aux utilisateurs ayant accepté d'être trouvables.
            Vos termes de recherche ne sont pas conservés.
            <a href="/privacy" class="underline hover:text-purple-200">En savoir plus</a>
        </p>
    </div>
</div>
@endsection
