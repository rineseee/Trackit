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

    <div class="workbench projects-workbench">
        <header class="workbench-header">
            <div>
                <p class="workbench-kicker">Projects</p>
                <h1>Plan and monitor active work</h1>
                <p>Review ownership, timelines, and issue progress without leaving the project list.</p>
            </div>

            <div class="workbench-actions">
                <a href="{{ route('issues.index') }}" class="ui-button secondary">
                    <i class="bi bi-list-check"></i>
                    Issues
                </a>
                @auth
                    <a href="{{ route('projects.create') }}" class="ui-button primary">
                        <i class="bi bi-plus-lg"></i>
                        New project
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
                <span>Projects</span>
                <strong>{{ $totalProjects }}</strong>
            </div>
            <div>
                <span>Issues</span>
                <strong>{{ $totalIssues }}</strong>
            </div>
            <div>
                <span>Open</span>
                <strong>{{ $openIssues }}</strong>
            </div>
            <div>
                <span>Completion</span>
                <strong>{{ $completionRate }}%</strong>
            </div>
        </section>

        <section class="workbench-panel">
            <div class="panel-heading">
                <div>
                    <h2>Project list</h2>
                    <p>{{ $projects->total() }} {{ Str::plural('project', $projects->total()) }}</p>
                </div>
            </div>

            <div class="project-list">
                @forelse ($projects as $project)
                    @php
                        $projectIssues = max($project->issues_count, 1);
                        $projectClosedIssues = $project->closed_issues_count ?? 0;
                        $projectProgress = round(($projectClosedIssues / $projectIssues) * 100);
                    @endphp

                    <article class="project-row">
                        <div class="project-main">
                            <div>
                                <a href="{{ route('projects.show', $project) }}" class="project-title">
                                    {{ $project->name }}
                                </a>
                                <p>{{ $project->description ?: 'No description provided.' }}</p>
                            </div>

                            <div class="project-meta">
                                <span><i class="bi bi-person"></i>{{ $project->owner?->name ?? 'Unassigned' }}</span>
                                <span><i
                                        class="bi bi-calendar3"></i>{{ $project->deadline?->format('M j, Y') ?? 'No deadline' }}</span>
                                <span><i class="bi bi-list-check"></i>{{ $project->issues_count }}
                                    {{ Str::plural('issue', $project->issues_count) }}</span>
                            </div>
                        </div>

                        <div class="project-progress">
                            <div class="progress-label">
                                <span>{{ $projectClosedIssues }} closed</span>
                                <strong>{{ $projectProgress }}%</strong>
                            </div>
                            <div class="progress-track">
                                <span style="width: {{ $projectProgress }}%"></span>
                            </div>
                        </div>

                        <div class="row-actions">
                            <a href="{{ route('projects.show', $project) }}" class="icon-button" title="Open project"
                                aria-label="Open project">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('update', $project)
                                <a href="{{ route('projects.edit', $project) }}" class="icon-button" title="Edit project"
                                    aria-label="Edit project">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endcan
                            @auth
                                <a href="{{ route('issues.create', ['project_id' => $project->id]) }}" class="icon-button"
                                    title="Create issue" aria-label="Create issue">
                                    <i class="bi bi-plus-lg"></i>
                                </a>
                            @endauth
                        </div>
                    </article>
                @empty
                    <div class="ui-empty">
                        <i class="bi bi-folder2-open"></i>
                        <h2>No projects yet</h2>
                        <p>Create the first project to start organizing issues.</p>
                        @auth
                            <a href="{{ route('projects.create') }}" class="ui-button primary">
                                <i class="bi bi-plus-lg"></i>
                                New project
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="ui-button primary">Log in</a>
                        @endauth
                    </div>
                @endforelse
            </div>

            @if ($projects->hasPages())
                <div class="panel-footer">
                    {{ $projects->links() }}
                </div>
            @endif
        </section>
    </div>

@endsection