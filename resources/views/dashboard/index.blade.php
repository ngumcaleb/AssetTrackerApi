@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8 animate-fade-in">
    <h1 class="text-3xl sm:text-4xl font-extrabold text-on-surface tracking-tight">Good {{ \Carbon\Carbon::now()->hour < 12 ? 'morning' : (\Carbon\Carbon::now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ Auth::user()->name }}</h1>
    <p class="text-on-surface-variant mt-1.5">Overview of Royalty World's asset management system.</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @php
        $stats = [
            ['value' => $totalAssets, 'label' => 'Total Assets', 'color' => 'from-brand-600 to-brand-700', 'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M8.25 3.75h7.5'],
            ['value' => $activeAssets, 'label' => 'Active', 'color' => 'from-emerald-600 to-emerald-700', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['value' => $checkedOut, 'label' => 'Checked Out', 'color' => 'from-amber-600 to-amber-700', 'icon' => 'M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5'],
            ['value' => $archived, 'label' => 'Archived', 'color' => 'from-stone-500 to-stone-600', 'icon' => 'M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25'],
        ];
    @endphp
    @foreach ($stats as $s)
        <div class="relative overflow-hidden bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-5 hover:shadow-[0_4px_24px_-4px_rgba(0,0,0,0.08)] transition-shadow duration-300">
            <div class="absolute top-0 right-0 w-24 h-24 -translate-y-6 translate-x-6">
                <div class="w-full h-full rounded-full bg-gradient-to-br {{ $s['color'] }} opacity-[0.06]"></div>
            </div>
            <div class="relative">
                <span class="block text-3xl font-extrabold text-on-surface tracking-tight">{{ $s['value'] }}</span>
                <span class="text-sm text-on-surface-variant mt-0.5 block">{{ $s['label'] }}</span>
            </div>
        </div>
    @endforeach
</div>

<div class="grid lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-bold text-lg text-on-surface">Recent Assets</h2>
            <a href="{{ route('assets.index') }}" class="text-sm font-semibold text-brand-700 hover:text-brand-800 transition-colors">View all</a>
        </div>
        @if($recentAssets->count())
            <div class="space-y-1">
                @foreach($recentAssets as $asset)
                    <a href="{{ route('assets.show', $asset) }}" class="flex items-center gap-3 py-2.5 px-3 -mx-3 rounded-xl hover:bg-surface-low/60 transition-colors group">
                        <div class="w-9 h-9 rounded-xl bg-brand-50 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M8.25 3.75h7.5" /></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="block text-sm font-semibold text-on-surface truncate group-hover:text-brand-700 transition-colors">{{ $asset->name }}</span>
                            <span class="text-xs text-on-surface-variant">{{ $asset->asset_tag }} · {{ $asset->category?->name }}</span>
                        </div>
                        <span class="text-xs font-semibold @if($asset->status === 'active') text-emerald-700 bg-emerald-50 @elseif($asset->status === 'checked_out') text-amber-700 bg-amber-50 @else text-stone-600 bg-stone-50 @endif px-2.5 py-1 rounded-lg capitalize">{{ $asset->status }}</span>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-sm text-on-surface-variant py-8 text-center">No assets yet. <a href="{{ route('assets.create') }}" class="text-brand-700 font-semibold hover:text-brand-800">Register one</a>.</p>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-bold text-lg text-on-surface">Active Check-Outs</h2>
            <a href="{{ route('checkouts.index') }}" class="text-sm font-semibold text-brand-700 hover:text-brand-800 transition-colors">View all</a>
        </div>
        @if($activeCheckouts->count())
            <div class="space-y-1">
                @foreach($activeCheckouts as $checkout)
                    <div class="flex items-center gap-3 py-2.5 px-3 -mx-3 rounded-xl hover:bg-surface-low/60 transition-colors group">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="block text-sm font-semibold text-on-surface truncate">{{ $checkout->asset?->name }}</span>
                                <span class="text-xs text-brand-700 bg-brand-50 px-1.5 py-0.5 rounded font-semibold">Active</span>
                            </div>
                            <span class="text-xs text-on-surface-variant">To: {{ $checkout->assignee_name }}@if($checkout->expected_return) · Due {{ $checkout->expected_return->format('M d, Y') }}@endif</span>
                        </div>
                        <a href="{{ route('checkouts.return', ['checkout' => $checkout]) }}" class="text-xs font-semibold text-brand-700 hover:text-brand-800 bg-brand-50 hover:bg-brand-100 px-3 py-1.5 rounded-lg transition-colors shrink-0">Check in</a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-on-surface-variant py-8 text-center">No active check-outs.</p>
        @endif
    </div>

    <div class="lg:col-span-2 bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="font-bold text-lg text-on-surface">Recent Activity</h2>
                <p class="text-xs text-on-surface-variant">Live audit log of check-ins and check-outs</p>
            </div>
            <a href="{{ route('activity.index') }}" class="text-xs font-semibold text-brand-700 hover:text-brand-800 bg-brand-50 hover:bg-brand-100 px-3 py-1.5 rounded-lg transition-colors">
                View full log &rarr;
            </a>
        </div>
        @if($recentActivity->count())
            <div class="relative pl-8 space-y-0 before:absolute before:left-[15px] before:top-3 before:bottom-3 before:w-0.5 before:bg-outline-variant/20">
                @foreach($recentActivity as $log)
                    @php
                        $isCheckIn = in_array($log->type, ['asset_checked_in', 'check_in', 'returned']);
                        $isCheckOut = in_array($log->type, ['asset_checked_out', 'check_out', 'checkout']);
                    @endphp
                    <div class="relative pb-4 last:pb-0 group">
                        {{-- Color-Coded Icon Node --}}
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
                                <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" /></svg>
                            @elseif($isCheckOut)
                                <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" /></svg>
                            @elseif($log->type === 'asset_created')
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @endif
                        </div>

                        {{-- Activity Card Container --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-3 rounded-xl transition-colors
                            @if($isCheckIn)
                                bg-emerald-50/50 border border-emerald-100/60
                            @elseif($isCheckOut)
                                bg-rose-50/50 border border-rose-100/60
                            @else
                                bg-surface-low/40 border border-transparent
                            @endif">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider
                                        @if($isCheckIn) bg-emerald-600 text-white
                                        @elseif($isCheckOut) bg-rose-600 text-white
                                        @elseif($log->type === 'asset_created') bg-sky-600 text-white
                                        @else bg-slate-600 text-white @endif">
                                        @if($isCheckIn) IN @elseif($isCheckOut) OUT @else {{ str_replace(['asset_', '_'], ['', ' '], $log->type) }} @endif
                                    </span>
                                    <span class="text-[11px] text-on-surface-variant/70 font-mono">{{ $log->created_at->format('H:i') }}</span>
                                </div>

                                <p class="text-xs font-semibold text-on-surface leading-snug">{{ $log->description }}</p>
                                <p class="text-[11px] text-on-surface-variant mt-0.5">By <strong>{{ $log->user?->name ?? 'System' }}</strong></p>
                            </div>

                            <span class="text-[11px] font-medium text-on-surface-variant/70 whitespace-nowrap self-start sm:self-center shrink-0">
                                {{ $log->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-on-surface-variant py-8 text-center">No activity yet.</p>
        @endif
    </div>
</div>
@endsection
