<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Royalty World</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800|plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 
                            50: '#fdf2f2', 100: '#fde6e6', 200: '#fbd0d0', 300: '#f7aab5', 
                            400: '#f1798b', 500: '#e03d52', 600: '#c52237', 700: '#800020', 
                            800: '#66001a', 900: '#4a0012', 950: '#2e000b' 
                        },
                        surface: { DEFAULT: '#f8fafc', low: '#f1f5f9' },
                        'on-surface': { DEFAULT: '#0f172a', variant: '#475569' },
                        'outline-variant': '#cbd5e1',
                    },
                    fontFamily: { 
                        sans: ['Plus Jakarta Sans', 'Instrument Sans', 'system-ui', 'sans-serif'] 
                    },
                    boxShadow: {
                        'glass': '0 4px 30px rgba(0, 0, 0, 0.03)',
                        'premium': '0 10px 30px -10px rgba(128, 0, 32, 0.15)',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .animate-fade-in { animation: fadeIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .animate-slide-up { animation: slideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        @media print { 
            header, aside, .no-print { display: none !important; } 
            main { margin-left: 0 !important; padding-top: 1rem !important; } 
        }
    </style>
    @stack('head')
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 min-h-dvh pt-16 lg:pt-20 overflow-x-hidden">

    {{-- Fixed Header --}}
    <header class="fixed inset-x-0 top-0 z-50 h-16 lg:h-20 bg-white/95 backdrop-blur-md border-b border-slate-200/90 flex items-center justify-between px-4 lg:px-8 transition-all shadow-sm">
        <div class="flex items-center gap-4">
            <button id="sidebar-toggle" class="lg:hidden w-10 h-10 rounded-xl hover:bg-slate-100 flex items-center justify-center text-slate-700 transition-colors -ml-1" aria-label="Toggle sidebar">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
            </button>
            
            {{-- Brand Logo & Title --}}
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="Royalty World" class="h-9 lg:h-11 w-auto object-contain transition-transform group-hover:scale-105">
                <div class="hidden sm:block">
                    <span class="block text-base font-extrabold text-slate-900 tracking-tight leading-none">Royalty World</span>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-brand-700 mt-1">Asset Tracker System</span>
                </div>
            </a>
        </div>

        {{-- Global Search Input --}}
        <div class="hidden md:flex items-center flex-1 max-w-md mx-8">
            <form action="{{ route('search.index') }}" method="GET" class="w-full relative">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                <input type="text" name="q" placeholder="Search equipment, tag ID, category..."
                    class="w-full h-10 pl-10 pr-4 bg-slate-100/80 border border-slate-200 focus:bg-white focus:border-brand-700 focus:ring-4 focus:ring-brand-700/10 rounded-xl text-sm text-slate-800 placeholder-slate-400 outline-none transition-all">
            </form>
        </div>

        {{-- User Profile & Actions --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('search.index') }}" class="md:hidden w-10 h-10 rounded-xl hover:bg-slate-100 flex items-center justify-center text-slate-600 transition-colors" aria-label="Search">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            </a>

            <div class="h-8 w-px bg-slate-200 hidden sm:block"></div>

            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-2 py-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-700 to-brand-900 flex items-center justify-center shadow-md shadow-brand-700/20 text-white font-bold text-xs ring-2 ring-white">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <div class="hidden lg:block text-left">
                    <span class="block text-xs font-bold text-slate-900 leading-tight">{{ Auth::user()->name }}</span>
                    <span class="block text-[10px] font-medium text-slate-500 leading-tight truncate max-w-[120px]">{{ Auth::user()->email }}</span>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-9 h-9 rounded-xl hover:bg-rose-50 hover:text-rose-700 text-slate-500 flex items-center justify-center transition-colors" title="Log out">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                </button>
            </form>
        </div>
    </header>

    {{-- Sidebar overlay for Mobile --}}
    <div id="sidebar-overlay" class="lg:hidden fixed inset-0 z-40 bg-slate-950/40 backdrop-blur-sm hidden transition-opacity duration-300"></div>

    {{-- Modern Sidebar --}}
    <aside id="sidebar" class="fixed top-16 lg:top-20 left-0 z-40 lg:z-30 h-[calc(100dvh-4rem)] lg:h-[calc(100dvh-5rem)] w-72 bg-white border-r border-slate-200 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0">

        <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-1">
            @php
                $navGroups = [
                    'MAIN NAVIGATION' => [
                        ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75a2.25 2.25 0 012.25-2.25h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z'],
                        ['route' => 'assets.index', 'label' => 'Assets', 'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z'],
                        ['route' => 'assets.create', 'label' => 'Register Asset', 'icon' => 'M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'checkouts.index', 'label' => 'Check-Outs', 'icon' => 'M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5'],
                    ],
                    'MANAGEMENT' => [
                        ['route' => 'categories.index', 'label' => 'Categories', 'icon' => 'M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z'],
                        ['route' => 'scan.index', 'label' => 'Scan QR Code', 'icon' => 'M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.16a15.53 15.53 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z'],
                        ['route' => 'activity.index', 'label' => 'Activity Audit', 'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'assets.archived', 'label' => 'Archived Assets', 'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z'],
                    ]
                ];
            @endphp

            @foreach ($navGroups as $groupLabel => $items)
                <div class="pt-3 pb-1 px-3">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">{{ $groupLabel }}</span>
                </div>

                @foreach ($items as $item)
                    @php
                        $isActive = Route::currentRouteName() === $item['route'] || (request()->routeIs($item['route'].'*') && $item['route'] !== 'dashboard');
                    @endphp
                    <a href="{{ route($item['route']) }}"
                        class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 group @if($isActive) text-brand-700 bg-brand-50 shadow-sm @else text-slate-600 hover:bg-slate-50 hover:text-slate-900 @endif">
                        @if($isActive)
                            <span class="absolute left-0 top-2 bottom-2 w-1 bg-brand-700 rounded-r-full"></span>
                        @endif
                        <svg class="w-5 h-5 shrink-0 transition-transform duration-200 group-hover:scale-110 @if($isActive) text-brand-700 @else text-slate-400 group-hover:text-slate-600 @endif" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" /></svg>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            @endforeach

            {{-- PDF Audit Reports Group --}}
            <div class="pt-5 pb-1 px-3">
                <span class="text-[10px] font-extrabold text-brand-700 uppercase tracking-widest">OFFICIAL REPORTS (PDF)</span>
            </div>

            <a href="{{ route('reports.assets-pdf') }}"
                class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-brand-50/60 hover:text-brand-700 transition-all border border-slate-200/80 bg-slate-50/50 my-1 group">
                <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-600 group-hover:border-brand-300 group-hover:text-brand-700 shadow-sm shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                </div>
                <span>Download Assets PDF</span>
            </a>

            <a href="{{ route('reports.status-pdf') }}"
                class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 hover:bg-brand-50/60 hover:text-brand-700 transition-all border border-slate-200/80 bg-slate-50/50 group">
                <div class="w-7 h-7 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-600 group-hover:border-brand-300 group-hover:text-brand-700 shadow-sm shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                </div>
                <span>IN / OUT Audit PDF</span>
            </a>
        </nav>

        {{-- Footer Profile Card --}}
        <div class="p-3 border-t border-slate-200 bg-slate-50/50">
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-white transition-all">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-700 to-brand-900 flex items-center justify-center text-white font-bold text-xs shadow-md shadow-brand-700/20 shrink-0">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <div class="flex-1 min-w-0">
                    <span class="block text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name }}</span>
                    <span class="block text-[10px] text-slate-500 font-medium">Account Settings</span>
                </div>
            </a>
        </div>
    </aside>

    {{-- Main Viewport --}}
    <div class="lg:ml-72 flex flex-col min-h-[calc(100dvh-4rem)] lg:min-h-[calc(100dvh-5rem)]">
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
