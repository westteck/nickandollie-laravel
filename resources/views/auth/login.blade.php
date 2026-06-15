@extends('layouts.guest')

@section('title', 'Login — Nick & Ollie Fortune Wedding')

@section('content')
<div class="auth-card" style="max-width: 420px; margin: 2rem auto;">
    <h2 class="text-center mb-1" style="color: var(--primary); font-weight: 600;">Welcome Back</h2>
    <p class="text-center text-muted mb-4" style="font-size: 0.85rem;">Sign in to your account</p>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="alert alert-success mb-3">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email / Username --}}
        <div class="mb-3">
            <label for="email" class="form-label">Email or Username</label>
            <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" class="form-control" name="password" required autocomplete="current-password">
            @error('password')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- Remember Me --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label text-muted" style="font-size: 0.85rem;">Remember me</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-muted" style="font-size: 0.85rem;">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">
            <i class="fas fa-sign-in-alt me-2"></i>Sign In
        </button>

        <p class="text-center text-muted mb-0" style="font-size: 0.85rem;">
            Don't have an account? <a href="{{ route('register') }}" style="color: var(--primary);">Create one</a>
        </p>
    </form>
</div>
@endsection
