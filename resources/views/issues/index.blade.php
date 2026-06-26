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

    <style>
        .issues-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--trackit-border);
        }

        .issues-header-content h1 {
            margin: 0 0 2px;
            font-size: 24px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .issues-header-content p {
            margin: 0;
            font-size: 13px;
            color: var(--trackit-muted);
        }

        .issues-header-actions {
            display: flex;
            gap: 8px;
        }

        .issues-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }

        .overview-card {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }

        .overview-card i {
            font-size: 16px;
            color: var(--trackit-primary);
            margin-bottom: 6px;
            display: block;
        }

        .overview-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--trackit-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }

        .overview-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .issues-toolbar {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-search {
            flex: 1;
            min-width: 200px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: var(--trackit-surface-soft);
            border: 1px solid var(--trackit-border);
            border-radius: 6px;
            transition: all 150ms ease;
        }

        .filter-search:focus-within {
            border-color: var(--trackit-primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .filter-search i {
            color: var(--trackit-muted);
            font-size: 14px;
        }

        .filter-search input {
            flex: 1;
            border: none;
            background: transparent;
            color: var(--trackit-text);
            font-size: 13px;
            outline: none;
        }

        .filter-search input::placeholder {
            color: var(--trackit-muted);
        }

        .issues-toolbar select {
            padding: 8px 10px;
            background: var(--trackit-surface-soft);
            border: 1px solid var(--trackit-border);
            border-radius: 6px;
            color: var(--trackit-text);
            font-size: 13px;
            cursor: pointer;
            transition: all 150ms ease;
            min-width: 120px;
        }

        .issues-toolbar select:hover,
        .issues-toolbar select:focus {
            border-color: var(--trackit-primary);
            background: var(--trackit-surface);
        }

        .issues-toolbar .btn {
            padding: 8px 12px;
            font-size: 13px;
            flex-shrink: 0;
        }

        .issues-panel {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 10px;
            overflow: hidden;
        }

        .issues-panel-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--trackit-border);
            background: var(--trackit-surface-soft);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .issues-panel-header h2 {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            color: var(--trackit-text);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .issues-panel-count {
            font-size: 12px;
            color: var(--trackit-muted);
            background: var(--trackit-surface);
            padding: 4px 8px;
            border-radius: 4px;
        }

        .responsive-table {
            overflow-x: auto;
        }

        .ui-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .ui-table thead {
            background: var(--trackit-surface-soft);
            border-bottom: 1px solid var(--trackit-border);
        }

        .ui-table thead th {
            padding: 10px 12px;
            text-align: left;
            font-weight: 700;
            color: var(--trackit-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-size: 11px;
            white-space: nowrap;
        }

        .ui-table tbody tr {
            border-bottom: 1px solid var(--trackit-border);
            transition: background 150ms ease;
            cursor: pointer;
        }

        .ui-table tbody tr:hover {
            background: var(--trackit-surface-soft);
        }

        .ui-table tbody tr:last-child {
            border-bottom: none;
        }

        .ui-table td {
            padding: 11px 12px;
            color: var(--trackit-text);
        }

        .issue-cell {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .issue-title {
            font-weight: 600;
            color: var(--trackit-text);
            text-decoration: none;
            display: inline-block;
        }

        .issue-title:hover {
            color: var(--trackit-primary);
        }

        .issue-id {
            font-size: 11px;
            color: var(--trackit-muted);
            font-weight: 500;
        }

        .issue-meta {
            font-size: 11px;
            color: var(--trackit-faint);
        }

        .status-badge,
        .priority-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .status-open {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .status-in_progress {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .status-closed {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .priority-high {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .priority-medium {
            background: rgba(245, 158, 11, 0.1);
            color: #d97706;
        }

        .priority-low {
            background: rgba(99, 102, 241, 0.1);
            color: #4f46e5;
        }

        .tags-cell {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
        }

        .tag-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 8px;
            background: var(--trackit-surface-soft);
            border: 1px solid var(--trackit-border);
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            color: var(--trackit-text);
            white-space: nowrap;
            transition: all 150ms ease;
        }

        .tag-badge:hover {
            border-color: var(--trackit-primary);
            background: var(--trackit-surface);
            box-shadow: 0 2px 6px rgba(79, 70, 229, 0.1);
        }

        .tag-badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 999px;
            flex-shrink: 0;
        }

        .tag-badge-empty {
            font-size: 11px;
            color: var(--trackit-faint);
        }

        .actions-cell {
            display: flex;
            gap: 6px;
            justify-content: flex-end;
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
        }

        .icon-button:hover {
            background: var(--trackit-border);
            color: var(--trackit-primary);
        }

        .empty-state {
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
            font-size: 16px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .empty-state p {
            margin: 0 0 16px;
            font-size: 13px;
        }

        .pagination-container {
            padding: 12px 16px;
            border-top: 1px solid var(--trackit-border);
            background: var(--trackit-surface-soft);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        @media (max-width: 1024px) {
            .issues-overview {
                grid-template-columns: repeat(2, 1fr);
            }

            .issues-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-search,
            .issues-toolbar select {
                flex: 1;
            }
        }

        @media (max-width: 640px) {
            .issues-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .issues-overview {
                grid-template-columns: repeat(2, 1fr);
            }

            .ui-table {
                font-size: 12px;
            }

            .ui-table td {
                padding: 8px;
            }

            .actions-cell {
                gap: 4px;
            }

            .icon-button {
                width: 24px;
                height: 24px;
                font-size: 12px;
            }
        }

        html[data-theme='dark'] .issues-toolbar,
        html[data-theme='dark'] .issues-panel,
        html[data-theme='dark'] .overview-card {
            background: var(--trackit-surface);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .filter-search {
            background: var(--trackit-surface-soft);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .issues-toolbar select {
            background: var(--trackit-surface-soft);
            border-color: rgba(148, 163, 184, 0.2);
            color: var(--trackit-text);
        }

        html[data-theme='dark'] .icon-button {
            background: var(--trackit-surface-soft);
            color: var(--trackit-text);
        }
    </style>

    <div class="issues-header">
        <div class="issues-header-content">
            <h1>Issues</h1>
            <p>Track, filter, and manage work items</p>
        </div>
        <div class="issues-header-actions">
            <a href="{{ route('issues.kanban') }}" class="ui-button secondary">
                <i class="bi bi-kanban"></i>
                Kanban
            </a>
            <a href="{{ route('issues.create') }}" class="ui-button primary">
                <i class="bi bi-plus-lg"></i>
                New Issue
            </a>
        </div>
    </div>

    <!-- QUICK OVERVIEW -->
    <div class="issues-overview">
        <div class="overview-card">
            <i class="bi bi-list-check"></i>
            <div class="overview-label">Open</div>
            <div class="overview-value">{{ $openIssues ?? 0 }}</div>
        </div>
        <div class="overview-card">
            <i class="bi bi-hourglass-split"></i>
            <div class="overview-label">In Progress</div>
            <div class="overview-value">{{ $inProgressIssues ?? 0 }}</div>
        </div>
        <div class="overview-card">
            <i class="bi bi-check2-circle"></i>
            <div class="overview-label">Closed</div>
            <div class="overview-value">{{ $closedIssues ?? 0 }}</div>
        </div>
        <div class="overview-card">
            <i class="bi bi-alarm"></i>
            <div class="overview-label">Overdue</div>
            <div class="overview-value">{{ $overdueIssues ?? 0 }}</div>
        </div>
    </div>

    <!-- SEARCH & FILTERS TOOLBAR -->
    <form method="GET" action="{{ route('issues.index') }}" class="issues-toolbar">
        <label class="filter-search">
            <i class="bi bi-search"></i>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search issues...">
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

        <select name="tag" aria-label="Filter by tag" onchange="this.form.submit()">
            <option value="">All tags</option>
            @foreach ($tags as $tag)
                <option value="{{ $tag->id }}" @selected(request('tag') == $tag->id)>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="ui-button secondary" style="white-space: nowrap;">
            <i class="bi bi-funnel"></i>
            Apply
        </button>

        @if(request()->hasAny(['search', 'status', 'priority']))
            <a href="{{ route('issues.index') }}" class="ui-button ghost" style="white-space: nowrap;">
                <i class="bi bi-x-lg"></i>
                Clear
            </a>
        @endif
    </form>

    <!-- ISSUES TABLE -->
    <div class="issues-panel">
        <div class="issues-panel-header">
            <h2>Issues</h2>
            <div class="issues-panel-count">{{ $issues->total() }} {{ Str::plural('issue', $issues->total()) }}</div>
        </div>

        @if($issues->count() > 0)
            <div class="responsive-table">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th style="width: 28%;">Issue</th>
                            <th style="width: 13%;">Project</th>
                            <th style="width: 11%;">Status</th>
                            <th style="width: 11%;">Priority</th>
                            <th style="width: 10%;">Due</th>
                            <th style="width: 15%;">Tags</th>
                            <th style="width: 12%; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($issues as $issue)
                            <tr onclick="window.location.href='{{ route('issues.show', $issue) }}';">
                                <td>
                                    <div class="issue-cell">
                                        <span class="issue-id">#{{ $issue->issue_number }}</span>
                                        <a href="{{ route('issues.show', $issue) }}" class="issue-title" onclick="event.stopPropagation();">
                                            {{ \Illuminate\Support\Str::limit($issue->title, 50) }}
                                        </a>
                                        <span class="issue-meta">{{ $issue->updated_at?->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td>{{ $issue->project->name }}</td>
                                <td>
                                    <span class="status-badge {{ $statusTone[$issue->status] ?? 'status-open' }}">
                                        {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="priority-badge {{ $priorityTone[$issue->priority] ?? 'priority-medium' }}">
                                        {{ ucfirst($issue->priority) }}
                                    </span>
                                </td>
                                <td>
                                    @if($issue->due_date)
                                        <span style="font-size: 12px; font-weight: 600;">{{ $issue->due_date->format('M d') }}</span>
                                    @else
                                        <span style="font-size: 12px; color: var(--trackit-faint);">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="tags-cell">
                                        @forelse($issue->tags as $tag)
                                            <span class="tag-badge" title="{{ $tag->name }}">
                                                <span class="tag-badge-dot" style="background-color: {{ $tag->color ?? '#6366f1' }};"></span>
                                                {{ \Illuminate\Support\Str::limit($tag->name, 12) }}
                                            </span>
                                        @empty
                                            <span class="tag-badge-empty">—</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    <div class="actions-cell" onclick="event.stopPropagation();">
                                        <a href="{{ route('issues.show', $issue) }}" class="icon-button" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('issues.edit', $issue) }}" class="icon-button" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($issues->hasPages())
                <div class="pagination-container">
                    {{ $issues->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h3>No issues found</h3>
                <p>Create a new issue or adjust your filters</p>
                <a href="{{ route('issues.create') }}" class="ui-button primary">
                    <i class="bi bi-plus-lg"></i>
                    Create Issue
                </a>
            </div>
        @endif
    </div>

@endsection
