@extends('layouts.app')

@section('title', $contest->title ?? 'Contest')

@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12 space-y-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('contest') }}" class="text-sm text-slate-500 hover:text-[#8b7355]">
            <i class="fas fa-arrow-left me-1"></i>All Contests
        </a>
    </div>

    <div class="flex flex-col gap-2">
        <div class="flex items-center gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--primary)">
                <i class="fas {{ $contest->icon ?? 'fa-trophy' }} text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold sm:text-3xl">{{ $contest->title }}</h1>
                @if($contest->status === 'active')
                    <span class="badge bg-success text-xs">Active</span>
                @elseif($contest->status === 'closed')
                    <span class="badge bg-warning text-xs">Closed</span>
                @endif
            </div>
        </div>
        @if($contest->description)
            <p class="text-slate-600 max-w-2xl">{{ $contest->description }}</p>
        @endif
        <div class="flex items-center gap-4 text-sm text-slate-500">
            <span><i class="fas fa-images me-1"></i>{{ $entries->count() }} entries</span>
            @if($contest->prize)
                <span><i class="fas fa-gift me-1"></i>Prize: {{ $contest->prize }}</span>
            @endif
            @if($contest->start_date)
                <span><i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($contest->start_date)->format('M j, Y') }}</span>
            @endif
        </div>
    </div>

    @if($entries->isEmpty())
        <div class="text-center py-12 bg-white rounded-3xl shadow-sm ring-1 ring-black/5">
            <i class="fas fa-images text-4xl text-gray-300 mb-4"></i>
            <h2 class="text-lg font-medium text-gray-500">No entries yet</h2>
            <p class="text-gray-400 mb-4">Be the first to enter!</p>
            <a href="{{ route('gallery') }}" class="inline-flex items-center rounded-lg px-4 py-2 text-white text-sm font-medium" style="background: var(--primary)">
                <i class="fas fa-plus me-1"></i>Enter Contest
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
            @foreach($entries as $entry)
            <div class="group relative rounded-2xl overflow-hidden bg-white shadow-sm ring-1 ring-black/5">
                <a href="/storage/originals/{{ $entry->filename }}" class="block aspect-square">
                    <img src="/storage/thumbs/{{ $entry->filename }}" alt="{{ $entry->caption ?? 'Entry' }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='/storage/originals/{{ $entry->filename }}'">
                </a>
                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-2">
                    <div class="flex items-center justify-between text-white text-xs">
                        <span class="truncate">{{ $entry->caption ?? 'Entry' }}</span>
                        <span class="flex items-center gap-1">
                            <i class="far fa-thumbs-up"></i>{{ $entry->votes ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    @if($contest->rules)
        <div class="bg-white rounded-3xl p-6 shadow-sm ring-1 ring-black/5">
            <h2 class="text-lg font-semibold mb-3" style="color: var(--primary)">Contest Rules</h2>
            <div class="prose prose-sm text-slate-600 max-w-none">
                {!! nl2br(e($contest->rules)) !!}
            </div>
        </div>
    @endif
</section>
@endsection
