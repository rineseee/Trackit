@extends('layouts.app')

@section('title', 'Create Issue')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8">
        <section class="page-banner animate-fade-in">
            <div class="page-banner-content">
                <span style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; background: var(--trackit-primary-soft); color: var(--trackit-primary); border-radius: 999px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 16px;">
                    <i class="bi bi-plus-circle"></i>
                    New issue
                </span>
                <h1 style="margin: 0 0 12px; font-size: 32px; font-weight: 700; color: var(--trackit-text);">
                    Create a new issue
                </h1>
                <p style="margin: 0; font-size: 16px; color: var(--trackit-muted); line-height: 1.6;">
                    Capture the problem, assign it to a project, set priorities, and track progress effortlessly.
                </p>
            </div>

            <div class="page-banner-meta">
                <div class="meta-tile">
                    <div class="meta-tile-label">Fast</div>
                    <div class="meta-tile-value">Setup</div>
                    <div style="font-size: 12px; color: var(--trackit-muted); margin-top: 4px;">Quick capture</div>
                </div>
                <div class="meta-tile">
                    <div class="meta-tile-label">Flexible</div>
                    <div class="meta-tile-value">Fields</div>
                    <div style="font-size: 12px; color: var(--trackit-muted); margin-top: 4px;">Organized</div>
                </div>
            </div>

            <div class="page-banner-actions">
                <a href="{{ route('issues.index') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-arrow-left"></i>
                    Back to issues
                </a>
            </div>
        </section>

        <div class="animate-slide-up" style="animation-delay: 100ms;">
            @include('issues._form', ['issue' => $issue, 'projects' => $projects, 'tags' => $tags])
    </div>
@endsection
