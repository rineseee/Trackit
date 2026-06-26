@extends('layouts.app')

@section('title', 'Projects')

@section('content')
    @php
        $totalProjects = $projects->total();
        $totalIssues = \App\Models\Issue::count();
        $closedIssues = \App\Models\Issue::where('status', 'closed')->count();
        $openIssues = max($totalIssues - $closedIssues, 0);
        $completionRate = $totalIssues > 0 ? round(($closedIssues / $totalIssues) * 100) : 0;
    @endphp

    <style>
        .projects-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            margin-bottom: 16px;
            border-bottom: 1px solid var(--trackit-border);
        }

        .projects-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .projects-header-actions {
            display: flex;
            gap: 8px;
        }

        .projects-header-actions .ui-button {
            padding: 8px 12px;
            font-size: 13px;
        }

        .projects-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin-bottom: 16px;
        }

        .metric-card {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }

        .metric-icon {
            font-size: 16px;
            color: var(--trackit-primary);
            margin-bottom: 4px;
            display: block;
        }

        .metric-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--trackit-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }

        .metric-value {
            font-size: 22px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .projects-toolbar {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 16px;
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .projects-toolbar input,
        .projects-toolbar select {
            height: 32px;
            padding: 6px 10px;
            border: 1px solid var(--trackit-border);
            background: var(--trackit-surface-soft);
            color: var(--trackit-text);
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
        }

        .projects-toolbar input {
            flex: 1;
            min-width: 160px;
        }

        .projects-toolbar input:focus,
        .projects-toolbar select:focus {
            border-color: var(--trackit-primary);
            outline: none;
        }

        .projects-toolbar .ui-button {
            padding: 6px 12px;
            font-size: 13px;
            flex-shrink: 0;
        }

        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .project-card {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 10px;
            padding: 16px;
            transition: all 200ms ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .project-card:hover {
            border-color: var(--trackit-primary);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
        }

        .project-card-header {
            margin-bottom: 12px;
        }

        .project-card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--trackit-text);
            margin: 0 0 4px;
            display: block;
            text-decoration: none;
            word-break: break-word;
        }

        .project-card-title:hover {
            color: var(--trackit-primary);
        }

        .project-card-desc {
            font-size: 12px;
            color: var(--trackit-muted);
            margin: 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .project-card-meta {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--trackit-border);
            font-size: 11px;
            color: var(--trackit-muted);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .meta-item i {
            font-size: 12px;
        }

        .project-card-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 12px;
        }

        .stat-box {
            background: var(--trackit-surface-soft);
            border-radius: 6px;
            padding: 8px;
            text-align: center;
        }

        .stat-label {
            font-size: 10px;
            color: var(--trackit-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 2px;
        }

        .stat-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .project-progress {
            margin-bottom: 12px;
        }

        .progress-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 11px;
        }

        .progress-label {
            font-weight: 600;
            color: var(--trackit-text);
        }

        .progress-percent {
            background: var(--trackit-surface-soft);
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: 700;
            color: var(--trackit-primary);
        }

        .progress-bar {
            height: 6px;
            background: var(--trackit-border);
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--trackit-primary), #3b82f6);
            border-radius: 3px;
            transition: width 400ms ease;
        }

        .project-card-footer {
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid var(--trackit-border);
            display: flex;
            gap: 6px;
        }

        .icon-button {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            border: 1px solid transparent;
            background: var(--trackit-surface-soft);
            color: var(--trackit-text);
            cursor: pointer;
            transition: all 150ms ease;
            font-size: 14px;
            text-decoration: none;
        }

        .icon-button:hover {
            background: var(--trackit-border);
            color: var(--trackit-primary);
        }

        .empty-state {
            grid-column: 1 / -1;
            padding: 64px 32px;
            text-align: center;
            color: var(--trackit-muted);
        }

        .empty-state i {
            font-size: 48px;
            display: block;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state h3 {
            margin: 0 0 8px;
            font-size: 18px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .empty-state p {
            margin: 0 0 16px;
            font-size: 13px;
        }

        .pagination-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--trackit-border);
        }

        @media (max-width: 1024px) {
            .projects-grid {
                grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            }

            .projects-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .projects-toolbar input,
            .projects-toolbar select {
                flex: 1;
            }
        }

        @media (max-width: 640px) {
            .projects-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .projects-header-actions {
                width: 100%;
            }

            .projects-header-actions .ui-button {
                flex: 1;
            }

            .projects-metrics {
                grid-template-columns: repeat(2, 1fr);
            }

            .projects-grid {
                grid-template-columns: 1fr;
            }

            .project-card {
                padding: 12px;
            }

            .project-card-meta {
                gap: 8px;
            }
        }

        html[data-theme='dark'] .project-card {
            background: var(--trackit-surface);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .project-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        html[data-theme='dark'] .metric-card {
            background: var(--trackit-surface);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .projects-toolbar {
            background: var(--trackit-surface);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .projects-toolbar input,
        html[data-theme='dark'] .projects-toolbar select {
            background: var(--trackit-surface-soft);
            border-color: rgba(148, 163, 184, 0.2);
            color: var(--trackit-text);
        }

        html[data-theme='dark'] .stat-box {
            background: var(--trackit-surface-soft);
        }

        html[data-theme='dark'] .icon-button {
            background: var(--trackit-surface-soft);
            color: var(--trackit-text);
        }

        html[data-theme='dark'] .project-card-meta,
        html[data-theme='dark'] .project-card-footer {
            border-color: rgba(148, 163, 184, 0.2);
        }
    </style>

    <!-- HEADER -->
    <div class="projects-header">
        <h1>Projects</h1>
        <div class="projects-header-actions">
            <a href="{{ route('issues.index') }}" class="ui-button secondary">
                <i class="bi bi-list-check"></i>
                Issues
            </a>
            @auth
                <a href="{{ route('projects.create') }}" class="ui-button primary">
                    <i class="bi bi-plus-lg"></i>
                    New Project
                </a>
            @else
                <a href="{{ route('login') }}" class="ui-button primary">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Log In
                </a>
            @endauth
        </div>
    </div>

    <!-- METRICS -->
    <div class="projects-metrics">
        <div class="metric-card">
            <i class="metric-icon bi bi-folder2"></i>
            <div class="metric-label">Total Projects</div>
            <div class="metric-value">{{ $totalProjects }}</div>
        </div>
        <div class="metric-card">
            <i class="metric-icon bi bi-list-check"></i>
            <div class="metric-label">Total Issues</div>
            <div class="metric-value">{{ $totalIssues }}</div>
        </div>
        <div class="metric-card">
            <i class="metric-icon bi bi-hourglass-split"></i>
            <div class="metric-label">Open</div>
            <div class="metric-value" style="color: #dc2626;">{{ $openIssues }}</div>
        </div>
        <div class="metric-card">
            <i class="metric-icon bi bi-check2-circle"></i>
            <div class="metric-label">Completion</div>
            <div class="metric-value" style="color: #059669;">{{ $completionRate }}%</div>
        </div>
    </div>

    <!-- TOOLBAR -->
    <form method="GET" action="{{ route('projects.index') }}" class="projects-toolbar">
        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search projects...">
        <button type="submit" class="ui-button secondary">
            <i class="bi bi-search"></i>
            Search
        </button>
    </form>

    <!-- PROJECTS GRID -->
    <div class="projects-grid">
        @forelse ($projects as $project)
            @php
                $projectIssues = max($project->issues_count, 1);
                $projectClosedIssues = $project->closed_issues_count ?? 0;
                $projectOpenIssues = $projectIssues - $projectClosedIssues;
                $projectProgress = round(($projectClosedIssues / $projectIssues) * 100);
            @endphp

            <div class="project-card" onclick="window.location.href='{{ route('projects.show', $project) }}';">
                <!-- Header -->
                <div class="project-card-header">
                    <a href="{{ route('projects.show', $project) }}" class="project-card-title" onclick="event.stopPropagation();">
                        {{ $project->name }}
                    </a>
                    @if($project->description)
                        <p class="project-card-desc">{{ $project->description }}</p>
                    @endif
                </div>

                <!-- Meta Info -->
                <div class="project-card-meta">
                    @if($project->owner)
                        <div class="meta-item" title="{{ $project->owner->name }}">
                            <i class="bi bi-person-fill"></i>
                            <span>{{ \Illuminate\Support\Str::limit($project->owner->name, 15) }}</span>
                        </div>
                    @endif
                    @if($project->deadline)
                        <div class="meta-item" title="{{ $project->deadline->format('M j, Y') }}">
                            <i class="bi bi-calendar"></i>
                            <span>{{ $project->deadline->format('M d') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Statistics -->
                <div class="project-card-stats">
                    <div class="stat-box">
                        <div class="stat-label">Issues</div>
                        <div class="stat-value">{{ $projectIssues }}</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-label">Open</div>
                        <div class="stat-value" style="color: #dc2626;">{{ $projectOpenIssues }}</div>
                    </div>
                </div>

                <!-- Progress -->
                <div class="project-progress">
                    <div class="progress-header">
                        <span class="progress-label">Progress</span>
                        <span class="progress-percent">{{ $projectProgress }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ max($projectProgress, 2) }}%"></div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="project-card-footer">
                    <a href="{{ route('projects.show', $project) }}" class="icon-button" title="Open project" onclick="event.stopPropagation();">
                        <i class="bi bi-eye"></i>
                    </a>
                    @can('update', $project)
                        <a href="{{ route('projects.edit', $project) }}" class="icon-button" title="Edit project" onclick="event.stopPropagation();">
                            <i class="bi bi-pencil"></i>
                        </a>
                    @endcan
                    @auth
                        <a href="{{ route('issues.create', ['project_id' => $project->id]) }}" class="icon-button" title="Create issue" onclick="event.stopPropagation();">
                            <i class="bi bi-plus-lg"></i>
                        </a>
                    @endauth
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bi bi-folder2-open"></i>
                <h3>No projects yet</h3>
                <p>Create the first project to start organizing issues.</p>
                @auth
                    <a href="{{ route('projects.create') }}" class="ui-button primary">
                        <i class="bi bi-plus-lg"></i>
                        New Project
                    </a>
                @else
                    <a href="{{ route('login') }}" class="ui-button primary">Log In</a>
                @endauth
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if ($projects->hasPages())
        <div class="pagination-container">
            {{ $projects->links() }}
        </div>
    @endif

@endsection
