@extends('layouts.dashboard')

@section('title', 'Check-Outs')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">Check-Outs</h1>
        <p class="text-on-surface-variant mt-1">Track asset assignments and returns.</p>
    </div>
    <a href="{{ route('checkouts.create') }}" class="inline-flex items-center gap-2 bg-brand-700 hover:bg-brand-800 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-brand-700/20 shrink-0">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        New Check-Out
    </a>
</div>

<form method="GET" class="flex flex-col sm:flex-row gap-3 mb-6 animate-fade-in" style="animation-delay: 0.05s">
    <div class="relative flex-1">
        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-on-surface-variant/60" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by asset name or tag..." class="w-full h-11 pl-10 pr-4 bg-white border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all">
    </div>
    <select name="status" class="h-11 px-4 bg-white border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
        <option value="">All Status</option>
        <option value="active" @selected(request('status') === 'active')>Active</option>
        <option value="returned" @selected(request('status') === 'returned')>Returned</option>
    </select>
    <button type="submit" class="h-11 px-5 bg-brand-700 hover:bg-brand-800 text-white font-semibold rounded-xl text-sm transition-all duration-200">Filter</button>
</form>

<div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] overflow-hidden animate-fade-in" style="animation-delay: 0.1s">
    @if($checkouts->count())
        <div class="divide-y divide-outline-variant/15">
            @foreach($checkouts as $checkout)
                <div class="flex items-center gap-4 px-5 py-4 hover:bg-surface-low/40 transition-colors">
                    <div class="w-10 h-10 rounded-xl @if($checkout->returned_at) bg-emerald-50 @else bg-amber-50 @endif flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 @if($checkout->returned_at) text-emerald-600 @else text-amber-600 @endif" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                    </div>
                    <div class="flex-1 min-w-0 grid sm:grid-cols-3 gap-2 items-center">
                        <div>
                            <a href="{{ route('checkouts.show', $checkout) }}" class="block text-sm font-semibold text-on-surface hover:text-brand-700 truncate transition-colors">{{ $checkout->asset?->name ?? 'Unknown Asset' }}</a>
                            <span class="text-xs text-on-surface-variant">To: {{ $checkout->assignee_name }}</span>
                        </div>
                        <span class="text-sm text-on-surface-variant hidden sm:block">{{ $checkout->department ?? '-' }}</span>
                        <div class="flex items-center justify-between sm:justify-end gap-3">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-lg @if($checkout->returned_at) text-emerald-700 bg-emerald-50 @else text-amber-700 bg-amber-50 @endif capitalize">
                                <span class="w-1.5 h-1.5 rounded-full @if($checkout->returned_at) bg-emerald-500 @else bg-amber-500 @endif"></span>
                                {{ $checkout->returned_at ? 'Returned' : 'Active' }}
                            </span>
                            <a href="{{ route('checkouts.show', $checkout) }}" class="text-xs font-semibold text-brand-700 hover:text-brand-800 transition-colors">View</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="px-5 py-4 border-t border-outline-variant/15">
            {{ $checkouts->links() }}
        </div>
    @else
        <div class="text-center py-16 px-6">
            <div class="w-14 h-14 rounded-xl bg-surface-low mx-auto mb-4 flex items-center justify-center">
                <svg class="w-7 h-7 text-on-surface-variant/50" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
            </div>
            <p class="text-on-surface-variant font-medium">No check-outs found</p>
            <p class="text-sm text-on-surface-variant/70 mt-1"><a href="{{ route('checkouts.create') }}" class="text-brand-700 font-semibold hover:text-brand-800">Check out an asset</a> to get started.</p>
        </div>
    @endif
</div>
@endsection
