@extends('layouts.dashboard')

@section('title', $asset->name)

@section('content')
<div class="mb-6 animate-fade-in">
    <a href="{{ route('assets.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-brand-700 transition-colors mb-3">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Back to Assets
    </a>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6 animate-fade-in" style="animation-delay: 0.05s">
            @if($asset->photo_url)
                <img src="{{ asset('storage/' . $asset->photo_url) }}" alt="{{ $asset->name }}" class="w-full h-48 sm:h-64 rounded-xl object-cover mb-4">
            @endif
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-extrabold text-on-surface tracking-tight">{{ $asset->name }}</h1>
                    <p class="text-sm text-on-surface-variant mt-0.5 font-mono">{{ $asset->asset_tag }}@if($asset->serial) · {{ $asset->serial }}@endif</p>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg @if($asset->status === 'active') text-emerald-700 bg-emerald-50 @elseif($asset->status === 'checked_out') text-amber-700 bg-amber-50 @elseif($asset->status === 'discarded') text-red-700 bg-red-50 @else text-stone-600 bg-stone-50 @endif capitalize">
                    <span class="w-1.5 h-1.5 rounded-full @if($asset->status === 'active') bg-emerald-500 @elseif($asset->status === 'checked_out') bg-amber-500 @elseif($asset->status === 'discarded') bg-red-500 @else bg-stone-400 @endif"></span>
                    {{ str_replace('_', ' ', $asset->status) }}
                </span>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                @php
                    $fields = [
                        ['label' => 'Category', 'value' => $asset->category?->name ?? '-'],
                        ['label' => 'Brand', 'value' => $asset->brand ?? '-'],
                        ['label' => 'Model', 'value' => $asset->model ?? '-'],
                        ['label' => 'Location', 'value' => $asset->location ?? '-'],
                        ['label' => 'Purchase Date', 'value' => $asset->purchase_date?->format('M d, Y') ?? '-'],
                        ['label' => 'Purchase Price', 'value' => $asset->purchase_price ? number_format($asset->purchase_price, 0, ',', ' ') . ' FCFA' : '-'],
                        ['label' => 'Supplier', 'value' => $asset->supplier ?? '-'],
                        ['label' => 'Condition', 'value' => $asset->condition ?? '-'],
                    ];
                @endphp
                @foreach($fields as $f)
                    <div class="py-2.5 border-b border-outline-variant/10 last:border-0">
                        <span class="block text-xs font-medium text-on-surface-variant uppercase tracking-wider">{{ $f['label'] }}</span>
                        <span class="block text-sm font-semibold text-on-surface mt-0.5">{{ $f['value'] }}</span>
                    </div>
                @endforeach
            </div>

            @if($asset->description)
                <div class="mt-4 pt-4 border-t border-outline-variant/10">
                    <span class="block text-xs font-medium text-on-surface-variant uppercase tracking-wider mb-1.5">Description</span>
                    <p class="text-sm text-on-surface leading-relaxed">{{ $asset->description }}</p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6 animate-fade-in" style="animation-delay: 0.1s">
            <h2 class="font-bold text-lg text-on-surface mb-5">Check-Out History</h2>
            @if($asset->checkouts->count())
                <div class="space-y-0">
                    @foreach($asset->checkouts as $checkout)
                        <div class="flex items-start gap-4 py-3 border-b border-outline-variant/10 last:border-0">
                            <div class="w-2.5 h-2.5 rounded-full mt-1.5 @if($checkout->returned_at) bg-emerald-400 @else bg-amber-400 @endif shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-on-surface">Checked out to <span class="font-semibold">{{ $checkout->assignee_name }}</span></p>
                                <p class="text-xs text-on-surface-variant mt-0.5">
                                    {{ $checkout->created_at->format('M d, Y H:i') }}
                                    @if($checkout->returned_at) · Returned {{ $checkout->returned_at->format('M d, Y H:i') }} @else (Active) @endif
                                    @if($checkout->return_notes) · {{ $checkout->return_notes }} @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-on-surface-variant py-8 text-center">No check-out history.</p>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6 animate-fade-in" style="animation-delay: 0.15s">
            <h2 class="font-bold text-lg text-on-surface mb-5">Activity Timeline</h2>
            @if($asset->activityLogs->count())
                <div class="relative pl-6 space-y-0 before:absolute before:left-[9px] before:top-2 before:bottom-2 before:w-px before:bg-outline-variant/40">
                    @foreach($asset->activityLogs as $log)
                        <div class="relative pb-4 last:pb-0">
                            <div class="absolute -left-[17px] top-1.5 w-4 h-4 rounded-full border-2 border-white bg-brand-100 flex items-center justify-center">
                                <div class="w-1.5 h-1.5 rounded-full bg-brand-500"></div>
                            </div>
                            <p class="text-sm text-on-surface">{{ $log->description }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">{{ $log->user?->name }} · {{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-on-surface-variant py-8 text-center">No activity recorded yet.</p>
            @endif
        </div>
    </div>

    <div class="space-y-4 animate-fade-in" style="animation-delay: 0.1s">
        @php $currentCheckout = $asset->currentCheckout; @endphp
        @if($asset->status === 'active')
            <a href="{{ route('checkouts.create', ['asset_id' => $asset->id]) }}" class="flex items-center justify-center gap-2 w-full h-12 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl shadow-lg shadow-brand-700/20 transition-all duration-200 text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" /></svg>
                Check Out
            </a>
        @elseif($asset->status === 'checked_out' && $currentCheckout)
            <a href="{{ route('checkouts.return', ['checkout' => $currentCheckout]) }}" class="flex items-center justify-center gap-2 w-full h-12 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/20 transition-all duration-200 text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Check In
            </a>
        @endif

        <a href="{{ route('assets.edit', $asset) }}" class="flex items-center justify-center gap-2 w-full h-12 bg-white border border-outline-variant/30 hover:bg-surface-low text-on-surface font-semibold rounded-xl transition-all duration-200 text-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
            Edit Asset
        </a>

        <a href="{{ route('assets.print-qr', $asset) }}" class="flex items-center justify-center gap-2 w-full h-12 bg-white border border-outline-variant/30 hover:bg-surface-low text-on-surface font-semibold rounded-xl transition-all duration-200 text-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" /></svg>
            Print QR Label
        </a>

        @if(!in_array($asset->status, ['archived', 'discarded']))
            <div class="pt-4 border-t border-outline-variant/10">
                <p class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-3">Lifecycle</p>
                <form method="POST" action="{{ route('assets.archive', $asset) }}" onsubmit="var r=prompt('Reason for archiving:');if(!r)return false;this.querySelector('[name=reason]').value=r;return true;" class="mb-2">
                    @csrf
                    <input type="hidden" name="reason">
                    <button type="submit" class="flex items-center justify-center gap-2 w-full h-11 bg-white border border-outline-variant/30 hover:bg-stone-50 text-stone-700 font-semibold rounded-xl transition-all duration-200 text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.75 7.5V5.25A2.25 2.25 0 017.5 3h9a2.25 2.25 0 012.25 2.25V7.5" /></svg>
                        Archive
                    </button>
                </form>
                <form method="POST" action="{{ route('assets.discard', $asset) }}" onsubmit="var r=prompt('Reason for discarding:');if(!r)return false;this.querySelector('[name=reason]').value=r;return true;">
                    @csrf
                    <input type="hidden" name="reason">
                    <button type="submit" class="flex items-center justify-center gap-2 w-full h-11 bg-white border border-red-200/50 hover:bg-red-50 text-red-700 font-semibold rounded-xl transition-all duration-200 text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                        Mark Discarded
                    </button>
                </form>
            </div>
        @elseif($asset->status === 'archived')
            <form method="POST" action="{{ route('assets.restore', $asset) }}" class="pt-4 border-t border-outline-variant/10">
                @csrf
                <button type="submit" class="flex items-center justify-center gap-2 w-full h-12 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/20 transition-all duration-200 text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Restore from Archive
                </button>
            </form>
        @endif

        <form method="POST" action="{{ route('assets.destroy', $asset) }}" onsubmit="return confirm('Are you sure you want to permanently delete this asset? This cannot be undone.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="flex items-center justify-center gap-2 w-full h-12 bg-white border border-red-200/50 hover:bg-red-50 text-red-700 font-semibold rounded-xl transition-all duration-200 text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                Delete Permanently
            </button>
        </form>
    </div>
</div>
@endsection
