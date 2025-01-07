@extends('layouts.app')

@section('title', 'Paramètres du profil')

@section('meta_description', 'Modifiez vos paramètres de profil sur GetOut')

@section('content')
    <div class="min-h-screen bg-black py-24 md:py-10">
        <div class="max-w-2xl mx-auto px-4">
            <h1 class="text-3xl font-bold text-white mb-8">Paramètres du profil</h1>

            <div class="space-y-6">
                <!-- Informations du profil -->
                <div class="bg-black rounded-lg p-6 border border-gray-800">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Mot de passe -->
                <div class="bg-black rounded-lg p-6 border border-gray-800">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Suppression du compte -->
                <div class="bg-black rounded-lg p-6 border border-gray-800">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
