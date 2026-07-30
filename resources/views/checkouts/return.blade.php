@extends('layouts.dashboard')

@section('title', 'Process Return')

@section('content')
<div class="mb-6 animate-fade-in">
    <a href="{{ route('checkouts.show', $checkout) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-brand-700 transition-colors mb-3">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Back to Check-Out
    </a>
    <h1 class="text-2xl sm:text-3xl font-extrabold text-on-surface tracking-tight">Process Return</h1>
    <p class="text-on-surface-variant mt-1">Check in "{{ $checkout->asset?->name }}" from {{ $checkout->assignee_name }}.</p>
</div>

<form method="POST" action="{{ route('checkouts.process-return', $checkout) }}" class="max-w-lg">
    @csrf

    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6 animate-fade-in" style="animation-delay: 0.05s">
        <div class="space-y-4">
            <div>
                <label for="return_notes" class="block text-sm font-medium text-on-surface mb-1.5">Return Notes</label>
                <textarea id="return_notes" name="return_notes" rows="4" class="w-full px-4 py-3 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all resize-none" placeholder="Any notes about the condition or status upon return...">{{ old('return_notes') }}</textarea>
            </div>
        </div>
    </div>

    <div class="mt-6 flex flex-col sm:flex-row gap-3">
        <button type="submit" class="h-12 px-8 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/20 transition-all duration-200 active:scale-[0.98]">Confirm Return</button>
        <a href="{{ route('checkouts.show', $checkout) }}" class="h-12 px-6 border border-outline-variant/30 hover:bg-surface-low text-on-surface font-semibold rounded-xl transition-all duration-200 flex items-center justify-center">Cancel</a>
    </div>
</form>
@endsection
