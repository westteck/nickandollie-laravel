@extends('layouts.app')
@section('title', 'Admin Phonebook')
@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-uppercase text-muted small mb-1" style="letter-spacing: 0.2em;">Admin</p>
            <h1 class="mb-0" style="color: var(--primary);">Phonebook</h1>
        </div>
        <a href="{{ route('admin.phonebook') }}" class="btn btn-primary">+ New Contact</a>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Contact List --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">All Contacts</h5>
        </div>
        @if($contacts->isEmpty())
            <div class="card-body text-center text-muted py-4">No contacts yet.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Connection</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>City</th>
                            <th>Public</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ $contact->entry_name }}</div>
                                    @if($contact->first_name)
                                        <div class="text-muted small">{{ $contact->first_name }}</div>
                                    @endif
                                </td>
                                <td>{{ $contact->family_connection ?: '—' }}</td>
                                <td>
                                    @if($contact->phone)
                                        <div>{{ $contact->phone }}</div>
                                    @endif
                                    @if($contact->mobile)
                                        <div class="text-muted small">{{ $contact->mobile }} (mobile)</div>
                                    @endif
                                </td>
                                <td>{{ $contact->email ?: '—' }}</td>
                                <td>
                                    @if($contact->city || $contact->state)
                                        {{ $contact->city }}{{ $contact->city && $contact->state ? ', ' : '' }}{{ $contact->state }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    @if($contact->show_in_phonebook)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.phonebook', ['edit' => $contact->id]) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                    <form method="POST" action="{{ route('admin.phonebook.destroy', $contact->id) }}" class="d-inline" onsubmit="return confirm('Delete this contact?');">
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
            <h5 class="mb-0">{{ $isEditing ? 'Edit Contact' : 'Add Contact' }}</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ $isEditing ? route('admin.phonebook.update', $editing->id) : route('admin.phonebook.store') }}">
                @csrf
                @if($isEditing)
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Entry Name *</label>
                        <input type="text" name="entry_name" value="{{ old('entry_name', $isEditing ? $editing->entry_name : '') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $isEditing ? $editing->first_name : '') }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Family Connection</label>
                        <input type="text" name="family_connection" value="{{ old('family_connection', $isEditing ? $editing->family_connection : '') }}" placeholder="e.g. Friend, Family, Wedding Party" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $isEditing ? $editing->email : '') }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $isEditing ? $editing->phone : '') }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mobile</label>
                        <input type="text" name="mobile" value="{{ old('mobile', $isEditing ? $editing->mobile : '') }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Street Address</label>
                        <input type="text" name="address" value="{{ old('address', $isEditing ? $editing->address : '') }}" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">City</label>
                        <input type="text" name="city" value="{{ old('city', $isEditing ? $editing->city : '') }}" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">State</label>
                        <input type="text" name="state" value="{{ old('state', $isEditing ? $editing->state : '') }}" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">ZIP</label>
                        <input type="text" name="zip" value="{{ old('zip', $isEditing ? $editing->zip : '') }}" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" rows="3" class="form-control">{{ old('notes', $isEditing ? $editing->notes : '') }}</textarea>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="show_in_phonebook" value="1" id="showInPhonebook" {{ old('show_in_phonebook', $isEditing ? $editing->show_in_phonebook : true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="showInPhonebook">Show in public phonebook</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">{{ $isEditing ? 'Update Contact' : 'Add Contact' }}</button>
                    @if($isEditing)
                        <a href="{{ route('admin.phonebook') }}" class="btn btn-outline-secondary">Cancel</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
