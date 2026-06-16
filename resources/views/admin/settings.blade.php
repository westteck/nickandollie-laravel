@extends('layouts.app')
@section('title', 'Admin Settings')
@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-uppercase text-muted small mb-1" style="letter-spacing: 0.2em;">Admin</p>
            <h1 class="mb-0" style="color: var(--primary);">Settings</h1>
            <p class="text-muted mb-0">Manage site identity, hero content, and contact information.</p>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif

    {{-- Site Info --}}
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="h5 mb-0">Site Information</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Site Title</label>
                        <input type="text" name="site_title"
                               value="{{ old('site_title', $settings->site_title ?? 'Nick & Ollie Fortune') }}"
                               class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Site Tagline</label>
                        <input type="text" name="site_tagline"
                               value="{{ old('site_tagline', $settings->site_tagline ?? '') }}"
                               placeholder="e.g. Our Wedding Journey"
                               class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email"
                               value="{{ old('contact_email', $settings->contact_email ?? '') }}"
                               class="form-control">
                    </div>
                </div>

                <hr style="border-color: var(--secondary);">

                <h3 class="h6 mb-3">Hero Section</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hero Title</label>
                        <input type="text" name="hero_title"
                               value="{{ old('hero_title', $settings->hero_title ?? '') }}"
                               placeholder="Welcome to our wedding site"
                               class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hero Subtitle</label>
                        <input type="text" name="hero_subtitle"
                               value="{{ old('hero_subtitle', $settings->hero_subtitle ?? '') }}"
                               placeholder="Celebrating our love story"
                               class="form-control">
                    </div>
                </div>

                <hr style="border-color: var(--secondary);">

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="maintenance_mode" value="1"
                           id="maintenanceMode"
                           {{ old('maintenance_mode', $settings->maintenance_mode ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="maintenanceMode">Enable maintenance mode</label>
                    <p class="form-text mb-0">When enabled, visitors will see a maintenance notice instead of the site.</p>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Settings
                </button>
            </form>
        </div>
    </div>

    {{-- Theme Settings Link --}}
    <div class="card">
        <div class="card-header">
            <h2 class="h5 mb-0">Appearance</h2>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">Customize site colors and theme presets.</p>
            <a href="{{ route('admin.themes') }}" class="btn btn-primary">
                <i class="fas fa-palette me-2"></i>Theme Settings
            </a>
        </div>
    </div>
</div>
@endsection
