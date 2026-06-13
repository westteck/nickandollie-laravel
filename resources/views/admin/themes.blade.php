@extends('layouts.app')

@section('title', 'Theme Settings')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6" style="color: var(--primary)">Theme Settings</h1>

    @if(session('status'))
        <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-800 text-sm">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.theme.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        @php
        $fields = [
            'primary' => 'Primary (headers, nav, buttons)',
            'secondary' => 'Secondary (borders, dividers)',
            'accent' => 'Accent (highlights, icons)',
            'background' => 'Background (page background)',
            'text' => 'Text (body text)',
        ];
        @endphp

        @foreach($fields as $key => $label)
        <div>
            <label for="{{ $key }}" class="block text-sm font-medium mb-1">{{ $label }}</label>
            <div class="flex items-center gap-3">
                <input type="color" name="{{ $key }}" id="{{ $key }}" value="{{ $theme->$key }}"
                    class="h-10 w-16 rounded cursor-pointer border border-gray-300" oninput="document.getElementById('{{ $key }}_hex').value = this.value">
                <input type="text" id="{{ $key }}_hex" value="{{ $theme->$key }}" maxlength="7"
                    class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm font-mono"
                    oninput="document.getElementById('{{ $key }}').value = this.value">
            </div>
            @error($key)
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        @endforeach

        <button type="submit" class="inline-flex items-center rounded-lg px-5 py-2.5 text-white text-sm font-medium"
            style="background: var(--primary)">Save Theme</button>
    </form>
</div>
@endsection
