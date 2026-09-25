<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Artisan Runner - Royalty World AssetTracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        code, pre { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen p-4 sm:p-8">
    <div class="max-w-3xl mx-auto space-y-6">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-slate-800 pb-5">
            <div>
                <span class="text-xs font-bold tracking-widest text-brand-400 text-rose-500 uppercase">SERVER UTILITY</span>
                <h1 class="text-2xl font-extrabold text-white tracking-tight flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span>
                    Web Artisan Console
                </h1>
                <p class="text-xs text-slate-400 mt-1">Execute Artisan commands directly from your browser without SSH access.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-xs font-semibold px-3 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg transition-colors">
                ← App Dashboard
            </a>
        </div>

        <!-- Quick Actions Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @php
                $buttons = [
                    ['label' => 'Run Migrations', 'cmd' => 'migrate', 'desc' => 'php artisan migrate --force', 'color' => 'bg-emerald-600 hover:bg-emerald-500'],
                    ['label' => 'Migration Status', 'cmd' => 'migrate:status', 'desc' => 'Check applied migrations', 'color' => 'bg-blue-600 hover:bg-blue-500'],
                    ['label' => 'Storage Link', 'cmd' => 'storage:link', 'desc' => 'Symlink public storage', 'color' => 'bg-indigo-600 hover:bg-indigo-500'],
                    ['label' => 'Clear All Caches', 'cmd' => 'optimize:clear', 'desc' => 'Config, route, view caches', 'color' => 'bg-amber-600 hover:bg-amber-500'],
                    ['label' => 'Route Clear', 'cmd' => 'route:clear', 'desc' => 'Clear route cache', 'color' => 'bg-purple-600 hover:bg-purple-500'],
                    ['label' => 'Config Clear', 'cmd' => 'config:clear', 'desc' => 'Clear configuration cache', 'color' => 'bg-rose-600 hover:bg-rose-500'],
                ];
            @endphp

            @foreach($buttons as $b)
                <form method="POST" action="{{ route('artisan.run') }}">
                    @csrf
                    <input type="hidden" name="key" value="{{ $key }}">
                    <input type="hidden" name="from_ui" value="1">
                    <input type="hidden" name="command" value="{{ $b['cmd'] }}">
                    <button type="submit" class="w-full text-left p-3.5 rounded-xl border border-slate-700/60 bg-slate-800/80 hover:bg-slate-800 transition-all hover:border-slate-600 group shadow-sm">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-bold text-white group-hover:text-emerald-400 transition-colors">{{ $b['label'] }}</span>
                            <span class="w-2 h-2 rounded-full {{ $b['color'] }}"></span>
                        </div>
                        <p class="text-[11px] text-slate-400 font-mono">{{ $b['desc'] }}</p>
                    </button>
                </form>
            @endforeach
        </div>

        <!-- Custom Command Input -->
        <div class="bg-slate-800/60 rounded-2xl border border-slate-700/60 p-5">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Custom Artisan Command</h2>
            <form method="POST" action="{{ route('artisan.run') }}" class="flex gap-2">
                @csrf
                <input type="hidden" name="key" value="{{ $key }}">
                <input type="hidden" name="from_ui" value="1">
                <div class="relative flex-1">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 font-mono text-sm select-none">php artisan</span>
                    <input type="text" name="command" placeholder="migrate --force" value="{{ $lastCommand ?? 'migrate' }}" required
                        class="w-full h-11 pl-28 pr-4 bg-slate-900 border border-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 rounded-xl text-sm font-mono text-slate-200 outline-none transition-all placeholder:text-slate-600">
                </div>
                <button type="submit" class="h-11 px-6 bg-emerald-600 hover:bg-emerald-500 font-bold text-white rounded-xl text-sm transition-colors shadow-sm flex items-center gap-1.5 shrink-0">
                    Run ↵
                </button>
            </form>
        </div>

        <!-- Command Output Terminal -->
        @if(isset($output))
            <div class="bg-slate-950 rounded-2xl border border-slate-800 overflow-hidden shadow-xl">
                <div class="bg-slate-900/90 px-4 py-2.5 border-b border-slate-800 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-rose-500 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>
                        <span class="text-xs font-mono text-slate-400 ml-2">Terminal Output &mdash; php artisan {{ $lastCommand ?? '' }}</span>
                    </div>
                    <span class="text-[11px] font-bold px-2 py-0.5 rounded {{ ($status ?? '') === 'error' ? 'bg-red-950 text-red-400 border border-red-800' : 'bg-emerald-950 text-emerald-400 border border-emerald-800' }}">
                        {{ strtoupper($status ?? 'DONE') }}
                    </span>
                </div>
                <div class="p-5 font-mono text-xs overflow-x-auto max-h-96">
                    <pre class="{{ ($status ?? '') === 'error' ? 'text-rose-400' : 'text-emerald-400' }} leading-relaxed whitespace-pre-wrap">{{ $output }}</pre>
                </div>
            </div>
        @endif

        <div class="text-center text-xs text-slate-500 pt-4">
            Direct one-click URL: <code class="text-slate-400 bg-slate-800/80 px-2 py-1 rounded">/artisan/migrate?key={{ $key }}</code>
        </div>
    </div>
</body>
</html>
