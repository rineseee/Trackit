@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $today = now()->startOfDay();
        $deadlineLabel = function ($issue) use ($today) {
            $daysLeft = $today->diffInDays($issue->due_date, false);
            return match(true) {
                $daysLeft < 0 => abs($daysLeft) . ' days overdue',
                $daysLeft === 0 => 'Due today',
                $daysLeft === 1 => 'Due tomorrow',
                default => 'Due in ' . $daysLeft . ' days'
            };
        };
        $projectIssueMax = max(collect($issuesByProject)->max() ?? 0, 1);
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
        $emoji = $hour < 12 ? '🌅' : ($hour < 18 ? '☀️' : '🌙');
        $userName = auth()->user()?->name ?? 'there';
        $firstName = explode(' ', $userName)[0];
    @endphp

    <style>
        .dashboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--trackit-border);
        }

        .dashboard-header-left h1 {
            margin: 0 0 2px;
            font-size: 28px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .dashboard-header-left p {
            margin: 0;
            font-size: 13px;
            color: var(--trackit-muted);
        }

        .dashboard-header-right {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .dashboard-header-right .btn {
            padding: 8px 14px;
            font-size: 13px;
        }

        .dashboard-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-bottom: 24px;
        }

        .metric-card {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 8px;
            padding: 12px;
            transition: all 200ms ease;
        }

        .metric-card:hover {
            border-color: var(--trackit-primary);
            box-shadow: var(--trackit-shadow-sm);
        }

        .metric-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--trackit-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .metric-label i {
            font-size: 14px;
        }

        .metric-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--trackit-text);
            margin-bottom: 4px;
        }

        .metric-desc {
            font-size: 12px;
            color: var(--trackit-muted);
        }

        .dashboard-layout {
            display: grid;
            grid-template-columns: 1fr 280px;
            gap: 20px;
        }

        .dashboard-section {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 10px;
            overflow: hidden;
        }

        .dashboard-section-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--trackit-border);
            background: var(--trackit-surface-soft);
        }

        .dashboard-section-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--trackit-text);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin: 0;
        }

        .dashboard-section-content {
            padding: 0;
        }

        .activity-item {
            padding: 12px 16px;
            border-bottom: 1px solid var(--trackit-border);
            display: flex;
            gap: 10px;
            align-items: flex-start;
            transition: background 150ms ease;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover {
            background: var(--trackit-surface-soft);
        }

        .activity-icon {
            width: 32px;
            height: 32px;
            min-width: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--trackit-primary-soft);
            color: var(--trackit-primary);
            font-size: 12px;
            font-weight: 700;
        }

        .activity-content {
            flex: 1;
            min-width: 0;
        }

        .activity-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--trackit-text);
            margin: 0 0 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .activity-title a {
            color: inherit;
            text-decoration: none;
        }

        .activity-title a:hover {
            color: var(--trackit-primary);
        }

        .activity-meta {
            font-size: 11px;
            color: var(--trackit-muted);
            margin: 0;
        }

        .activity-time {
            font-size: 11px;
            color: var(--trackit-muted);
            white-space: nowrap;
            text-align: right;
        }

        .empty-state {
            padding: 32px 16px;
            text-align: center;
            color: var(--trackit-muted);
        }

        .empty-state i {
            font-size: 24px;
            display: block;
            margin-bottom: 8px;
            opacity: 0.5;
        }

        .empty-state p {
            margin: 0;
            font-size: 12px;
        }

        .sidebar-stat {
            padding: 12px;
            background: var(--trackit-surface-soft);
            border-radius: 8px;
            text-align: center;
            margin-bottom: 8px;
        }

        .sidebar-stat:last-child {
            margin-bottom: 0;
        }

        .sidebar-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--trackit-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 4px;
        }

        .sidebar-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .progress-bar {
            height: 4px;
            background: var(--trackit-border);
            border-radius: 2px;
            overflow: hidden;
            margin-top: 8px;
        }

        .progress-fill {
            height: 100%;
            background: var(--trackit-primary);
            border-radius: 2px;
            transition: width 600ms ease;
        }

        @media (max-width: 1024px) {
            .dashboard-layout {
                grid-template-columns: 1fr;
            }

            .dashboard-metrics {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }

        @media (max-width: 640px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .dashboard-header-right {
                width: 100%;
            }

            .dashboard-header-right .btn {
                flex: 1;
            }

            .dashboard-metrics {
                grid-template-columns: 1fr 1fr;
            }

            .metric-card {
                padding: 10px;
            }

            .metric-value {
                font-size: 20px;
            }
        }

        html[data-theme='dark'] .metric-card {
            background: var(--trackit-surface);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .dashboard-section {
            background: var(--trackit-surface);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .dashboard-section-header {
            background: var(--trackit-surface-soft);
            border-bottom-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .activity-item {
            border-bottom-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .activity-item:hover {
            background: var(--trackit-surface-soft);
        }

        html[data-theme='dark'] .sidebar-stat {
            background: var(--trackit-surface-soft);
        }

        html[data-theme='dark'] .progress-bar {
            background: rgba(148, 163, 184, 0.2);
        }
    </style>

    <!-- HEADER -->
    <div class="dashboard-header">
        <div class="dashboard-header-left">
            <h1>{{ $greeting }}, {{ $firstName }} {{ $emoji }}</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
        <div class="dashboard-header-right">
            <a href="{{ route('issues.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                New Issue
            </a>
            <a href="{{ route('issues.kanban') }}" class="btn btn-secondary">
                <i class="bi bi-kanban"></i>
                Kanban
            </a>
        </div>
    </div>

    <!-- METRICS GRID -->
    <div class="dashboard-metrics">
        <div class="metric-card">
            <div class="metric-label">
                <i class="bi bi-list-check"></i>
                Total Issues
            </div>
            <div class="metric-value">{{ $totalIssues }}</div>
            <div class="metric-desc">{{ $totalIssues }} tracked items</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">
                <i class="bi bi-folder2"></i>
                Projects
            </div>
            <div class="metric-value">{{ $totalProjects }}</div>
            <div class="metric-desc">{{ $totalProjects }} active</div>
        </div>

        <div class="metric-card">
            <div class="metric-label">
                <i class="bi bi-hourglass-split"></i>
                Open
            </div>
            <div class="metric-value">{{ $openIssues }}</div>
            <div class="metric-desc">{{ $openRate }}% of total</div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ max($openRate, 5) }}%"></div>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-label">
                <i class="bi bi-alarm"></i>
                Overdue
            </div>
            <div class="metric-value" style="color: {{ $overdueIssues > 0 ? '#ef4444' : '#10b981' }};">
                {{ $overdueIssues }}
            </div>
            <div class="metric-desc">{{ $overdueRate }}% overdue</div>
        </div>
    </div>

    <!-- MAIN LAYOUT (2-Column) -->
    <div class="dashboard-layout">
        <!-- LEFT COLUMN: Activity Feed -->
        <div>
            <!-- Recent Activity -->
            <div class="dashboard-section" style="margin-bottom: 20px;">
                <div class="dashboard-section-header">
                    <h3 class="dashboard-section-title">Recent Activity</h3>
                </div>
                <div class="dashboard-section-content">
                    @forelse($recentIssues as $issue)
                        <div class="activity-item">
                            <div class="activity-icon">{{ strtoupper(substr($issue->title, 0, 1)) }}</div>
                            <div class="activity-content">
                                <p class="activity-title">
                                    <a href="{{ route('issues.show', $issue) }}">#{{ $issue->id }} {{ \Illuminate\Support\Str::limit($issue->title, 40) }}</a>
                                </p>
                                <p class="activity-meta">{{ $issue->project->name }} • {{ ucfirst(str_replace('_', ' ', $issue->status)) }}</p>
                            </div>
                            <div class="activity-time">{{ $issue->updated_at?->diffForHumans() }}</div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-clock-history"></i>
                            <p>No recent activity</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="dashboard-section">
                <div class="dashboard-section-header">
                    <h3 class="dashboard-section-title">Upcoming Deadlines</h3>
                </div>
                <div class="dashboard-section-content">
                    @forelse($upcomingDeadlines as $issue)
                        @php
                            $daysLeft = $today->diffInDays($issue->due_date, false);
                            $color = $daysLeft < 0 ? '#ef4444' : ($daysLeft === 0 ? '#f59e0b' : '#10b981');
                        @endphp
                        <div class="activity-item">
                            <div class="activity-icon" style="background: {{ str_contains($color, 'ef44') ? 'rgba(239, 68, 68, 0.1)' : (str_contains($color, 'f59e') ? 'rgba(245, 158, 11, 0.1)' : 'rgba(16, 185, 129, 0.1)') }}; color: {{ $color }};">
                                <i class="bi bi-calendar"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-title">
                                    <a href="{{ route('issues.show', $issue) }}">#{{ $issue->id }} {{ \Illuminate\Support\Str::limit($issue->title, 35) }}</a>
                                </p>
                                <p class="activity-meta">{{ $deadlineLabel($issue) }}</p>
                            </div>
                            <div class="activity-time">{{ $issue->due_date?->format('M d') }}</div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="bi bi-calendar2-x"></i>
                            <p>No upcoming deadlines</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Insights & Statistics -->
        <div>
            <!-- Status Breakdown -->
            <div class="dashboard-section" style="margin-bottom: 20px;">
                <div class="dashboard-section-header">
                    <h3 class="dashboard-section-title">Status Mix</h3>
                </div>
                <div style="padding: 16px;">
                    <div class="sidebar-stat">
                        <div class="sidebar-label">Open</div>
                        <div class="sidebar-value" style="color: #dc2626;">{{ $openIssues }}</div>
                    </div>
                    <div class="sidebar-stat">
                        <div class="sidebar-label">In Progress</div>
                        <div class="sidebar-value" style="color: #d97706;">{{ $inProgressIssues }}</div>
                    </div>
                    <div class="sidebar-stat">
                        <div class="sidebar-label">Closed</div>
                        <div class="sidebar-value" style="color: #059669;">{{ $closedIssues }}</div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="dashboard-section">
                <div class="dashboard-section-header">
                    <h3 class="dashboard-section-title">Health</h3>
                </div>
                <div style="padding: 16px;">
                    <div class="sidebar-stat">
                        <div class="sidebar-label">Completion</div>
                        <div class="sidebar-value">{{ $closedRate }}%</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ max($closedRate, 5) }}%"></div>
                        </div>
                    </div>
                    <div style="padding: 12px 0; border-top: 1px solid var(--trackit-border); margin-top: 12px;">
                        <div style="font-size: 11px; color: var(--trackit-muted); margin-bottom: 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em;">
                            Performance
                        </div>
                        <div style="font-size: 13px; color: var(--trackit-text); margin-bottom: 8px;">
                            <strong>{{ round(($closedIssues / max($totalIssues, 1)) * 100) }}%</strong> closed
                        </div>
                        <div style="font-size: 13px; color: var(--trackit-text);">
                            <strong>{{ round(($overdueIssues / max($totalIssues, 1)) * 100) }}%</strong> overdue
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
