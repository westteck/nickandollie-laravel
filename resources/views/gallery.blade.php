@extends('layouts.app')
@section('title','Gallery')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12 space-y-6">
    <div class="flex flex-col gap-2">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#8b7355]">Shared memories</p>
        <h1 class="text-3xl font-bold sm:text-4xl">Photo Gallery</h1>
        <p class="max-w-2xl text-slate-700">Legacy gallery includes uploaded wedding photos, print/thumb generation, and counts. This shell is ready for controller-backed data.</p>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-[#8b7355]">Photos</h2>
            <p class="mt-2 text-sm text-slate-700">Display the uploaded photo grid here.</p>
        </div>
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-[#8b7355]">Counts</h2>
            <p class="mt-2 text-sm text-slate-700">Show totals, votes, and comments from the legacy gallery list.</p>
        </div>
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-[#8b7355]">Mobile first</h2>
            <p class="mt-2 text-sm text-slate-700">Keep the same compact layout for small screens first.</p>
        </div>
    </div>
</section>
@endsection
