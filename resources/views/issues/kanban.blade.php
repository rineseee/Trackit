@extends('layouts.app')

@section('title', 'Kanban Board')

@section('content')
    @php
        // Calculate metrics from grouped issues
        $allIssues = $issues->flatten();
        $totalIssues = $allIssues->count();
        $openIssues = $issues->get('open', collect())->count();
        $inProgressIssues = $issues->get('in_progress', collect())->count();
        $closedIssues = $issues->get('closed', collect())->count();
        $statuses = ['open', 'in_progress', 'closed'];

        $statusClass = function($status) {
            return match($status) {
                'open' => 'kanban-status-open',
                'in_progress' => 'kanban-status-progress',
                'closed' => 'kanban-status-closed',
                default => 'kanban-status-open'
            };
        };
    @endphp

    <style>
        .kanban-wrapper {
            display: flex;
            flex-direction: column;
            height: calc(100vh - var(--trackit-topbar-height) - 24px);
        }

        .kanban-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            margin-bottom: 12px;
            border-bottom: 1px solid var(--trackit-border);
            flex-shrink: 0;
        }

        .kanban-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .kanban-actions {
            display: flex;
            gap: 8px;
        }

        .kanban-actions .ui-button {
            padding: 8px 12px;
            font-size: 13px;
        }

        .kanban-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
            gap: 8px;
            margin-bottom: 12px;
            flex-shrink: 0;
        }

        .metric-box {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 6px;
            padding: 8px;
            text-align: center;
        }

        .metric-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--trackit-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 3px;
        }

        .metric-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .kanban-toolbar {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 8px;
            padding: 8px;
            margin-bottom: 12px;
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
            flex-shrink: 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .kanban-toolbar input,
        .kanban-toolbar select,
        .kanban-toolbar button {
            height: 32px;
            font-size: 13px;
            padding: 6px 10px;
            border-radius: 6px;
        }

        .kanban-toolbar input {
            flex: 1;
            min-width: 150px;
            border: 1px solid var(--trackit-border);
            background: var(--trackit-surface-soft);
        }

        .kanban-toolbar select {
            border: 1px solid var(--trackit-border);
            background: var(--trackit-surface-soft);
            color: var(--trackit-text);
            cursor: pointer;
        }

        .board-container {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            overflow-y: hidden;
            flex: 1;
            padding-bottom: 12px;
        }

        .kanban-column {
            flex: 0 0 min(100%, 380px);
            display: flex;
            flex-direction: column;
            background: var(--trackit-surface-soft);
            border: 1px solid var(--trackit-border);
            border-radius: 8px;
            overflow: hidden;
        }

        .kanban-column-header {
            padding: 10px 12px;
            border-bottom: 2px solid var(--trackit-border);
            background: var(--trackit-surface);
            flex-shrink: 0;
        }

        .column-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .column-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--trackit-text);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .column-title i {
            font-size: 14px;
        }

        .column-badge {
            background: var(--trackit-surface-soft);
            color: var(--trackit-muted);
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 700;
        }

        .column-progress {
            font-size: 10px;
            color: var(--trackit-muted);
            height: 3px;
            background: var(--trackit-border);
            border-radius: 2px;
            overflow: hidden;
        }

        .column-progress-fill {
            height: 100%;
            background: var(--trackit-primary);
            width: 0%;
            transition: width 300ms ease;
        }

        .kanban-dropzone {
            flex: 1;
            overflow-y: auto;
            padding: 8px;
            min-height: 200px;
        }

        .kanban-card {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 6px;
            padding: 8px;
            margin-bottom: 8px;
            cursor: grab;
            transition: all 150ms ease;
            position: relative;
        }

        .kanban-card:hover {
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            border-color: var(--trackit-primary);
        }

        .kanban-card.dragging {
            opacity: 0.6;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.15);
        }

        .card-drag-handle {
            cursor: grab;
            color: var(--trackit-muted);
            font-size: 12px;
            position: absolute;
            left: 4px;
            top: 4px;
        }

        .card-drag-handle:active {
            cursor: grabbing;
        }

        .card-content {
            padding-left: 16px;
        }

        .card-title {
            font-weight: 600;
            font-size: 12px;
            color: var(--trackit-text);
            margin: 0 0 4px;
            display: block;
            text-decoration: none;
            word-break: break-word;
        }

        .card-title:hover {
            color: var(--trackit-primary);
        }

        .card-id {
            font-size: 10px;
            color: var(--trackit-muted);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .card-meta {
            display: flex;
            gap: 4px;
            margin-top: 6px;
            flex-wrap: wrap;
            font-size: 10px;
        }

        .card-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 4px;
            border-radius: 3px;
            background: var(--trackit-surface-soft);
            color: var(--trackit-muted);
            font-weight: 600;
        }

        .card-priority {
            font-weight: 700;
        }

        .priority-high {
            color: #dc2626;
        }

        .priority-medium {
            color: #d97706;
        }

        .priority-low {
            color: #4f46e5;
        }

        .card-assignees {
            display: flex;
            gap: -4px;
            margin-top: 6px;
        }

        .card-avatar {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--trackit-primary-soft);
            color: var(--trackit-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            font-weight: 700;
            border: 2px solid var(--trackit-surface);
            margin-left: -8px;
        }

        .card-avatar:first-child {
            margin-left: 0;
        }

        .empty-state {
            padding: 24px 12px;
            text-align: center;
            color: var(--trackit-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .empty-state i {
            font-size: 32px;
            display: block;
            margin-bottom: 8px;
            opacity: 0.4;
        }

        .empty-state-text {
            font-size: 12px;
            margin: 0;
        }

        .kanban-status-open {
            border-left: 3px solid #dc2626;
        }

        .kanban-status-progress {
            border-left: 3px solid #d97706;
        }

        .kanban-status-closed {
            border-left: 3px solid #059669;
        }

        @media (max-width: 1024px) {
            .board-container {
                gap: 8px;
            }

            .kanban-column {
                flex: 0 0 min(100%, 320px);
            }

            .kanban-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .kanban-toolbar input,
            .kanban-toolbar select,
            .kanban-toolbar button {
                flex: 1;
            }
        }

        @media (max-width: 640px) {
            .kanban-metrics {
                grid-template-columns: repeat(2, 1fr);
            }

            .board-container {
                gap: 4px;
            }

            .kanban-column {
                flex: 0 0 min(100%, 90vw);
            }

            .kanban-card {
                padding: 6px;
            }

            .card-content {
                padding-left: 12px;
            }

            .card-title {
                font-size: 11px;
            }
        }

        html[data-theme='dark'] .kanban-column {
            background: rgba(30, 41, 59, 0.5);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .kanban-column-header {
            background: var(--trackit-surface);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .kanban-card {
            background: var(--trackit-surface);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .kanban-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        html[data-theme='dark'] .metric-box {
            background: var(--trackit-surface);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .kanban-toolbar {
            background: var(--trackit-surface);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .kanban-toolbar input,
        html[data-theme='dark'] .kanban-toolbar select {
            background: var(--trackit-surface-soft);
            border-color: rgba(148, 163, 184, 0.2);
            color: var(--trackit-text);
        }
    </style>

    <div class="kanban-wrapper">
        <!-- HEADER -->
        <div class="kanban-header">
            <h1>Kanban Board</h1>
            <div class="kanban-actions">
                <a href="{{ route('issues.create') }}" class="ui-button primary">
                    <i class="bi bi-plus-lg"></i>
                    Create Issue
                </a>
                <a href="{{ route('issues.index') }}" class="ui-button secondary">
                    <i class="bi bi-list-ul"></i>
                    List View
                </a>
            </div>
        </div>

        <!-- METRICS -->
        <div class="kanban-metrics">
            <div class="metric-box">
                <div class="metric-label">Total</div>
                <div class="metric-value">{{ $totalIssues }}</div>
            </div>
            <div class="metric-box">
                <div class="metric-label">Open</div>
                <div class="metric-value" style="color: #dc2626;">{{ $openIssues }}</div>
            </div>
            <div class="metric-box">
                <div class="metric-label">Progress</div>
                <div class="metric-value" style="color: #d97706;">{{ $inProgressIssues }}</div>
            </div>
            <div class="metric-box">
                <div class="metric-label">Closed</div>
                <div class="metric-value" style="color: #059669;">{{ $closedIssues }}</div>
            </div>
        </div>

        <!-- TOOLBAR -->
        <form class="kanban-toolbar">
            <input type="search" placeholder="Search issues..." style="min-width: 180px;">
            <select style="min-width: 120px;">
                <option>All statuses</option>
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="closed">Closed</option>
            </select>
            <select style="min-width: 120px;">
                <option>All priorities</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
            </select>
            <button class="ui-button secondary" style="flex-shrink: 0;">
                <i class="bi bi-funnel"></i>
                Filter
            </button>
        </form>

        <!-- BOARD -->
        <div class="board-container">
            @foreach ($statuses as $status)
                @php
                    $columnIssues = $issues->get($status, collect());
                    $columnCount = $columnIssues->count();
                    $columnTitle = match($status) {
                        'open' => 'Open',
                        'in_progress' => 'In Progress',
                        'closed' => 'Closed',
                        default => ucfirst(str_replace('_', ' ', $status))
                    };
                    $columnIcon = match($status) {
                        'open' => 'bi-exclamation-circle',
                        'in_progress' => 'bi-arrow-repeat',
                        'closed' => 'bi-check2-circle',
                        default => 'bi-kanban'
                    };
                @endphp

                <div class="kanban-column" data-kanban-column="{{ $status }}">
                    <div class="kanban-column-header">
                        <div class="column-title-row">
                            <h3 class="column-title">
                                <i class="bi {{ $columnIcon }}"></i>
                                {{ $columnTitle }}
                            </h3>
                            <span class="column-badge">{{ $columnCount }}</span>
                        </div>
                        <div class="column-progress">
                            <div class="column-progress-fill" style="width: {{ $columnCount > 0 ? min(($columnCount / max($totalIssues, 1)) * 100, 100) : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="kanban-dropzone" data-column-body>
                        @forelse ($columnIssues as $issue)
                            @php
                                $isOverdue = $issue->due_date && $issue->due_date->isPast() && $issue->status !== 'closed';
                                $isDueToday = $issue->due_date && $issue->due_date->isToday();
                            @endphp

                            <div class="kanban-card {{ $statusClass($issue->status) }}"
                                data-issue-card
                                data-issue-id="{{ $issue->id }}"
                                data-issue-status="{{ $issue->status }}"
                                data-update-url="{{ route('issues.kanban.status', $issue) }}"
                                onclick="window.location.href='{{ route('issues.show', $issue) }}';">

                                <span class="card-drag-handle" title="Drag to move" onclick="event.stopPropagation()">
                                    <i class="bi bi-grip-vertical"></i>
                                </span>

                                <div class="card-content">
                                    <span class="card-id">#{{ $issue->id }}</span>
                                    <a href="{{ route('issues.show', $issue) }}" class="card-title" onclick="event.stopPropagation()">
                                        {{ \Illuminate\Support\Str::limit($issue->title, 45) }}
                                    </a>

                                    <div class="card-meta">
                                        <span class="card-badge card-priority {{ 'priority-' . $issue->priority }}">
                                            {{ ucfirst($issue->priority) }}
                                        </span>
                                        @if($issue->project)
                                            <span class="card-badge">{{ \Illuminate\Support\Str::limit($issue->project->name, 12) }}</span>
                                        @endif
                                        @if($isOverdue)
                                            <span class="card-badge" style="color: #dc2626; background: rgba(220, 38, 38, 0.1);">Overdue</span>
                                        @elseif($isDueToday)
                                            <span class="card-badge" style="color: #d97706; background: rgba(217, 119, 6, 0.1);">Today</span>
                                        @elseif($issue->due_date)
                                            <span class="card-badge">{{ $issue->due_date->format('M d') }}</span>
                                        @endif
                                    </div>

                                    @if($issue->members && count($issue->members) > 0)
                                        <div class="card-assignees">
                                            @foreach($issue->members as $member)
                                                <span class="card-avatar" title="{{ $member->name }}">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <p class="empty-state-text">No issues</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
