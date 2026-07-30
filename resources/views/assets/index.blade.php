@extends('layouts.dashboard')

@section('title', 'Assets')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">Assets</h1>
        <p class="text-on-surface-variant mt-1">Manage all registered equipment and supplies.</p>
    </div>
    <a href="{{ route('assets.create') }}" class="inline-flex items-center gap-2 bg-brand-700 hover:bg-brand-800 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-brand-700/20 shrink-0">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Register Asset
    </a>
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
