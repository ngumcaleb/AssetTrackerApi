@extends('layouts.dashboard')

@section('title', 'Search')

@section('content')
<div class="mb-6 animate-fade-in">
    <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">Search Assets</h1>
    <p class="text-on-surface-variant mt-1">Search across all assets in the system.</p>
</div>

<form method="GET" action="{{ route('search.index') }}" class="mb-8 animate-fade-in" style="animation-delay: 0.05s">
    <div class="flex gap-3 max-w-xl">
        <div class="relative flex-1">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-on-surface-variant/50" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input type="text" name="q" value="{{ $query }}" placeholder="Search by name, tag, serial, brand, or location..." autofocus
                class="w-full h-12 pl-10 pr-4 bg-white border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all">
        </div>
        <button type="submit" class="h-12 px-6 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl shadow-lg shadow-brand-700/20 transition-all duration-200 active:scale-[0.98]">Search</button>
    </div>
</form>

@if($query)
    @if($assets->count())
        <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] overflow-hidden animate-fade-in" style="animation-delay: 0.1s">
            <div class="divide-y divide-outline-variant/15">
                @foreach($assets as $asset)
                    <div class="flex items-center gap-4 px-5 py-4 hover:bg-surface-low/40 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M8.25 3.75h7.5" /></svg>
                        </div>
                        <div class="flex-1 min-w-0 grid sm:grid-cols-3 gap-2 items-center">
                            <div>
                                <a href="{{ route('assets.show', $asset) }}" class="text-sm font-semibold text-on-surface hover:text-brand-700 truncate transition-colors">{{ $asset->name }}</a>
                                <span class="text-xs text-on-surface-variant font-mono">{{ $asset->asset_tag }}</span>
                            </div>
                            <span class="text-sm text-on-surface-variant hidden sm:block">{{ $asset->category?->name ?? '-' }}</span>
                            <div class="flex items-center justify-between sm:justify-end gap-3">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-lg @if($asset->status === 'active') text-emerald-700 bg-emerald-50 @elseif($asset->status === 'checked_out') text-amber-700 bg-amber-50 @elseif($asset->status === 'discarded') text-red-700 bg-red-50 @else text-stone-600 bg-stone-50 @endif capitalize">
                                    <span class="w-1.5 h-1.5 rounded-full @if($asset->status === 'active') bg-emerald-500 @elseif($asset->status === 'checked_out') bg-amber-500 @elseif($asset->status === 'discarded') bg-red-500 @else bg-stone-400 @endif"></span>
                                    {{ str_replace('_', ' ', $asset->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <p class="text-sm text-on-surface-variant mt-4">{{ $assets->count() }} result(s) for "{{ $query }}"</p>
    @else
        <div class="text-center py-20 animate-fade-in" style="animation-delay: 0.1s">
            <div class="w-16 h-16 rounded-xl bg-surface-low mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8 text-on-surface-variant/40" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            </div>
            <p class="text-on-surface-variant font-medium">No results found</p>
            <p class="text-sm text-on-surface-variant/70 mt-1">No matches for "{{ $query }}". Try different terms.</p>
        </div>
    @endif
@else
    <div class="text-center py-24 animate-fade-in" style="animation-delay: 0.05s">
        <div class="w-16 h-16 rounded-xl bg-surface-low mx-auto mb-4 flex items-center justify-center">
            <svg class="w-8 h-8 text-on-surface-variant/40" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
        </div>
        <p class="text-on-surface-variant font-medium">Search across all assets</p>
        <p class="text-sm text-on-surface-variant/70 mt-1">Enter a term above to find assets.</p>
    </div>
@endif
@endsection
