@extends('layouts.app')

@section('title', 'Create Project')

@section('content')
    <style>
        .create-project-wrapper {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .create-project-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--trackit-border);
        }

        .create-project-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            background: var(--trackit-surface-soft);
            border: 1px solid var(--trackit-border);
            border-radius: 6px;
            color: var(--trackit-text);
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 150ms ease;
        }

        .back-button:hover {
            background: var(--trackit-border);
        }

        .form-card {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 10px;
            padding: 20px;
            animation: fadeIn 300ms ease;
        }

        .form-header {
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--trackit-border);
        }

        .form-header p {
            margin: 0;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--trackit-muted);
        }

        .form-header h2 {
            margin: 4px 0 0;
            font-size: 18px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .form-grid {
            display: grid;
            gap: 12px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--trackit-text);
        }

        .form-input,
        .form-textarea {
            padding: 8px 10px;
            border: 1px solid var(--trackit-border);
            background: var(--trackit-surface-soft);
            color: var(--trackit-text);
            border-radius: 6px;
            font-size: 12px;
            transition: all 150ms ease;
            font-family: inherit;
        }

        .form-input:focus,
        .form-textarea:focus {
            border-color: var(--trackit-primary);
            outline: none;
            background: var(--trackit-surface);
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
        }

        .form-textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-actions {
            display: flex;
            gap: 8px;
            margin-top: 16px;
            padding-top: 12px;
            border-top: 1px solid var(--trackit-border);
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: var(--trackit-primary);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 150ms ease;
        }

        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            background: var(--trackit-surface);
            color: var(--trackit-text);
            border: 1px solid var(--trackit-border);
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 150ms ease;
        }

        .btn-secondary:hover {
            background: var(--trackit-surface-soft);
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .create-project-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        html[data-theme='dark'] .form-card {
            background: var(--trackit-surface);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .form-input,
        html[data-theme='dark'] .form-textarea {
            background: var(--trackit-surface-soft);
            border-color: rgba(148, 163, 184, 0.2);
            color: var(--trackit-text);
        }

        html[data-theme='dark'] .form-input:focus,
        html[data-theme='dark'] .form-textarea:focus {
            border-color: var(--trackit-primary);
            box-shadow: 0 0 0 3px rgba(100, 150, 255, 0.1);
        }

        html[data-theme='dark'] .back-button {
            background: var(--trackit-surface-soft);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .btn-secondary {
            background: var(--trackit-surface);
            border-color: rgba(148, 163, 184, 0.2);
        }
    </style>

    <div class="create-project-wrapper">
        <!-- HEADER -->
        <div class="create-project-header">
            <h1>Create Project</h1>
            <a href="{{ route('projects.index') }}" class="back-button">
                <i class="bi bi-arrow-left"></i>
                Back
            </a>
        </div>

        <!-- FORM -->
        @include('projects._form', ['project' => $project])
    </div>

@endsection
