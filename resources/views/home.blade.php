@extends('layouts.app')
@section('title', 'Nick & Ollie Fortune Wedding')
@section('meta_description', 'Join Nick & Ollie Fortune\'s wedding photo sharing platform — upload photos, enter contests, and share memories from your special day.')
@section('content')

{{-- Hero Section --}}
<section class="hero-section" aria-label="Wedding announcement">
    <div class="hero-content">
        {!! $hero_content !!}
    </div>
</section>

{{-- Flower Icons Strip — ported from legacy index.php --}}
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

{{-- Login / Register Panel --}}
<section class="mx-auto max-w-md px-4 py-6" x-data="{ tab: 'login' }">
    <div class="glass-panel overflow-hidden rounded-2xl">

        {{-- Tabs --}}
        <div class="flex border-b border-sec/20">
            <button @click="tab = 'login'"
                    :class="tab === 'login' ? 'text-sec border-b-2 border-sec' : 'text-body hover:text-sec'"
                    class="flex-1 py-3.5 text-sm font-semibold uppercase tracking-widest transition">
                Login
            </button>
            <button @click="tab = 'register'"
                    :class="tab === 'register' ? 'text-sec border-b-2 border-sec' : 'text-body hover:text-sec'"
                    class="flex-1 py-3.5 text-sm font-semibold uppercase tracking-widest transition">
                Create Account
            </button>
        </div>

        {{-- Login Form --}}
        <div x-show="tab === 'login'" class="p-6 space-y-4">
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <input type="text" name="login" placeholder="Email or Username"
                           class="w-full rounded-xl border border-sec/30 bg-white/70 px-4 py-3 text-sm text-night placeholder-body/60 backdrop-blur-sm focus:border-sec focus:outline-none focus:ring-1 focus:ring-sec/50"
                           required autocomplete="username">
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password"
                           class="w-full rounded-xl border border-sec/30 bg-white/70 px-4 py-3 text-sm text-night placeholder-body/60 backdrop-blur-sm focus:border-sec focus:outline-none focus:ring-1 focus:ring-sec/50"
                           required autocomplete="current-password">
                </div>
                <button type="submit" class="w-full rounded-xl bg-primary py-3 text-sm font-semibold uppercase tracking-widest text-white transition hover:bg-sec">
                    Login
                </button>
            </form>
            <p class="text-center text-xs text-body/80">
                <a href="{{ route('password.request') }}" class="hover:text-sec">Forgot your password?</a>
            </p>
        </div>

        {{-- Register Form --}}
        <div x-show="tab === 'register'" x-cloak class="p-6 space-y-4">
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <input type="text" name="name" placeholder="Full Name"
                           class="w-full rounded-xl border border-sec/30 bg-white/70 px-4 py-3 text-sm text-night placeholder-body/60 backdrop-blur-sm focus:border-sec focus:outline-none focus:ring-1 focus:ring-sec/50"
                           required autocomplete="name">
                </div>
                <div>
                    <input type="email" name="email" placeholder="Email Address"
                           class="w-full rounded-xl border border-sec/30 bg-white/70 px-4 py-3 text-sm text-night placeholder-body/60 backdrop-blur-sm focus:border-sec focus:outline-none focus:ring-1 focus:ring-sec/50"
                           required autocomplete="email">
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password"
                           class="w-full rounded-xl border border-sec/30 bg-white/70 px-4 py-3 text-sm text-night placeholder-body/60 backdrop-blur-sm focus:border-sec focus:outline-none focus:ring-1 focus:ring-sec/50"
                           required autocomplete="new-password">
                </div>
                <div>
                    <input type="password" name="password_confirmation" placeholder="Confirm Password"
                           class="w-full rounded-xl border border-sec/30 bg-white/70 px-4 py-3 text-sm text-night placeholder-body/60 backdrop-blur-sm focus:border-sec focus:outline-none focus:ring-1 focus:ring-sec/50"
                           required autocomplete="new-password">
                </div>
                <button type="submit" class="w-full rounded-xl bg-primary py-3 text-sm font-semibold uppercase tracking-widest text-white transition hover:bg-sec">
                    Create Account
                </button>
            </form>
        </div>

    </div>
</section>

<style>
[x-cloak] { display: none !important; }

.hero-section {
    background: linear-gradient(160deg, #f5ede0 0%, #faf8f5 60%);
    padding: 3rem 1.5rem 2rem;
    text-align: center;
}
.hero-content {
    max-width: 600px;
    margin: 0 auto;
}
.hero-content h1 {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: clamp(1.75rem, 5vw, 2.5rem);
    font-weight: 300;
    line-height: 1.2;
    letter-spacing: 0.12em;
    color: #8b7355;
    margin-bottom: 0.5rem;
}
.hero-content .hero__date {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 0.85rem;
    font-weight: 400;
    line-height: 1.5;
    letter-spacing: 0.15em;
    color: #7a726a;
    margin-top: 0.5rem;
}
.hero-content .hero__tagline {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.6;
    color: #3d3530;
    margin-top: 0.5rem;
}
</style>

@endsection
