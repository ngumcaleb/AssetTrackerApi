@extends('layouts.dashboard')

@section('title', 'Register Asset')

@section('content')
<div class="mb-6 animate-fade-in">
    <a href="{{ route('assets.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-on-surface-variant hover:text-brand-700 transition-colors mb-3">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
        Back to Assets
    </a>
    <h1 class="text-2xl sm:text-3xl font-extrabold text-on-surface tracking-tight">Register Asset</h1>
    <p class="text-on-surface-variant mt-1">Add a new piece of equipment or supply to the system.</p>
</div>

<form method="POST" action="{{ route('assets.store') }}" class="max-w-2xl" enctype="multipart/form-data">
    @csrf

    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6 space-y-5 animate-fade-in" style="animation-delay: 0.05s">
        <div class="grid sm:grid-cols-2 gap-4 sm:gap-5">
            <div class="sm:col-span-2">
                <label for="name" class="block text-sm font-medium text-on-surface mb-1.5">Asset Name *</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all"
                    placeholder="e.g. Dell Latitude 5540">
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="asset_tag" class="block text-sm font-medium text-on-surface mb-1.5">Asset Tag *</label>
                <input id="asset_tag" type="text" name="asset_tag" value="{{ old('asset_tag', $nextTag) }}" required
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all"
                    placeholder="e.g. RW-001">
                @error('asset_tag') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="serial" class="block text-sm font-medium text-on-surface mb-1.5">Serial Number *</label>
                <input id="serial" type="text" name="serial" value="{{ old('serial') }}"
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all"
                    placeholder="e.g. SN-001">
                @error('serial') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium text-on-surface mb-1.5">Category *</label>
                <select id="category_id" name="category_id" required
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
                    <option value="">Select category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="brand" class="block text-sm font-medium text-on-surface mb-1.5">Brand</label>
                <input id="brand" type="text" name="brand" value="{{ old('brand') }}"
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all"
                    placeholder="e.g. Dell">
            </div>

            <div>
                <label for="model" class="block text-sm font-medium text-on-surface mb-1.5">Model</label>
                <input id="model" type="text" name="model" value="{{ old('model') }}"
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all">
            </div>

            <div>
                <label for="location" class="block text-sm font-medium text-on-surface mb-1.5">Location</label>
                <input id="location" type="text" name="location" value="{{ old('location') }}"
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all"
                    placeholder="e.g. Main Office">
            </div>

            <div>
                <label for="purchase_date" class="block text-sm font-medium text-on-surface mb-1.5">Purchase Date</label>
                <input id="purchase_date" type="date" name="purchase_date" value="{{ old('purchase_date') }}"
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <div>
                <label for="purchase_price" class="block text-sm font-medium text-on-surface mb-1.5">Purchase Price (FCFA)</label>
                <input id="purchase_price" type="number" step="0.01" name="purchase_price" value="{{ old('purchase_price') }}"
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all">
            </div>

            <div>
                <label for="supplier" class="block text-sm font-medium text-on-surface mb-1.5">Supplier</label>
                <input id="supplier" type="text" name="supplier" value="{{ old('supplier') }}"
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all">
            </div>

            <div>
                <label for="condition" class="block text-sm font-medium text-on-surface mb-1.5">Condition</label>
                <select id="condition" name="condition"
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
                    <option value="">Select condition</option>
                    <option value="new" @selected(old('condition') === 'new')>New</option>
                    <option value="good" @selected(old('condition') === 'good')>Good</option>
                    <option value="fair" @selected(old('condition') === 'fair')>Fair</option>
                    <option value="poor" @selected(old('condition') === 'poor')>Poor</option>
                </select>
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-on-surface mb-1.5">Description</label>
            <textarea id="description" name="description" rows="3"
                class="w-full px-4 py-3 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface placeholder-on-surface-variant/50 outline-none transition-all resize-none">{{ old('description') }}</textarea>
        </div>

        <div>
            <label for="photo" class="block text-sm font-medium text-on-surface mb-1.5">Photo</label>
            <input id="photo" type="file" name="photo" accept="image/*"
                class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 file:font-semibold file:text-xs hover:file:bg-brand-100 outline-none transition-all">
            @error('photo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="mt-6">
        <button type="submit" class="w-full sm:w-auto h-12 px-8 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl shadow-lg shadow-brand-700/20 transition-all duration-200 active:scale-[0.98]">Register Asset</button>
    </div>
</form>
@endsection
