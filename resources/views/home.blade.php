@extends('layouts.app')
@section('title', 'Nick & Ollie Fortune Wedding')
@section('meta_description', 'Wedding photo sharing site for Nick & Ollie Fortune.')
@section('content')
{{-- Hero Section with DB-driven content --}}
<section class="hero-section" aria-label="Wedding announcement">
    <div class="hero-content">
        {!! $hero_content !!}
    </div>
</section>

{{-- Main Content --}}
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12">
    <div class="grid gap-6 lg:grid-cols-[1.05fr_.95fr] lg:items-center">
        <div class="space-y-5">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[#8b7355]">Wedding Photo Sharing</p>
            <h2 class="text-4xl font-bold leading-tight sm:text-5xl">Welcome to Our Celebration</h2>
            <p class="max-w-2xl text-lg text-slate-700">Tulay sa aming pagdiriwang — ikuwento ang iyong kwento, ikuwento ang saya.</p>
            <div class="flex flex-wrap gap-3 text-sm font-medium">
                <a href="{{ route('gallery') }}" class="rounded-full bg-[#8b7355] px-5 py-3 text-white">View Gallery</a>
                @auth
                    <a href="{{ route('upload') }}" class="rounded-full border border-[#8b7355] px-5 py-3 text-[#8b7355]">Upload Photos</a>
                    <a href="{{ route('contest') }}" class="rounded-full border border-[#8b7355] px-5 py-3 text-[#8b7355]">Contests</a>
                @else
                    <a href="{{ route('register') }}" class="rounded-full border border-[#8b7355] px-5 py-3 text-[#8b7355]">Join Us</a>
                @endauth
            </div>
        </div>
        <div class="grid gap-4 rounded-[1.25rem] border border-black/5 bg-white p-5 shadow-sm sm:p-6">
            <div class="grid grid-cols-2 gap-3 text-center text-sm">
                <div class="rounded-2xl bg-[#faf8f5] p-4"><div class="text-[11px] uppercase tracking-[0.25em] text-slate-500">Date</div><div class="mt-1 font-semibold">November 13, 2026</div></div>
                <div class="rounded-2xl bg-[#faf8f5] p-4"><div class="text-[11px] uppercase tracking-[0.25em] text-slate-500">Venue</div><div class="mt-1 font-semibold">Los Angeles, CA</div></div>
            </div>
            @guest
            <div class="space-y-3 rounded-2xl border border-[#d4c4b0]/60 bg-[#faf8f5] p-4 text-sm text-slate-700">
                <p class="font-semibold text-[#8b7355]">Join the Celebration</p>
                <p>Create an account to upload photos, enter contests, and share memories with Nick &amp; Ollie.</p>
                <div class="flex gap-2">
                    <a href="{{ route('register') }}" class="rounded-md bg-[#8b7355] px-4 py-2 text-white text-sm">Register</a>
                    <a href="{{ route('login') }}" class="rounded-md border border-[#8b7355] px-4 py-2 text-[#8b7355] text-sm">Login</a>
                </div>
            </div>
            @endguest
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl bg-[#faf8f5] p-4 text-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Gallery</p>
                    <p class="mt-1 font-medium text-slate-700">Browse {{ $totalPhotos ?? 0 }}+ wedding photos from family and friends.</p>
                    <a href="{{ route('gallery') }}" class="mt-2 inline-block text-[#8b7355] text-sm font-medium">View All →</a>
                </div>
                <div class="rounded-2xl bg-[#faf8f5] p-4 text-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Contests</p>
                    <p class="mt-1 font-medium text-slate-700">Enter your favorite photos in wedding photo contests.</p>
                    <a href="{{ route('contest') }}" class="mt-2 inline-block text-[#8b7355] text-sm font-medium">See Contests →</a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[1.25rem] bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h3 class="text-lg font-semibold text-[#8b7355]">Share Memories</h3>
            <p class="mt-2 text-sm text-slate-700">Upload your favorite moments from the wedding celebration.</p>
        </div>
        <div class="rounded-[1.25rem] bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h3 class="text-lg font-semibold text-[#8b7355]">Contests &amp; Voting</h3>
            <p class="mt-2 text-sm text-slate-700">Rate photos, favorite your favorites, and enter contests.</p>
        </div>
        <div class="rounded-[1.25rem] bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h3 class="text-lg font-semibold text-[#8b7355]">Community</h3>
            <p class="mt-2 text-sm text-slate-700">Connect with family and friends through the phonebook.</p>
            <a href="{{ route('phonebook') }}" class="mt-2 inline-block text-[#8b7355] text-sm font-medium">View Phonebook →</a>
        </div>
        <div class="rounded-[1.25rem] bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h3 class="text-lg font-semibold text-[#8b7355]">Your Profile</h3>
            <p class="mt-2 text-sm text-slate-700">Track your uploads, favorites, and comments all in one place.</p>
            @auth
                <a href="{{ route('wedding.profile', auth()->id()) }}" class="mt-2 inline-block text-[#8b7355] text-sm font-medium">View Profile →</a>
            @endauth
        </div>
    </div>
</section>

<style>
.hero-section {
    background: linear-gradient(135deg, #faf8f5 0%, #f5f0eb 100%);
    border-bottom: 1px solid #d4c4b0;
    padding: 3rem 1rem;
    text-align: center;
}
.hero-content {
    max-width: 800px;
    margin: 0 auto;
}
.hero-content h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #8b7355;
    margin-bottom: 0.5rem;
}
.hero-content .hero__date {
    font-size: 1.25rem;
    color: #6b5a47;
    margin-top: 0.5rem;
}
</style>
@endsection
