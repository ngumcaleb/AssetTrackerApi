@extends('layouts.dashboard')

@section('title', $asset->name . ' History')

@section('content')
<div class="mb-6 animate-fade-in">
    <a href="{{ route('assets.show', $asset) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-brand-700 transition-colors mb-3">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Back to Asset
    </a>
    <h1 class="text-2xl sm:text-3xl font-extrabold text-on-surface tracking-tight">Activity History</h1>
    <p class="text-on-surface-variant mt-1">{{ $asset->name }} ({{ $asset->asset_tag }})</p>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6 animate-fade-in" style="animation-delay: 0.05s">
        @if($asset->activityLogs->count())
            <div class="relative pl-6 space-y-0 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-px before:bg-outline-variant/30">
                @foreach($asset->activityLogs as $log)
                    <div class="relative pb-4 last:pb-0">
                        <div class="absolute -left-[19px] top-1.5 w-[18px] h-[18px] rounded-full border-2 border-white bg-brand-100 flex items-center justify-center">
                            <div class="w-[6px] h-[6px] rounded-full bg-brand-500"></div>
                        </div>
                        <p class="text-sm text-on-surface font-medium">{{ $log->description }}</p>
                        <p class="text-xs text-on-surface-variant mt-0.5">{{ $log->user?->name }} · {{ $log->created_at->format('M d, Y H:i') }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center py-12 text-on-surface-variant">No activity recorded for this asset.</p>
        @endif
    </div>
</div>
@endsection
