@extends('layouts.dashboard')

@section('title', 'Archived Assets')

@section('content')
<div class="mb-6 animate-fade-in">
    <a href="{{ route('assets.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-brand-700 transition-colors mb-3">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Back to Assets
    </a>
    <h1 class="text-2xl sm:text-3xl font-extrabold text-on-surface tracking-tight">Archived Assets</h1>
    <p class="text-on-surface-variant mt-1">Restore archived assets to make them active again.</p>
</div>

@if($assets->count())
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 animate-fade-in" style="animation-delay: 0.05s">
        @foreach($assets as $asset)
            <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-5 hover:shadow-[0_4px_24px_-4px_rgba(0,0,0,0.08)] transition-shadow duration-300">
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-9 h-9 rounded-xl bg-stone-50 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-stone-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25" /></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="block text-sm font-semibold text-on-surface truncate">{{ $asset->name }}</span>
                        <span class="text-xs text-on-surface-variant font-mono">{{ $asset->asset_tag }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-stone-600 bg-stone-50 px-2.5 py-1 rounded-lg font-semibold">Archived</span>
                    <form method="POST" action="{{ route('assets.restore', $asset) }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-semibold text-brand-700 hover:text-brand-800 bg-brand-50 hover:bg-brand-100 px-3 py-1.5 rounded-lg transition-colors">Restore</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">
        {{ $assets->links() }}
    </div>
@else
    <div class="text-center py-16 animate-fade-in">
        <div class="w-14 h-14 rounded-xl bg-surface-low mx-auto mb-4 flex items-center justify-center">
            <svg class="w-7 h-7 text-on-surface-variant/50" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4" /></svg>
        </div>
        <p class="text-on-surface-variant font-medium">No archived assets</p>
    </div>
@endif
@endsection
