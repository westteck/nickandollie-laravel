@extends('layouts.app')
@section('title', 'Admin — User Management')
@section('content')
<div class="mx-auto max-w-6xl px-4 py-6">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0"><i class="fas fa-users me-2"></i>User Management</h1>
        <button class="btn btn-primary btn-sm" onclick="showCreateUserModal()">
            <i class="fas fa-plus me-1"></i> New User
        </button>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Connection</th>
                            <th>Group</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-table">
                        <tr><td colspan="9" class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create/Edit User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalTitle">Create User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="userId">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="userFirstName" class="form-label small fw-medium">First Name *</label>
                        <input type="text" class="form-control" id="userFirstName" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="userLastName" class="form-label small fw-medium">Last Name *</label>
                        <input type="text" class="form-control" id="userLastName" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="userUsername" class="form-label small fw-medium">Username *</label>
                    <input type="text" class="form-control" id="userUsername" required>
                </div>
                <div class="mb-3">
                    <label for="userEmail" class="form-label small fw-medium">Email *</label>
                    <input type="email" class="form-control" id="userEmail" required>
                </div>
                <div class="mb-3">
                    <label for="userType" class="form-label small fw-medium">Role</label>
                    <select class="form-select" id="userType">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="userPassword" class="form-label small fw-medium">Password <span id="passwordRequiredLabel" class="text-danger">*</span></label>
                    <input type="password" class="form-control" id="userPassword" placeholder="Min 6 characters">
                    <div class="form-text" id="passwordHelpText" style="display:none;">Leave blank to keep current password.</div>
                </div>
                <hr class="my-3">
                <h6 class="text-muted mb-3"><i class="fas fa-link me-2"></i>Connection</h6>
                <div class="mb-3">
                    <label for="userConnection" class="form-label small fw-medium">Who are they connected to?</label>
                    <select class="form-select" id="userConnection">
                        <option value="">Select...</option>
                        <option value="nick">Nick</option>
                        <option value="ollie">Ollie</option>
                        <option value="both">Both</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="userCoreGroup" class="form-label small fw-medium">Core Group</label>
                    <select class="form-select" id="userCoreGroup" disabled>
                        <option value="">Select connection first...</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="userRelationship" class="form-label small fw-medium">Relationship</label>
                    <select class="form-select" id="userRelationship" disabled>
                        <option value="">Select core group first...</option>
                    </select>
                </div>
                <div class="mb-3" id="customRelationshipWrap" style="display:none;">
                    <label for="userCustomRelationship" class="form-label small fw-medium">Describe relationship</label>
                    <input type="text" class="form-control" id="userCustomRelationship" placeholder="e.g. Nick's college roommate" maxlength="100">
                </div>
                <hr class="my-3">
                <h6 class="text-muted mb-3"><i class="fas fa-address-book me-2"></i>Phone Book</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="userPhone" class="form-label small fw-medium">Phone</label>
                        <input type="tel" class="form-control" id="userPhone">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="userMobile" class="form-label small fw-medium">Mobile</label>
                        <input type="tel" class="form-control" id="userMobile">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="userPbEmail" class="form-label small fw-medium">Email (Phone Book)</label>
                    <input type="email" class="form-control" id="userPbEmail">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveUser()">
                    <i class="fas fa-save me-1"></i> <span id="saveUserBtnText">Create User</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Are you sure you want to delete this user? This will also remove all their photos, comments, favorites, and votes.</p>
                <input type="hidden" id="deleteUserId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteUserBtn" onclick="confirmDeleteUser()">
                    <i class="fas fa-trash me-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
var connectionData = {
    'nick': ['Immediate Family', 'Extended Family / Relatives', 'Sponsors & Godparents', 'Friends & Community'],
    'ollie': ['Immediate Family', 'Extended Family / Relatives', 'Sponsors & Godparents', 'Friends & Community'],
    'both': ['Immediate Family', 'Extended Family / Relatives', 'Sponsors & Godparents', 'Friends & Community']
};
var relationshipData = {
    'Immediate Family': ['Magulang (Parent)', 'Kuya / Ate (Older Sibling)', 'Bunso (Youngest)', 'Grandparent'],
    'Extended Family / Relatives': ['Tito / Tita (Uncle/Aunt)', 'Pinsan (Cousin)', 'Apo (Grandchild)'],
    'Sponsors & Godparents': ['Ninong / Ninang (Godparent)', 'Kasambahay', 'Other'],
    'Friends & Community': ['Klassmate', 'Katrabaho (Colleague)', 'Kapit-bahay (Neighbor)', 'Barkada', 'Other']
};

