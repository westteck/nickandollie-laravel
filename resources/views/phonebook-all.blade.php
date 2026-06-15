@extends('layouts.app')
@section('title', 'Phone Book — All Entries')
@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-sec">Wedding Directory</p>
            <h1 class="text-3xl font-bold sm:text-4xl"><i class="fas fa-address-book me-2"></i>Phone Book</h1>
            <p class="text-body/70">Family and guest contact information</p>
        </div>
        <a href="{{ route('phonebook') }}" class="rounded-md border border-sec/30 px-4 py-2 text-sm text-body/80 hover:bg-slate-50">
            <i class="fas fa-arrow-left me-1"></i>Back to Phonebook
        </a>
    </div>

    @forelse($grouped as $letter => $entries)
        <div class="mb-4">
            <h5 class="text-sec border-bottom pb-1 mb-3"><i class="fas fa-font me-1"></i>{{ $letter }}</h5>
            <div class="row g-2">
                @foreach($entries as $e)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100">
                            <div class="card-body py-2 px-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong class="d-block">{{ $e->entry_name }}</strong>
                                        @if($e->first_name)
                                            <span class="text-muted small">{{ $e->first_name }}</span>
                                        @endif
                                    </div>
                                </div>
                                @if($e->family_connection)
                                    <div class="mt-1">
                                        <span class="badge bg-light text-dark"><i class="fas fa-link me-1"></i>{{ $e->family_connection }}</span>
                                    </div>
                                @endif
                                <div class="mt-1 small">
                                    @if($e->phone)
                                        <span class="me-3"><i class="fas fa-phone me-1 text-muted"></i>{{ $e->phone }}</span>
                                    @endif
                                    @if($e->mobile)
                                        <span class="me-3"><i class="fas fa-mobile-alt me-1 text-muted"></i>{{ $e->mobile }}</span>
                                    @endif
                                </div>
                                @if($e->email)
                                    <div class="mt-1 small">
                                        <i class="fas fa-envelope me-1 text-muted"></i>
                                        <a href="mailto:{{ $e->email }}">{{ $e->email }}</a>
                                    </div>
                                @endif
                                @if($e->address || $e->city || $e->state || $e->zip)
                                    <div class="text-muted small mt-1">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ array_filter([$e->address, $e->city, $e->state, $e->zip]) ? implode(', ', array_filter([$e->address, $e->city, $e->state, $e->zip])) : '' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-10">
            <i class="fas fa-address-book fa-3x text-body/40 mb-3"></i>
            <p class="text-body/70">No entries yet.</p>
        </div>
    @endforelse
</section>
@endsection
