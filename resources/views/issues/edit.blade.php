@extends('layouts.app')

@section('title', 'Edit Issue')

@push('styles')
    <style>
        /* Dark mode support for edit form */
        html[data-theme='dark'] .bg-white {
            background-color: #1e293b !important;
        }

        html[data-theme='dark'] .bg-slate-50 {
            background-color: #0f172a !important;
        }

        html[data-theme='dark'] .text-slate-900 {
            color: #f1f5f9 !important;
        }

        html[data-theme='dark'] .text-slate-700 {
            color: #cbd5e1 !important;
        }

        html[data-theme='dark'] .text-slate-600 {
            color: #94a3b8 !important;
        }

        html[data-theme='dark'] .text-slate-500 {
            color: #64748b !important;
        }

        html[data-theme='dark'] .border-slate-200 {
            border-color: #334155 !important;
        }

        html[data-theme='dark'] .border-slate-300 {
            border-color: #475569 !important;
        }

        html[data-theme='dark'] .ring-slate-200 {
            --tw-ring-color: rgb(226 232 240 / 0.3) !important;
        }

        html[data-theme='dark'] .ring-slate-100 {
            --tw-ring-color: rgb(241 245 249 / 0.1) !important;
        }

        html[data-theme='dark'] input,
        html[data-theme='dark'] select,
        html[data-theme='dark'] textarea {
            background-color: #0f172a !important;
            color: #f1f5f9 !important;
            border-color: #475569 !important;
        }

        html[data-theme='dark'] input::placeholder,
        html[data-theme='dark'] textarea::placeholder {
            color: #64748b !important;
        }

        html[data-theme='dark'] input:focus,
        html[data-theme='dark'] select:focus,
        html[data-theme='dark'] textarea:focus {
            border-color: #2563eb !important;
            --tw-ring-color: rgba(37, 99, 235, 0.2) !important;
        }

        html[data-theme='dark'] .bg-sky-600 {
            background-color: #2563eb !important;
        }

        html[data-theme='dark'] .bg-sky-600:hover {
            background-color: #1d4ed8 !important;
        }

        html[data-theme='dark'] .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.5) !important;
        }

        html[data-theme='dark'] .shadow-slate-200 {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.5) !important;
        }

        html[data-theme='dark'] .text-amber-500 {
            color: #fbbf24 !important;
        }

        html[data-theme='dark'] .text-sky-500 {
            color: #0ea5e9 !important;
        }

        html[data-theme='dark'] .ring-sky-100 {
            --tw-ring-color: rgba(14, 165, 233, 0.1) !important;
        }

        html[data-theme='dark'] .focus\:border-sky-500:focus {
            border-color: #0ea5e9 !important;
        }

        html[data-theme='dark'] .focus\:ring-sky-100:focus {
            --tw-ring-color: rgba(14, 165, 233, 0.1) !important;
        }
    </style>
@endpush

@section('content')
    <div class="mx-auto max-w-7xl space-y-8">
        <section class="page-banner animate-fade-in">
            <div class="page-banner-content">
                <span style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; background: var(--trackit-primary-soft); color: var(--trackit-primary); border-radius: 999px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 16px;">
                    <i class="bi bi-pencil"></i>
                    Edit issue
                </span>
                <h1 style="margin: 0 0 12px; font-size: 32px; font-weight: 700; color: var(--trackit-text);">
                    Update this issue
                </h1>
                <p style="margin: 0; font-size: 16px; color: var(--trackit-muted); line-height: 1.6;">
                    Edit the title, description, priority, status, and assignees to keep the issue tracking accurate and up to date.
                </p>
            </div>

            <div class="page-banner-meta">
                <div class="meta-tile">
                    <div class="meta-tile-label">Status</div>
                    <div class="meta-tile-value">{{ ucfirst(str_replace('_', ' ', $issue->status)) }}</div>
                    <div style="font-size: 12px; color: var(--trackit-muted); margin-top: 4px;">Current</div>
                </div>
                <div class="meta-tile">
                    <div class="meta-tile-label">Updated</div>
                    <div class="meta-tile-value">{{ $issue->updated_at->format('M d') }}</div>
                    <div style="font-size: 12px; color: var(--trackit-muted); margin-top: 4px;">Recently</div>
                </div>
            </div>

            <div class="page-banner-actions">
                <a href="{{ route('issues.show', $issue) }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-arrow-left"></i>
                    Back to issue
                </a>
            </div>
        </section>

        <div class="animate-slide-up" style="animation-delay: 100ms;">
            @include('issues._form', ['issue' => $issue, 'projects' => $projects, 'tags' => $tags])
    </div>
@endsection
