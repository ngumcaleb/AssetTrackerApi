@extends('layouts.dashboard')

@section('title', 'Check-Out Details')

@section('content')
<div class="mb-6 animate-fade-in">
    <a href="{{ route('checkouts.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-brand-700 transition-colors mb-3">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Back to Check-Outs
    </a>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6 animate-fade-in" style="animation-delay: 0.05s">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-extrabold text-on-surface tracking-tight">Check-Out Details</h1>
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg @if($checkout->returned_at) text-emerald-700 bg-emerald-50 @else text-amber-700 bg-amber-50 @endif capitalize">
                <span class="w-1.5 h-1.5 rounded-full @if($checkout->returned_at) bg-emerald-500 @else bg-amber-500 @endif"></span>
                {{ $checkout->returned_at ? 'Returned' : 'Active' }}
            </span>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div class="py-2.5 border-b border-outline-variant/10">
                <span class="block text-xs font-medium text-on-surface-variant uppercase tracking-wider">Asset</span>
                @if($checkout->asset)
                    <a href="{{ route('assets.show', $checkout->asset) }}" class="block text-sm font-semibold text-brand-700 hover:text-brand-800 mt-0.5">{{ $checkout->asset->name }}</a>
                @else
                    <span class="block text-sm text-on-surface-variant mt-0.5">Deleted Asset</span>
                @endif
            </div>
            <div class="py-2.5 border-b border-outline-variant/10">
                <span class="block text-xs font-medium text-on-surface-variant uppercase tracking-wider">Assignee</span>
                <span class="block text-sm font-semibold text-on-surface mt-0.5">{{ $checkout->assignee_name }}</span>
            </div>
            <div class="py-2.5 border-b border-outline-variant/10">
                <span class="block text-xs font-medium text-on-surface-variant uppercase tracking-wider">Department</span>
                <span class="block text-sm font-semibold text-on-surface mt-0.5">{{ $checkout->department ?? '-' }}</span>
            </div>
            <div class="py-2.5 border-b border-outline-variant/10">
                <span class="block text-xs font-medium text-on-surface-variant uppercase tracking-wider">Purpose</span>
                <span class="block text-sm font-semibold text-on-surface mt-0.5">{{ $checkout->purpose ?? '-' }}</span>
            </div>
            <div class="py-2.5 border-b border-outline-variant/10">
                <span class="block text-xs font-medium text-on-surface-variant uppercase tracking-wider">Destination</span>
                <span class="block text-sm font-semibold text-on-surface mt-0.5">{{ $checkout->destination ?? '-' }}</span>
            </div>
            <div class="py-2.5 border-b border-outline-variant/10">
                <span class="block text-xs font-medium text-on-surface-variant uppercase tracking-wider">Checked Out</span>
                <span class="block text-sm font-semibold text-on-surface mt-0.5">{{ $checkout->created_at?->format('M d, Y H:i') ?? '-' }}</span>
            </div>
            @if($checkout->expected_return)
                <div class="py-2.5 border-b border-outline-variant/10">
                    <span class="block text-xs font-medium text-on-surface-variant uppercase tracking-wider">Expected Return</span>
                    <span class="block text-sm font-semibold text-on-surface mt-0.5">{{ $checkout->expected_return->format('M d, Y') }}</span>
                </div>
            @endif
            @if($checkout->returned_at)
                <div class="py-2.5 border-b border-outline-variant/10">
                    <span class="block text-xs font-medium text-on-surface-variant uppercase tracking-wider">Returned At</span>
                    <span class="block text-sm font-semibold text-on-surface mt-0.5">{{ $checkout->returned_at->format('M d, Y H:i') }}</span>
                </div>
            @endif
        </div>

        @if($checkout->notes)
            <div class="mt-4 pt-4 border-t border-outline-variant/10">
                <span class="block text-xs font-medium text-on-surface-variant uppercase tracking-wider mb-1.5">Notes</span>
                <p class="text-sm text-on-surface leading-relaxed">{{ $checkout->notes }}</p>
            </div>
        @endif

        @if($checkout->return_notes)
            <div class="mt-4 pt-4 border-t border-outline-variant/10">
                <span class="block text-xs font-medium text-on-surface-variant uppercase tracking-wider mb-1.5">Return Notes</span>
                <p class="text-sm text-on-surface leading-relaxed">{{ $checkout->return_notes }}</p>
            </div>
        @endif
    </div>

    @if(!$checkout->returned_at)
        <a href="{{ route('checkouts.return', ['checkout' => $checkout]) }}" class="mt-4 inline-flex items-center gap-2 h-12 px-8 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/20 transition-all duration-200 active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            Process Return
        </a>
    @endif
</div>
@endsection
