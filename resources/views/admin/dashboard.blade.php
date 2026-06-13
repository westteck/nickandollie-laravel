@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12 space-y-6">
    <div class="flex flex-col gap-2">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#8b7355]">Admin</p>
        <h1 class="text-3xl font-bold sm:text-4xl">Dashboard</h1>
        <p class="max-w-2xl text-slate-700">Laravel admin shell for themes, contests, phonebook, and settings migration.</p>
    </div>
    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">Theme tools</div>
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">Contest moderation</div>
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">Phonebook admin</div>
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">Site settings</div>
    </div>
</section>
@endsection
