<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Artisan Security Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-slate-800/80 border border-slate-700/60 rounded-2xl p-6 sm:p-8 shadow-2xl space-y-6">
        <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-500 flex items-center justify-center mx-auto text-xl font-bold">
                🔒
            </div>
            <h1 class="text-xl font-extrabold text-white">Artisan Console Access</h1>
            <p class="text-xs text-slate-400">Enter your security key or log in to run commands without SSH.</p>
        </div>

        <form method="GET" action="{{ route('artisan.index') }}" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Artisan Security Key</label>
                <input type="password" name="key" placeholder="Enter key (default: assettracker-artisan-2026)" required autofocus
                    class="w-full h-11 px-4 bg-slate-900 border border-slate-700 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 rounded-xl text-sm font-mono text-slate-200 outline-none transition-all placeholder:text-slate-600">
            </div>
            <button type="submit" class="w-full h-11 bg-rose-600 hover:bg-rose-500 font-bold text-white rounded-xl text-sm transition-colors shadow-sm">
                Authorize Access
            </button>
        </form>

        <div class="pt-4 border-t border-slate-700/50 text-center">
            <a href="{{ route('login') }}" class="text-xs text-slate-400 hover:text-white transition-colors">
                Or sign in with your admin account →
            </a>
        </div>
    </div>
</body>
</html>
