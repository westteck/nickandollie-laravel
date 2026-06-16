@extends('layouts.app')
@section('title', 'Admin — Photo Management')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0"><i class="fas fa-images me-2"></i>Photo Management</h1>
        <button class="btn btn-sm btn-outline-primary" onclick="loadPhotos()">
            <i class="fas fa-sync me-1"></i> Refresh
        </button>
    </div>

    <div id="photos-grid" class="row g-3">
        <div class="col-12 text-center text-muted py-5">
            <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
            <p>Loading photos...</p>
        </div>
    </div>
</div>

<!-- Edit Caption Modal -->
<div class="modal fade" id="captionModal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Caption</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="captionPhotoId">
                <div class="mb-3">
                    <img id="captionPreview" src="" class="img-fluid rounded" style="max-height:200px;">
                </div>
                <div class="mb-3">
                    <label for="captionInput" class="form-label">Caption</label>
                    <input type="text" class="form-control" id="captionInput" maxlength="500">
                    <div class="form-text">Uploaded by: <span id="captionUploader"></span></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveCaption()">Save Caption</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Photo Modal -->
<div class="modal fade" id="deletePhotoModal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Delete this photo permanently? All comments, votes, and contest entries for this photo will also be removed.</p>
                <input type="hidden" id="deletePhotoId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeletePhoto()">
                    <i class="fas fa-trash me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

function loadPhotos() {
    fetch('{{ route("admin.photos.list") }}')
        .then(r => r.json())
        .then(data => {
            var grid = document.getElementById('photos-grid');
            if (!data.photos || !data.photos.length) {
                grid.innerHTML = '<div class="col-12 text-center text-muted py-5"><i class="fas fa-images fa-3x mb-3"></i><p>No photos uploaded yet.</p></div>';
                return;
            }
            grid.innerHTML = data.photos.map(function(p) {
                return '<div class="col-6 col-md-4 col-lg-3">' +
                    '<div class="card h-100 shadow-sm">' +
                    '<a href="/photo/' + p.id + '" target="_blank">' +
                    '<img src="' + p.thumb_url + '" class="card-img-top" alt="' + escapeHtml(p.caption || 'Photo') + '" style="aspect-ratio:1;object-fit:cover;" onerror="this.src=\'/storage/originals/' + p.filename + '\'">' +
                    '</a>' +
                    '<div class="card-body p-2">' +
                    '<p class="card-text small text-truncate mb-1">' + escapeHtml(p.caption || 'No caption') + '</p>' +
                    '<p class="card-text small text-muted mb-1"><i class="fas fa-user me-1"></i>' + escapeHtml(p.uploader) + '</p>' +
                    '<p class="card-text small text-muted mb-0">' +
                    '<i class="fas fa-heart text-danger me-1"></i>' + p.likes + ' likes' +
                    '</p>' +
                    '</div>' +
                    '<div class="card-footer p-1 d-flex gap-1">' +
                    '<button class="btn btn-sm btn-outline-primary flex-fill" onclick="editCaption(' + p.id + ', \'' + escapeJs(p.caption || '') + '\', \'' + escapeJs(p.uploader) + '\', \'' + p.photo_url + '\')"><i class="fas fa-edit"></i></button>' +
                    '<button class="btn btn-sm btn-outline-danger flex-fill" onclick="deletePhoto(' + p.id + ')"><i class="fas fa-trash"></i></button>' +
                    '</div>' +
                    '</div></div>';
            }).join('');
        })
        .catch(function() {
            document.getElementById('photos-grid').innerHTML = '<div class="col-12 text-center text-danger py-5">Failed to load photos.</div>';
        });
}

function editCaption(id, caption, uploader, photoUrl) {
    document.getElementById('captionPhotoId').value = id;
    document.getElementById('captionInput').value = caption;
    document.getElementById('captionUploader').textContent = uploader;
    document.getElementById('captionPreview').src = photoUrl;
    new bootstrap.Modal(document.getElementById('captionModal')).show();
}

function saveCaption() {
    var id = document.getElementById('captionPhotoId').value;
    var caption = document.getElementById('captionInput').value;
    fetch('/admin/photos/' + id, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ caption: caption })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('captionModal')).hide();
            loadPhotos();
        } else {
            alert(data.error || 'Failed to update caption');
        }
    })
    .catch(function() { alert('Failed to update caption'); });
}

function deletePhoto(id) {
    document.getElementById('deletePhotoId').value = id;
    new bootstrap.Modal(document.getElementById('deletePhotoModal')).show();
}

function confirmDeletePhoto() {
    var id = document.getElementById('deletePhotoId').value;
    fetch('/admin/photos/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('deletePhotoModal')).hide();
        if (data.success) {
            loadPhotos();
        } else {
            alert(data.error || 'Failed to delete photo');
        }
    })
    .catch(function() { alert('Failed to delete photo'); });
}

function escapeHtml(str) {
    if (!str) return '';
    var d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}

function escapeJs(str) {
    return str.replace(/'/g, "\\'").replace(/"/g, '\\"');
}

loadPhotos();
</script>
@endsection
