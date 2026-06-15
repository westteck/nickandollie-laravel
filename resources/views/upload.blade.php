@extends('layouts.app')
@section('title', 'Upload Photos')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
<style>
.upload-area {
    border: 3px dashed var(--secondary);
    border-radius: 1rem;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
    background: rgba(212, 196, 176, 0.1);
    margin-bottom: 1.5rem;
}
.upload-area:hover,
.upload-area.dragover {
    border-color: var(--primary);
    background: rgba(139, 115, 85, 0.05);
}
.upload-area i {
    font-size: 3rem;
    color: var(--primary);
    margin-bottom: 1rem;
    display: block;
}
.upload-preview {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}
.upload-preview-item {
    position: relative;
    border-radius: 0.5rem;
    overflow: hidden;
    aspect-ratio: 1;
}
.upload-preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
@endpush

@section('content')
<div class="container py-4">
    <h1 class="mb-1" style="color: var(--primary)">
        <i class="fas fa-cloud-upload-alt me-2"></i>Upload Photos
    </h1>
    <p class="text-muted mb-4" style="font-size: 0.85rem;">Select up to 20 photos (jpg, png, webp). Each photo is saved as original + thumb (400px) + print (2000px) in WebP format.</p>

    @if(session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('upload.store') }}" enctype="multipart/form-data" id="upload-form">
                @csrf

                {{-- Hidden file input --}}
                <input type="file" name="photos[]" id="photo-input" multiple
                       accept="image/jpeg,image/png,image/webp" class="d-none">

                {{-- Dropzone tap area --}}
                <div id="dropzone" class="upload-area" role="button" tabindex="0" aria-label="Tap to select photos">
                    <i class="fas fa-images"></i>
                    <p class="mb-1 fw-medium" style="font-size: 1.1rem; color: var(--text);">Tap to select photos</p>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">JPG, PNG, or WebP — up to 20 photos</p>
                </div>

                @error('photos')
                    <p class="form-error">{{ $message }}</p>
                @enderror

                {{-- Thumbnail preview grid --}}
                <div id="preview-grid" class="upload-preview d-none"></div>

                {{-- Caption input --}}
                <div class="mb-3">
                    <label for="caption" class="form-label">Caption</label>
                    <p class="text-muted mb-2" style="font-size: 0.8rem;">Applied to all selected photos</p>
                    <input type="text" name="caption" id="caption" maxlength="255"
                           placeholder="Optional caption for all photos..."
                           class="form-control">
                </div>

                {{-- Upload button --}}
                <button type="submit" id="upload-btn" class="btn btn-primary" disabled>
                    <i class="fas fa-upload me-2"></i>Upload Photos
                </button>

                {{-- Progress bar --}}
                <div id="progress-container" class="d-none mt-3">
                    <div class="d-flex justify-content-between text-muted mb-1" style="font-size: 0.85rem;">
                        <span id="progress-label">Uploading...</span>
                        <span id="progress-percent">0%</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Cropper Modal --}}
<div id="cropper-modal" class="d-none" style="position:fixed;inset:0;z-index:1050;background:rgba(0,0,0,0.6);display:none;align-items:center;justify-content:center;padding:1rem;" role="dialog" aria-modal="true" aria-labelledby="cropper-modal-title">
    <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden">
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
            <h2 id="cropper-modal-title" class="h5 mb-0">Crop Photo</h2>
            <span id="cropper-photo-label" class="text-muted" style="font-size:0.85rem;"></span>
        </div>
        <div class="p-3 text-center">
            <img id="cropper-image" src="" alt="Crop preview" style="max-height:50vh;max-width:100%;">
        </div>
        <div class="d-flex gap-2 p-3 border-top">
            <button type="button" id="cropper-skip-btn" class="btn btn-outline-secondary flex-grow-1">Skip</button>
            <button type="button" id="cropper-crop-btn" class="btn btn-primary flex-grow-1">Crop & Continue</button>
        </div>
    </div>
</div>

{{-- Success Overlay --}}
<div id="success-overlay" class="d-none" style="position:fixed;inset:0;z-index:1050;background:rgba(0,0,0,0.8);display:none;align-items:center;justify-content:center;padding:1rem;">
    <div class="text-center text-white">
        <div class="mb-3">
            <i class="fas fa-check-circle" style="font-size:4rem;color:var(--success);"></i>
        </div>
        <h2 class="h4 mb-2">Upload Complete!</h2>
        <p id="success-count" class="text-muted mb-3"></p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ route('gallery') }}" class="btn btn-primary">View Gallery</a>
            <button type="button" id="upload-more-btn" class="btn btn-outline-light">Upload More</button>
        </div>
    </div>
</div>

{{-- Error Toast --}}
<div id="error-toast" class="d-none" style="position:fixed;bottom:1rem;left:50%;transform:translateX(-50%);z-index:1060;">
    <div class="alert alert-danger mb-0">
        <span id="error-message"></span>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script src="{{ asset('js/upload.js') }}"></script>
@endpush
