@extends('layouts.app')
@section('title', 'My Profile')
@section('content')
<div class="container py-4">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-lg-3 mb-4">
            <div class="profile-sidebar">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <h5 class="mb-2" id="profile-name">{{ auth()->user()->guest_name }}</h5>
                        <span class="badge bg-primary side-badge mb-2" id="profile-side">
                            {{ auth()->user()->specific_relationship ?? auth()->user()->core_group ?? 'Guest' }}
                        </span>

                        <div class="profile-pic-wrapper mb-2">
                            <img id="profile-pic-img" src="" class="profile-pic d-none" alt="Profile">
                            <div class="profile-pic-placeholder" id="pic-placeholder">
                                <i class="fas fa-user"></i>
                                <span>Your Pic</span>
                            </div>
                            <input type="file" id="pic-input" class="d-none" accept="image/*">
                        </div>

                        <div class="profile-pic-actions" id="profile-pic-actions">
                            <button class="btn btn-outline-secondary btn-sm" onclick="document.getElementById('pic-input').click()">
                                <i class="fas fa-camera me-1"></i>Change Photo
                            </button>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary btn-sm" onclick="document.getElementById('pic-input').click()">
                                    <i class="fas fa-image me-1"></i>Choose
                                </button>
                                <button class="btn btn-success btn-sm" id="btn-take-selfie" title="Take photo">
                                    <i class="fas fa-camera me-1"></i>Take
                                </button>
                            </div>
                        </div>

                        <p class="text-muted small mb-0 mt-2" id="member-since">Member since {{ auth()->user()->created_at ? \Carbon\Carbon::parse(auth()->user()->created_at)->format('F Y') : date('F Y') }}</p>
                    </div>
                </div>
                <ul class="nav flex-column" id="profile-nav">
                    <li class="nav-item"><a class="nav-link active" href="#" data-tab="account"><i class="fas fa-user-cog me-2"></i>Account Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-tab="password"><i class="fas fa-lock me-2"></i>Change Password</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-tab="favorites"><i class="fas fa-heart me-2"></i>My Favorites</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-tab="uploads"><i class="fas fa-cloud-upload-alt me-2"></i>My Uploads</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-tab="votes"><i class="fas fa-vote-yea me-2"></i>My Votes</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" data-tab="comments"><i class="fas fa-comments me-2"></i>My Comments</a></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="col-lg-9">
            {{-- Account Settings Tab --}}
            <div class="tab-pane-content" id="tab-account">
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-user-cog me-2"></i>Account Settings</h5></div>
                    <div class="card-body">
                        <form id="form-account">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="input-firstname">First Name</label>
                                    <input type="text" class="form-control" id="input-firstname" placeholder="First name" maxlength="50">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="input-lastname">Last Name</label>
                                    <input type="text" class="form-control" id="input-lastname" placeholder="Last name" maxlength="50">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="input-username">Username</label>
                                <input type="text" class="form-control" id="input-username" placeholder="Username" maxlength="30">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="input-email">Email</label>
                                <input type="email" class="form-control" id="input-email" placeholder="email@example.com">
                            </div>

                            <hr class="my-3">
                            <h6 class="text-muted mb-3"><i class="fas fa-address-book me-2"></i>Phone Book</h6>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="input-pb-visible" checked>
                                <label class="form-check-label" for="input-pb-visible">Include my details in the public phonebook</label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="input-pb-address">Street Address</label>
                                <input type="text" class="form-control" id="input-pb-address" placeholder="123 Main St" maxlength="200">
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="input-pb-city">City</label>
                                    <input type="text" class="form-control" id="input-pb-city" placeholder="City" maxlength="100">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label" for="input-pb-state">State</label>
                                    <input type="text" class="form-control" id="input-pb-state" placeholder="CA" maxlength="50">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label" for="input-pb-zip">Zip</label>
                                    <input type="text" class="form-control" id="input-pb-zip" placeholder="12345" maxlength="20">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="input-pb-email">Email</label>
                                    <input type="email" class="form-control" id="input-pb-email" placeholder="email@example.com">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="input-pb-phone">Phone</label>
                                    <input type="tel" class="form-control" id="input-pb-phone" placeholder="(555) 123-4567" maxlength="30">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label" for="input-pb-mobile">Mobile</label>
                                    <input type="tel" class="form-control" id="input-pb-mobile" placeholder="(555) 987-6543" maxlength="30">
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="mb-3">
                                <label class="form-label" for="input-connection"><i class="fas fa-link me-2"></i>Who are you connected to?</label>
                                <select class="form-select" id="input-connection">
                                    <option value="">Select...</option>
                                    <option value="nick">Nick</option>
                                    <option value="ollie">Ollie</option>
                                    <option value="both">Both</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="input-core-group"><i class="fas fa-users me-2"></i>Select your core group:</label>
                                <select class="form-select" id="input-core-group" disabled>
                                    <option value="">Select connection first...</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="input-relationship"><i class="fas fa-user-tag me-2"></i>Your specific relationship:</label>
                                <select class="form-select" id="input-relationship" disabled>
                                    <option value="">Select core group first...</option>
                                </select>
                            </div>
                            <div class="mb-3 d-none" id="custom-relationship-wrap">
                                <label class="form-label" for="input-custom-relationship"><i class="fas fa-pencil-alt me-2"></i>Describe your relationship</label>
                                <input type="text" class="form-control" id="input-custom-relationship" placeholder="e.g. Nick's college roommate, Ollie's cousin" maxlength="100">
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Changes</button>
                        </form>
                    </div>
                </div>

                {{-- Contacts Card --}}
                <div class="card" id="profile-contacts-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-address-card me-2"></i>My Contacts</h5>
                        <a href="{{ route('phonebook') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-external-link-alt me-1"></i>Manage Contacts</a>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Add family and friends to your personal contact list. To add new people, visit the <a href="{{ route('phonebook') }}">Phone Book</a>.</p>
                    </div>
                </div>
            </div>

            {{-- Change Password Tab --}}
            <div class="tab-pane-content d-none" id="tab-password">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-lock me-2"></i>Change Password</h5></div>
                    <div class="card-body">
                        <form id="form-password">
                            <div class="mb-3">
                                <label class="form-label" for="input-current-pass">Current Password</label>
                                <input type="password" class="form-control" id="input-current-pass">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="input-new-pass">New Password</label>
                                <input type="password" class="form-control" id="input-new-pass">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="input-confirm-pass">Confirm New Password</label>
                                <input type="password" class="form-control" id="input-confirm-pass">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Favorites Tab --}}
            <div class="tab-pane-content d-none" id="tab-favorites">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-heart me-2"></i>My Favorites</h5></div>
                    <div class="card-body">
                        <div class="row g-2" id="favorites-grid">
                            <div class="col-12 text-center text-muted py-4" id="favorites-loading">
                                <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                <p>Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Votes Tab --}}
            <div class="tab-pane-content d-none" id="tab-votes">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-vote-yea me-2"></i>My Contest Votes</h5></div>
                    <div class="card-body">
                        <div class="row g-3" id="votes-grid">
                            <div class="col-12 text-center text-muted py-4" id="votes-loading">
                                <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                <p>Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Comments Tab --}}
            <div class="tab-pane-content d-none" id="tab-comments">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-comments me-2"></i>My Comments</h5></div>
                    <div class="card-body">
                        <div id="comments-list">
                            <div class="col-12 text-center text-muted py-4" id="comments-loading">
                                <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                <p>Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Uploads Tab --}}
            <div class="tab-pane-content d-none" id="tab-uploads">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0"><i class="fas fa-cloud-upload-alt me-2"></i>My Uploads</h5></div>
                    <div class="card-body">
                        <div class="row g-2" id="uploads-grid">
                            <div class="col-12 text-center text-muted py-4" id="uploads-loading">
                                <i class="fas fa-spinner fa-spin fa-2x mb-2"></i>
                                <p>Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-pic-wrapper { position: relative; display: inline-block; }
