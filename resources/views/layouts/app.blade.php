<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LinkVault')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface font-body-md text-on-surface antialiased">

    {{-- Navbar --}}
    <header class="fixed top-0 w-full z-50 bg-surface-container-lowest/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
        <div class="h-16 max-w-7xl mx-auto px-6 lg:px-12 flex items-center justify-between gap-4">
            <div class="flex items-center gap-6">
                <a href="{{ route('explore') }}" class="flex items-center gap-2">
                    <span class="font-headline-md text-headline-md tracking-tight text-on-surface">LinkVault</span>
                </a>
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('explore') }}"
                       class="font-label-md text-label-md px-3 py-1.5 rounded-lg transition-colors {{ request()->routeIs('explore') ? 'bg-surface-container text-on-surface font-medium' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                        Explore
                    </a>
                    @auth
                    <a href="{{ route('bookmarks.index') }}"
                       class="font-label-md text-label-md px-3 py-1.5 rounded-lg transition-colors {{ request()->routeIs('bookmarks.*') ? 'bg-surface-container text-on-surface font-medium' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' }}">
                        My Bookmarks
                    </a>
                    @endauth
                </nav>
            </div>

            <div class="flex items-center gap-2">
                @auth
                    <span class="hidden xl:block font-label-md text-label-md text-on-surface-variant">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 font-label-md text-label-md px-3.5 py-2 rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="font-label-md text-label-md px-3.5 py-2 rounded-lg text-on-surface-variant hover:bg-surface-container transition-colors">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Flash messages --}}
    @if (session('success') || session('error'))
        <div class="fixed top-20 right-6 z-50 space-y-2 max-w-sm">
            @if (session('success'))
                <div class="flex items-center justify-between gap-3 bg-surface-container-lowest border border-outline-variant text-on-surface text-body-sm px-4 py-3 rounded-xl shadow-lg">
                    <div class="flex items-center gap-2">
                        <span class="text-secondary material-symbols-outlined text-base" style="font-variation-settings:'FILL' 1;">check_circle</span>
                        {{ session('success') }}
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-on-surface-variant hover:text-on-surface font-bold text-lg leading-none">&times;</button>
                </div>
            @endif
            @if (session('error'))
                <div class="flex items-center justify-between gap-3 bg-error-container text-on-error-container text-body-sm px-4 py-3 rounded-xl shadow-lg">
                    {{ session('error') }}
                    <button onclick="this.parentElement.remove()" class="text-on-surface-variant hover:text-on-surface font-bold text-lg leading-none">&times;</button>
                </div>
            @endif
        </div>
    @endif

    {{-- Main --}}
    <main class="w-full pt-16 bg-surface min-h-screen">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="w-full bg-surface-container-lowest py-8 shadow-[0_-1px_8px_rgba(0,0,0,0.03)]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 flex flex-col md:flex-row items-center justify-between gap-4 font-body-sm text-body-sm text-on-surface-variant">
            <span>&copy; {{ date('Y') }} LinkVault. All rights reserved.</span>
            <div class="flex items-center gap-6">
                <a href="{{ route('explore') }}" class="hover:text-on-surface transition-colors">Explore</a>
                @auth
                    <a href="{{ route('bookmarks.index') }}" class="hover:text-on-surface transition-colors">My Bookmarks</a>
                @endauth
            </div>
        </div>
    </footer>

</body>
</html>
