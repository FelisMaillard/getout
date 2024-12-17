<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GetOut - Connexion</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">
</head>
<body class="bg-gray-950 text-white antialiased h-full">
    <div class="min-h-screen flex">
        <!-- Login Container -->
        <main class="flex-1 flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <div class="mb-8 text-center">
                    <img src="{{ asset('images/logo.png') }}" alt="GetOut" class="w-16 h-16 mx-auto">
                </div>

                <!-- Login Form -->
                <div class="bg-black p-8 rounded-lg border border-gray-800">
                    <h2 class="text-2xl font-bold mb-6 text-center">Connexion</h2>

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium mb-2">Email</label>
                            <input id="email" type="email" name="email" required autofocus
                                class="w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                                value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium mb-2">Mot de passe</label>
                            <input id="password" type="password" name="password" required
                                class="w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center space-x-2 text-sm">
                                <input type="checkbox" name="remember" class="rounded bg-gray-900 border-gray-800 text-purple-600 focus:ring-purple-600">
                                <span>Se souvenir de moi</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-purple-400 hover:text-purple-300">
                                    Mot de passe oublié ?
                                </a>
                            @endif
                        </div>

                        <button type="submit"
                            class="w-full py-2 px-4 bg-purple-600 hover:bg-purple-700 rounded-lg font-medium transition duration-200">
                            Se connecter
                        </button>
                    </form>

                    <!-- Register Link -->
                    <p class="mt-6 text-center text-sm">
                        Pas encore de compte ?
                        <a href="{{ route('register') }}" class="text-purple-400 hover:text-purple-300">
                            S'inscrire
                        </a>
                    </p>

                    <!-- RGPD Notice -->
                    <p class="mt-8 text-xs text-gray-500 text-center">
                        En vous connectant, vous acceptez notre
                        <a href="/privacy" class="text-purple-400 hover:text-purple-300">politique de confidentialité</a>
                        et nos
                        <a href="/terms" class="text-purple-400 hover:text-purple-300">conditions d'utilisation</a>.
                        Vos données de connexion sont conservées de manière sécurisée pendant 30 jours.
                    </p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