// Connection cascade
document.getElementById('userConnection').addEventListener('change', function() {
    var conn = this.value;
    var cg = document.getElementById('userCoreGroup');
    var rel = document.getElementById('userRelationship');
    cg.innerHTML = '<option value="">Select...</option>';
    rel.innerHTML = '<option value="">Select core group first...</option>';
    rel.disabled = cg.disabled = true;
    document.getElementById('customRelationshipWrap').style.display = 'none';
    if (conn && connectionData[conn]) {
        connectionData[conn].forEach(function(g) {
            var opt = document.createElement('option');
            opt.value = g; opt.textContent = g;
            cg.appendChild(opt);
        });
        cg.disabled = false;
    }
});

document.getElementById('userCoreGroup').addEventListener('change', function() {
    var group = this.value;
    var rel = document.getElementById('userRelationship');
    rel.innerHTML = '<option value="">Select...</option>';
    rel.disabled = true;
    document.getElementById('customRelationshipWrap').style.display = 'none';
    if (group && relationshipData[group]) {
        relationshipData[group].forEach(function(r) {
            var opt = document.createElement('option');
            opt.value = r; opt.textContent = r;
            rel.appendChild(opt);
        });
        rel.disabled = false;
    }
});

document.getElementById('userRelationship').addEventListener('change', function() {
    document.getElementById('customRelationshipWrap').style.display = this.value === 'Other' ? '' : 'none';
});

function loadUsers() {
    fetch('{{ route("admin.users.list") }}')
        .then(r => r.json())
        .then(data => {
            var tbody = document.getElementById('users-table');
            if (!data.users || !data.users.length) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted py-4">No users found.</td></tr>';
                return;
            }
            tbody.innerHTML = data.users.map(function(u) {
                var name = [u.first_name, u.last_name].filter(Boolean).join(' ') || u.guest_name || '—';
                return '<tr>' +
                    '<td>' + u.id + '</td>' +
                    '<td><strong>' + escapeHtml(name) + '</strong></td>' +
                    '<td>' + escapeHtml(u.username || '—') + '</td>' +
                    '<td>' + escapeHtml(u.email) + '</td>' +
                    '<td>' + escapeHtml(u.connection_label || u.connection || '—') + '</td>' +
                    '<td>' + escapeHtml(u.core_group_label || u.core_group || '—') + '</td>' +
                    '<td><span class="badge bg-' + (u.user_type === 'admin' ? 'danger' : 'secondary') + '">' + u.user_type + '</span></td>' +
                    '<td class="text-muted small">' + (u.created_at ? new Date(u.created_at).toLocaleDateString() : '—') + '</td>' +
                    '<td>' +
                    '<button class="btn btn-sm btn-outline-primary me-1" onclick="editUser(' + u.id + ')" title="Edit"><i class="fas fa-edit"></i></button>' +
                    (u.id !== {{ auth()->id() }} ? '<button class="btn btn-sm btn-outline-danger" onclick="deleteUser(' + u.id + ')" title="Delete"><i class="fas fa-trash"></i></button>' : '') +
                    '</td>' +
                '</tr>';
            }).join('');
        })
        .catch(function() {
            document.getElementById('users-table').innerHTML = '<tr><td colspan="9" class="text-center text-danger py-4">Failed to load users.</td></tr>';
        });
}

function showCreateUserModal() {
    document.getElementById('userModalTitle').textContent = 'Create User';
    document.getElementById('saveUserBtnText').textContent = 'Create User';
    document.getElementById('userId').value = '';
    document.getElementById('userFirstName').value = '';
    document.getElementById('userLastName').value = '';
    document.getElementById('userUsername').value = '';
    document.getElementById('userEmail').value = '';
    document.getElementById('userPassword').value = '';
    document.getElementById('userPassword').required = true;
    document.getElementById('passwordRequiredLabel').style.display = '';
    document.getElementById('passwordHelpText').style.display = 'none';
    document.getElementById('userType').value = 'user';
    document.getElementById('userConnection').value = '';
    document.getElementById('userCoreGroup').innerHTML = '<option value="">Select connection first...</option>';
    document.getElementById('userCoreGroup').disabled = true;
    document.getElementById('userRelationship').innerHTML = '<option value="">Select core group first...</option>';
    document.getElementById('userRelationship').disabled = true;
    document.getElementById('customRelationshipWrap').style.display = 'none';
    document.getElementById('userPhone').value = '';
    document.getElementById('userMobile').value = '';
    document.getElementById('userPbEmail').value = '';
    new bootstrap.Modal(document.getElementById('userModal')).show();
}

