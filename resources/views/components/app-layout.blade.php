<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-900">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-gray-800 border-b border-purple-500/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="text-purple-500 font-bold text-xl">
                                {{ config('app.name', 'Laravel') }}
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('dashboard') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 
                                      {{ request()->routeIs('dashboard') ? 'border-purple-500 text-purple-100' : 'border-transparent text-gray-300 hover:text-purple-200' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('tasks.trash') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 
                                      {{ request()->routeIs('tasks.trash') ? 'border-purple-500 text-purple-100' : 'border-transparent text-gray-300 hover:text-purple-200' }}">
                                Trash
                            </a>
                        </div>
                    </div>

                    <!-- User Menu -->
                    <div class="flex items-center">
                        @auth
                            <div class="relative">
                                <button class="flex items-center text-purple-100 hover:text-purple-200">
                                    <span class="mr-2">{{ Auth::user()->name }}</span>
                                    <img class="h-8 w-8 rounded-full object-cover" 
                                         src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=6D28D9&color=fff' }}" 
                                         alt="{{ Auth::user()->name }}">
                                </button>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="ml-4">
                                @csrf
                                <button type="submit" class="text-gray-300 hover:text-purple-200">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-300 hover:text-purple-200">Login</a>
                            <a href="{{ route('register') }}" class="ml-4 text-gray-300 hover:text-purple-200">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-gray-800 border-b border-purple-500/20">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html> 