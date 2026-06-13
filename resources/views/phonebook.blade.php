@extends('layouts.app')
@section('title', 'Phonebook')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12 space-y-6">
    <div class="flex flex-col gap-2">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#8b7355]">Wedding Directory</p>
        <h1 class="text-3xl font-bold sm:text-4xl">Phonebook</h1>
        <p class="max-w-2xl text-slate-700">Find contact details for family, sponsors, and friends.</p>
    </div>

    <!-- Search & Filter -->
    <form method="GET" action="{{ route('phonebook') }}" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name..." class="rounded-lg border border-slate-200 px-4 py-2 text-sm">
        <select name="group" class="rounded-lg border border-slate-200 px-4 py-2 text-sm">
            <option value="">All Groups</option>
            @foreach($groups as $g)
                <option value="{{ $g }}" {{ $group == $g ? 'selected' : '' }}>{{ $g }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-md bg-[#8b7355] px-4 py-2 text-sm text-white">Filter</button>
        @if($search || $group)
            <a href="{{ route('phonebook') }}" class="rounded-md border border-slate-200 px-4 py-2 text-sm text-slate-600">Clear</a>
        @endif
    </form>

    <!-- Entries Grid -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($entries as $e)
            <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-black/5">
                <h3 class="text-lg font-semibold text-[#8b7355]">{{ $e->entry_name }}</h3>
                @if($e->first_name)
                    <p class="text-sm text-slate-500">{{ $e->first_name }}</p>
                @endif
                @if($e->family_connection)
                    <p class="mt-1 text-xs font-medium uppercase tracking-wider text-slate-400">{{ $e->family_connection }}</p>
                @endif
                @if($e->connection || $e->core_group)
                    <p class="mt-1 text-xs text-slate-400">{{ $e->connection }} · {{ $e->core_group }}</p>
                @endif
                <div class="mt-3 space-y-1 text-sm">
                    @if($e->phone)
                        <p class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $e->phone }}
                        </p>
                    @endif
                    @if($e->mobile)
                        <p class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            {{ $e->mobile }}
                        </p>
                    @endif
                    @if($e->email)
                        <p class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:{{ $e->email }}" class="text-[#8b7355] hover:underline">{{ $e->email }}</a>
                        </p>
                    @endif
                    @if($e->address)
                        <p class="flex items-start gap-2">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <span>{{ $e->address }}{{ $e->city ? ', ' . $e->city : '' }}{{ $e->state ? ', ' . $e->state : '' }} {{ $e->zip }}</span>
                        </p>
                    @endif
                </div>
                @if($e->notes)
                    <p class="mt-3 text-xs text-slate-400">{{ $e->notes }}</p>
                @endif
            </div>
        @empty
            <div class="col-span-full text-center py-10">
                <p class="text-slate-500">No entries found.</p>
            </div>
        @endforelse
    </div>
</section>
@endsection
