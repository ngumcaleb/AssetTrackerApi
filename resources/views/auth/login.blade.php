@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="min-h-dvh flex bg-surface">
    <div class="hidden lg:flex flex-1 relative overflow-hidden items-center justify-center bg-gradient-to-br from-brand-900 via-[#2a0a0a] to-brand-800">
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>
        <div class="absolute top-1/3 -left-24 w-80 h-80 rounded-full bg-brand-600/20 blur-[100px]"></div>
        <div class="absolute bottom-1/3 -right-24 w-80 h-80 rounded-full bg-brand-500/15 blur-[100px]"></div>

        <div class="relative z-10 max-w-sm text-center px-8">
            <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-white/10 backdrop-blur-xl p-3.5 shadow-lg ring-1 ring-white/10">
                <img src="{{ asset('images/logo.png') }}" alt="" class="w-full h-full object-contain brightness-0 invert">
            </div>
            <h2 class="text-3xl font-extrabold text-white tracking-tight mb-2">Royalty World</h2>
            <p class="text-white/50 mb-10">Internal Asset Management</p>
            @php $benefits = ['QR code asset tracking', 'Check in/out management', 'Real-time dashboard', 'Team accountability']; @endphp
            <div class="space-y-3.5 text-left">
                @foreach ($benefits as $b)
                    <div class="flex items-center gap-3 bg-white/5 rounded-xl px-4 py-3 backdrop-blur-sm ring-1 ring-white/5">
                        <svg class="w-5 h-5 text-brand-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-white/70 text-sm">{{ $b }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="flex-1 flex items-center justify-center p-6 sm:p-10">
        <div class="w-full max-w-sm">
            <div class="lg:hidden text-center mb-8">
                <div class="w-14 h-14 mx-auto mb-3 rounded-xl bg-brand-100 p-3">
                    <img src="{{ asset('images/logo.png') }}" alt="" class="w-full h-full object-contain">
                </div>
                <h2 class="text-2xl font-bold text-on-surface">Welcome Back</h2>
                <p class="text-sm text-on-surface-variant mt-1">Sign in to manage assets</p>
            </div>

            <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.06)] p-6 sm:p-8">
                @if ($errors->any())
                    <div class="flex items-center gap-2.5 bg-red-50 border border-red-200/60 text-red-700 text-sm font-medium px-4 py-3 rounded-xl mb-6">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-on-surface mb-1.5">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all"
                            placeholder="name@royaltyworld.org">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="text-sm font-medium text-on-surface">Password</label>
                            <a href="{{ route('forgot-password') }}" class="text-xs font-semibold text-brand-700 hover:text-brand-800 transition-colors">Forgot?</a>
                        </div>
                        <input id="password" type="password" name="password" required
                            class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all"
                            placeholder="Enter your password">
                    </div>

                    <button type="submit"
                        class="w-full h-11 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl shadow-lg shadow-brand-700/20 transition-all duration-200 active:scale-[0.98]">
                        Sign In
                    </button>
                </form>
            </div>

            <p class="text-center text-sm text-on-surface-variant mt-8">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-bold text-brand-700 hover:text-brand-800">Create one</a>
            </p>
        </div>
    </div>
</div>
@endsection
