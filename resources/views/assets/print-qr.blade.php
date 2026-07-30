@extends('layouts.dashboard')

@section('title', 'Print QR - ' . $asset->name)

@section('content')
<div class="max-w-md mx-auto animate-fade-in">
    <a href="{{ route('assets.show', $asset) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-brand-700 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Back to Asset
    </a>

    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-8 text-center">
        <h1 class="text-xl font-bold text-on-surface mb-1">{{ $asset->name }}</h1>
        <p class="text-sm text-on-surface-variant mb-6 font-mono">{{ $asset->asset_tag }}</p>

        <div class="w-56 h-56 mx-auto flex items-center justify-center mb-6">
            <img src="{{ $qrDataUri }}" alt="QR Code for {{ $asset->asset_tag }}" class="w-full h-full">
        </div>

        <button onclick="window.print()" class="inline-flex items-center gap-2 bg-brand-700 hover:bg-brand-800 text-white font-bold px-6 py-3 rounded-xl shadow-lg shadow-brand-700/20 transition-all duration-200 active:scale-[0.98] text-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" /></svg>
            Print Label
        </button>
    </div>
</div>
@endsection
