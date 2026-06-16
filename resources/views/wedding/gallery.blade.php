@extends('layouts.app')
@section('title', 'Photo Gallery')
@section('meta_description', 'Browse and enjoy all the photos from Nick & Ollie Fortune\'s wedding celebration.')
@section('content')

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0" style="color: var(--primary);">Photo Gallery</h1>
        @auth
        <a href="{{ route('upload') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-upload me-1"></i> Upload
        </a>
        @endauth
    </div>

    @if($photos->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-images fa-3x text-muted mb-3"></i>
            <h2 class="h5 text-muted">No photos yet</h2>
            <p class="text-muted mb-4">Be the first to share a memory!</p>
            @auth
            <a href="{{ route('upload') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Upload Photos
            </a>
            @endauth
        </div>
    @else
        <div class="gallery-grid" id="gallery-grid">
            @foreach($photos as $photo)
            <a href="{{ route('photo.show', $photo['id']) }}" class="gallery-item">
                <img src="{{ $photo['thumb_url'] }}"
                     alt="{{ $photo['caption'] ?? 'Wedding photo' }}"
                     loading="lazy"
                     onerror="this.src='{{ $photo['photo_url'] }}'">
                <div class="overlay">
                    <i class="fas fa-expand"></i>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($pages > 1)
        <nav aria-label="Gallery pagination" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="?page={{ $page - 1 }}&limit={{ $limit }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                @for($i = max(1, $page - 2); $i <= min($pages, $page + 2); $i++)
                <li class="page-item {{ $i == $page ? 'active' : '' }}">
                    <a class="page-link" href="?page={{ $i }}&limit={{ $limit }}">{{ $i }}</a>
                </li>
                @endfor
                <li class="page-item {{ $page >= $pages ? 'disabled' : '' }}">
                    <a class="page-link" href="?page={{ $page + 1 }}&limit={{ $limit }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        @endif
    @endif
</div>

@endsection