function editUser(id) {
    fetch('{{ route("admin.users.list") }}')
        .then(r => r.json())
        .then(data => {
            var u = data.users.find(function(x) { return x.id === id; });
            if (!u) return;
            document.getElementById('userModalTitle').textContent = 'Edit User';
            document.getElementById('saveUserBtnText').textContent = 'Save Changes';
            document.getElementById('userId').value = u.id;
            document.getElementById('userFirstName').value = u.first_name || '';
            document.getElementById('userLastName').value = u.last_name || '';
            document.getElementById('userUsername').value = u.username || '';
            document.getElementById('userEmail').value = u.email || '';
            document.getElementById('userPassword').value = '';
            document.getElementById('userPassword').required = false;
            document.getElementById('passwordRequiredLabel').style.display = 'none';
            document.getElementById('passwordHelpText').style.display = '';
            document.getElementById('userType').value = u.user_type || 'user';
            document.getElementById('userPhone').value = u.ab_phone || u.phone || '';
            document.getElementById('userMobile').value = u.mobile || '';
            document.getElementById('userPbEmail').value = u.ab_email || '';

            // Connection cascade
            if (u.connection) {
                document.getElementById('userConnection').value = u.connection;
                document.getElementById('userConnection').dispatchEvent(new Event('change'));
                setTimeout(function() {
                    if (u.core_group) {
                        document.getElementById('userCoreGroup').value = u.core_group;
                        document.getElementById('userCoreGroup').dispatchEvent(new Event('change'));
                        setTimeout(function() {
                            if (u.specific_relationship) {
                                var opts = Array.from(document.getElementById('userRelationship').options).map(function(o) { return o.value; });
                                if (opts.indexOf(u.specific_relationship) > -1) {
                                    document.getElementById('userRelationship').value = u.specific_relationship;
                                } else {
                                    document.getElementById('userRelationship').value = 'Other';
                                    document.getElementById('customRelationshipWrap').style.display = '';
                                    document.getElementById('userCustomRelationship').value = u.specific_relationship;
                                }
                            }
                        }, 50);
                    }
                }, 50);
            }
            new bootstrap.Modal(document.getElementById('userModal')).show();
        });
}

function saveUser() {
    var id = document.getElementById('userId').value;
    var payload = {
        firstname: document.getElementById('userFirstName').value.trim(),
        lastname: document.getElementById('userLastName').value.trim(),
        username: document.getElementById('userUsername').value.trim(),
        email: document.getElementById('userEmail').value.trim(),
        user_type: document.getElementById('userType').value,
        connection: document.getElementById('userConnection').value,
        core_group: document.getElementById('userCoreGroup').value,
        specific_relationship: document.getElementById('userRelationship').value === 'Other'
            ? document.getElementById('userCustomRelationship').value.trim()
            : document.getElementById('userRelationship').value,
        phone: document.getElementById('userPhone').value.trim(),
        mobile: document.getElementById('userMobile').value.trim(),
        pb_email: document.getElementById('userPbEmail').value.trim()
    };
    var password = document.getElementById('userPassword').value;
    if (password) payload.password = password;

    if (!payload.firstname || !payload.lastname || !payload.username || !payload.email) {
        alert('First name, last name, username, and email are required.');
        return;
    }
    if (!id && !payload.password) {
        alert('Password is required for new users.');
        return;
    }

    var url = id ? '{{ route("admin.users.update", "") }}/' + id : '{{ route("admin.users.store") }}';
    var method = id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
            loadUsers();
        } else {
            alert(data.error || 'Failed to save user');
        }
    })
    .catch(function() { alert('Failed to save user'); });
}

function deleteUser(id) {
    document.getElementById('deleteUserId').value = id;
    new bootstrap.Modal(document.getElementById('deleteUserModal')).show();
}

function confirmDeleteUser() {
    var id = document.getElementById('deleteUserId').value;
    fetch('{{ route("admin.users.destroy", "") }}/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('deleteUserModal')).hide();
        if (data.success) {
            loadUsers();
        } else {
            alert(data.error || 'Failed to delete user');
        }
    })
    .catch(function() { alert('Failed to delete user'); });
}

function escapeHtml(str) {
    if (!str) return '';
    var d = document.createElement('div');
    d.textContent = str;
    return d.innerHTML;
}

// Load on page load
loadUsers();
</script>
@endsection
