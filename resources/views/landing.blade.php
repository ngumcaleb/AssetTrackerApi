@extends('layouts.app')

@section('content')
<div class="relative min-h-dvh flex items-center justify-center overflow-hidden bg-[#1a0a0a]">
    <div class="absolute inset-0 bg-gradient-to-br from-brand-900/90 via-[#1a0a0a] to-black"></div>

    <div class="absolute inset-0 opacity-[0.04]" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 40px 40px;"></div>

    <div class="absolute top-1/4 -left-32 w-96 h-96 rounded-full bg-brand-800/20 blur-[120px]"></div>
    <div class="absolute bottom-1/4 -right-32 w-96 h-96 rounded-full bg-brand-700/20 blur-[120px]"></div>

    <div class="relative z-10 w-full max-w-4xl mx-auto px-6 text-center py-20">
        <div class="mb-8 animate-fade-in-up">
            <div class="w-24 h-24 sm:w-28 sm:h-28 mx-auto mb-6 rounded-2xl bg-white/10 backdrop-blur-xl p-3 shadow-2xl shadow-black/40 ring-1 ring-white/10">
                <img src="{{ asset('images/logo.png') }}" alt="" class="w-full h-full object-contain brightness-0 invert">
            </div>
        </div>

        <h1 class="text-5xl sm:text-7xl lg:text-8xl font-extrabold text-white tracking-tight leading-[0.95] mb-5 animate-fade-in-up" style="animation-delay: 0.1s">
            Manage
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-200 to-brand-400">Assets</span>
            <br>with Purpose
        </h1>

        <p class="text-base sm:text-lg lg:text-xl text-white/50 max-w-lg mx-auto mb-10 leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s">
            Royalty World's central system for tracking equipment, supplies, and resources across every field office.
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4 animate-fade-in-up" style="animation-delay: 0.3s">
            <a href="{{ route('register') }}" class="group inline-flex items-center justify-center gap-2.5 bg-white hover:bg-brand-50 text-brand-900 font-bold px-8 py-4 rounded-xl shadow-2xl shadow-black/20 transition-all duration-300 text-base sm:text-lg hover:scale-[1.02]">
                Get Started
                <svg class="w-4 h-4 sm:w-5 sm:h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
            </a>
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 border border-white/10 text-white/60 hover:text-white hover:bg-white/5 font-semibold px-8 py-4 rounded-xl transition-all duration-300 text-base sm:text-lg backdrop-blur-sm">
                Sign In
            </a>
        </div>

        <div class="mt-16 grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 max-w-lg mx-auto animate-fade-in-up" style="animation-delay: 0.4s">
            @php $features = [['QR Tracking', 'Scan & locate'], ['Check In/Out', 'Full audit trail'], ['Dashboard', 'Real-time insights']]; @endphp
            @foreach ($features as $f)
                <div class="text-center">
                    <span class="block text-white/90 font-semibold text-sm">{{ $f[0] }}</span>
                    <span class="block text-white/40 text-xs mt-0.5">{{ $f[1] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-[#1a0a0a] to-transparent"></div>
</div>
@endsection
