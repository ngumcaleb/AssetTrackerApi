@extends('layouts.dashboard')

@section('title', 'Activity Log')

@section('content')
<div class="mb-6 animate-fade-in">
    <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">Activity Log</h1>
    <p class="text-on-surface-variant mt-1">All system activity recorded.</p>
</div>

<form method="GET" class="flex flex-col sm:flex-row gap-3 mb-6 animate-fade-in" style="animation-delay: 0.05s">
    <div class="relative flex-1">
        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-on-surface-variant/60" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search activity..." class="w-full h-11 pl-10 pr-4 bg-white border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all">
    </div>
    <select name="type" class="w-full sm:w-auto h-11 px-4 bg-white border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
        <option value="">All Types</option>
        <option value="asset_created" @selected(request('type') === 'asset_created')>Asset Created</option>
        <option value="asset_updated" @selected(request('type') === 'asset_updated')>Asset Updated</option>
        <option value="asset_checked_out" @selected(request('type') === 'asset_checked_out')>Checked Out</option>
        <option value="asset_checked_in" @selected(request('type') === 'asset_checked_in')>Checked In</option>
        <option value="asset_archived" @selected(request('type') === 'asset_archived')>Archived</option>
        <option value="asset_restored" @selected(request('type') === 'asset_restored')>Restored</option>
    </select>
    <button type="submit" class="h-11 px-5 bg-brand-700 hover:bg-brand-800 text-white font-semibold rounded-xl text-sm transition-all duration-200">Filter</button>
</form>

<div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6 animate-fade-in" style="animation-delay: 0.1s">
    @if($activities->count())
        <div class="relative pl-6 space-y-0 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-px before:bg-outline-variant/30">
            @foreach($activities as $log)
                <div class="relative pb-5 last:pb-0">
                    <div class="absolute -left-[19px] top-1.5 w-[18px] h-[18px] rounded-full border-2 border-white bg-brand-100 flex items-center justify-center">
                        <div class="w-[6px] h-[6px] rounded-full bg-brand-500"></div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-on-surface font-medium">{{ $log->description }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">
                                {{ $log->user?->name ?? 'System' }} · {{ $log->created_at->format('M d, Y H:i') }}
                                @if($log->asset) · <a href="{{ route('assets.show', $log->asset) }}" class="text-brand-700 hover:text-brand-800 font-semibold">View asset</a> @endif
                            </p>
                        </div>
                        <span class="text-xs text-on-surface-variant/70 whitespace-nowrap shrink-0">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4 pt-4 border-t border-outline-variant/15">
            {{ $activities->links() }}
        </div>
    @else
        <p class="text-center py-12 text-on-surface-variant">No activity recorded yet.</p>
    @endif
</div>
@endsection
