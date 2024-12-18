@extends('layouts.app')

@section('title', 'Accueil')

@section('meta_description', 'Bienvenue sur GetOut - Connectez-vous avec vos amis et partagez vos moments')

@section('content')
<div class="min-h-screen bg-gray-950 flex flex-col">
    <div class="flex-1 flex flex-col items-center justify-start pt-20 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-white mb-8">Fil d’actualités</h1>

        <div class="w-full max-w-3xl space-y-6">
            <!-- Mock post 1 -->
            <div class="bg-black border border-gray-800 rounded-lg p-4">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                        JD
                    </div>
                    <div>
                        <h3 class="text-white font-semibold">John Doe</h3>
                        <p class="text-gray-400">@johnnyD</p>
                    </div>
                </div>
                <p class="text-white mb-4">Voici une super journée passée avec mes amis ! 🌟</p>
                <div class="bg-gray-900 rounded-lg h-60 overflow-hidden">
                    <img src="https://via.placeholder.com/600x400" alt="Post image" class="w-full h-full object-cover">
                </div>
                <div class="flex justify-between items-center mt-4">
                    <div class="flex items-center space-x-2 text-gray-400">
                        <button class="hover:text-purple-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 9l-5 5m0 0l-5-5m5 5V3"></path>
                            </svg>
                        </button>
                        <span>120</span>
                    </div>
                    <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">Commenter</button>
                </div>
            </div>

            <!-- Mock post 2 -->
            <div class="bg-black border border-gray-800 rounded-lg p-4">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                        AC
                    </div>
                    <div>
                        <h3 class="text-white font-semibold">Alice Cooper</h3>
                        <p class="text-gray-400">@alice_in_wonderland</p>
                    </div>
                </div>
                <p class="text-white mb-4">J’ai enfin terminé mon projet ! 🚀 Qui veut en savoir plus ? 👇</p>
                <div class="flex justify-between items-center mt-4">
                    <div class="flex items-center space-x-2 text-gray-400">
                        <button class="hover:text-purple-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 9l-5 5m0 0l-5-5m5 5V3"></path>
                            </svg>
                        </button>
                        <span>75</span>
                    </div>
                    <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">Commenter</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
