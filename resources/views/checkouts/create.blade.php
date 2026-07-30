@extends('layouts.dashboard')

@section('title', 'New Check-Out')

@section('content')
<div class="mb-6 animate-fade-in">
    <a href="{{ route('checkouts.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-brand-700 transition-colors mb-3">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Back to Check-Outs
    </a>
    <h1 class="text-2xl sm:text-3xl font-extrabold text-on-surface tracking-tight">New Check-Out</h1>
    <p class="text-on-surface-variant mt-1">Assign an asset to a team member.</p>
</div>

<form method="POST" action="{{ route('checkouts.store') }}" class="max-w-2xl">
    @csrf

    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6 space-y-5 animate-fade-in" style="animation-delay: 0.05s">
        <div class="grid sm:grid-cols-2 gap-4 sm:gap-5">
            <div class="sm:col-span-2">
                <label for="asset_id" class="block text-sm font-medium text-on-surface mb-1.5">Asset *</label>
                <select id="asset_id" name="asset_id" required
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
                    <option value="">Select asset</option>
                    @foreach($assets as $a)
                        <option value="{{ $a->id }}" @selected(old('asset_id') == $a->id || request('asset_id') == $a->id)>
                            {{ $a->name }} ({{ $a->asset_tag }})
                        </option>
                    @endforeach
                </select>
                @error('asset_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="assignee_name" class="block text-sm font-medium text-on-surface mb-1.5">Assignee Name *</label>
                <input id="assignee_name" type="text" name="assignee_name" value="{{ old('assignee_name') }}" required
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all"
                    placeholder="e.g. John Smith">
                @error('assignee_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="department" class="block text-sm font-medium text-on-surface mb-1.5">Department</label>
                <input id="department" type="text" name="department" value="{{ old('department') }}"
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all"
                    placeholder="e.g. Logistics">
            </div>

            <div>
                <label for="expected_return" class="block text-sm font-medium text-on-surface mb-1.5">Expected Return</label>
                <input id="expected_return" type="date" name="expected_return" value="{{ old('expected_return') }}"
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
                @error('expected_return') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="purpose" class="block text-sm font-medium text-on-surface mb-1.5">Purpose</label>
                <input id="purpose" type="text" name="purpose" value="{{ old('purpose') }}"
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all"
                    placeholder="e.g. Field monitoring">
            </div>

            <div>
                <label for="destination" class="block text-sm font-medium text-on-surface mb-1.5">Destination</label>
                <input id="destination" type="text" name="destination" value="{{ old('destination') }}"
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all"
                    placeholder="e.g. Regional Office">
            </div>

            <div class="sm:col-span-2">
                <label for="notes" class="block text-sm font-medium text-on-surface mb-1.5">Notes</label>
                <textarea id="notes" name="notes" rows="3"
                    class="w-full px-4 py-3 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all resize-none">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>

    <div class="mt-6 flex flex-col sm:flex-row gap-3">
        <button type="submit" class="h-12 px-8 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl shadow-lg shadow-brand-700/20 transition-all duration-200 active:scale-[0.98]">Check Out Asset</button>
        <a href="{{ route('checkouts.index') }}" class="h-12 px-6 border border-outline-variant/30 hover:bg-surface-low text-on-surface font-semibold rounded-xl transition-all duration-200 flex items-center justify-center">Cancel</a>
    </div>
</form>
@endsection
