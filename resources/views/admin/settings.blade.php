@extends('layouts.app')
@section('title', 'Admin Settings')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12 space-y-6">
    <div class="flex flex-col gap-2">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#8b7355]">Admin</p>
        <h1 class="text-3xl font-bold sm:text-4xl">Settings</h1>
        <p class="max-w-2xl text-slate-700">Manage site identity, hero content, and contact information.</p>
    </div>

    @if(session('status'))
        <div class="rounded-md bg-green-50 p-3 text-sm text-green-800">{{ session('status') }}</div>
    @endif

    {{-- Site Info --}}
    <div class="rounded-lg border bg-white shadow-sm">
        <div class="border-b px-4 py-3">
            <h2 class="font-semibold text-slate-800">Site Information</h2>
        </div>
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4 p-6">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Site Title</label>
                    <input type="text" name="site_title"
                           value="{{ old('site_title', $settings->site_title ?? 'Nick & Ollie Fortune') }}"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#8b7355] focus:outline-none focus:ring-1 focus:ring-[#8b7355]">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Site Tagline</label>
                    <input type="text" name="site_tagline"
                           value="{{ old('site_tagline', $settings->site_tagline ?? '') }}"
                           placeholder="e.g. Our Wedding Journey"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#8b7355] focus:outline-none focus:ring-1 focus:ring-[#8b7355]">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Contact Email</label>
                    <input type="email" name="contact_email"
                           value="{{ old('contact_email', $settings->contact_email ?? '') }}"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#8b7355] focus:outline-none focus:ring-1 focus:ring-[#8b7355]">
                </div>
            </div>

            <hr class="border-slate-200">

            <div>
                <h3 class="mb-3 text-sm font-semibold text-slate-700">Hero Section</h3>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Hero Title</label>
                        <input type="text" name="hero_title"
                               value="{{ old('hero_title', $settings->hero_title ?? '') }}"
                               placeholder="Welcome to our wedding site"
                               class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#8b7355] focus:outline-none focus:ring-1 focus:ring-[#8b7355]">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Hero Subtitle</label>
                        <input type="text" name="hero_subtitle"
                               value="{{ old('hero_subtitle', $settings->hero_subtitle ?? '') }}"
                               placeholder="Celebrating our love story"
                               class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm focus:border-[#8b7355] focus:outline-none focus:ring-1 focus:ring-[#8b7355]">
                    </div>
                </div>
            </div>

            <hr class="border-slate-200">

            <div>
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox" name="maintenance_mode" value="1"
                           {{ old('maintenance_mode', $settings->maintenance_mode ?? false) ? 'checked' : '' }}
                           class="rounded border-slate-300 text-[#8b7355] focus:ring-[#8b7355]">
                    <span class="text-sm font-medium text-slate-700">Enable maintenance mode</span>
                </label>
                <p class="mt-1 text-xs text-slate-500">When enabled, visitors will see a maintenance notice instead of the site.</p>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="rounded-md bg-[#8b7355] px-5 py-2 text-sm font-medium text-white hover:bg-[#7a6548]">
                    Save Settings
                </button>
            </div>
        </form>
    </div>

    {{-- Theme Settings Link --}}
    <div class="rounded-lg border bg-white shadow-sm">
        <div class="border-b px-4 py-3">
            <h2 class="font-semibold text-slate-800">Appearance</h2>
        </div>
        <div class="p-6">
            <p class="mb-4 text-sm text-slate-600">Customize site colors and theme presets.</p>
            <a href="{{ route('admin.themes') }}"
               class="inline-flex items-center gap-2 rounded-md bg-[#8b7355] px-4 py-2 text-sm font-medium text-white hover:bg-[#7a6548]">
                <i class="fas fa-palette"></i> Theme Settings
            </a>
        </div>
    </div>
</section>
@endsection
