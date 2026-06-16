@extends('layouts.app')

@section('title', 'Wedding Profile')
@section('meta_description', 'Wedding profile for Nick & Ollie Fortune celebration.')

@section('content')
<div class="container py-4">
    {{-- Profile Header --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ strtoupper(substr($profileUser->guest_name ?? $profileUser->first_name ?? 'G', 0, 1)) }}
                </div>
                <div>
                    <h2 class="mb-1">{{ $profileUser->guest_name ?? ($profileUser->first_name . ' ' . $profileUser->last_name) }}</h2>
                    <span class="badge bg-primary">{{ $relationshipLabel }}</span>
                    <p class="text-muted small mb-0 mt-1">
                        {{ $photoCount }} photo{{ $photoCount != 1 ? 's' : '' }} uploaded
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Own Profile Tabs --}}
    @if($isOwnProfile)
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="photos-tab" data-bs-toggle="tab" data-bs-target="#photos-pane" type="button" role="tab">My Photos</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="favorites-tab" data-bs-toggle="tab" data-bs-target="#favorites-pane" type="button" role="tab">Favorites</button>
        </li>
    </ul>
    @endif

    <div class="tab-content" id="profileTabContent">
        {{-- Photos Grid --}}
        <div class="tab-pane fade show active" id="photos-pane" role="tabpanel">
            @if($photos->count() > 0)
            <div class="row g-2" id="gallery-grid">
                @foreach($photos as $photo)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <a href="{{ route('photo.show', $photo['id']) }}">
                            <img src="{{ $photo['thumb_url'] }}" class="card-img-top" alt="{{ $photo['caption'] ?? 'Photo' }}">
                        </a>
                        <div class="card-body p-2">
                            <p class="card-text mb-1 small text-truncate">{{ $photo['caption'] ?? '' }}</p>
                            <p class="card-text small text-muted mb-0">
                                <i class="fas fa-heart text-danger"></i> {{ $photo['likes'] }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($totalPages > 1)
            <div class="mt-4 d-flex justify-content-center gap-2">
                @if($currentPage > 1)
                    <a href="?page={{ $currentPage - 1 }}" class="btn btn-outline-secondary btn-sm">← Prev</a>
                @endif
                <span class="btn btn-outline-secondary btn-sm disabled">Page {{ $currentPage }} of {{ $totalPages }}</span>
                @if($currentPage < $totalPages)
                    <a href="?page={{ $currentPage + 1 }}" class="btn btn-outline-secondary btn-sm">Next →</a>
                @endif
            </div>
            @endif
            @else
            <p class="text-muted text-center py-5">No photos uploaded yet.</p>
            @endif
        </div>

        {{-- Favorites Tab (Own Profile Only) --}}
        @if($isOwnProfile)
        <div class="tab-pane fade" id="favorites-pane" role="tabpanel">
            @if(count($favorites) > 0)
            <div class="row g-2">
                @foreach($favorites as $photo)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 shadow-sm">
                        <a href="{{ route('photo.show', $photo['id']) }}">
                            <img src="{{ $photo['thumb_url'] }}" class="card-img-top" alt="{{ $photo['caption'] ?? 'Photo' }}">
                        </a>
                        <div class="card-body p-2">
                            <p class="card-text mb-1 small text-truncate">{{ $photo['caption'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-muted text-center py-5">No favorites yet.</p>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
