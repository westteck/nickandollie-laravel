@extends('layouts.app')
@section('title', 'Admin — Comment Moderation')
@section('content')
<div class="mx-auto max-w-6xl px-4 py-6">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0"><i class="fas fa-comments me-2"></i>Comment Moderation</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn" onclick="bulkDelete()" style="display:none;">
                <i class="fas fa-trash me-1"></i> Delete Selected
            </button>
            <button class="btn btn-sm btn-outline-primary" onclick="loadComments()">
                <i class="fas fa-sync me-1"></i> Refresh
            </button>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width:40px;"><input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)"></th>
                            <th>ID</th>
                            <th>User</th>
                            <th>Comment</th>
                            <th>Photo</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="comments-table">
                        <tr><td colspan="7" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Comment Modal -->
<div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Comment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Delete this comment permanently?</p>
                <input type="hidden" id="deleteCommentId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="confirmDeleteComment()">
                    <i class="fas fa-trash me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
var selectedIds = [];

function loadComments() {
    fetch('{{ route("admin.comments.list") }}')
        .then(r => r.json())
        .then(data => {
            var tbody = document.getElementById('comments-table');
            if (!data.comments || !data.comments.length) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4">No comments found.</td></tr>';
                return;
            }
            selectedIds = [];
            updateBulkDeleteBtn();
            tbody.innerHTML = data.comments.map(function(c) {
                return '<tr>' +
                    '<td><input type="checkbox" class="comment-checkbox" data-id="' + c.id + '" onchange="toggleCheckbox(' + c.id + ')"></td>' +
                    '<td>' + c.id + '</td>' +
                    '<td><strong>' + escapeHtml(c.user_name) + '</strong></td>' +
                    '<td>' + escapeHtml(c.comment) + '</td>' +
                    '<td><a href="/photo/' + c.photo_id + '" target="_blank">' + escapeHtml(c.photo_caption || 'Photo #' + c.photo_id) + '</a></td>' +
                    '<td class="text-muted small">' + c.time + '</td>' +
                    '<td><button class="btn btn-sm btn-outline-danger" onclick="deleteComment(' + c.id + ')"><i class="fas fa-trash"></i></button></td>' +
                '</tr>';
            }).join('');
            document.getElementById('selectAll').checked = false;
        })
        .catch(function() {
            document.getElementById('comments-table').innerHTML = '<tr><td colspan="7" class="text-center text-danger py-4">Failed to load comments.</td></tr>';
        });
}

function toggleCheckbox(id) {
    var idx = selectedIds.indexOf(id);
    if (idx > -1) { selectedIds.splice(idx, 1); } else { selectedIds.push(id); }
    updateBulkDeleteBtn();
}

function toggleSelectAll(cb) {
    var boxes = document.querySelectorAll('.comment-checkbox');
    selectedIds = [];
    boxes.forEach(function(box) {
        box.checked = cb.checked;
        if (cb.checked) selectedIds.push(parseInt(box.dataset.id));
    });
    updateBulkDeleteBtn();
}

function updateBulkDeleteBtn() {
    document.getElementById('bulkDeleteBtn').style.display = selectedIds.length > 0 ? '' : 'none';
}

function deleteComment(id) {
    document.getElementById('deleteCommentId').value = id;
    new bootstrap.Modal(document.getElementById('deleteCommentModal')).show();
}

function confirmDeleteComment() {
    var id = document.getElementById('deleteCommentId').value;
    fetch('{{ route("admin.comments.destroy", "") }}/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('deleteCommentModal')).hide();
        if (data.success) { loadComments(); } else { alert(data.error || 'Failed to delete'); }
    })
    .catch(function() { alert('Failed to delete comment'); });
}

function bulkDelete() {
    if (!selectedIds.length) return;
    if (!confirm('Delete ' + selectedIds.length + ' selected comments permanently?')) return;
    fetch('{{ route("admin.comments.bulk-destroy") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ ids: selectedIds })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) { loadComments(); } else { alert(data.error || 'Failed to delete'); }
    })
    .catch(function() { alert('Failed to delete comments'); });
}

function escapeHtml(str) {
    if (!str) return '';
    var d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}

loadComments();
</script>
@endsection
