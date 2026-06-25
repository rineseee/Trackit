@extends('layouts.app')

@section('title', 'Create Tag')

@section('content')
    <div class="mx-auto max-w-2xl space-y-3">
        <div style="border-radius: 10px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; flex-direction: column; gap: 12px; align-items: flex-start;">
                <div>
                    <p style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--trackit-muted); margin: 0;">Tags</p>
                    <h1 style="font-size: 18px; font-weight: 700; color: var(--trackit-text); margin: 4px 0 0; letter-spacing: -0.01em;">Create tag</h1>
                    <p style="font-size: 12px; color: var(--trackit-muted); margin: 4px 0 0; line-height: 1.5;">
                        Add a reusable label for organizing work.
                    </p>
                </div>

                <a href="{{ route('tags.index') }}" style="display: inline-flex; align-items: center; gap: 6px; border-radius: 6px; background: var(--trackit-surface-soft); padding: 8px 12px; font-size: 12px; font-weight: 600; color: var(--trackit-text); text-decoration: none; transition: all 150ms ease;">
                    <i class="bi bi-arrow-left" style="font-size: 13px;"></i>
                    Back
                </a>
            </div>
        </div>

        @include('tags._form', ['tag' => $tag])
    </div>
@endsection
