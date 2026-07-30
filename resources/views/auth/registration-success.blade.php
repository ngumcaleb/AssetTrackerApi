@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="min-h-dvh flex items-center justify-center p-6 bg-gradient-to-br from-brand-900 via-[#2a0a0a] to-brand-800 relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>

    <div class="relative z-10 max-w-md w-full text-center">
        <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-white/10 backdrop-blur-xl p-4 shadow-2xl ring-1 ring-white/10 flex items-center justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="" class="w-full h-full object-contain brightness-0 invert">
        </div>

        <div class="w-16 h-16 rounded-full bg-emerald-500/20 flex items-center justify-center mx-auto mb-6 ring-1 ring-emerald-500/20">
            <svg class="w-8 h-8 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
        </div>

        <h1 class="text-3xl font-extrabold text-white tracking-tight mb-2">You're all set, {{ Auth::user()->name }}</h1>
        <p class="text-white/50 mb-8">Your account has been created. Start managing assets now.</p>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 bg-white hover:bg-brand-50 text-brand-800 font-bold px-8 py-4 rounded-xl shadow-2xl shadow-black/20 transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
            Go to Dashboard
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
        </a>
    </div>
</div>
@endsection
