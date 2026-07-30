@extends('layouts.dashboard')

@section('title', 'Settings')

@section('content')
<div class="mb-6 animate-fade-in">
    <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">Settings</h1>
    <p class="text-on-surface-variant mt-1">System preferences and configuration.</p>
</div>

<div class="max-w-xl">
    <div class="bg-white rounded-2xl border border-outline-variant/30 shadow-[0_2px_20px_-4px_rgba(0,0,0,0.04)] p-6 animate-fade-in" style="animation-delay: 0.05s">
        <p class="text-sm text-on-surface-variant">Settings panel coming soon. This will include organization preferences, notification configuration, and system-wide defaults.</p>

        <div class="mt-6 space-y-1">
            <div class="flex items-center justify-between py-3.5 border-b border-outline-variant/10 gap-4">
                <div class="min-w-0">
                    <span class="block text-sm font-semibold text-on-surface">Email Notifications</span>
                    <span class="text-xs text-on-surface-variant mt-0.5 block">Receive email alerts for check-outs and returns</span>
                </div>
                <div class="w-11 h-6 bg-brand-700 rounded-full relative cursor-pointer shrink-0">
                    <div class="w-4 h-4 bg-white rounded-full absolute right-1 top-1 shadow-sm"></div>
                </div>
            </div>

            <div class="flex items-center justify-between py-3.5 border-b border-outline-variant/10 gap-4">
                <div class="min-w-0">
                    <span class="block text-sm font-semibold text-on-surface">Default Check-Out Period</span>
                    <span class="text-xs text-on-surface-variant mt-0.5 block">Default duration before an asset is due</span>
                </div>
                <span class="text-sm font-semibold text-on-surface shrink-0">14 days</span>
            </div>

            <div class="flex items-center justify-between py-3.5 gap-4">
                <div class="min-w-0">
                    <span class="block text-sm font-semibold text-on-surface">Currency</span>
                    <span class="text-xs text-on-surface-variant mt-0.5 block">Display currency for purchase prices</span>
                </div>
                <span class="text-sm font-semibold text-on-surface shrink-0">USD ($)</span>
            </div>
        </div>
    </div>
</div>
@endsection
