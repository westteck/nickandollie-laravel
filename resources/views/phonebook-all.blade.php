@extends('layouts.app')
@section('title', 'Phone Book — All Entries')
@section('content')

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="mb-0" style="color: var(--primary);"><i class="fas fa-address-book me-2"></i>Phone Book</h1>
            <p class="text-muted small mb-0">Family and guest contact information</p>
        </div>
        <a href="{{ route('phonebook') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Back to Phonebook
        </a>
    </div>

    @forelse($grouped as $letter => $entries)
        <div class="mb-4">
            <h5 class="text-primary border-bottom pb-1 mb-3"><i class="fas fa-font me-1"></i>{{ $letter }}</h5>
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
                                        {{ implode(', ', array_filter([$e->address, $e->city, $e->state, $e->zip])) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="fas fa-address-book fa-3x text-muted mb-3"></i>
            <p class="text-muted">No entries yet.</p>
        </div>
    @endforelse
</div>

@endsection
