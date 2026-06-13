@extends('layouts.app')
@section('title','Phonebook')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12 space-y-6">
    <div class="flex flex-col gap-2">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#8b7355]">Directory</p>
        <h1 class="text-3xl font-bold sm:text-4xl">Phone Book</h1>
        <p class="max-w-2xl text-slate-700">Guest directory shell for the Laravel rebuild. Legacy CRUD, lookup options, and fallback labels still need full data binding.</p>
    </div>
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-[#8b7355]">Contacts</h2>
            <p class="mt-2 text-sm text-slate-700">List family and guest entries here.</p>
        </div>
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-[#8b7355]">Lookup mapping</h2>
            <p class="mt-2 text-sm text-slate-700">Port connection/core-group labels and fallbacks from legacy schema.</p>
        </div>
        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold text-[#8b7355]">Admin CRUD</h2>
            <p class="mt-2 text-sm text-slate-700">Keep the edit/create/remove flow visible for later wiring.</p>
        </div>
    </div>
</section>
@endsection
