@extends('layouts.app')

@section('title', 'Theme Settings')

@section('content')
<div class="container py-4">
    <h1 class="mb-2" style="color: var(--primary)">Theme Settings</h1>
    <p class="text-muted mb-4" style="font-size: 0.85rem;">Choose a preset or customize colors manually.</p>

    @if(session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-3">{{ session('error') }}</div>
    @endif

    {{-- Preset Picker --}}
    <div class="mb-5">
        <h2 class="h5 mb-3" style="color: var(--primary)">Theme Presets</h2>
        <div class="row g-3">
            @foreach($presets as $key => $preset)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 {{ $currentPreset === $key ? 'border-success' : '' }}"
                         style="{{ $currentPreset === $key ? 'box-shadow: 0 0 0 2px #5c9c6b;' : '' }}">
                        {{-- Color swatch strip --}}
                        <div class="d-flex" style="height: 12px;">
                            <div class="flex-grow-1" style="background: {{ $preset['primary'] }}"></div>
                            <div class="flex-grow-1" style="background: {{ $preset['secondary'] }}"></div>
                            <div class="flex-grow-1" style="background: {{ $preset['accent'] }}"></div>
                            <div class="flex-grow-1" style="background: {{ $preset['background'] }}; border: 1px solid #eee;"></div>
                            <div class="flex-grow-1" style="background: {{ $preset['text'] }}"></div>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold" style="font-size: 0.9rem;">{{ $preset['name'] }}</span>
                                @if($currentPreset === $key)
                                    <span class="badge bg-success" style="font-size: 0.7rem;">Active</span>
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('admin.theme.preset') }}" class="flex-grow-1">
                                    @csrf
                                    <input type="hidden" name="preset" value="{{ $key }}">
                                    <button type="submit" class="btn btn-primary btn-sm w-100" style="font-size: 0.75rem;">
                                        Use This
                                    </button>
                                </form>
                                <button type="button" class="btn btn-outline-secondary btn-sm preview-btn"
                                        data-preset="{{ $key }}" style="font-size: 0.75rem;">
                                    Preview
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Preview banner --}}
        <div id="preview-banner" class="alert alert-info mt-3 d-none align-items-center justify-content-between">
            <div>
                <strong>Previewing:</strong>
                <span id="preview-name"></span>
                <small class="text-muted">(temporarily applied — not saved)</small>
            </div>
            <div class="d-flex gap-2">
                <button id="preview-apply-btn" type="button" class="btn btn-primary btn-sm">Apply This Preset</button>
                <button id="preview-cancel-btn" type="button" class="btn btn-outline-secondary btn-sm">Cancel</button>
            </div>
        </div>
    </div>

    {{-- Custom Color Editor --}}
    <div class="card">
        <div class="card-header">
            <h2 class="h5 mb-0" style="color: var(--primary)">Custom Colors</h2>
        </div>
        <div class="card-body">
            <p class="text-muted mb-4" style="font-size: 0.85rem;">Fine-tune individual color values. These take precedence over any preset.</p>

            <form method="POST" action="{{ route('admin.theme.update') }}">
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

                @foreach($fields as $fkey => $label)
                <div class="mb-3">
                    <label for="{{ $fkey }}" class="form-label">{{ $label }}</label>
                    <div class="d-flex gap-2">
                        <input type="color" name="{{ $fkey }}" id="{{ $fkey }}"
                               value="{{ $currentColors[$fkey] }}"
                               class="form-control form-control-color" style="width: 60px;"
                               oninput="document.getElementById('{{ $fkey }}_hex').value = this.value">
                        <input type="text" id="{{ $fkey }}_hex" value="{{ $currentColors[$fkey] }}"
                               class="form-control font-monospace" maxlength="7" style="max-width: 120px;"
                               oninput="if(/^#[0-9a-fA-F]{6}$/.test(this.value)) document.getElementById('{{ $fkey }}').value = this.value">
                    </div>
                    @error($fkey)
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
                @endforeach

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Custom Theme
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var previewKey = null;

    document.querySelectorAll('.preview-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var key = this.getAttribute('data-preset');
            fetch('{{ route('admin.theme.preview') }}?preset=' + encodeURIComponent(key))
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    previewKey = key;
                    var c = data.colors;
                    var root = document.documentElement;
                    root.style.setProperty('--color-primary', c.primary);
                    root.style.setProperty('--color-secondary', c.secondary);
                    root.style.setProperty('--color-accent', c.accent);
                    root.style.setProperty('--color-background', c.background);
                    root.style.setProperty('--color-text', c.text);
                    root.style.setProperty('--primary', c.primary);
                    root.style.setProperty('--secondary', c.secondary);
                    root.style.setProperty('--accent', c.accent);
                    root.style.setProperty('--bg', c.background);
                    root.style.setProperty('--text', c.text);

                    var names = {!! json_encode(array_map(fn($p) => $p['name'], $presets), JSON_FORCE_OBJECT) !!};
                    document.getElementById('preview-name').textContent = names[key] || key;
                    document.getElementById('preview-banner').classList.remove('d-none');
                })
                .catch(function(err) { console.error('Preview error:', err); });
        });
    });

    document.getElementById('preview-cancel-btn').addEventListener('click', function() {
        var orig = {!! json_encode($currentColors) !!};
        var root = document.documentElement;
        root.style.setProperty('--color-primary', orig.primary);
        root.style.setProperty('--color-secondary', orig.secondary);
        root.style.setProperty('--color-accent', orig.accent);
        root.style.setProperty('--color-background', orig.background);
        root.style.setProperty('--color-text', orig.text);
        root.style.setProperty('--primary', orig.primary);
        root.style.setProperty('--secondary', orig.secondary);
        root.style.setProperty('--accent', orig.accent);
        root.style.setProperty('--bg', orig.background);
        root.style.setProperty('--text', orig.text);
        document.getElementById('preview-banner').classList.add('d-none');
        previewKey = null;
    });

    document.getElementById('preview-apply-btn').addEventListener('click', function() {
        if (!previewKey) return;
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.theme.preset') }}';
        var csrf = document.createElement('input');
        csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        var inp = document.createElement('input');
        inp.type = 'hidden'; inp.name = 'preset'; inp.value = previewKey;
        form.appendChild(inp);
        document.body.appendChild(form);
        form.submit();
    });
});
</script>
@endsection
