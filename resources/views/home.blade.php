@extends('layouts.app')
@section('title', 'Nick & Ollie Fortune Wedding')
@section('meta_description', 'Wedding photo sharing site for Nick & Ollie Fortune.')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12">
    <div class="grid gap-6 lg:grid-cols-[1.05fr_.95fr] lg:items-center">
        <div class="space-y-5">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[#8b7355]">Wedding Photo Sharing</p>
            <h1 class="text-4xl font-bold leading-tight sm:text-5xl">Nick &amp; Ollie Fortune</h1>
            <p class="max-w-2xl text-lg text-slate-700">Tulay sa aming pagdiriwang — ikuwento ang iyong kwento, ikuwento ang saya.</p>
            <div class="flex flex-wrap gap-3 text-sm font-medium">
                <a href="{{ route('gallery') }}" class="rounded-full bg-[#8b7355] px-5 py-3 text-white">View Gallery</a>
                <a href="{{ route('upload') }}" class="rounded-full border border-[#8b7355] px-5 py-3 text-[#8b7355]">Upload Photos</a>
                <a href="{{ route('contest') }}" class="rounded-full border border-[#8b7355] px-5 py-3 text-[#8b7355]">Contests</a>
            </div>
        </div>
        <div class="grid gap-4 rounded-[1.25rem] border border-black/5 bg-white p-5 shadow-sm sm:p-6">
            <div class="grid grid-cols-2 gap-3 text-center text-sm">
                <div class="rounded-2xl bg-[#faf8f5] p-4"><div class="text-[11px] uppercase tracking-[0.25em] text-slate-500">Date</div><div class="mt-1 font-semibold">November 13, 2026</div></div>
                <div class="rounded-2xl bg-[#faf8f5] p-4"><div class="text-[11px] uppercase tracking-[0.25em] text-slate-500">Welcome</div><div class="mt-1 font-semibold">Share memories</div></div>
            </div>
            <div class="space-y-3 rounded-2xl border border-[#d4c4b0]/60 bg-[#faf8f5] p-4 text-sm text-slate-700">
                <p class="font-semibold text-[#8b7355]">Legacy page inventory</p>
                <p>Home, gallery, upload, contest, phonebook, register, admin dashboard, themes, settings, and mail-notifier helpers.</p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl bg-[#faf8f5] p-4 text-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Ported</p>
                    <p class="mt-1 font-medium text-slate-700">Layout, routing shells, mobile nav, footer, and landing content.</p>
                </div>
                <div class="rounded-2xl bg-[#faf8f5] p-4 text-sm">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Next</p>
                    <p class="mt-1 font-medium text-slate-700">Controller-backed data, theme DB binding, and feature parity per page.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-[1.25rem] bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-[#8b7355]">Legacy pages found</h2>
            <p class="mt-2 text-sm text-slate-700">Home, register, gallery, contest, upload, phonebook, admin dashboard, theme tools, and API endpoints.</p>
        </div>
        <div class="rounded-[1.25rem] bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-[#8b7355]">Ported so far</h2>
            <p class="mt-2 text-sm text-slate-700">Shared layout, route shells, and landing content are now in Blade with mobile-first structure.</p>
        </div>
        <div class="rounded-[1.25rem] bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-[#8b7355]">Still to migrate</h2>
            <p class="mt-2 text-sm text-slate-700">Legacy hero DB binding, theme selector, gallery data, contest logic, upload flow, and phonebook CRUD.</p>
        </div>
        <div class="rounded-[1.25rem] bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-[#8b7355]">Mail settings</h2>
            <p class="mt-2 text-sm text-slate-700">SMTP_HOST, SMTP_PORT, SMTP_USERNAME, SMTP_PASSWORD, SMTP_ENCRYPTION, SMTP_FROM_EMAIL, SMTP_FROM_NAME still need Laravel mail config mapping later.</p>
        </div>
    </div>
</section>
@endsection
