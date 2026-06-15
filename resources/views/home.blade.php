@extends('layouts.app')
@section('title', 'Nick & Ollie Fortune Wedding')
@section('meta_description', 'Join Nick & Ollie Fortune\'s wedding photo sharing platform — upload photos, enter contests, and share memories from your special day.')
@section('content')

{{-- Hero Section --}}
<section class="hero" aria-label="Wedding announcement">
    {!! $hero_content !!}
</section>

{{-- Flower Icons Strip --}}
<div class="flower-strip" style="text-align:center;padding:10px 0;opacity:0.9;">
    <img src="/images/flowers/sampaguita.svg" alt="Sampaguita" width="48" height="48"
         style="margin:0 8px;vertical-align:middle;filter:drop-shadow(0 2px 3px rgba(0,0,0,.12));transition:transform .25s ease,filter .25s;"
         data-tooltip="Sampaguita (Jasminum sambac) — Philippines' national flower. Symbolizes purity, divine hope, and faithful union."
         onmouseover="this.style.transform='scale(1.15)';this.style.filter='drop-shadow(0 4px 6px rgba(0,0,0,.18))'"
         onmouseout="this.style.transform='scale(1)';this.style.filter='drop-shadow(0 2px 3px rgba(0,0,0,.12))'">
    <img src="/images/flowers/waling_waling.svg" alt="Waling-Waling" width="48" height="48"
         style="margin:0 8px;vertical-align:middle;filter:drop-shadow(0 2px 3px rgba(0,0,0,.12));transition:transform .25s ease,filter .25s;"
         data-tooltip="Waling-Waling (Vanda sanderiana) — Queen of Philippine Orchids. Epitome of exotic beauty and regal elegance."
         onmouseover="this.style.transform='scale(1.15)';this.style.filter='drop-shadow(0 4px 6px rgba(0,0,0,.18))'"
         onmouseout="this.style.transform='scale(1)';this.style.filter='drop-shadow(0 2px 3px rgba(0,0,0,.12))'">
    <img src="/images/flowers/gumamela.svg" alt="Gumamela" width="48" height="48"
         style="margin:0 8px;vertical-align:middle;filter:drop-shadow(0 2px 3px rgba(0,0,0,.12));transition:transform .25s ease,filter .25s;"
         data-tooltip="Gumamela (Hibiscus rosa-sinensis) — Delicate beauty and the fleeting, ardent passion of new love."
         onmouseover="this.style.transform='scale(1.15)';this.style.filter='drop-shadow(0 4px 6px rgba(0,0,0,.18))'"
         onmouseout="this.style.transform='scale(1)';this.style.filter='drop-shadow(0 2px 3px rgba(0,0,0,.12))'">
    <img src="/images/flowers/calachuchi.svg" alt="Calachuchi" width="48" height="48"
         style="margin:0 8px;vertical-align:middle;filter:drop-shadow(0 2px 3px rgba(0,0,0,.12));transition:transform .25s ease,filter .25s;"
         data-tooltip="Calachuchi (Plumeria) — Beloved in Filipino celebrations. New beginnings and the blossoming of two souls."
         onmouseover="this.style.transform='scale(1.15)';this.style.filter='drop-shadow(0 4px 6px rgba(0,0,0,.18))'"
         onmouseout="this.style.transform='scale(1)';this.style.filter='drop-shadow(0 2px 3px rgba(0,0,0,.12))'">
</div>

{{-- Login / Register Card --}}
<section class="auth-card" aria-label="Login and registration">
    <div id="flash-messages"></div>

    <ul class="nav nav-tabs" id="auth-tabs" role="tablist" aria-label="Authentication options">
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
                <button type="submit" class="btn btn-primary" id="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </button>
            </form>
            <p class="auth-links small text-muted">
                Forgot your password? Contact <a href="mailto:?subject=Wedding%20Photo%20App%20-%20Password%20Reset">the couple</a> for a reset.
            </p>
        </div>
    </div>
</section>

@endsection
