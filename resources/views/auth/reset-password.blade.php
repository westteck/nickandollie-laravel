@extends('layouts.guest')

@section('title', 'Reset Password — Nick & Ollie Fortune Wedding')

@section('content')
<div class="auth-card" style="max-width: 420px; margin: 2rem auto;">
    <h2 class="text-center mb-1" style="color: var(--primary); font-weight: 600;">Reset Password</h2>
    <p class="text-center text-muted mb-4" style="font-size: 0.85rem;">Enter your email and new password</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            @error('email') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
            @error('password') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="d-flex justify-content-end pt-2">
            <button type="submit" class="btn btn-primary">
                Reset Password
            </button>
        </div>
    </form>
</div>
@endsection
