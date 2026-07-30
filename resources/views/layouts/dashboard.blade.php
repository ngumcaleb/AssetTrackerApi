<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Royalty World</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50: '#fcf2f2', 100: '#f9e6e6', 200: '#f3cccc', 300: '#e99999', 400: '#df6666', 500: '#d43333', 600: '#a92929', 700: '#7a1c1c', 800: '#5c1515', 900: '#3d0e0e', 950: '#2e0a0a' },
                        surface: { DEFAULT: '#f8f7f4', low: '#f1efe9' },
                        'on-surface': { DEFAULT: '#1c1b18', variant: '#6b6862' },
                        'outline-variant': '#d0cdc5',
                    },
                    fontFamily: { sans: ['Instrument Sans', 'system-ui', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .animate-fade-in { animation: fadeIn 0.4s ease-out both; }
        .animate-slide-up { animation: slideUp 0.4s ease-out both; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes pulse-soft { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
        .animate-pulse-soft { animation: pulse-soft 2s ease-in-out infinite; }
        @media print { header, aside, .no-print { display: none !important; } main { margin-left: 0 !important; padding-top: 1rem !important; } }
    </style>
    @vite(['resources/js/app.js'])
    @stack('head')
</head>
<body class="font-sans antialiased bg-surface text-on-surface min-h-dvh pt-14 lg:pt-16 overflow-x-hidden">

    {{-- Fixed Header --}}
    <header class="fixed top-0 left-0 right-0 z-50 h-14 lg:h-16 bg-white/90 backdrop-blur-xl border-b border-outline-variant/20 flex items-center justify-between px-4 lg:px-6">
        <div class="flex items-center gap-3">
            <button id="sidebar-toggle" class="lg:hidden w-9 h-9 rounded-xl hover:bg-surface-low flex items-center justify-center transition-colors -ml-1" aria-label="Toggle sidebar">
                <svg class="w-5 h-5 text-on-surface" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
            </button>
            <div class="w-8 h-8 lg:w-9 lg:h-9 rounded-xl bg-brand-700 flex items-center justify-center shadow-sm shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Royalty World" class="w-5 h-5 lg:w-6 lg:h-6 object-contain brightness-0 invert">
            </div>
        </div>

        <div class="hidden sm:flex items-center flex-1 max-w-md mx-4 lg:mx-8">
            <div class="relative w-full">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-on-surface-variant/50" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                <form action="{{ route('search.index') }}" method="GET">
                    <input type="text" name="q" placeholder="Search assets..."
                        class="w-full h-9 pl-10 pr-4 bg-surface-low/70 border border-outline-variant/20 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/30 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all">
                </form>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('search.index') }}" class="sm:hidden w-9 h-9 rounded-xl hover:bg-surface-low flex items-center justify-center transition-colors" aria-label="Search">
                <svg class="w-5 h-5 text-on-surface-variant" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            </a>
            <a href="{{ route('profile.index') }}" class="flex items-center gap-2.5 px-2 py-1.5 rounded-xl hover:bg-surface-low transition-colors">
                <div class="hidden lg:block text-right">
                    <span class="block text-sm font-semibold text-on-surface leading-tight">{{ Auth::user()->name }}</span>
                    <span class="block text-xs text-on-surface-variant leading-tight">{{ Auth::user()->email }}</span>
                </div>
                <div class="w-8 h-8 rounded-xl bg-brand-700 flex items-center justify-center shadow-sm">
                    <span class="text-white font-bold text-xs">{{ substr(Auth::user()->name, 0, 2) }}</span>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="sm:block">
                @csrf
                <button type="submit" class="w-8 h-8 rounded-xl hover:bg-red-50 flex items-center justify-center text-on-surface-variant hover:text-red-700 transition-colors" title="Log out">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                </button>
            </form>
        </div>
    </header>

    {{-- Sidebar overlay --}}
    <div id="sidebar-overlay" class="lg:hidden fixed inset-0 z-40 bg-black/40 backdrop-blur-sm hidden transition-opacity duration-300"></div>

    {{-- Sidebar --}}
    <aside id="sidebar" class="fixed top-14 lg:top-16 left-0 z-40 lg:z-30 h-[calc(100dvh-3.5rem)] lg:h-[calc(100dvh-4rem)] w-72 bg-white/90 backdrop-blur-xl border-r border-outline-variant/20 shadow-2xl shadow-black/5 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0">

        <nav class="flex-1 overflow-y-auto py-3 lg:py-4 px-2 lg:px-3 space-y-0.5">
            @php
                $navItems = [
                    ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75a2.25 2.25 0 012.25-2.25h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z'],
                    ['route' => 'assets.index', 'label' => 'Assets', 'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z'],
                    ['route' => 'assets.create', 'label' => 'Register Asset', 'icon' => 'M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['route' => 'categories.index', 'label' => 'Categories', 'icon' => 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z'],
                    ['route' => 'checkouts.index', 'label' => 'Check-Outs', 'icon' => 'M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5'],
                    ['route' => 'assets.archived', 'label' => 'Archived', 'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z'],
                    ['route' => 'activity.index', 'label' => 'Activity Log', 'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['route' => 'scan.index', 'label' => 'Scan QR', 'icon' => 'M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.16a15.53 15.53 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z'],
                    ['route' => 'search.index', 'label' => 'Search', 'icon' => 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z'],
                ];
            @endphp

            @foreach ($navItems as $item)
                @php
                    $isActive = Route::currentRouteName() === $item['route'] || (request()->routeIs($item['route'].'*') && $item['route'] !== 'dashboard');
                @endphp
                <a href="{{ route($item['route']) }}"
                    class="relative flex items-center gap-3 px-3 lg:px-4 py-2.5 lg:py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 active:scale-[0.98] @if($isActive) text-brand-700 bg-brand-50/80 @else text-on-surface-variant hover:bg-surface-low/80 hover:text-on-surface @endif">
                    @if($isActive)
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 rounded-r-full bg-brand-600"></span>
                    @endif
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" /></svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="p-2 lg:p-3 border-t border-outline-variant/20">
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-3 lg:px-4 py-2.5 rounded-xl text-sm font-semibold text-on-surface-variant hover:bg-surface-low/80 hover:text-on-surface transition-all duration-200">
                <div class="w-8 h-8 rounded-xl bg-brand-700 flex items-center justify-center shadow-sm shrink-0">
                    <span class="text-white font-bold text-xs">{{ substr(Auth::user()->name, 0, 2) }}</span>
                </div>
                <span class="font-semibold text-on-surface">Profile</span>
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-0.5">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 lg:px-4 py-2.5 rounded-xl text-sm font-semibold text-on-surface-variant hover:bg-red-50 hover:text-red-700 transition-all duration-200 active:scale-[0.98]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                    Log out
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="lg:ml-72 flex flex-col min-h-[calc(100dvh-3.5rem)] lg:min-h-[calc(100dvh-4rem)]">
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full animate-fade-in">
            @yield('content')
        </main>
    </div>

    <script>
        (function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggle = document.getElementById('sidebar-toggle');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }

            if (toggle) toggle.addEventListener('click', openSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeSidebar();
            });

            sidebar.querySelectorAll('nav a').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) closeSidebar();
                });
            });
        })();
    </script>

    @stack('scripts')

</body>
</html>
