@extends('layouts.app')
@section('title','Contest')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12 space-y-6">
    <div class="flex flex-col gap-2">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#8b7355]">Community votes</p>
        <h1 class="text-3xl font-bold sm:text-4xl">Contests</h1>
        <p class="max-w-2xl text-slate-700">Legacy contests support photo voting and related UI. This Blade shell keeps the same mobile-first direction while data is ported.</p>
    </div>
    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-[#8b7355]">Voting</h2>
            <p class="mt-2 text-sm text-slate-700">Add vote totals and ranking once controller data is wired in.</p>
        </div>
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-[#8b7355]">Share flow</h2>
            <p class="mt-2 text-sm text-slate-700">Keep the contest entry flow simple on phones first.</p>
        </div>
    </div>
</section>
@endsection
