@extends('layouts.app')
@section('title', 'Photo Gallery')
@section('meta_description', 'Browse and enjoy all the photos from Nick & Ollie Fortune\'s wedding celebration.')
@section('content')

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="font-display mb-0" style="font-family:'Playfair Display',serif;font-weight:600;color:#FAEBD7;font-size:2rem;letter-spacing:0.02em;">Photo Gallery</h1>
        @auth
        <a href="{{ route('upload') }}" class="nav-cta">
            <i class="fas fa-upload me-2"></i>Upload
        </a>
        @endauth
    </div>

    @if($photos->isEmpty())
        <div class="glass-panel text-center py-5" style="border-radius:1.5rem;">
            <i class="fas fa-images fa-3x mb-3" style="color:rgba(194,184,183,0.6);"></i>
            <h2 class="h5" style="color:rgba(250,235,215,0.85);font-family:'Playfair Display',serif;">No photos yet</h2>
            <p style="color:rgba(250,235,215,0.6);font-family:'Source Sans 3',sans-serif;">Be the first to share a memory!</p>
            @auth
            <a href="{{ route('upload') }}" class="nav-cta">
                <i class="fas fa-plus me-2"></i>Upload Photos
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
                    <a class="page-link" href="?page={{ $page - 1 }}&limit={{ $limit }}" aria-label="Previous" style="background:rgba(11,16,32,0.6);border-color:rgba(194,184,183,0.3);color:#FAEBD7;">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                @for($i = max(1, $page - 2); $i <= min($pages, $page + 2); $i++)
                <li class="page-item {{ $i == $page ? 'active' : '' }}">
                    <a class="page-link" href="?page={{ $i }}&limit={{ $limit }}" style="{{ $i == $page ? 'background:linear-gradient(135deg,#171d33,#36538f);border-color:transparent;color:#FAEBD7;' : 'background:rgba(11,16,32,0.6);border-color:rgba(194,184,183,0.3);color:#FAEBD7;' }}">{{ $i }}</a>
                </li>
                @endfor
                <li class="page-item {{ $page >= $pages ? 'disabled' : '' }}">
                    <a class="page-link" href="?page={{ $page + 1 }}&limit={{ $limit }}" aria-label="Next" style="background:rgba(11,16,32,0.6);border-color:rgba(194,184,183,0.3);color:#FAEBD7;">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        @endif
    @endif
</div>

@endsection
