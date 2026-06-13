@extends('layouts.app')

@section('title', 'Photo Gallery')
@section('description', 'Browse and enjoy all the photos from Nick & Ollie Fortune\'s wedding celebration.')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="mb-0">Photo Gallery</h1>
    @auth
        <a href="{{ route('upload') }}" class="btn btn-primary btn-sm"><i class="fas fa-upload me-1"></i> Upload</a>
    @endauth
</div>

<div class="row g-2" id="gallery-grid">
    @forelse($photos as $photo)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 shadow-sm">
                <a href="{{ route('photo.show', $photo['id']) }}">
                    <img src="{{ $photo['thumb_url'] }}" class="card-img-top" alt="{{ $photo['caption'] ?? 'Photo' }}">
                </a>
                <div class="card-body p-2">
                    <p class="card-text mb-1 small text-truncate">{{ $photo['caption'] ?? '' }}</p>
                    <p class="card-text small text-muted mb-0">by {{ $photo['uploader'] }}</p>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">No photos in gallery yet.</p>
        </div>
    @endforelse
</div>

<div class="mt-4">
    {{ $photos->appends(['page' => $page, 'limit' => $limit])->links() }}
</div>
@endsection
