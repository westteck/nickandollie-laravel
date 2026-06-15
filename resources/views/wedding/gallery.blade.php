@extends('layouts.app')

@section('title', 'Photo Gallery')
@section('description', 'Browse and enjoy all the photos from Nick & Ollie Fortune\'s wedding celebration.')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-6">

    {{-- Gallery Header --}}
    <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="font-display text-2xl font-semibold text-night dark:text-accent">Photo Gallery</h1>
            <p class="text-sm text-body">{{ $total ?? 0 }} photos</p>
        </div>
        @auth
            <a href="{{ route('upload') }}"
               class="inline-flex items-center gap-2 rounded-full bg-primary px-5 py-2.5 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-sec">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Upload
            </a>
        @endauth
    </div>

    {{-- 5-column photo grid --}}
    <div class="grid grid-cols-3 gap-2 sm:grid-cols-4 md:grid-cols-5"
         id="gallery-grid">
        @forelse($photos as $photo)
            <a href="{{ route('photo.show', $photo['id']) }}"
               class="group aspect-square overflow-hidden rounded-xl glass-panel/10 ring-1 ring-white/10 transition hover:ring-sec">
                <img src="{{ $photo['thumb_url'] }}"
                     alt="{{ $photo['caption'] ?? 'Photo' }}"
                     class="h-full w-full object-cover transition group-hover:scale-105">
            </a>
        @empty
            <div class="col-span-full py-16 text-center">
                <p class="text-body/70">No photos in gallery yet.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if(($pages ?? 1) > 1)
    <div class="mt-6 flex items-center justify-center gap-3">
        @if($page > 1)
            <a href="?page={{ $page - 1 }}&limit={{ $limit }}"
               class="rounded-full border border-sec/40 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-sec transition hover:bg-sec/10">
                ← Prev
            </a>
        @endif
        <span class="text-xs text-body">Page {{ $page }} of {{ $pages }}</span>
        @if($page < $pages)
            <a href="?page={{ $page + 1 }}&limit={{ $limit }}"
               class="rounded-full border border-sec/40 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-sec transition hover:bg-sec/10">
                Next →
            </a>
        @endif
    </div>
    @endif

</div>
@endsection