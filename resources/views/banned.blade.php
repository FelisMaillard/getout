@extends('layouts.app')

@section('title', 'Compte suspendu')

@section('content')
<div class="min-h-screen bg-black py-12">
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 md:pt-0">
        <div class="bg-black rounded-lg border border-red-800 p-6">
            <div class="text-center mb-8">
                <svg class="w-16 h-16 text-red-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h1 class="text-2xl font-bold text-white mb-2">Compte suspendu</h1>
                <p class="text-gray-400">Votre compte a été temporairement suspendu.</p>
            </div>

            <div class="bg-red-900/20 border border-red-800 rounded-lg p-4 mb-6">
                @php
                    $sanction = session('user_sanction');
                @endphp

                @if($sanction)
                    <h2 class="text-lg font-medium text-white mb-2">Informations sur la suspension</h2>

                    <div class="space-y-2 text-gray-300">
                        <p><strong>Motif:</strong> {{ $sanction->typeReport->description ?? 'Non spécifié' }}</p>

                        @if($sanction->description)
                            <p><strong>Description:</strong> {{ $sanction->description }}</p>
                        @endif

                        <p><strong>Début:</strong> {{ $sanction->start_at ? $sanction->start_at->format('d/m/Y H:i') : 'Immédiat' }}</p>

                        @if($sanction->is_permanent)
                            <p><strong>Durée:</strong> <span class="text-red-400">Permanente</span></p>
                        @else
                            <p><strong>Fin:</strong> {{ $sanction->end_at ? $sanction->end_at->format('d/m/Y H:i') : 'Indéterminée' }}</p>

                            @if($sanction->end_at)
                                <p><strong>Temps restant:</strong> {{ now()->diffForHumans($sanction->end_at, ['parts' => 2]) }}</p>
                            @endif
                        @endif
                    </div>
                @else
                    <p class="text-gray-300">Aucune information supplémentaire n'est disponible.</p>
                @endif
            </div>

            <div class="text-center">
                <p class="text-gray-400 mb-4">Si vous pensez qu'il s'agit d'une erreur, vous pouvez contacter notre équipe de support.</p>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                        Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
