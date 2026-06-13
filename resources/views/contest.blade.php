@extends('layouts.app')

@section('title', 'Contests')

@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12 space-y-6">
    <div class="flex flex-col gap-2">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#8b7355]">Community votes</p>
        <h1 class="text-3xl font-bold sm:text-4xl">Contests</h1>
        <p class="max-w-2xl text-slate-700">Vote for your favorite wedding photos in each category!</p>
    </div>

    @if($contests->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-trophy text-4xl text-gray-300 mb-4"></i>
            <h2 class="text-lg font-medium text-gray-500">No contests available yet</h2>
            <p class="text-gray-400">Check back soon for new contests!</p>
        </div>
    @else
        <div class="grid gap-4 md:grid-cols-2">
            @foreach($contests as $contest)
            <a href="{{ route('contest.show', $contest->id) }}" class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5 hover:shadow-md transition-shadow">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-2xl flex items-center justify-center" style="background: var(--primary)">
                        <i class="fas {{ $contest->icon ?? 'fa-trophy' }} text-white text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h2 class="text-lg font-semibold truncate" style="color: var(--primary)">{{ $contest->title }}</h2>
                            @if($contest->status === 'active')
                                <span class="badge bg-success text-xs">Active</span>
                            @elseif($contest->status === 'closed')
                                <span class="badge bg-warning text-xs">Closed</span>
                            @elseif($contest->status === 'draft')
                                <span class="badge bg-secondary text-xs">Draft</span>
                            @endif
                        </div>
                        @if($contest->description)
                            <p class="text-sm text-slate-600 line-clamp-2">{{ $contest->description }}</p>
                        @endif
                        <div class="flex items-center gap-4 mt-3 text-xs text-slate-500">
                            <span><i class="fas fa-images me-1"></i>{{ $contest->entry_count }} entries</span>
                            @if($contest->prize)
                                <span><i class="fas fa-gift me-1"></i>{{ $contest->prize }}</span>
                            @endif
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-300 mt-1"></i>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</section>
@endsection
