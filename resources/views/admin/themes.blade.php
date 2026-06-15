@extends('layouts.app')

@section('title', 'Theme Settings')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-2" style="color: var(--color-primary)">Theme Settings</h1>
    <p class="text-sm text-body/70 mb-8">Choose a preset or customize colors manually.</p>

    @if(session('status'))
        <div class="mb-6 p-3 rounded-lg bg-green-100 text-accent text-sm">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-3 rounded-lg bg-red-100 text-red-800 text-sm">{{ session('error') }}</div>
    @endif

    {{-- ============================================= --}}
    {{-- PRESET PICKER --}}
    {{-- ============================================= --}}
    <div class="mb-10">
        <h2 class="text-lg font-semibold mb-4" style="color: var(--color-primary)">Theme Presets</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach($presets as $key => $preset)
                <div class="preset-card rounded-xl border overflow-hidden {{ $currentPreset === $key ? 'ring-2 ring-green-500' : 'border-sec/30' }}"
                     data-preset="{{ $key }}"
                     style="background: {{ $preset['background'] }}; color: {{ $preset['text'] }};">

                    {{-- Color swatch strip --}}
                    <div class="flex h-3">
                        <div class="flex-1" style="background: {{ $preset['primary'] }}"></div>
                        <div class="flex-1" style="background: {{ $preset['secondary'] }}"></div>
                        <div class="flex-1" style="background: {{ $preset['accent'] }}"></div>
                        <div class="flex-1" style="background: {{ $preset['background'] }}"></div>
                        <div class="flex-1" style="background: {{ $preset['text'] }}"></div>
                    </div>

                    {{-- Card body --}}
                    <div class="p-3">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold">{{ $preset['name'] }}</span>
                            @if($currentPreset === $key)
                                <span class="inline-flex items-center gap-1 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Active
                                </span>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            {{-- Use This button --}}
                            <form method="POST" action="{{ route('admin.theme.preset') }}" class="flex-1">
                                @csrf
                                <input type="hidden" name="preset" value="{{ $key }}">
                                <button type="submit"
                                        class="w-full text-xs px-2 py-1.5 rounded font-medium transition-colors"
                                        style="background: var(--color-primary); color: white;">
                                    Use This
                                </button>
                            </form>

                            {{-- Preview button --}}
                            <button type="button"
                                    class="preview-btn text-xs px-2 py-1.5 rounded font-medium border transition-colors"
                                    style="border-color: var(--color-primary); color: var(--color-primary);"
                                    data-preset="{{ $key }}">
                                Preview
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Preview banner (shown when preview is active) --}}
        <div id="preview-banner" class="hidden mt-4 p-4 rounded-xl border-2 border-dashed flex items-center justify-between"
             style="border-color: var(--color-primary);">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium" style="color: var(--color-primary)">Previewing:</span>
                <span id="preview-name" class="text-sm font-semibold"></span>
                <span class="text-xs text-body/70">(temporarily applied — not saved)</span>
            </div>
            <div class="flex gap-2">
                <button id="preview-apply-btn" type="button" class="text-xs px-3 py-1.5 rounded font-medium text-white" style="background: var(--color-primary);">
                    Apply This Preset
                </button>
                <button id="preview-cancel-btn" type="button" class="text-xs px-3 py-1.5 rounded font-medium border" style="border-color: var(--color-primary); color: var(--color-primary);">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- CUSTOM COLOR EDITOR --}}
    {{-- ============================================= --}}
    <div class="bg-white rounded-xl border border-sec/30 p-6">
        <h2 class="text-lg font-semibold mb-4" style="color: var(--color-primary)">Custom Colors</h2>
        <p class="text-sm text-body/70 mb-6">Fine-tune individual color values. These take precedence over any preset.</p>

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
                    <input type="color" name="{{ $key }}" id="{{ $key }}" value="{{ $currentColors[$key] }}"
                        class="h-10 w-16 rounded cursor-pointer border border-sec/30"
                        oninput="document.getElementById('{{ $key }}_hex').value = this.value">
                    <input type="text" id="{{ $key }}_hex" value="{{ $currentColors[$key] }}" maxlength="7"
                        class="flex-1 rounded-lg border border-sec/30 px-3 py-2 text-sm font-mono"
                        pattern="^#[0-9a-fA-F]{6}$"
                        oninput="if(/^#[0-9a-fA-F]{6}$/.test(this.value)) document.getElementById('{{ $key }}').value = this.value">
                </div>
                @error($key)
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endforeach

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center rounded-lg px-5 py-2.5 text-white text-sm font-medium"
                    style="background: var(--color-primary);">
                    Save Custom Theme
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ---------- Preview functionality ----------
    let previewKey = null;

    document.querySelectorAll('.preview-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var key = this.getAttribute('data-preset');

            // Fetch preset colors via AJAX
            fetch('{{ route('admin.theme.preview') }}?preset=' + encodeURIComponent(key))
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    previewKey = key;
                    var colors = data.colors;

                    // Temporarily apply to page CSS variables
                    var root = document.documentElement;
                    root.style.setProperty('--color-primary', colors.primary);
                    root.style.setProperty('--color-secondary', colors.secondary);
                    root.style.setProperty('--color-accent', colors.accent);
                    root.style.setProperty('--color-background', colors.background);
                    root.style.setProperty('--color-text', colors.text);

                    // Show preview banner
                    var banner = document.getElementById('preview-banner');
                    var nameEl = document.getElementById('preview-name');
                    var presetNames = {!! json_encode(array_map(fn($p) => $p['name'], $presets), JSON_FORCE_OBJECT) !!};
                    nameEl.textContent = presetNames[key] || key;
                    banner.classList.remove('hidden');
                })
                .catch(function(err) {
                    console.error('Preview error:', err);
                });
        });
    });

    // Cancel preview — restore actual saved colors
    document.getElementById('preview-cancel-btn').addEventListener('click', function() {
        var originalColors = {!! json_encode($currentColors) !!};
        var root = document.documentElement;
        root.style.setProperty('--color-primary', originalColors.primary);
        root.style.setProperty('--color-secondary', originalColors.secondary);
        root.style.setProperty('--color-accent', originalColors.accent);
        root.style.setProperty('--color-background', originalColors.background);
        root.style.setProperty('--color-text', originalColors.text);

        document.getElementById('preview-banner').classList.add('hidden');
        previewKey = null;
    });

    // Apply preview preset — submit the preset form
    document.getElementById('preview-apply-btn').addEventListener('click', function() {
        if (!previewKey) return;

        // Create and submit a form for the preset
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.theme.preset') }}';
        form.style.display = 'none';

        var csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        var presetInput = document.createElement('input');
        presetInput.type = 'hidden';
        presetInput.name = 'preset';
        presetInput.value = previewKey;
        form.appendChild(presetInput);

        document.body.appendChild(form);
        form.submit();
    });
});
</script>
@endsection
