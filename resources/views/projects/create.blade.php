@extends('layouts.app')

@section('title', 'Create Project')

@section('content')
    <div class="mx-auto max-w-7xl space-y-3">
        <section style="border-radius: 10px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; flex-direction: column; gap: 12px; align-items: flex-start;">
                <div>
                    <p style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--trackit-muted); margin: 0;">New project</p>
                    <h1 style="margin: 4px 0 0; font-size: 18px; font-weight: 700; color: var(--trackit-text); letter-spacing: -0.01em;">Create a new project</h1>
                    <p style="margin: 4px 0 0; font-size: 12px; color: var(--trackit-muted); line-height: 1.5;">Set up a workspace to organize work.</p>
                </div>

                <a href="{{ route('projects.index') }}" style="display: inline-flex; align-items: center; gap: 6px; border-radius: 6px; background: var(--trackit-surface-soft); padding: 8px 12px; font-size: 12px; font-weight: 600; color: var(--trackit-text); text-decoration: none; transition: all 150ms ease;">
                    <i class="bi bi-arrow-left" style="font-size: 13px;"></i>
                    Back
                </a>
            </div>
        </section>

        <div style="animation: fadeIn 300ms ease;">
            @include('projects._form', ['project' => $project])
        </div>
    </div>
@endsection