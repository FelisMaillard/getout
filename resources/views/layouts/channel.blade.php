<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $channel->name }} - {{ $server->name }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white antialiased overflow-hidden">
    <div class="h-screen flex">
        <!-- Main Content -->
        <main class="flex-1 flex">
            @yield('content')
        </main>

        <!-- Members Sidebar (Desktop) -->
        <div class="hidden lg:flex flex-col w-64 bg-black border-l border-gray-800">
            <div class="p-4 border-b border-gray-800">
                <h2 class="text-lg font-medium text-white">Membres</h2>
                <p class="text-sm text-gray-400">{{ $server->members->count() }} membres</p>
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-2">
                @foreach($server->members as $member)
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            @if($member->user->profile_photo_url)
                                <img src="{{ Storage::url($member->user->profile_photo_url) }}"
                                     alt="{{ $member->user->name }}"
                                     class="w-8 h-8 rounded-full">
                            @else
                                <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">
                                        {{ substr($member->user->prenom, 0, 1) . substr($member->user->nom, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="text-white text-sm font-medium">{{ $member->user->nom }}</p>
                            <p class="text-gray-400 text-xs">{{ $member->role }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
