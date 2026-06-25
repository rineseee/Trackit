@extends('layouts.app')

@section('title', 'Issues')

@section('content')
    @php
        $statusTone = [
            'open' => 'status-open',
            'in_progress' => 'status-in_progress',
            'closed' => 'status-closed',
        ];

        $priorityTone = [
            'high' => 'priority-high',
            'medium' => 'priority-medium',
            'low' => 'priority-low',
        ];
    @endphp

    <div class="workbench">
        <header class="workbench-header">
            <div>
                <p class="workbench-kicker">Issues</p>
                <h1>Track and triage work</h1>
                <p>Search, filter, and open issue details from a compact list built for repeat use.</p>
            </div>

            <div class="workbench-actions">
                <a href="{{ route('issues.kanban') }}" class="ui-button secondary">
                    <i class="bi bi-kanban"></i>
                    Kanban
                </a>
                <a href="{{ route('issues.create') }}" class="ui-button primary">
                    <i class="bi bi-plus-lg"></i>
                    New issue
                </a>
            </div>
        </header>

        <form method="GET" action="{{ route('issues.index') }}" class="workbench-filters">
            <label class="filter-search">
                <i class="bi bi-search"></i>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search issues">
            </label>

            <select name="status" aria-label="Filter by status" onchange="this.form.submit()">
                <option value="">All statuses</option>
                @foreach (\App\Models\Issue::STATUSES as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>

            <select name="priority" aria-label="Filter by priority" onchange="this.form.submit()">
                <option value="">All priorities</option>
                @foreach (\App\Models\Issue::PRIORITIES as $priority)
                    <option value="{{ $priority }}" @selected(request('priority') === $priority)>
                        {{ ucfirst($priority) }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="ui-button secondary">
                <i class="bi bi-funnel"></i>
                Apply
            </button>

            @if(request()->hasAny(['search', 'status', 'priority']))
                <a href="{{ route('issues.index') }}" class="ui-button ghost">
                    <i class="bi bi-x-lg"></i>
                    Clear
                </a>
            @endif
        </form>

        <section class="workbench-panel">
            <div class="panel-heading">
                <div>
                    <h2>Issue list</h2>
                    <p>{{ $issues->total() }} {{ Str::plural('issue', $issues->total()) }}</p>
                </div>
            </div>

            @if($issues->count() > 0)
                <div class="responsive-table">
                    <table class="ui-table">
                        <thead>
                            <tr>
                                <th>Issue</th>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Due</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($issues as $issue)
                                <tr>
                                    <td>
                                        <a href="{{ route('issues.show', $issue) }}" class="table-title">
                                            {{ $issue->title }}
                                        </a>
                                        <div class="table-meta">
                                            Updated {{ $issue->updated_at?->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td>{{ $issue->project->name }}</td>
                                    <td>
                                        <span class="badge-soft {{ $statusTone[$issue->status] ?? 'status-open' }}">
                                            {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-soft {{ $priorityTone[$issue->priority] ?? 'priority-low' }}">
                                            {{ ucfirst($issue->priority) }}
                                        </span>
                                    </td>
                                    <td>{{ $issue->due_date ? $issue->due_date->format('M j, Y') : 'No due date' }}</td>
                                    <td>
                                        <div class="row-actions">
                                            <a href="{{ route('issues.show', $issue) }}" class="icon-button" title="View issue"
                                                aria-label="View issue">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            @auth
                                                <a href="{{ route('issues.edit', $issue) }}" class="icon-button" title="Edit issue"
                                                    aria-label="Edit issue">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="POST" action="{{ route('issues.destroy', $issue) }}"
                                                    onsubmit="return confirm('Delete this issue?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="icon-button danger" title="Delete issue"
                                                        aria-label="Delete issue">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endauth
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="panel-footer">
                    {{ $issues->links() }}
                </div>
            @else
                <div class="ui-empty">
                    <i class="bi bi-inbox"></i>
                    <h2>No issues found</h2>
                    <p>Adjust the filters or create a new issue for this workspace.</p>
                    <a href="{{ route('issues.create') }}" class="ui-button primary">
                        <i class="bi bi-plus-lg"></i>
                        New issue
                    </a>
                </div>
            @endif
        </section>
    </div>
@endsection