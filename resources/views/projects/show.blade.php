@extends('layouts.app')

@section('title', $project->name)

@section('content')
    @php
        $issueTotal = $project->issues->count();
        $closedIssues = $project->issues->where('status', 'closed')->count();
        $openIssues = max($issueTotal - $closedIssues, 0);
        $progress = $issueTotal > 0 ? round(($closedIssues / $issueTotal) * 100) : 0;
    @endphp

    <div class="workbench projects-workbench">
        <header class="workbench-header">
            <div>
                <p class="workbench-kicker">Project</p>
                <h1>{{ $project->name }}</h1>
                <p>{{ $project->description ?: 'No description provided.' }}</p>
            </div>

            <div class="workbench-actions">
                <a href="{{ route('projects.index') }}" class="ui-button secondary">
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>
                @can('update', $project)
                    <a href="{{ route('projects.edit', $project) }}" class="ui-button secondary">
                        <i class="bi bi-pencil"></i>
                        Edit
                    </a>
                @endcan
                @auth
                    <a href="{{ route('issues.create', ['project_id' => $project->id]) }}" class="ui-button primary">
                        <i class="bi bi-plus-lg"></i>
                        New issue
                    </a>
                @else
                    <a href="{{ route('login') }}" class="ui-button primary">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Log in
                    </a>
                @endauth
            </div>
        </header>

        <section class="metric-strip">
            <div>
                <span>Owner</span>
                <strong>{{ $project->owner?->name ?? 'Unassigned' }}</strong>
            </div>
            <div>
                <span>Open issues</span>
                <strong>{{ $openIssues }}</strong>
            </div>
            <div>
                <span>Closed</span>
                <strong>{{ $closedIssues }}</strong>
            </div>
            <div>
                <span>Progress</span>
                <strong>{{ $progress }}%</strong>
            </div>
        </section>

        <section class="workbench-panel">
            <div class="panel-heading">
                <div>
                    <h2>Issues</h2>
                    <p>{{ $issueTotal }} {{ Str::plural('issue', $issueTotal) }}</p>
                </div>
            </div>

            @if ($issueTotal > 0)
                <div class="issue-list">
                    @foreach ($project->issues as $issue)
                        <article class="issue-row">
                            <div class="issue-main">
                                <div>
                                    <a href="{{ route('issues.show', $issue) }}" class="issue-title">
                                        {{ $issue->title }}
                                    </a>
                                    <p>{{ Str::limit($issue->description ?: 'No description', 120) }}</p>
                                </div>

                                <div class="issue-meta">
                                    <span class="badge-soft status-{{ $issue->status }}">
                                        {{ str_replace('_', ' ', ucfirst($issue->status)) }}
                                    </span>
                                    <span class="badge-soft priority-{{ $issue->priority }}">
                                        {{ ucfirst($issue->priority) }}
                                    </span>
                                    @if($issue->due_date)
                                        <span><i class="bi bi-calendar3"></i>{{ $issue->due_date->format('M j, Y') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row-actions">
                                <a href="{{ route('issues.show', $issue) }}" class="icon-button" title="View issue" aria-label="View issue">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @auth
                                    <a href="{{ route('issues.edit', $issue) }}" class="icon-button" title="Edit issue" aria-label="Edit issue">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endauth
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="ui-empty">
                    <i class="bi bi-inbox"></i>
                    <h2>No issues yet</h2>
                    <p>Create the first issue to start tracking this project.</p>
                    @auth
                        <a href="{{ route('issues.create', ['project_id' => $project->id]) }}" class="ui-button primary">
                            <i class="bi bi-plus-lg"></i>
                            New issue
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="ui-button primary">Log in</a>
                    @endauth
                </div>
            @endif
        </section>
    </div>

    <style>
        .workbench {
            display: grid;
            gap: 16px;
            max-width: 1240px;
            margin: 0 auto;
        }

        .workbench-header,
        .workbench-panel,
        .metric-strip {
            background: var(--ui-panel);
            border: 1px solid var(--ui-border);
            border-radius: var(--ui-radius);
            box-shadow: var(--ui-shadow);
        }

        .workbench-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            padding: 20px;
        }

        .workbench-kicker {
            margin: 0 0 4px;
            color: var(--ui-faint);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .workbench-header h1 {
            margin: 0;
            font-size: clamp(1.5rem, 2vw, 2rem);
            font-weight: 700;
        }

        .workbench-header p,
        .panel-heading p,
        .issue-main p {
            margin: 4px 0 0;
            color: var(--ui-muted);
        }

        .workbench-actions,
        .row-actions,
        .issue-meta {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .metric-strip {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .metric-strip>div {
            padding: 14px 16px;
            border-right: 1px solid var(--ui-border);
        }

        .metric-strip>div:last-child {
            border-right: 0;
        }

        .metric-strip span {
            display: block;
            color: var(--ui-muted);
            font-size: 0.75rem;
            font-weight: 650;
        }

        .metric-strip strong {
            display: block;
            margin-top: 4px;
            color: var(--ui-heading);
            font-size: 1.35rem;
            line-height: 1;
        }

        .panel-heading,
        .panel-footer {
            padding: 14px 16px;
        }

        .panel-heading {
            border-bottom: 1px solid var(--ui-border);
        }

        .panel-heading h2 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .issue-list {
            display: grid;
        }

        .issue-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 18px;
            align-items: center;
            padding: 16px;
            border-bottom: 1px solid var(--ui-border);
        }

        .issue-row:last-child {
            border-bottom: 0;
        }

        .issue-row:hover {
            background: var(--ui-panel-muted);
        }

        .issue-title {
            color: var(--ui-heading);
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
        }

        .issue-main p {
            max-width: 68ch;
            font-size: 0.9rem;
        }

        .issue-meta {
            flex-wrap: wrap;
            margin-top: 10px;
            color: var(--ui-muted);
            font-size: 0.8rem;
        }

        .issue-meta span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .ui-button,
        .icon-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 1px solid var(--ui-border);
            border-radius: var(--ui-radius-sm);
            background: var(--ui-panel);
            color: var(--ui-text);
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
        }

        .ui-button {
            min-height: 38px;
            padding: 0 12px;
        }

        .ui-button.primary {
            background: var(--ui-heading);
            border-color: var(--ui-heading);
            color: var(--ui-panel);
        }

        .icon-button {
            width: 32px;
            height: 32px;
            color: var(--ui-muted);
        }

        .ui-button:hover,
        .icon-button:hover {
            background: var(--ui-panel-subtle);
            color: var(--ui-heading);
        }

        .ui-button.primary:hover {
            background: var(--ui-text);
            color: var(--ui-panel);
        }

        .ui-empty {
            display: grid;
            justify-items: center;
            gap: 10px;
            padding: 48px 20px;
            text-align: center;
        }

        .ui-empty i {
            color: var(--ui-faint);
            font-size: 2rem;
        }

        .ui-empty h2 {
            margin: 0;
            font-size: 1.05rem;
        }

        .ui-empty p {
            margin: 0;
            color: var(--ui-muted);
        }

        .badge-soft {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 9px;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            line-height: 1;
            white-space: nowrap;
        }

        .badge-soft.status-open {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
        }

        .badge-soft.status-in_progress {
            background: rgba(245, 158, 11, 0.14);
            color: #d97706;
        }

        .badge-soft.status-closed {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
        }

        .badge-soft.priority-high {
            background: rgba(220, 38, 38, 0.12);
            color: #b91c1c;
        }

        .badge-soft.priority-medium {
            background: rgba(245, 158, 11, 0.14);
            color: #d97706;
        }

        .badge-soft.priority-low {
            background: rgba(100, 116, 139, 0.12);
            color: #475569;
        }

        @media (max-width: 960px) {
            .workbench-header,
            .issue-row {
                align-items: stretch;
                grid-template-columns: 1fr;
            }

            .workbench-header {
                flex-direction: column;
            }

            .metric-strip {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .metric-strip>div:nth-child(2) {
                border-right: 0;
            }

            .metric-strip>div:nth-child(-n + 2) {
                border-bottom: 1px solid var(--ui-border);
            }
        }
    </style>
@endsection