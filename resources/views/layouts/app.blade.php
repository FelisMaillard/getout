<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'GetOut - Connectez-vous avec vos amis')">
    <meta name="keywords" content="@yield('meta_keywords', 'réseau social, amis, partage, communauté')">
    <meta name="author" content="GetOut">
    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="GetOut - @yield('title', 'Accueil')">
    <meta property="og:description" content="@yield('meta_description', 'GetOut - Connectez-vous avec vos amis')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="GetOut - @yield('title', 'Accueil')">
    <meta name="twitter:description" content="@yield('meta_description', 'GetOut - Connectez-vous avec vos amis')">

    <title>GetOut - @yield('title', 'Accueil')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- RGPD Banner Script -->
    @include('components.rgpd-banner')
</head>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full">
<head>
    <!-- Métadonnées de base -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Titre dynamique -->
    <title>{{ config('app.name', 'GetOut') }} - @yield('title', 'Accueil')</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'GetOut - Connectez-vous avec vos amis et partagez vos moments')" />
    <meta name="keywords" content="@yield('meta_keywords', 'réseau social, amis, événements, sorties, partage, communauté')" />
    <meta name="author" content="GetOut" />
    <meta name="robots" content="index, follow" />
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="@yield('title', 'GetOut - Connectez-vous avec vos amis')" />
    <meta property="og:description" content="@yield('meta_description', 'GetOut - Connectez-vous avec vos amis et partagez vos moments')" />
    <meta property="og:image" content="@yield('meta_image', asset('images/og-image.jpg'))" />
    <meta property="og:site_name" content="GetOut" />
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}" />

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:url" content="{{ url()->current() }}" />
    <meta name="twitter:title" content="@yield('title', 'GetOut - Connectez-vous avec vos amis')" />
    <meta name="twitter:description" content="@yield('meta_description', 'GetOut - Connectez-vous avec vos amis et partagez vos moments')" />
    <meta name="twitter:image" content="@yield('meta_image', asset('images/twitter-image.jpg'))" />

    <!-- PWA Meta Tags -->
    <meta name="application-name" content="GetOut">
    <meta name="theme-color" content="#000000">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="GetOut">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" type="image/x-icon">

    <!-- Optionnel: Manifest pour PWA -->
    <link rel="manifest" href="{{ asset('favicons/site.webmanifest') }}">

    <!-- Sécurité -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; img-src 'self' data: https:; style-src 'self' 'unsafe-inline' https://fonts.bunny.net; font-src 'self' https://fonts.bunny.net;">
    <meta name="referrer" content="strict-origin-when-cross-origin">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Favicons -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white antialiased h-full">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <nav class="fixed inset-y-0 left-0 z-50 flex group/sidebar">
            <div class="w-16 group-hover/sidebar:w-64 bg-black flex flex-col transition-all duration-300 ease-in-out border-r border-gray-800">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="pt-8 p-4 mb-8 flex justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="GetOut" class="w-8 h-8">
                </a>

                <!-- Navigation Links -->
                <div class="flex-1 flex flex-col space-y-2">
                    <ul class="px-2 space-y-2">
                        <!-- Home -->
                        <li>
                            <a href="{{ route('home') }}" class="flex items-center p-2 hover:bg-gray-800 rounded-lg transition-colors duration-200">
                                <div class="w-6 flex justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                </div>
                                <span class="ml-4 text-white w-0 group-hover/sidebar:w-auto overflow-hidden whitespace-nowrap transition-all duration-300">Accueil</span>
                            </a>
                        </li>

                        <!-- Search -->
                        <li>
                            <a href="{{ route('search.index') }}" class="flex items-center p-2 hover:bg-gray-800 rounded-lg transition-colors duration-200">
                                <div class="w-6 flex justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <span class="ml-4 text-white w-0 group-hover/sidebar:w-auto overflow-hidden whitespace-nowrap transition-all duration-300">Rechercher</span>
                            </a>
                        </li>

                        <!-- Messages -->
                        <li>
                            <a href="#" class="flex items-center p-2 hover:bg-gray-800 rounded-lg transition-colors duration-200">
                                <div class="w-6 flex justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                    </svg>
                                </div>
                                <span class="ml-4 text-white w-0 group-hover/sidebar:w-auto overflow-hidden whitespace-nowrap transition-all duration-300">Messages</span>
                            </a>
                        </li>

                        <!-- Notifications -->
                        <li>
                            <a href="#" class="flex items-center p-2 hover:bg-gray-800 rounded-lg transition-colors duration-200">
                                <div class="w-6 flex justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                </div>
                                <span class="ml-4 text-white w-0 group-hover/sidebar:w-auto overflow-hidden whitespace-nowrap transition-all duration-300">Notifications</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Bottom Section -->
                <div class="px-2 pb-4 space-y-2">
                    <!-- Profile -->
                    <a href="#" class="flex items-center p-2 hover:bg-gray-800 rounded-lg transition-colors duration-200">
                        <div class="w-6 flex justify-center">
                            @if(Auth::user()->profile_photo_url)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-6 h-6 rounded-full">
                            @else
                                <div class="w-6 h-6 rounded-full bg-purple-600 flex items-center justify-center text-xs font-medium text-white">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <span class="ml-4 text-white w-0 group-hover/sidebar:w-auto overflow-hidden whitespace-nowrap transition-all duration-300">Profil</span>
                    </a>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center p-2 text-red-500 hover:bg-red-500/10 rounded-lg transition-colors duration-200">
                            <div class="w-6 flex justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </div>
                            <span class="ml-4 w-0 group-hover/sidebar:w-auto overflow-hidden whitespace-nowrap transition-all duration-300">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 ml-16">
            <div class=""   >
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
