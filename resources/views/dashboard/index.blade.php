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
            <h2 class="font-bold text-lg text-on-surface">Recent Activity</h2>
            <a href="{{ route('activity.index') }}" class="text-sm font-semibold text-brand-700 hover:text-brand-800 transition-colors">View all</a>
        </div>
        @if($recentActivity->count())
            <div class="relative pl-6 space-y-0 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-px before:bg-outline-variant/40">
                @foreach($recentActivity as $log)
                    <div class="relative pb-4 last:pb-0">
                        <div class="absolute -left-[19px] top-1.5 w-[18px] h-[18px] rounded-full border-2 border-white bg-brand-100 flex items-center justify-center">
                            <div class="w-[6px] h-[6px] rounded-full bg-brand-500"></div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <span class="block text-sm text-on-surface font-medium">{{ $log->description }}</span>
                                <span class="text-xs text-on-surface-variant">{{ $log->user?->name }} · {{ $log->created_at->diffForHumans() }}</span>
                            </div>
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
