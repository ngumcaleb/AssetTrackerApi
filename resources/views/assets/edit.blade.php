@extends('layouts.dashboard')

@section('title', 'Edit Asset')

@section('content')
<div class="mb-6 animate-fade-in">
    <a href="{{ route('assets.show', $asset) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-brand-700 transition-colors mb-3">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Back to Asset
    </a>
    <h1 class="text-2xl sm:text-3xl font-extrabold text-on-surface tracking-tight">Edit Asset</h1>
</div>

<form method="POST" action="{{ route('assets.update', $asset) }}" class="max-w-2xl" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6 space-y-5 animate-fade-in" style="animation-delay: 0.05s">
        <div class="grid sm:grid-cols-2 gap-4 sm:gap-5">
            <div class="sm:col-span-2">
                <label for="name" class="block text-sm font-medium text-on-surface mb-1.5">Asset Name *</label>
                <input id="name" type="text" name="name" value="{{ old('name', $asset->name) }}" required class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <div>
                <label for="asset_tag" class="block text-sm font-medium text-on-surface mb-1.5">Asset Tag *</label>
                <input id="asset_tag" type="text" name="asset_tag" value="{{ old('asset_tag', $asset->asset_tag) }}" required class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <div>
                <label for="serial" class="block text-sm font-medium text-on-surface mb-1.5">Serial Number *</label>
                <input id="serial" type="text" name="serial" value="{{ old('serial', $asset->serial) }}" class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium text-on-surface mb-1.5">Category *</label>
                <select id="category_id" name="category_id" required class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
                    <option value="">Select category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id', $asset->category_id) == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="brand" class="block text-sm font-medium text-on-surface mb-1.5">Brand</label>
                <input id="brand" type="text" name="brand" value="{{ old('brand', $asset->brand) }}" class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <div>
                <label for="model" class="block text-sm font-medium text-on-surface mb-1.5">Model</label>
                <input id="model" type="text" name="model" value="{{ old('model', $asset->model) }}" class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <div>
                <label for="location" class="block text-sm font-medium text-on-surface mb-1.5">Location</label>
                <input id="location" type="text" name="location" value="{{ old('location', $asset->location) }}" class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <div>
                <label for="purchase_date" class="block text-sm font-medium text-on-surface mb-1.5">Purchase Date</label>
                <input id="purchase_date" type="date" name="purchase_date" value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}" class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <div>
                <label for="purchase_price" class="block text-sm font-medium text-on-surface mb-1.5">Purchase Price ($)</label>
                <input id="purchase_price" type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price', $asset->purchase_price) }}" class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <div>
                <label for="supplier" class="block text-sm font-medium text-on-surface mb-1.5">Supplier</label>
                <input id="supplier" type="text" name="supplier" value="{{ old('supplier', $asset->supplier) }}" class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <div>
                <label for="condition" class="block text-sm font-medium text-on-surface mb-1.5">Condition</label>
                <select id="condition" name="condition" class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
                    <option value="">Select</option>
                    <option value="new" @selected(old('condition', $asset->condition) === 'new')>New</option>
                    <option value="good" @selected(old('condition', $asset->condition) === 'good')>Good</option>
                    <option value="fair" @selected(old('condition', $asset->condition) === 'fair')>Fair</option>
                    <option value="poor" @selected(old('condition', $asset->condition) === 'poor')>Poor</option>
                </select>
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-on-surface mb-1.5">Description</label>
            <textarea id="description" name="description" rows="3" class="w-full px-4 py-3 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all resize-none">{{ old('description', $asset->description) }}</textarea>
        </div>

        <div>
            <label for="photo" class="block text-sm font-medium text-on-surface mb-1.5">Photo</label>
            @if($asset->photo_url)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $asset->photo_url) }}" alt="{{ $asset->name }}" class="w-32 h-32 rounded-xl object-cover border border-outline-variant/30">
                </div>
            @endif
            <input id="photo" type="file" name="photo" accept="image/*"
                class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 file:font-semibold file:text-xs hover:file:bg-brand-100 outline-none transition-all">
            @error('photo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="mt-6 flex flex-col sm:flex-row gap-3">
        <button type="submit" class="h-12 px-8 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl shadow-lg shadow-brand-700/20 transition-all duration-200 active:scale-[0.98]">Update Asset</button>
        <a href="{{ route('assets.show', $asset) }}" class="h-12 px-6 border border-outline-variant/30 hover:bg-surface-low text-on-surface font-semibold rounded-xl transition-all duration-200 flex items-center justify-center">Cancel</a>
    </div>
</form>
@endsection
