@extends('layouts.guest')

@section('title', 'Create Account — Nick & Ollie Fortune Wedding')

@section('content')
<div class="auth-card" style="max-width: 520px; margin: 2rem auto;">
    <h2 class="text-center mb-1" style="color: var(--primary); font-weight: 600;">Create Account</h2>
    <p class="text-center text-muted mb-4" style="font-size: 0.85rem;">Join Nick & Ollie Fortune's wedding</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Name row --}}
        <div class="row">
            <div class="col-6 mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" autocomplete="given-name">
                @error('first_name') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="col-6 mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" autocomplete="family-name">
                @error('last_name') <span class="form-error">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Display Name --}}
        <div class="mb-3">
            <label for="guest_name" class="form-label">Display Name <small class="text-muted">(how others see you)</small></label>
            <input id="guest_name" type="text" class="form-control" name="guest_name" value="{{ old('guest_name') }}" required autocomplete="name">
            @error('guest_name') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Username --}}
        <div class="mb-3">
            <label for="username" class="form-label">Username <small class="text-muted">(optional)</small></label>
            <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" autocomplete="username">
            @error('username') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <hr style="border-color: var(--secondary);">

        {{-- Connection --}}
        <div class="row">
            <div class="col-6 mb-3">
                <label for="connection" class="form-label">Connection</label>
                <select id="connection" name="connection" class="form-select" required>
                    <option value="">Select Connection</option>
                    @foreach($connections as $conn)
                        <option value="{{ $conn->value }}" {{ old('connection') == $conn->value ? 'selected' : '' }}>{{ $conn->label }}</option>
                    @endforeach
                </select>
                @error('connection') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            {{-- Core Group --}}
            <div class="col-6 mb-3">
                <label for="core_group" class="form-label">Core Group</label>
                <select id="core_group" name="core_group" class="form-select" required>
                    <option value="">Select Group</option>
                    @foreach($coreGroups as $cg)
                        <option value="{{ $cg->value }}" {{ old('core_group') == $cg->value ? 'selected' : '' }}>{{ $cg->label }}</option>
                    @endforeach
                </select>
                @error('core_group') <span class="form-error">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Specific Relationship --}}
        <div class="mb-3">
            <label for="specific_relationship" class="form-label">Your Relationship <small class="text-muted">(optional)</small></label>
            <input id="specific_relationship" type="text" class="form-control" name="specific_relationship" value="{{ old('specific_relationship') }}" placeholder="e.g., Best Man, Bridesmaid, Cousin">
            @error('specific_relationship') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <hr style="border-color: var(--secondary);">

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="username">
            @error('email') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Password --}}
        <div class="row">
            <div class="col-6 mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
                @error('password') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="col-6 mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                @error('password_confirmation') <span class="form-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <hr style="border-color: var(--secondary);">

        {{-- Address --}}
        <div class="mb-3">
            <label for="address" class="form-label">Street Address <small class="text-muted">(optional)</small></label>
            <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" autocomplete="street-address">
            @error('address') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="row">
            <div class="col-4 mb-3">
                <label for="city" class="form-label">City</label>
                <input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}" autocomplete="address-level2">
                @error('city') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="col-4 mb-3">
                <label for="state" class="form-label">State</label>
                <input id="state" type="text" class="form-control" name="state" value="{{ old('state') }}" autocomplete="address-level1">
                @error('state') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="col-4 mb-3">
                <label for="zip" class="form-label">Zip</label>
                <input id="zip" type="text" class="form-control" name="zip" value="{{ old('zip') }}" autocomplete="postal-code">
                @error('zip') <span class="form-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <hr style="border-color: var(--secondary);">

        {{-- Phone book email --}}
        <div class="mb-3">
            <label for="phone_email" class="form-label">Phone Book Email <small class="text-muted">(optional)</small></label>
            <input id="phone_email" type="email" class="form-control" name="phone_email" value="{{ old('phone_email') }}">
            @error('phone_email') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="row">
            <div class="col-6 mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input id="phone" type="tel" class="form-control" name="phone" value="{{ old('phone') }}" autocomplete="tel">
                @error('phone') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="col-6 mb-3">
                <label for="mobile" class="form-label">Mobile</label>
                <input id="mobile" type="tel" class="form-control" name="mobile" value="{{ old('mobile') }}" autocomplete="tel">
                @error('mobile') <span class="form-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="d-flex justify-content-end align-items-center pt-2">
            <a href="{{ route('login') }}" class="text-muted me-3" style="font-size: 0.85rem;">Already registered?</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Create Account
            </button>
        </div>
    </form>
</div>
@endsection
