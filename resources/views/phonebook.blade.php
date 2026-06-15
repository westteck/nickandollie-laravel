@extends('layouts.app')
@section('title', 'Phonebook')
@section('content')
<div class="container py-4">
    <div class="section-header">
        <h2>Phonebook</h2>
        <p class="text-muted">Find contact details for family, sponsors, and friends.</p>
    </div>

    <!-- Search & Filter -->
    <form method="GET" action="{{ route('phonebook') }}" class="row g-2 mb-4">
        <div class="col-md-5">
            <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Search by name...">
        </div>
        <div class="col-md-4">
            <select name="group" class="form-select">
                <option value="">All Groups</option>
                @foreach($groups as $g)
                    <option value="{{ $g }}" {{ $group == $g ? 'selected' : '' }}>{{ $g }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
            @if($search || $group)
                <a href="{{ route('phonebook') }}" class="btn btn-outline-secondary">Clear</a>
            @endif
        </div>
    </form>

    <!-- Entries Grid -->
    <div class="row g-3">
        @forelse($entries as $e)
        <div class="col-md-6 col-lg-4">
            <div class="card phonebook-card h-100">
                <div class="card-body">
                    <h5 class="card-title" style="color: var(--primary)">{{ $e->entry_name }}</h5>
                    @if($e->first_name)
                        <p class="text-muted small mb-1">{{ $e->first_name }}</p>
                    @endif
                    @if($e->family_connection)
                        <p class="small text-uppercase text-muted">{{ $e->family_connection }}</p>
                    @endif
                    @if($e->connection || $e->core_group)
                        <p class="small text-muted">{{ $e->connection }} &middot; {{ $e->core_group }}</p>
                    @endif
                    <hr>
                    <div class="small">
                        @if($e->phone)
                            <p class="mb-1"><i class="fas fa-phone me-2 text-muted"></i>{{ $e->phone }}</p>
                        @endif
                        @if($e->mobile)
                            <p class="mb-1"><i class="fas fa-mobile-alt me-2 text-muted"></i>{{ $e->mobile }}</p>
                        @endif
                        @if($e->email)
                            <p class="mb-1"><i class="fas fa-envelope me-2 text-muted"></i><a href="mailto:{{ $e->email }}">{{ $e->email }}</a></p>
                        @endif
                        @if($e->address)
                            <p class="mb-1"><i class="fas fa-map-marker-alt me-2 text-muted"></i>{{ $e->address }}{{ $e->city ? ', ' . $e->city : '' }}{{ $e->state ? ', ' . $e->state : '' }} {{ $e->zip }}</p>
                        @endif
                    </div>
                    @if($e->notes)
                        <p class="mt-2 text-muted small">{{ $e->notes }}</p>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">No entries found.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
