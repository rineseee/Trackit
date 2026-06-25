@extends('layouts.auth')

@section('title', 'Verify Email - Trackit')

@section('content')
    <div class="auth-card-header">
        <div class="auth-mini">
            <i class="bi bi-envelope-check"></i>
            Verify email
        </div>
    </div>

    <div class="auth-card-body">
        <h2>Check your inbox</h2>
        <p class="auth-subtitle">We sent a verification link to your email address.</p>

        @if (session('resent'))
            <div class="alert alert-success">
                A new verification link has been sent.
            </div>
        @endif

        <div class="alert alert-info">
            Please verify your email before continuing. If you did not receive it, request another link below.
        </div>

        <form class="d-grid gap-2" action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary btn-auth-primary">
                Resend verification email
            </button>
        </form>
    </div>

    <div class="auth-footer">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="auth-link btn btn-link p-0 align-baseline border-0">Sign out</button>
        </form>
    </div>
@endsection
