@extends('layouts.app')

@section('title', 'Contests')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="mb-0" style="color: var(--primary);">Contests</h1>
            <p class="text-muted small mb-0">Vote for your favorite photos in each category!</p>
        </div>
    </div>

    @if($contests->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
            <h2 class="h5 text-muted">No contests available yet</h2>
            <p class="text-muted">Check back soon for new contests!</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($contests as $contest)
            <div class="col-md-6 col-lg-4">
                <div class="card contest-card h-100 {{ $contest->status === 'active' ? 'border-success' : ($contest->status === 'closed' ? 'border-warning' : 'border-secondary') }}">
                    <div class="card-header bg-white py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas {{ $contest->icon ?? 'fa-trophy' }} me-2 text-primary"></i>{{ $contest->title }}
                            </h5>
                            @if($contest->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($contest->status === 'closed')
                                <span class="badge bg-warning text-dark"><i class="fas fa-lock me-1"></i>Closed</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($contest->status) }}</span>
                            @endif
                        </div>
                        @if($contest->description)
                            <p class="small text-muted mb-0 mt-1">{{ $contest->description }}</p>
                        @endif
                    </div>
                    <div class="card-body py-2">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small text-muted"><i class="fas fa-images me-1"></i> {{ $contest->entry_count }} entries</span>
                        </div>
                        @if($contest->prize)
                            <div class="small text-muted"><i class="fas fa-gift me-1"></i>{{ $contest->prize }}</div>
                        @endif
                    </div>
                    <div class="card-footer bg-white py-2">
                        <a href="{{ route('contest.show', $contest->id) }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-eye me-1"></i> View Contest
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.contest-card {
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.contest-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08);
}
</style>
@endsection
