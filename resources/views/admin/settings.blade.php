@extends('layouts.app')
@section('title', 'Admin Settings')
@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-uppercase text-muted small mb-1" style="letter-spacing: 0.2em;">Admin</p>
            <h1 class="mb-0" style="color: var(--primary);">Settings</h1>
            <p class="text-muted mb-0">Manage site identity, hero content, and contact information.</p>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif

    {{-- Site Info --}}
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="h5 mb-0">Site Information</h2>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Site Title</label>
                        <input type="text" name="site_title"
                               value="{{ old('site_title', $settings->site_title ?? 'Nick & Ollie Fortune') }}"
                               class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Site Tagline</label>
                        <input type="text" name="site_tagline"
                               value="{{ old('site_tagline', $settings->site_tagline ?? '') }}"
                               placeholder="e.g. Our Wedding Journey"
                               class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Email</label>
                        <input type="email" name="contact_email"
                               value="{{ old('contact_email', $settings->contact_email ?? '') }}"
                               class="form-control">
                    </div>
                </div>

                <hr style="border-color: var(--secondary);">

                <h3 class="h6 mb-3">Hero Section</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hero Title</label>
                        <input type="text" name="hero_title"
                               value="{{ old('hero_title', $settings->hero_title ?? '') }}"
                               placeholder="Welcome to our wedding site"
                               class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hero Subtitle</label>
                        <input type="text" name="hero_subtitle"
                               value="{{ old('hero_subtitle', $settings->hero_subtitle ?? '') }}"
                               placeholder="Celebrating our love story"
                               class="form-control">
                    </div>
                </div>

                <hr style="border-color: var(--secondary);">

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="maintenance_mode" value="1"
                           id="maintenanceMode"
                           {{ old('maintenance_mode', $settings->maintenance_mode ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="maintenanceMode">Enable maintenance mode</label>
                    <p class="form-text mb-0">When enabled, visitors will see a maintenance notice instead of the site.</p>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Settings
                </button>
            </form>
        </div>
    </div>

    {{-- Theme Settings Link --}}
    <div class="card">
        <div class="card-header">
            <h2 class="h5 mb-0">Appearance</h2>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">Customize site colors and theme presets.</p>
            <a href="{{ route('admin.themes') }}" class="btn btn-primary">
                <i class="fas fa-palette me-2"></i>Theme Settings
            </a>
        </div>
    </div>

    {{-- Page Content Manager --}}
    <div class="card mt-4">
        <div class="card-header">
            <h2 class="h5 mb-0">Page Content</h2>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3" style="font-size: 0.85rem;">Manage static page content stored in the database. Used for hero sections and other editable text blocks.</p>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Page Key</th>
                            <th>Content Preview</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sitePages as $page)
                        <tr>
                            <td><code>{{ $page->page_key }}</code></td>
                            <td class="text-muted small" style="max-width: 300px;">{{ Str::limit(strip_tags($page->content), 80) }}</td>
                            <td class="text-muted small">{{ $page->updated_at ? \Carbon\Carbon::parse($page->updated_at)->diffForHumans() : '—' }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" onclick="editPage('{{ $page->page_key }}', '{{ addslashes(strip_tags($page->content)) }}')">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">No page content yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Page Edit Modal --}}
<div class="modal fade" id="pageEditModal" tabindex="-1" aria-labelledby="pageEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pageEditModalLabel">Edit Page Content</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editPageKey">
                <div class="mb-3">
                    <label for="editPageContent" class="form-label">Content (HTML allowed)</label>
                    <textarea class="form-control" id="editPageContent" rows="10" placeholder="<h1>...</h1><p>...</p>"></textarea>
                    <div class="form-text">Allowed: h1, h2, h3, p, br, strong, em, ul, li</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePage()">
                    <i class="fas fa-save me-1"></i>Save
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function editPage(key, content) {
    document.getElementById('editPageKey').value = key;
    document.getElementById('editPageContent').value = content;
    var modal = new bootstrap.Modal(document.getElementById('pageEditModal'));
    modal.show();
}

function savePage() {
    var key = document.getElementById('editPageKey').value;
    var content = document.getElementById('editPageContent').value;
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    fetch('/admin/settings/page', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ page_key: key, content: content })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            location.reload();
        } else {
            alert(data.error || 'Failed to save.');
        }
    })
    .catch(function() { alert('Network error.'); });
}
</script>
@endpush
@endsection