.profile-pic { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; }
.profile-pic-placeholder { width: 100px; height: 100px; border-radius: 50%; background: #d4c4b0; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #8b7355; }
.profile-pic-placeholder i { font-size: 2rem; }
.profile-pic-placeholder span { font-size: 0.7rem; }
.side-badge { font-size: 0.75rem; }
</style>

@push('scripts')
<script>
(function() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // --- Tab Switching ---
    document.querySelectorAll('#profile-nav [data-tab]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var tab = this.dataset.tab;
            document.querySelectorAll('.tab-pane-content').forEach(function(el) { el.classList.add('d-none'); });
            document.querySelectorAll('#profile-nav .nav-link').forEach(function(el) { el.classList.remove('active'); });
            document.getElementById('tab-' + tab).classList.remove('d-none');
            this.classList.add('active');
        });
    });

    // --- Relationship Dropdown Data (from legacy profile.php) ---
    var connectionData = {
        'nick': ['Immediate Family', 'Extended Family / Relatives', 'Sponsors & Godparents', 'Friends & Community'],
        'ollie': ['Immediate Family', 'Extended Family / Relatives', 'Sponsors & Godparents', 'Friends & Community'],
        'both': ['Immediate Family', 'Extended Family / Relatives', 'Sponsors & Godparents', 'Friends & Community']
    };
    var relationshipData = {
        'Immediate Family': ['Magulang (Parent)', 'Kuya / Ate (Older Brother/Sister)', 'Bunso (Youngest Child)', 'Grandparent (Lolo / Lola)', 'Anak (Child)'],
        'Extended Family / Relatives': ['Tito / Tita (Uncle / Aunt)', 'Pinsan (Cousin)', 'Tiyo / Tiya (Ninong / Ninang side)', 'Lola / Lolo (maternal/paternal)', 'Bayan / Kapamilya (Community kin)'],
        'Sponsors & Godparents': ['Ninong / Ninang (Godfather / Godmother)', 'Kasambahay (Household helper)', 'Aliping Tagabukid (Farm helper)'],
        'Friends & Community': ['Klasrummate (Classmate)', 'Katrabaho (Colleague)', 'Kapit-bahay (Neighbor)', 'Barkada (Close friends group)', 'Kumpare / Kumbaba (Parish friends)', 'Other']
    };

    var connectionSelect = document.getElementById('input-connection');
    var coreGroupSelect = document.getElementById('input-core-group');
    var relationshipSelect = document.getElementById('input-relationship');

    connectionSelect.addEventListener('change', function() {
        var conn = this.value;
        coreGroupSelect.innerHTML = '<option value="">Select...</option>';
        relationshipSelect.innerHTML = '<option value="">Select core group first...</option>';
        relationshipSelect.disabled = coreGroupSelect.disabled = true;
        if (conn && connectionData[conn]) {
            connectionData[conn].forEach(function(g) {
                var opt = document.createElement('option');
                opt.value = g; opt.textContent = g;
                coreGroupSelect.appendChild(opt);
            });
            coreGroupSelect.disabled = false;
        }
    });

    coreGroupSelect.addEventListener('change', function() {
        var group = this.value;
        relationshipSelect.innerHTML = '<option value="">Select...</option>';
        relationshipSelect.disabled = true;
        document.getElementById('custom-relationship-wrap').classList.add('d-none');
        if (group && relationshipData[group]) {
            relationshipData[group].forEach(function(r) {
                var opt = document.createElement('option');
                opt.value = r; opt.textContent = r;
                relationshipSelect.appendChild(opt);
            });
            relationshipSelect.disabled = false;
        }
    });

    relationshipSelect.addEventListener('change', function() {
        var customWrap = document.getElementById('custom-relationship-wrap');
        if (this.value === 'Other') {
            customWrap.classList.remove('d-none');
        } else {
            customWrap.classList.add('d-none');
            document.getElementById('input-custom-relationship').value = '';
        }
    });

    // --- Load Profile Data ---
    function loadProfile() {
        fetch('{{ route("profile.edit") }}', { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.success) return;
                var u = data.user || {};
                document.getElementById('input-firstname').value = u.firstname || '';
                document.getElementById('input-lastname').value = u.lastname || '';
                document.getElementById('input-username').value = u.username || '';
                document.getElementById('input-email').value = u.email || '';
                document.getElementById('input-pb-address').value = u.pb_address || '';
                document.getElementById('input-pb-city').value = u.pb_city || '';
                document.getElementById('input-pb-state').value = u.pb_state || '';
                document.getElementById('input-pb-zip').value = u.pb_zip || '';
                document.getElementById('input-pb-email').value = u.pb_email || '';
                document.getElementById('input-pb-phone').value = u.pb_phone || '';
                document.getElementById('input-pb-mobile').value = u.pb_mobile || '';
                document.getElementById('input-pb-visible').checked = !!u.pb_show_in_phonebook;

                if (u.profile_pic) {
                    document.getElementById('profile-pic-img').src = u.profile_pic;
                    document.getElementById('profile-pic-img').classList.remove('d-none');
                    document.getElementById('pic-placeholder').classList.add('d-none');
                }

                // Cascade connection dropdowns
                if (u.connection) {
                    connectionSelect.value = u.connection;
                    connectionSelect.dispatchEvent(new Event('change'));
                    setTimeout(function() {
                        if (u.core_group) {
                            coreGroupSelect.value = u.core_group;
                            coreGroupSelect.dispatchEvent(new Event('change'));
                            setTimeout(function() {
                                if (u.specific_relationship) {
                                    var allOpts = Array.from(relationshipSelect.options).map(function(o) { return o.value; });
                                    if (allOpts.indexOf(u.specific_relationship) > -1) {
                                        relationshipSelect.value = u.specific_relationship;
                                    } else {
                                        relationshipSelect.value = 'Other';
                                        document.getElementById('custom-relationship-wrap').classList.remove('d-none');
                                        document.getElementById('input-custom-relationship').value = u.specific_relationship;
                                    }
                                }
                            }, 30);
                        }
                    }, 30);
                }
            })
            .catch(function() {});
    }
    loadProfile();

    // --- Save Profile ---
    document.getElementById('form-account').addEventListener('submit', function(e) {
        e.preventDefault();
        var payload = {
            firstname: document.getElementById('input-firstname').value,
            lastname: document.getElementById('input-lastname').value,
            username: document.getElementById('input-username').value,
            email: document.getElementById('input-email').value,
            connection: connectionSelect.value,
            core_group: coreGroupSelect.value,
            specific_relationship: relationshipSelect.value === 'Other'
                ? document.getElementById('input-custom-relationship').value
                : relationshipSelect.value,
            pb_address: document.getElementById('input-pb-address').value,
            pb_city: document.getElementById('input-pb-city').value,
            pb_state: document.getElementById('input-pb-state').value,
            pb_zip: document.getElementById('input-pb-zip').value,
            pb_email: document.getElementById('input-pb-email').value,
            pb_phone: document.getElementById('input-pb-phone').value,
            pb_mobile: document.getElementById('input-pb-mobile').value,
            show_in_phonebook: document.getElementById('input-pb-visible').checked ? 1 : 0
        };
        fetch('{{ route("profile.update.post") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                alert('Profile updated successfully');
            } else {
                alert(data.error || 'Failed to update profile');
            }
        })
        .catch(function() { alert('Failed to update profile'); });
    });

    // --- Change Password ---
    document.getElementById('form-password').addEventListener('submit', function(e) {
        e.preventDefault();
        var current = document.getElementById('input-current-pass').value;
        var newPass = document.getElementById('input-new-pass').value;
        var confirm = document.getElementById('input-confirm-pass').value;
        if (newPass !== confirm) { alert('Passwords do not match'); return; }
        fetch('{{ route("password.update") }}', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ current_password: current, password: newPass, password_confirmation: confirm })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                alert('Password updated');
                document.getElementById('form-password').reset();
            } else {
                alert(data.error || 'Failed to update password');
            }
        })
        .catch(function() { alert('Failed to update password'); });
    });

    // --- Profile Pic Upload ---
    document.getElementById('pic-input').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('profile-pic-img').src = ev.target.result;
            document.getElementById('profile-pic-img').classList.remove('d-none');
            document.getElementById('pic-placeholder').classList.add('d-none');
            fetch('{{ route("profile.update.post") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ profile_pic: ev.target.result })
            }).catch(function() {});
        };
        reader.readAsDataURL(file);
    });

    // --- Selfie Modal (Camera Capture) ---
    var selfieStream = null;
    var selfieVideo = null;

    document.getElementById('btn-take-selfie')?.addEventListener('click', function() {
        // Create modal dynamically
        var modal = document.createElement('div');
        modal.id = 'selfie-modal';
        modal.style.cssText = 'position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.9);display:flex;flex-direction:column;align-items:center;justify-content:center;';
        modal.innerHTML =
            '<video id="selfie-video" autoplay playsinline style="max-width:90vw;max-height:60vh;border-radius:12px;"></video>' +
            '<div style="display:flex;gap:12px;margin-top:16px;">' +
            '  <button id="selfie-capture" style="padding:12px 24px;border-radius:8px;border:none;background:#8b7355;color:#fff;font-size:16px;cursor:pointer;">📸 Capture</button>' +
            '  <button id="selfie-cancel" style="padding:12px 24px;border-radius:8px;border:1px solid #ccc;background:transparent;color:#fff;font-size:16px;cursor:pointer;">Cancel</button>' +
            '</div>' +
            '<canvas id="selfie-canvas" style="display:none;"></canvas>';
        document.body.appendChild(modal);

        selfieVideo = document.getElementById('selfie-video');
        var canvas = document.getElementById('selfie-canvas');

        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
            .then(function(stream) {
                selfieStream = stream;
                selfieVideo.srcObject = stream;
            })
            .catch(function(err) {
                alert('Camera not available: ' + err.message);
                modal.remove();
            });

        document.getElementById('selfie-capture').addEventListener('click', function() {
            canvas.width = selfieVideo.videoWidth;
            canvas.height = selfieVideo.videoHeight;
            canvas.getContext('2d').drawImage(selfieVideo, 0, 0);
            var dataUrl = canvas.toDataURL('image/jpeg', 0.85);

            // Stop camera
            if (selfieStream) selfieStream.getTracks().forEach(function(t) { t.stop(); });
            modal.remove();

            // Update preview
            document.getElementById('profile-pic-img').src = dataUrl;
            document.getElementById('profile-pic-img').classList.remove('d-none');
            document.getElementById('pic-placeholder').classList.add('d-none');

            // Save to server
            fetch('{{ route("profile.update.post") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ profile_pic: dataUrl })
            }).catch(function() {});
        });

        document.getElementById('selfie-cancel').addEventListener('click', function() {
            if (selfieStream) selfieStream.getTracks().forEach(function(t) { t.stop(); });
            modal.remove();
        });
    });

    // --- Load Favorites ---
    document.querySelector('[data-tab="favorites"]')?.addEventListener('click', function() {
        document.getElementById('favorites-loading').style.display = 'block';
        fetch('{{ route("profile.favorites") }}', { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                document.getElementById('favorites-loading').style.display = 'none';
                var grid = document.getElementById('favorites-grid');
                if (data.favorites && data.favorites.length) {
                    grid.innerHTML = data.favorites.map(function(p) {
                        return '<div class="col-6 col-md-4 col-lg-3"><div class="card h-100 shadow-sm">' +
                            '<a href="/photo/' + p.id + '"><img src="' + p.thumb_url + '" class="card-img-top" alt="' + (p.caption || 'Photo') + '"></a>' +
                            '<div class="card-body p-2"><p class="card-text mb-1 small text-truncate">' + (p.caption || '') + '</p></div></div></div>';
                    }).join('');
                } else {
                    grid.innerHTML = '<p class="text-muted text-center py-5 col-12">No favorites yet.</p>';
                }
            })
            .catch(function() { document.getElementById('favorites-loading').style.display = 'none'; });
    });

    // --- Load Uploads ---
    document.querySelector('[data-tab="uploads"]')?.addEventListener('click', function() {
        document.getElementById('uploads-loading').style.display = 'block';
        fetch('{{ route("profile.uploads") }}', { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                document.getElementById('uploads-loading').style.display = 'none';
                var grid = document.getElementById('uploads-grid');
                if (data.uploads && data.uploads.length) {
                    grid.innerHTML = data.uploads.map(function(p) {
                        return '<div class="col-6 col-md-4 col-lg-3"><div class="card h-100 shadow-sm">' +
                            '<a href="/photo/' + p.id + '"><img src="' + p.thumb_url + '" class="card-img-top" alt="' + (p.caption || 'Photo') + '"></a>' +
                            '<div class="card-body p-2"><p class="card-text mb-1 small text-truncate">' + (p.caption || '') + '</p></div></div></div>';
                    }).join('');
                } else {
                    grid.innerHTML = '<p class="text-muted text-center py-5 col-12">No uploads yet.</p>';
                }
            })
            .catch(function() { document.getElementById('uploads-loading').style.display = 'none'; });
    });

    // --- Load Votes ---
    document.querySelector('[data-tab="votes"]')?.addEventListener('click', function() {
        document.getElementById('votes-loading').style.display = 'block';
        fetch('{{ route("profile.votes") }}', { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                document.getElementById('votes-loading').style.display = 'none';
                var grid = document.getElementById('votes-grid');
                if (data.votes && data.votes.length) {
                    grid.innerHTML = data.votes.map(function(v) {
                        return '<div class="col-6 col-md-4 col-lg-3"><div class="card h-100 shadow-sm">' +
                            '<a href="/photo/' + v.id + '"><img src="' + v.thumb_url + '" class="card-img-top" alt="' + (v.caption || 'Photo') + '"></a>' +
                            '<div class="card-body p-2"><p class="card-text mb-1 small text-truncate">' + (v.caption || '') + '</p></div></div></div>';
                    }).join('');
                } else {
                    grid.innerHTML = '<p class="text-muted text-center py-5 col-12">No votes yet.</p>';
                }
            })
            .catch(function() { document.getElementById('votes-loading').style.display = 'none'; });
    });

    // --- Load Comments ---
    document.querySelector('[data-tab="comments"]')?.addEventListener('click', function() {
        document.getElementById('comments-loading').style.display = 'block';
        fetch('{{ route("profile.comments") }}', { headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                document.getElementById('comments-loading').style.display = 'none';
                var list = document.getElementById('comments-list');
                if (data.comments && data.comments.length) {
                    list.innerHTML = data.comments.map(function(c) {
                        return '<div class="card mb-2"><div class="card-body py-2 px-3">' +
                            '<a href="/photo/' + c.photo_id + '" class="small fw-medium">' + (c.photo_caption || 'Photo') + '</a>' +
                            '<p class="mb-0 mt-1 small">' + escapeHtml(c.comment) + '</p>' +
                            '<span class="text-muted" style="font-size:0.75rem;">' + c.created_at + '</span>' +
                            '</div></div>';
                    }).join('');
                } else {
                    list.innerHTML = '<p class="text-muted text-center py-5">No comments yet.</p>';
                }
            })
            .catch(function() { document.getElementById('comments-loading').style.display = 'none'; });
    });

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
})();
</script>
@endpush
@endsection
