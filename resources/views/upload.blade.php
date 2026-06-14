@extends('layouts.app')
@section('title', 'Upload Photos')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
@endpush

@section('content')
<section class="mx-auto max-w-6xl px-4 py-8 sm:py-12 space-y-6">
    <div class="flex flex-col gap-2">
        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#8b7355]">Add photos</p>
        <h1 class="text-3xl font-bold sm:text-4xl">Upload Photos</h1>
        <p class="max-w-2xl text-slate-700">Select up to 20 photos (jpg, png, webp). Each photo is saved as original + thumb (400px) + print (2000px) in WebP format.</p>
    </div>

    @if(session('status'))
        <div class="rounded-xl bg-green-50 p-4 text-green-800">{{ session('status') }}</div>
    @endif

    <!-- Upload Form -->
    <form method="POST" action="{{ route('upload.store') }}" enctype="multipart/form-data"
          id="upload-form"
          class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5 space-y-6">

        @csrf

        <!-- Hidden file input -->
        <input type="file" name="photos[]" id="photo-input" multiple
               accept="image/jpeg,image/png,image/webp" class="hidden">

        <!-- Dropzone tap area -->
        <div id="dropzone"
             class="dropzone flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-[#8b7355] bg-[#faf8f5] p-8 sm:p-12 cursor-pointer transition-colors hover:border-[#6d5a42] hover:bg-[#f5f0ea] active:bg-[#efe9e0]"
             role="button"
             tabindex="0"
             aria-label="Tap to select photos">

            <svg class="mb-4 h-12 w-12 text-[#8b7355]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="text-center text-lg font-medium text-[#8b7355]">Tap to select photos</p>
            <p class="mt-1 text-sm text-slate-500">JPG, PNG, or WebP — up to 20 photos</p>
        </div>

        @error('photos')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <!-- Thumbnail preview grid -->
        <div id="preview-grid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3 hidden">
            <!-- Thumbnails injected by JS -->
        </div>

        <!-- Caption input -->
        <div>
            <label for="caption" class="block text-sm font-medium text-slate-700 mb-1">Caption</label>
            <p class="text-xs text-slate-400 mb-2">Applied to all selected photos</p>
            <input type="text" name="caption" id="caption" maxlength="255"
                   placeholder="Optional caption for all photos..."
                   class="w-full rounded-lg border border-slate-200 px-4 py-3 text-sm focus:border-[#8b7355] focus:outline-none focus:ring-2 focus:ring-[#8b7355]/20">
        </div>

        <!-- Upload button -->
        <button type="submit" id="upload-btn" disabled
                class="w-full sm:w-auto rounded-full bg-[#8b7355] px-8 py-3 text-sm font-medium text-white hover:bg-[#6d5a42] disabled:cursor-not-allowed disabled:opacity-50 transition-colors min-h-[44px]">
            Upload Photos
        </button>

        <!-- Overall progress bar -->
        <div id="progress-container" class="hidden space-y-2">
            <div class="flex justify-between text-sm text-slate-600">
                <span id="progress-label">Uploading...</span>
                <span id="progress-percent">0%</span>
            </div>
            <div class="h-2.5 w-full rounded-full bg-slate-200 overflow-hidden">
                <div id="progress-bar" class="h-full bg-[#8b7355] rounded-full transition-all duration-200" style="width: 0%"></div>
            </div>
        </div>
    </form>
</section>

<!-- Cropper Modal -->
<div id="cropper-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" role="dialog" aria-modal="true" aria-labelledby="cropper-modal-title">
    <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <h2 id="cropper-modal-title" class="text-lg font-semibold text-slate-800">Crop Photo</h2>
            <span id="cropper-photo-label" class="text-sm text-slate-400"></span>
        </div>
        <div class="flex-1 overflow-hidden p-4">
            <img id="cropper-image" src="" alt="Crop preview" class="max-h-[50vh] w-full object-contain">
        </div>
        <div class="flex gap-3 border-t border-slate-100 px-5 py-4">
            <button type="button" id="cropper-skip-btn"
                    class="flex-1 rounded-full border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors min-h-[44px]">
                Skip
            </button>
            <button type="button" id="cropper-crop-btn"
                    class="flex-1 rounded-full bg-[#8b7355] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#6d5a42] transition-colors min-h-[44px]">
                Crop & Continue
            </button>
        </div>
    </div>
</div>

<!-- Success Overlay -->
<div id="success-overlay" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-[#faf8f5]/95 backdrop-blur-sm p-6" role="dialog" aria-modal="true">
    <div class="text-center space-y-6 max-w-sm">
        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-green-100">
            <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Upload Complete!</h2>
            <p id="success-count" class="mt-2 text-slate-600"></p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('gallery') }}"
               class="rounded-full bg-[#8b7355] px-6 py-3 text-sm font-medium text-white hover:bg-[#6d5a42] transition-colors min-h-[44px] flex items-center justify-center">
                View Gallery
            </a>
            <button type="button" id="upload-more-btn"
                    class="rounded-full border border-slate-200 px-6 py-3 text-sm font-medium text-slate-600 hover:bg-slate-50 transition-colors min-h-[44px]">
                Upload More
            </button>
        </div>
    </div>
</div>

<!-- Error Toast -->
<div id="error-toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden rounded-xl bg-red-50 border border-red-200 px-5 py-3 text-red-700 shadow-lg text-sm max-w-sm text-center">
    <span id="error-message"></span>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script src="{{ asset('js/upload.js') }}"></script>
@endpush