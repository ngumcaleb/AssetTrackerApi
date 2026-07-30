@extends('layouts.dashboard')

@section('title', 'Profile')

@section('content')
<div class="mb-6 animate-fade-in">
    <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">Profile</h1>
    <p class="text-on-surface-variant mt-1">Manage your account details.</p>
</div>

@if(session('success'))
    <div class="flex items-center gap-2.5 bg-emerald-50 border border-emerald-200/60 text-emerald-700 text-sm font-medium px-4 py-3 rounded-xl mb-6 max-w-xl animate-fade-in">
        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span>{{ session('success') }}</span>
    </div>
@endif

<div class="grid lg:grid-cols-2 gap-6 max-w-3xl">
    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6 animate-fade-in" style="animation-delay: 0.05s">
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <h2 class="font-bold text-lg text-on-surface">Personal Information</h2>

            <div>
                <label for="name" class="block text-sm font-medium text-on-surface mb-1.5">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-on-surface mb-1.5">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-on-surface mb-1.5">Phone</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <div>
                <label for="department" class="block text-sm font-medium text-on-surface mb-1.5">Department</label>
                <input id="department" type="text" name="department" value="{{ old('department', $user->department) }}"
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <button type="submit" class="h-11 px-8 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl shadow-lg shadow-brand-700/20 transition-all duration-200 active:scale-[0.98]">Update Profile</button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6 animate-fade-in" style="animation-delay: 0.1s">
        <form method="POST" action="{{ route('profile.password') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <h2 class="font-bold text-lg text-on-surface">Change Password</h2>

            <div>
                <label for="current_password" class="block text-sm font-medium text-on-surface mb-1.5">Current Password</label>
                <input id="current_password" type="password" name="current_password" required
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-on-surface mb-1.5">New Password</label>
                <input id="password" type="password" name="password" required
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-on-surface mb-1.5">Confirm New Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full h-11 px-4 bg-surface-low/70 border border-outline-variant/30 focus:border-brand-500 focus:ring-2 focus:ring-brand-200/50 rounded-xl text-sm text-on-surface outline-none transition-all">
            </div>

            <button type="submit" class="h-11 px-8 bg-brand-700 hover:bg-brand-800 text-white font-bold rounded-xl shadow-lg shadow-brand-700/20 transition-all duration-200 active:scale-[0.98]">Update Password</button>
        </form>
    </div>
</div>
@endsection
