@extends('layouts.dashboard')

@section('title', 'Categories')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 animate-fade-in">
    <div>
        <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">Categories</h1>
        <p class="text-on-surface-variant mt-1">Organize assets by category.</p>
    </div>
    <a href="{{ route('categories.create') }}" class="inline-flex items-center gap-2 bg-brand-700 hover:bg-brand-800 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-brand-700/20 shrink-0">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        New Category
    </a>
</div>

<div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] overflow-hidden animate-fade-in" style="animation-delay: 0.05s">
    @if($categories->count())
        <div class="divide-y divide-outline-variant/15">
            @foreach($categories as $cat)
                <div class="flex items-center gap-4 px-5 py-4 hover:bg-surface-low/40 transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /></svg>
                    </div>
                    <div class="flex-1 min-w-0 grid sm:grid-cols-3 gap-2 items-center">
                        <div>
                            <span class="block text-sm font-semibold text-on-surface">{{ $cat->name }}</span>
                            <span class="text-xs text-on-surface-variant font-mono">{{ $cat->slug }}</span>
                        </div>
                        <span class="text-sm text-on-surface-variant hidden sm:block">{{ $cat->assets_count }} asset(s)</span>
                        <div class="flex items-center justify-between sm:justify-end gap-3">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-lg @if($cat->is_active) text-emerald-700 bg-emerald-50 @else text-stone-600 bg-stone-50 @endif">
                                <span class="w-1.5 h-1.5 rounded-full @if($cat->is_active) bg-emerald-500 @else bg-stone-400 @endif"></span>
                                {{ $cat->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <a href="{{ route('categories.edit', $cat) }}" class="text-xs font-semibold text-brand-700 hover:text-brand-800 transition-colors">Edit</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="px-5 py-4 border-t border-outline-variant/15">
            {{ $categories->links() }}
        </div>
    @else
        <div class="text-center py-16 px-6">
            <div class="w-14 h-14 rounded-xl bg-surface-low mx-auto mb-4 flex items-center justify-center">
                <svg class="w-7 h-7 text-on-surface-variant/50" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /></svg>
            </div>
            <p class="text-on-surface-variant font-medium">No categories yet</p>
            <p class="text-sm text-on-surface-variant/70 mt-1"><a href="{{ route('categories.create') }}" class="text-brand-700 font-semibold hover:text-brand-800">Create one</a> to get started.</p>
        </div>
    @endif
</div>
@endsection
