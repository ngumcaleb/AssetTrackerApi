@extends('layouts.dashboard')

@section('title', 'Edit Category')

@section('content')
<div class="mb-6 animate-fade-in">
    <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-brand-700 transition-colors mb-3">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Back to Categories
    </a>
    <h1 class="text-2xl sm:text-3xl font-extrabold text-on-surface tracking-tight">Edit Category</h1>
</div>

<form method="POST" action="{{ route('categories.update', $category) }}" class="max-w-lg">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6 space-y-5 animate-fade-in" style="animation-delay: 0.05s">
        <div>
            <label for="name" class="block text-sm font-medium text-on-surface mb-1.5">Name *</label>
            <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" required
                class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-on-surface mb-1.5">Description</label>
            <textarea id="description" name="description" rows="3"
                class="w-full px-4 py-3 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all resize-none">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="flex items-center gap-3">
            <input id="is_active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active))
                class="w-4 h-4 rounded border-outline-variant/50 text-brand-700 focus:ring-brand-200/50">
            <label for="is_active" class="text-sm font-medium text-on-surface">Active</label>
        </div>
    </div>

    <div class="mt-6 flex flex-col sm:flex-row gap-3">
        <button type="submit" class="h-12 px-8 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl shadow-lg shadow-brand-700/20 transition-all duration-200 active:scale-[0.98]">Update Category</button>
        <a href="{{ route('categories.index') }}" class="h-12 px-6 border border-outline-variant/30 hover:bg-surface-low text-on-surface font-semibold rounded-xl transition-all duration-200 flex items-center justify-center">Cancel</a>
    </div>
</form>
@endsection
