@extends('layouts.app')
@section('title','Upload')
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

    <form method="POST" action="{{ route('upload.store') }}" enctype="multipart/form-data" class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5">
        @csrf
        <div class="space-y-4">
            <label for="photos" class="block text-sm font-medium text-slate-700">Select photos</label>
            <input type="file" name="photos[]" id="photos" multiple accept="image/jpeg,image/png,image/webp" class="block w-full rounded-lg border border-slate-200 p-3 text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-[#8b7355] file:px-4 file:py-2 file:text-sm file:font-medium file:text-white hover:file:bg-[#6d5a42]">
            @error('photos')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-6">
            <button type="submit" class="rounded-full bg-[#8b7355] px-6 py-3 text-sm font-medium text-white hover:bg-[#6d5a42]">Upload Photos</button>
        </div>
    </form>
</section>
@endsection
