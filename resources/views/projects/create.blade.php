@extends('layouts.app')

@section('title', 'Create Project')

@section('content')
    <div class="mx-auto max-w-6xl space-y-8">
        <section class="page-banner animate-fade-in">
            <div class="page-banner-content">
                <span style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; background: var(--trackit-primary-soft); color: var(--trackit-primary); border-radius: 999px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 16px;">
                    <i class="bi bi-plus-circle"></i>
                    New project
                </span>
                <h1 style="margin: 0 0 12px; font-size: 32px; font-weight: 700; color: var(--trackit-text);">
                    Create a new project
                </h1>
                <p style="margin: 0; font-size: 16px; color: var(--trackit-muted); line-height: 1.6;">
                    Set up a workspace to organize issues, track progress, and collaborate with your team.
                </p>
            </div>

            <div class="page-banner-meta">
                <div class="meta-tile">
                    <div class="meta-tile-label">Quick Setup</div>
                    <div class="meta-tile-value">3</div>
                    <div style="font-size: 12px; color: var(--trackit-muted); margin-top: 4px;">Simple fields</div>
                </div>
                <div class="meta-tile">
                    <div class="meta-tile-label">Instant</div>
                    <div class="meta-tile-value">Ready</div>
                    <div style="font-size: 12px; color: var(--trackit-muted); margin-top: 4px;">To use</div>
                </div>
            </div>

            <div class="page-banner-actions">
                <a href="{{ route('projects.index') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-arrow-left"></i>
                    Back to projects
                </a>
            </div>
        </section>

        <div class="animate-slide-up" style="animation-delay: 100ms;">
            @include('projects._form', ['project' => $project])
        </div>
    </div>
@endsection