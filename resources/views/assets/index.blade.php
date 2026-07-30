@extends('layouts.dashboard')

@section('title', 'Assets')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">Assets</h1>
        <p class="text-on-surface-variant mt-1">Manage all registered equipment and supplies.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('reports.assets-pdf') }}" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition-all shadow-md shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
            Export All Assets (PDF)
        </a>
        <a href="{{ route('reports.status-pdf') }}" class="inline-flex items-center gap-2 bg-sky-700 hover:bg-sky-800 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition-all shadow-md shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
            IN / OUT Audit (PDF)
        </a>
        <a href="{{ route('assets.create') }}" class="inline-flex items-center gap-2 bg-brand-700 hover:bg-brand-800 text-white font-bold px-4 py-2.5 rounded-xl text-sm transition-all shadow-lg shadow-brand-700/20 shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Register Asset
        </a>
    </div>
</div>

<form method="GET" class="flex flex-col sm:flex-row gap-3 mb-6 animate-fade-in" style="animation-delay: 0.05s">
    <div class="relative flex-1">
        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-on-surface-variant/60" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, tag, serial..." class="w-full h-11 pl-10 pr-4 bg-white border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all">
    </div>
    <select name="status" class="w-full sm:w-auto h-11 px-4 bg-white border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
        <option value="">All Status</option>
        <option value="active" @selected(request('status') === 'active')>Active</option>
        <option value="checked_out" @selected(request('status') === 'checked_out')>Checked Out</option>
        <option value="archived" @selected(request('status') === 'archived')>Archived</option>
        <option value="discarded" @selected(request('status') === 'discarded')>Discarded</option>
    </select>
    <select name="category" class="w-full sm:w-auto h-11 px-4 bg-white border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="h-11 px-5 bg-brand-700 hover:bg-brand-800 text-white font-semibold rounded-xl text-sm transition-all duration-200">Filter</button>
</form>

<div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] overflow-hidden animate-fade-in" style="animation-delay: 0.1s">
    @if($assets->count())
        <div class="divide-y divide-outline-variant/15">
            @foreach($assets as $asset)
                <div class="flex items-center gap-4 px-5 py-4 hover:bg-surface-low/40 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center shrink-0 overflow-hidden">
                        @if($asset->photo_url)
                            <img src="{{ asset('storage/' . $asset->photo_url) }}" alt="" class="w-full h-full object-cover">
                        @else
                            <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M8.25 3.75h7.5" /></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0 grid sm:grid-cols-4 gap-2 sm:gap-4 items-center">
                        <div class="sm:col-span-1">
                            <a href="{{ route('assets.show', $asset) }}" class="block text-sm font-semibold text-on-surface hover:text-brand-700 truncate transition-colors">{{ $asset->name }}</a>
                            <span class="text-xs text-on-surface-variant font-mono">{{ $asset->asset_tag }}</span>
                        </div>
                        <span class="text-sm text-on-surface-variant hidden sm:block">{{ $asset->category?->name ?? '-' }}</span>
                        <span class="text-sm text-on-surface-variant hidden sm:block">{{ $asset->location ?? '-' }}</span>
                        <div class="flex items-center justify-between sm:justify-end gap-2">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-lg @if($asset->status === 'active') text-emerald-700 bg-emerald-50 @elseif($asset->status === 'checked_out') text-amber-700 bg-amber-50 @elseif($asset->status === 'discarded') text-red-700 bg-red-50 @else text-stone-600 bg-stone-50 @endif capitalize">
                                <span class="w-1.5 h-1.5 rounded-full @if($asset->status === 'active') bg-emerald-500 @elseif($asset->status === 'checked_out') bg-amber-500 @elseif($asset->status === 'discarded') bg-red-500 @else bg-stone-400 @endif"></span>
                                {{ str_replace('_', ' ', $asset->status) }}
                            </span>
                            <div class="flex items-center gap-1.5">
                                @if($asset->status === 'active')
                                    <a href="{{ route('checkouts.create', ['asset_id' => $asset->id]) }}" class="text-xs font-semibold text-white bg-brand-700 hover:bg-brand-800 px-2.5 py-1.5 rounded-lg transition-colors">Check Out</a>
                                @elseif($asset->status === 'checked_out' && $asset->currentCheckout)
                                    <a href="{{ route('checkouts.return', ['checkout' => $asset->currentCheckout]) }}" class="text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 px-2.5 py-1.5 rounded-lg transition-colors">Check In</a>
                                @endif
                                <a href="{{ route('assets.edit', $asset) }}" class="text-xs font-semibold text-on-surface-variant hover:text-brand-700 transition-colors">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="px-5 py-4 border-t border-outline-variant/15">
            {{ $assets->links() }}
        </div>
    @else
        <div class="text-center py-16 px-6">
            <div class="w-14 h-14 rounded-xl bg-surface-low mx-auto mb-4 flex items-center justify-center">
                <svg class="w-7 h-7 text-on-surface-variant/50" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M8.25 3.75h7.5" /></svg>
            </div>
            <p class="text-on-surface-variant font-medium">No assets found</p>
            <p class="text-sm text-on-surface-variant/70 mt-1">Try adjusting your search or <a href="{{ route('assets.create') }}" class="text-brand-700 font-semibold hover:text-brand-800">register a new asset</a>.</p>
        </div>
    @endif
</div>
@endsection
