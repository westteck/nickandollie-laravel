@extends('layouts.app')
@section('title','Gallery')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12 space-y-6">
    <div class="flex flex-col gap-2">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sec">Shared memories</p>
        <h1 class="font-display text-3xl font-bold text-accent sm:text-4xl">Photo Gallery</h1>
        <p class="max-w-2xl text-body">A collection of photos from Nick &amp; Ollie's special day.</p>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        <div class="glass-panel rounded-2xl p-5">
            <h2 class="text-lg font-semibold text-sec">Photos</h2>
            <p class="mt-2 text-sm text-body">Photo grid will appear here.</p>
        </div>
        <div class="glass-panel rounded-2xl p-5">
            <h2 class="text-lg font-semibold text-sec">Counts</h2>
            <p class="mt-2 text-sm text-body">Show totals, votes, and comments here.</p>
        </div>
        <div class="glass-panel rounded-2xl p-5">
            <h2 class="text-lg font-semibold text-sec">Coming Soon</h2>
            <p class="mt-2 text-sm text-body">More features on the way!</p>
        </div>
    </div>
</section>
@endsection