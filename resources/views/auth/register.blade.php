<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GetOut - Inscription</title>
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
        <!-- Register Container -->
        <main class="flex-1 flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <div class="mb-8 text-center">
                    <img src="{{ asset('images/logo.png') }}" alt="GetOut" class="w-16 h-16 mx-auto">
                </div>

                <!-- Register Form -->
                <div class="bg-black p-8 rounded-lg border border-gray-800">
                    <h2 class="text-2xl font-bold mb-6 text-center">Créer un compte</h2>

                    <form method="POST" action="{{ route('register') }}" class="space-y-6">
                        @csrf

                        <!-- Nom -->
                        <div>
                            <label for="nom" class="block text-sm font-medium mb-2">Nom</label>
                            <input id="nom" type="text" name="nom" required autofocus
                                class="w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                                value="{{ old('nom') }}">
                            @error('nom')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Prénom -->
                        <div>
                            <label for="prenom" class="block text-sm font-medium mb-2">Prénom</label>
                            <input id="prenom" type="text" name="prenom" required
                                class="w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                                value="{{ old('prenom') }}">
                            @error('prenom')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tag -->
                        <div class="mt-4">
                            <x-input-label for="tag" :value="__('@Tag')" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">@</span>
                                <x-text-input id="tag"
                                            class="block mt-1 w-full pl-8"
                                            type="text"
                                            name="tag"
                                            :value="old('tag')"
                                            required
                                            autocomplete="username" />
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Uniquement des lettres minuscules et des chiffres, minimum 3 caractères
                            </p>
                            <x-input-error :messages="$errors->get('tag')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium mb-2">Email</label>
                            <input id="email" type="email" name="email" required
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
                            <ul class="mt-1 text-xs text-gray-500 space-y-1">
                                <li>• Minimum 10 caractères, une lettre majuscule, une lettre minuscule, un chiffre, un caractère spécial (@$!%*#?&...)</li>
                            </ul>
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium mb-2">Confirmer le mot de passe</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="w-full px-4 py-2 bg-gray-900 border border-gray-800 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                        </div>

                        <!-- Terms and Privacy Policy -->
                        <div class="space-y-4">
                            <label class="flex items-start space-x-2">
                                <input type="checkbox" name="terms" required
                                    class="mt-1 rounded bg-gray-900 border-gray-800 text-purple-600 focus:ring-purple-600">
                                <span class="text-sm">
                                    J'accepte les <a href="/terms" class="text-purple-400 hover:text-purple-300">conditions d'utilisation</a>
                                    et la <a href="/privacy" class="text-purple-400 hover:text-purple-300">politique de confidentialité</a>
                                </span>
                            </label>
                        </div>

                        <button type="submit"
                            class="w-full py-2 px-4 bg-purple-600 hover:bg-purple-700 rounded-lg font-medium transition duration-200">
                            Créer mon compte
                        </button>
                    </form>

                    <!-- Login Link -->
                    <p class="mt-6 text-center text-sm">
                        Déjà inscrit ?
                        <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300">
                            Se connecter
                        </a>
                    </p>

                    <!-- RGPD Notice -->
                    <div class="mt-8 text-xs text-gray-500 space-y-2">
                        <p>
                            Vos données personnelles sont traitées par GetOut pour la gestion de votre compte utilisateur.
                        </p>
                        <p>
                            Conformément au RGPD, vous disposez d'un droit d'accès, de rectification, d'effacement et de portabilité de vos données.
                            Pour exercer ces droits ou pour toute question, contactez-nous à
                            <a href="mailto:privacy@getout.com" class="text-purple-400 hover:text-purple-300">privacy@getout.com</a>
                        </p>
                        <p>
                            Pour plus d'informations sur le traitement de vos données, consultez notre
                            <a href="/privacy" class="text-purple-400 hover:text-purple-300">politique de confidentialité</a>.
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
