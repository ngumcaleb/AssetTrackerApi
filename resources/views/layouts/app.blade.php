<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Royalty World') — Royalty World</title>
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
        .animate-fade-in { animation: fadeIn 0.4s ease-out both; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
</head>
<body class="font-sans antialiased bg-surface text-on-surface min-h-screen flex flex-col overflow-x-hidden">

    <!-- Navigation -->
    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-outline-variant/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo.png') }}" alt="" class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl object-contain">
                </a>

                @auth('web')
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-on-surface-variant hover:text-on-surface px-4 py-2 rounded-lg hover:bg-surface-low transition-colors">
                            Log out
                        </button>
                    </form>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-on-surface-variant hover:text-on-surface px-4 py-2 rounded-lg hover:bg-surface-low transition-colors">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-brand-700 hover:bg-brand-800 px-5 py-2 rounded-lg shadow-sm transition-colors">
                            Get Started
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-outline-variant/40 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="" class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl object-contain">
                </div>
                <p class="text-sm text-outline">&copy; {{ date('Y') }} Royalty World. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
