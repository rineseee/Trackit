@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
    <div class="mx-auto max-w-7xl space-y-8">
        <section class="page-banner animate-fade-in">
            <div class="page-banner-content">
                <span style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; background: var(--trackit-primary-soft); color: var(--trackit-primary); border-radius: 999px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 16px;">
                    <i class="bi bi-pencil"></i>
                    Edit project
                </span>
                <h1 style="margin: 0 0 12px; font-size: 32px; font-weight: 700; color: var(--trackit-text);">
                    {{ $project->name }}
                </h1>
                <p style="margin: 0; font-size: 16px; color: var(--trackit-muted); line-height: 1.6;">
                    Keep the project details up to date so everyone stays aligned on goals, deadlines, and ownership.
                </p>
            </div>

            <div class="page-banner-actions">
                <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-arrow-left"></i>
                    Back to project
                </a>
            </div>
        </section>

        <div class="animate-slide-up" style="animation-delay: 100ms;">
            @include('projects._form', ['project' => $project])
    </div>
@endsection