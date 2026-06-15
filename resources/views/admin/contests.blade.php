@extends('layouts.app')
@section('title', 'Admin Contests')
@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-uppercase text-muted small mb-1" style="letter-spacing: 0.2em;">Admin</p>
            <h1 class="mb-0" style="color: var(--primary);">Contests</h1>
        </div>
        <a href="{{ route('admin.contests') }}" class="btn btn-primary">+ New Contest</a>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Contest List --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">All Contests</h5>
        </div>
        @if($contests->isEmpty())
            <div class="card-body text-center text-muted py-4">No contests yet.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Icon</th>
                            <th>Status</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Prize</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contests as $contest)
                            <tr>
                                <td class="fw-medium">{{ $contest->title }}</td>
                                <td>
                                    @if($contest->icon)
                                        <i class="{{ $contest->icon }}"></i>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
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
                                <td>{{ $contest->start_date ? \Carbon\Carbon::parse($contest->start_date)->format('M j, Y') : '—' }}</td>
                                <td>{{ $contest->end_date ? \Carbon\Carbon::parse($contest->end_date)->format('M j, Y') : '—' }}</td>
                                <td>{{ $contest->prize ?: '—' }}</td>
                                <td>
                                    <a href="{{ route('admin.contests', ['edit' => $contest->id]) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                    <form method="POST" action="{{ route('admin.contests.destroy', $contest->id) }}" class="d-inline" onsubmit="return confirm('Delete this contest?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Create / Edit Form --}}
    @php $isEditing = $editing !== null; @endphp
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">{{ $isEditing ? 'Edit Contest' : 'Create Contest' }}</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ $isEditing ? route('admin.contests.update', $editing->id) : route('admin.contests.store') }}">
                @csrf
                @if($isEditing)
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" value="{{ old('title', $isEditing ? $editing->title : '') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Icon (e.g. fa-trophy)</label>
                        <input type="text" name="icon" value="{{ old('icon', $isEditing ? $editing->icon : '') }}" placeholder="fa-trophy" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @foreach(['draft', 'active', 'inactive', 'closed'] as $status)
                                <option value="{{ $status }}" {{ (old('status', $isEditing ? $editing->status : 'draft') === $status) ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Prize</label>
                        <input type="text" name="prize" value="{{ old('prize', $isEditing ? $editing->prize : '') }}" placeholder="e.g. Gift card" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $isEditing && $editing->start_date ? \Carbon\Carbon::parse($editing->start_date)->format('Y-m-d') : '') }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $isEditing && $editing->end_date ? \Carbon\Carbon::parse($editing->end_date)->format('Y-m-d') : '') }}" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3" class="form-control">{{ old('description', $isEditing ? $editing->description : '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Rules</label>
                    <textarea name="rules" rows="3" class="form-control">{{ old('rules', $isEditing ? $editing->rules : '') }}</textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ $isEditing ? 'Update Contest' : 'Create Contest' }}</button>
                    @if($isEditing)
                        <a href="{{ route('admin.contests') }}" class="btn btn-outline-secondary">Cancel</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
