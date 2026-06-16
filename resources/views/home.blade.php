@extends('layouts.app')
@section('title', 'Nick & Ollie Fortune Wedding')
@section('meta_description', 'Join Nick & Ollie Fortune\'s wedding photo sharing platform — upload photos, enter contests, and share memories from your special day.')
@section('content')

{{-- Hero Section (moons/stars glassmorphism per design.md) --}}
<section class="hero-pattern" aria-label="Wedding announcement">
    <div class="container py-5 my-5">
        <div class="text-center mx-auto" style="max-width: 760px;">

            <p class="badge-soft border-sec text-sec mb-4" style="border-color:#36538f;color:#36538f;background:rgba(54,83,143,0.08);">
                <i class="fa-regular fa-calendar-heart me-2"></i> November 13, 2026
            </p>

            <p class="font-accent" style="font-family:'Great Vibes',cursive;font-size:2.25rem;color:#c2b8b7;margin-bottom:0.5rem;">
                We're getting married
            </p>

            <h1 class="font-display" style="font-family:'Playfair Display',serif;font-size:clamp(2.5rem,6vw,4.5rem);font-weight:600;color:#FAEBD7;letter-spacing:0.02em;line-height:1.15;margin-top:0.75rem;">
                Nick &amp; Ollie Fortune
            </h1>

            <div class="section-divider mx-auto" style="max-width:220px;margin-top:1.75rem;margin-bottom:1.75rem;"></div>

            <p style="font-family:'Source Sans 3',sans-serif;font-size:1.15rem;color:rgba(250,235,215,0.82);line-height:1.65;margin-bottom:0.5rem;">
                Tulay sa aming pagdiriyang &mdash; ikuwento ang iyong kwento, ikuwento ang saya!
            </p>
            <p style="font-family:'Source Sans 3',sans-serif;font-style:italic;font-size:1rem;color:rgba(194,184,183,0.9);line-height:1.55;">
                A bridge to our celebration &mdash; share your story, share the joy!
            </p>

            @guest
                <p class="mt-4 mb-0" style="font-family:'Source Sans 3',sans-serif;font-size:0.95rem;color:rgba(194,184,183,0.9);">
                    <i class="fa-regular fa-circle-user me-2"></i> New here? <a href="{{ route('register') }}" style="color:#c2b8b7;text-decoration:underline;text-decoration-color:rgba(194,184,183,0.5);">Create an account</a> to share photos and enter contests.
                </p>
            @endguest

        </div>
    </div>
</section>

{{-- Login / Register Card (glassmorphism per design.md §5c) --}}
@guest
<section class="container my-5" aria-label="Login and registration">
    <div class="glass-panel mx-auto p-4 p-md-5" style="max-width: 480px; border-radius: 1.5rem;">

        <div id="flash-messages"></div>

        <ul class="nav nav-tabs mb-4" id="auth-tabs" role="tablist" aria-label="Authentication options">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-pane" type="button" role="tab" aria-controls="login-pane" aria-selected="true">Login</button>
            </li>
            <li class="nav-item" role="none">
                <a class="nav-link" href="{{ route('register') }}" id="register-link">Create Account</a>
            </li>
        </ul>

        <div class="tab-content" id="auth-tabContent">
            <div class="tab-pane active" id="login-pane" role="tabpanel" aria-labelledby="login-tab">
                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="login-email" class="form-label">Email or Username</label>
                        <input type="text" class="form-control" id="login-email" name="login" placeholder="your@email.com or username" autocomplete="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="login-password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="login-password" name="password" placeholder="Your password" autocomplete="current-password" required>
                    </div>
                    <button type="submit" class="nav-cta w-100 mt-2" id="btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </button>
                </form>
                <p class="small mt-3 mb-0" style="color:rgba(250,235,215,0.6);">
                    Forgot your password? Contact <a href="mailto:?subject=Wedding%20Photo%20App%20-%20Password%20Reset" style="color:#c2b8b7;">the couple</a> for a reset.
                </p>
            </div>
        </div>

    </div>
</section>
@endguest

@auth
<section class="container my-5">
    <div class="glass-panel p-4 p-md-5" style="border-radius: 1.5rem;">
        <h2 class="font-display mb-2" style="font-family:'Playfair Display',serif;color:#FAEBD7;">Welcome back, {{ Auth::user()->guest_name ?? Auth::user()->name }}.</h2>
        <p style="color:rgba(250,235,215,0.78);font-family:'Source Sans 3',sans-serif;">
            Head to the <a href="{{ route('gallery') }}" style="color:#c2b8b7;">Gallery</a> to see what's new,
            or <a href="{{ route('upload') }}" style="color:#c2b8b7;">upload a photo</a> from the celebration.
        </p>
    </div>
</section>
@endauth

@endsection
