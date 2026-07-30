@extends('layouts.dashboard')

@section('title', 'Activity Log')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">Activity Log</h1>
        <p class="text-on-surface-variant mt-1">Audit trail of all equipment check-ins, check-outs, and updates.</p>
    </div>
</div>

<form method="GET" class="flex flex-col sm:flex-row gap-3 mb-6 animate-fade-in" style="animation-delay: 0.05s">
    <div class="relative flex-1">
        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-on-surface-variant/60" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search activity description..." class="w-full h-11 pl-10 pr-4 bg-white border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all">
    </div>
    <select name="type" class="w-full sm:w-auto h-11 px-4 bg-white border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
        <option value="">All Action Types</option>
        <option value="asset_checked_in" @selected(request('type') === 'asset_checked_in')>Check-Ins (IN)</option>
        <option value="asset_checked_out" @selected(request('type') === 'asset_checked_out')>Check-Outs (OUT)</option>
        <option value="asset_created" @selected(request('type') === 'asset_created')>Asset Created</option>
        <option value="asset_updated" @selected(request('type') === 'asset_updated')>Asset Updated</option>
        <option value="asset_archived" @selected(request('type') === 'asset_archived')>Archived</option>
        <option value="asset_restored" @selected(request('type') === 'asset_restored')>Restored</option>
    </select>
    <button type="submit" class="h-11 px-5 bg-brand-700 hover:bg-brand-800 text-white font-semibold rounded-xl text-sm transition-all duration-200 shadow-sm">Filter</button>
</form>

<div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6 sm:p-8 animate-fade-in" style="animation-delay: 0.1s">
    @if($activities->count())
        <div class="relative pl-8 space-y-0 before:absolute before:left-[15px] before:top-3 before:bottom-3 before:w-0.5 before:bg-outline-variant/20">
            @foreach($activities as $log)
                @php
                    $isCheckIn = in_array($log->type, ['asset_checked_in', 'check_in', 'returned']);
                    $isCheckOut = in_array($log->type, ['asset_checked_out', 'check_out', 'checkout']);
                @endphp
                <div class="relative pb-6 last:pb-0 group">
                    {{-- Color-Coded Activity Node --}}
                    <div class="absolute -left-[31px] top-1 w-8 h-8 rounded-full border-2 border-white flex items-center justify-center transition-transform group-hover:scale-110 shadow-sm
                        @if($isCheckIn)
                            bg-emerald-50 text-emerald-600 border-emerald-500
                        @elseif($isCheckOut)
                            bg-rose-50 text-rose-600 border-rose-500
                        @elseif($log->type === 'asset_created')
                            bg-sky-50 text-sky-600 border-sky-500
                        @else
                            bg-slate-50 text-slate-600 border-slate-400
                        @endif">
                        @if($isCheckIn)
                            {{-- Check-In Green Arrow Down/In Icon --}}
                            <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg>
                        @elseif($isCheckOut)
                            {{-- Check-Out Red Arrow Up/Out Icon --}}
                            <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" /></svg>
                        @elseif($log->type === 'asset_created')
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @endif
                    </div>

                    {{-- Activity Card --}}
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-3.5 rounded-xl transition-colors
                        @if($isCheckIn)
                            bg-emerald-50/50 border border-emerald-100/60
                        @elseif($isCheckOut)
                            bg-rose-50/50 border border-rose-100/60
                        @else
                            bg-surface-low/40 hover:bg-surface-low/70 border border-transparent
                        @endif">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                {{-- Status Tag --}}
                                <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider
                                    @if($isCheckIn)
                                        bg-emerald-600 text-white
                                    @elseif($isCheckOut)
                                        bg-rose-600 text-white
                                    @elseif($log->type === 'asset_created')
                                        bg-sky-600 text-white
                                    @else
                                        bg-slate-600 text-white
                                    @endif">
                                    @if($isCheckIn) Check-In (IN) @elseif($isCheckOut) Check-Out (OUT) @else {{ str_replace(['asset_', '_'], ['', ' '], $log->type) }} @endif
                                </span>
                                <span class="text-xs text-on-surface-variant/70 font-mono">{{ $log->created_at->format('M d, Y · H:i') }}</span>
                            </div>

                            <p class="text-sm font-semibold text-on-surface leading-snug">{{ $log->description }}</p>

                            <p class="text-xs text-on-surface-variant mt-1 flex items-center gap-2">
                                <span>Recorded by <strong>{{ $log->user?->name ?? 'System' }}</strong></span>
                                @if($log->asset)
                                    <span>&bull;</span>
                                    <a href="{{ route('assets.show', $log->asset) }}" class="inline-flex items-center gap-1 font-semibold text-brand-700 hover:underline">
                                        View Asset details &rarr;
                                    </a>
                                @endif
                            </p>
                        </div>

                        <span class="text-xs font-medium text-on-surface-variant/70 whitespace-nowrap self-start sm:self-center shrink-0">
                            {{ $log->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6 pt-4 border-t border-outline-variant/15">
            {{ $activities->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-12 h-12 rounded-2xl bg-surface-low flex items-center justify-center mx-auto mb-3 text-on-surface-variant/50">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <p class="text-on-surface font-semibold">No activity logs found</p>
            <p class="text-xs text-on-surface-variant mt-1">Activities will be automatically recorded when equipment is checked in or out.</p>
        </div>
    @endif
</div>
@endsection
