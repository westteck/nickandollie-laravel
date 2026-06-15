@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-uppercase text-muted small mb-1" style="letter-spacing: 0.2em;">Admin</p>
            <h1 class="mb-1" style="color: var(--primary);">Dashboard</h1>
            <p class="text-muted mb-0">Overview of wedding site activity and quick admin actions.</p>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <p class="h2 mb-0" style="color: var(--primary);">{{ $stats['users'] }}</p>
                    <p class="text-muted small text-uppercase mb-0 mt-1">Guests</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <p class="h2 mb-0" style="color: var(--primary);">{{ $stats['photos'] }}</p>
                    <p class="text-muted small text-uppercase mb-0 mt-1">Photos</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <p class="h2 mb-0" style="color: var(--primary);">{{ $stats['comments'] }}</p>
                    <p class="text-muted small text-uppercase mb-0 mt-1">Comments</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <p class="h2 mb-0" style="color: var(--primary);">{{ $stats['votes'] }}</p>
                    <p class="text-muted small text-uppercase mb-0 mt-1">Votes</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <p class="h2 mb-0" style="color: var(--primary);">{{ $stats['contests'] }}</p>
                    <p class="text-muted small text-uppercase mb-0 mt-1">Active Contests</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        {{-- Recent Uploads --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Uploads</h5>
                    <a href="{{ route('gallery') }}" class="small" style="color: var(--primary);">View Gallery</a>
                </div>
                <div class="card-body">
                    @if($recentPhotos->isEmpty())
                        <p class="text-muted text-center py-3 mb-0">No uploads yet.</p>
                    @else
                        <div class="row g-2">
                            @foreach($recentPhotos as $photo)
                                <div class="col-3">
                                    <a href="{{ route('photo.show', $photo->id) }}" class="d-block" style="aspect-ratio:1;overflow:hidden;border-radius:8px;">
                                        <img src="/storage/thumbs/{{ $photo->thumb_filename }}"
                                             alt="Photo"
                                             class="img-fluid w-100 h-100"
                                             style="object-fit:cover;"
                                             onerror="this.src='/storage/originals/{{ $photo->thumb_filename }}'">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Registrations --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Recent Registrations</h5>
                </div>
                <div class="card-body">
                    @if($recentUsers->isEmpty())
                        <p class="text-muted text-center py-3 mb-0">No registrations yet.</p>
                    @else
                        @foreach($recentUsers as $user)
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                         style="width:32px;height:32px;background:var(--secondary);color:var(--primary);font-weight:bold;font-size:0.8rem;">
                                        {{ strtoupper(substr($user->guest_name, 0, 1)) }}
                                    </div>
                                    <span class="fw-medium">{{ $user->guest_name }}</span>
                                </div>
                                <span class="text-muted small">{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Contest Summary --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Contests</h5>
            <a href="{{ route('admin.contests') }}" class="small" style="color: var(--primary);">Manage Contests</a>
        </div>
        @if($contests->isEmpty())
            <div class="card-body text-center text-muted py-4">
                No contests yet. <a href="{{ route('admin.contests') }}" style="color: var(--primary);">Create one</a>.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Entries</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contests as $contest)
                            <tr>
                                <td class="fw-medium">{{ $contest->title }}</td>
                                <td>
                                    @php
                                        $statusClass = match($contest->status) {
                                            'active' => 'bg-success',
                                            'closed' => 'bg-danger',
                                            'draft' => 'bg-secondary',
                                            default => 'bg-warning text-dark',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($contest->status) }}</span>
                                </td>
                                <td>{{ $contest->entry_count }}</td>
                                <td>
                                    <a href="{{ route('contest.show', $contest->id) }}" class="small me-2" style="color: var(--primary);">View</a>
                                    <a href="{{ route('admin.contests', ['edit' => $contest->id]) }}" class="small text-info">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Quick Links --}}
    <div class="row g-3">
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.users') }}" class="card text-center h-100 text-decoration-none">
                <div class="card-body">
                    <i class="fas fa-users fa-2x mb-2" style="color: var(--primary);"></i>
                    <h6 class="mb-1">Users</h6>
                    <p class="text-muted small mb-0">Manage guest accounts</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.photos') }}" class="card text-center h-100 text-decoration-none">
                <div class="card-body">
                    <i class="fas fa-images fa-2x mb-2" style="color: var(--primary);"></i>
                    <h6 class="mb-1">Photos</h6>
                    <p class="text-muted small mb-0">Manage uploaded photos</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.comments') }}" class="card text-center h-100 text-decoration-none">
                <div class="card-body">
                    <i class="fas fa-comments fa-2x mb-2" style="color: var(--primary);"></i>
                    <h6 class="mb-1">Comments</h6>
                    <p class="text-muted small mb-0">Moderate guest comments</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.themes') }}" class="card text-center h-100 text-decoration-none">
                <div class="card-body">
                    <i class="fas fa-palette fa-2x mb-2" style="color: var(--primary);"></i>
                    <h6 class="mb-1">Themes</h6>
                    <p class="text-muted small mb-0">Customize colors and presets</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.contests') }}" class="card text-center h-100 text-decoration-none">
                <div class="card-body">
                    <i class="fas fa-trophy fa-2x mb-2" style="color: var(--primary);"></i>
                    <h6 class="mb-1">Contests</h6>
                    <p class="text-muted small mb-0">Manage photo contests</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.phonebook') }}" class="card text-center h-100 text-decoration-none">
                <div class="card-body">
                    <i class="fas fa-address-book fa-2x mb-2" style="color: var(--primary);"></i>
                    <h6 class="mb-1">Phonebook</h6>
                    <p class="text-muted small mb-0">Manage contact entries</p>
                </div>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-3">
            <a href="{{ route('admin.settings') }}" class="card text-center h-100 text-decoration-none">
                <div class="card-body">
                    <i class="fas fa-cog fa-2x mb-2" style="color: var(--primary);"></i>
                    <h6 class="mb-1">Settings</h6>
                    <p class="text-muted small mb-0">Site title, hero, contact</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
