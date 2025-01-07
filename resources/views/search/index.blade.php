@extends('layouts.app')

@section('title', 'Recherche')

@section('meta_description', 'Rechercher des utilisateurs sur GetOut')

@section('content')
<div class="min-h-screen bg-black flex flex-col">
    <div class="flex-1 flex flex-col items-center justify-start pt-20 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-white mb-8">Rechercher</h1>

        <div class="w-full max-w-3xl">
            <livewire:user-search />
        </div>
    </div>
</div>
@endsection
