@extends('layouts.app')
@section('title','Upload')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12 space-y-6">
    <div class="flex flex-col gap-2">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#8b7355]">Add photos</p>
        <h1 class="text-3xl font-bold sm:text-4xl">Upload</h1>
        <p class="max-w-2xl text-slate-700">Legacy upload flow accepts jpg, png, and webp, then generates thumb and print sizes. The Laravel controller can mirror that next.</p>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-[#8b7355]">Accepted files</h2>
            <p class="mt-2 text-sm text-slate-700">jpg, jpeg, png, webp.</p>
        </div>
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-[#8b7355]">Legacy processing</h2>
            <p class="mt-2 text-sm text-slate-700">Thumb 400px at quality 90, print 2000px at quality 80.</p>
        </div>
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-[#8b7355]">Mail note</h2>
            <p class="mt-2 text-sm text-slate-700">Upload notifier SMTP settings still need Laravel mail config mapping later.</p>
        </div>
    </div>
</section>
@endsection
