@extends('layouts.app')

@section('title', 'Forgot Password')

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
            <p class="text-white/50">Asset Management for Missions</p>
        </div>
    </div>

    <div class="flex-1 flex items-center justify-center p-6 sm:p-10">
        <div class="w-full max-w-sm">
            @if (session('status'))
                <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.06)] p-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-emerald-500/20 flex items-center justify-center mx-auto mb-5 ring-1 ring-emerald-500/20">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-on-surface mb-2">Check your email</h2>
                    <p class="text-sm text-on-surface-variant mb-6">We've sent a password reset link to your email address.</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-700 hover:text-brand-800 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                        Back to Sign In
                    </a>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.06)] p-8">
                    @if ($errors->any())
                        <div class="flex items-center gap-2.5 bg-red-50 border border-red-200/60 text-red-700 text-sm font-medium px-4 py-3 rounded-xl mb-6">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <div class="text-center mb-6">
                        <div class="w-14 h-14 rounded-full bg-brand-50 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-on-surface mb-2">Forgot password?</h2>
                        <p class="text-sm text-on-surface-variant">Enter your email and we'll send you a reset link.</p>
                    </div>

                    <form method="POST" action="{{ route('forgot-password') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-on-surface mb-1.5">Email address</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all"
                                placeholder="you@royaltyworld.org">
                        </div>

                        <button type="submit"
                            class="w-full h-11 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl shadow-lg shadow-brand-700/20 transition-all duration-200 active:scale-[0.98]">
                            Send Reset Link
                        </button>
                    </form>
                </div>

                <a href="{{ route('login') }}" class="flex items-center justify-center gap-1.5 text-sm font-semibold text-brand-700 hover:text-brand-800 mt-8 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Back to Sign In
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
