@extends('layouts.auth')

@section('title', 'Register - Trackit')

@section('content')
    <div class="auth-card-header">
        <div class="auth-mini">
            <i class="bi bi-person-plus"></i>
            Create account
        </div>
    </div>

    <div class="auth-card-body">
        <h2>Start your workspace</h2>
        <p class="auth-subtitle">Create an account with a simple form and a clear flow.</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="d-grid gap-3">
            @csrf

            <div>
                <label class="form-label" for="register-name">Full name</label>
                <input id="register-name" type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" required autocomplete="name" placeholder="John Doe">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="form-label" for="register-email">Email address</label>
                <input id="register-email" type="email" name="email"
                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required
                    autocomplete="email" placeholder="you@example.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label class="form-label" for="register-password">Password</label>
                <input id="register-password" type="password" name="password"
                    class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password"
                    placeholder="Create a password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted d-block mt-2">
                    Use a strong password you can remember.
                </small>
            </div>

            <div>
                <label class="form-label" for="register-password-confirmation">Confirm password</label>
                <input id="register-password-confirmation" type="password" name="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror" required
                    autocomplete="new-password" placeholder="Confirm your password">
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="auth-actions d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-auth-primary">
                    Create account
                </button>
            </div>
        </form>
    </div>

    <div class="auth-footer">
        Already have an account?
        <a href="{{ route('login') }}" class="auth-link">Sign in</a>
    </div>
@endsection
