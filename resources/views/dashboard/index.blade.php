@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $today = now()->startOfDay();

        $deadlineLabel = function ($issue) use ($today) {
            $daysLeft = $today->diffInDays($issue->due_date, false);

            if ($daysLeft < 0) {
                return abs($daysLeft) . ' days overdue';
            }

            if ($daysLeft === 0) {
                return 'Due today';
            }

            if ($daysLeft === 1) {
                return 'Due tomorrow';
            }

            return 'Due in ' . $daysLeft . ' days';
        };

        $projectIssueMax = max(collect($issuesByProject)->max() ?? 0, 1);
        $priorityTotal = array_sum($priorityCounts ?? []);
    @endphp


    <div class="container-fluid px-0 dashboard-shell">
        <section class="dashboard-greeting mb-5">
            <div>
                @php
                    $hour = now()->hour;
                    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
                    $emoji = $hour < 12 ? '🌅' : ($hour < 18 ? '☀️' : '🌙');
                    $userName = auth()->user()?->name ?? 'there';
                    $firstName = explode(' ', $userName)[0];
                @endphp
                <h1 class="dashboard-greeting-title">{{ $greeting }}, {{ $firstName }} {{ $emoji }}</h1>
                <p class="dashboard-greeting-subtitle">Here's what's happening with your workspace today.</p>
            </div>
        </section>

        <section class="dashboard-hero">
            <h2>Quick overview of your workspace</h2>
            <p>Track all your projects, issues, and team activity in one place. Monitor progress, catch deadlines, and stay organized.</p>

            <div class="dashboard-actions">
                <a href="{{ route('issues.index') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-list-check"></i>
                    Open Issues
                </a>
                <a href="{{ route('projects.index') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-folder2"></i>
                    Projects
                </a>
                <a href="{{ route('issues.kanban') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-kanban"></i>
                    Kanban Board
                </a>
            </div>

            <div class="dashboard-hero-stats">
                <div class="dashboard-hero-stat">
                    <div class="dashboard-hero-stat-label">Total Issues</div>
                    <div class="dashboard-hero-stat-value counter" data-target="{{ $totalIssues }}">0</div>
                </div>
                <div class="dashboard-hero-stat">
                    <div class="dashboard-hero-stat-label">Projects</div>
                    <div class="dashboard-hero-stat-value counter" data-target="{{ $totalProjects }}">0</div>
                </div>
                <div class="dashboard-hero-stat">
                    <div class="dashboard-hero-stat-label">Open</div>
                    <div class="dashboard-hero-stat-value counter" data-target="{{ $openIssues }}">0</div>
                </div>
                <div class="dashboard-hero-stat">
                    <div class="dashboard-hero-stat-label">Overdue</div>
                    <div class="dashboard-hero-stat-value counter" data-target="{{ $overdueIssues }}">0</div>
                </div>
            </div>
        </section>

        <div class="dashboard-metrics">
            <div class="dashboard-metric-card" style="--metric-color: #3b82f6;">
                <div class="dashboard-metric-header">
                    <div>
                        <div class="dashboard-metric-label">Open Issues</div>
                    </div>
                    <div class="dashboard-metric-icon">
                        <i class="bi bi-circle"></i>
                    </div>
                </div>
                <div class="dashboard-metric-value">{{ $openIssues }}</div>
                <div class="dashboard-metric-desc">Open rate {{ $openRate }}%</div>
                <div class="dashboard-progress">
                    <div class="dashboard-progress-bar" style="width: {{ $openRate > 0 ? max($openRate, 8) : 0 }}%;"></div>
                </div>
            </div>

            <div class="dashboard-metric-card" style="--metric-color: #f59e0b;">
                <div class="dashboard-metric-header">
                    <div>
                        <div class="dashboard-metric-label">In Progress</div>
                    </div>
                    <div class="dashboard-metric-icon">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                </div>
                <div class="dashboard-metric-value">{{ $inProgressIssues }}</div>
                <div class="dashboard-metric-desc">Active work items</div>
                <div class="dashboard-progress">
                    <div class="dashboard-progress-bar" style="width: {{ $progressRate > 0 ? max($progressRate, 8) : 0 }}%;"></div>
                </div>
            </div>

            <div class="dashboard-metric-card" style="--metric-color: #10b981;">
                <div class="dashboard-metric-header">
                    <div>
                        <div class="dashboard-metric-label">Closed</div>
                    </div>
                    <div class="dashboard-metric-icon">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>
                <div class="dashboard-metric-value">{{ $closedIssues }}</div>
                <div class="dashboard-metric-desc">Resolution rate {{ $closedRate }}%</div>
                <div class="dashboard-progress">
                    <div class="dashboard-progress-bar" style="width: {{ $closedRate > 0 ? max($closedRate, 8) : 0 }}%;"></div>
                </div>
            </div>

            <div class="dashboard-metric-card" style="--metric-color: #a855f7;">
                <div class="dashboard-metric-header">
                    <div>
                        <div class="dashboard-metric-label">Overdue</div>
                    </div>
                    <div class="dashboard-metric-icon">
                        <i class="bi bi-alarm"></i>
                    </div>
                </div>
                <div class="dashboard-metric-value">{{ $overdueIssues }}</div>
                <div class="dashboard-metric-desc">Overdue rate {{ $overdueRate }}%</div>
                <div class="dashboard-progress">
                    <div class="dashboard-progress-bar" style="width: {{ $overdueRate > 0 ? max($overdueRate, 8) : 0 }}%;"></div>
                </div>
            </div>
        </div>

        <div class="dashboard-section" style="padding: 0 32px;">
            <div class="dashboard-section-title">Recent Activity</div>
            <div class="dashboard-list" style="margin-bottom: 32px;">
                @forelse($recentIssues as $issue)
                    <div class="dashboard-list-item">
                        <div class="dashboard-list-item-icon">
                            {{ strtoupper(substr($issue->title, 0, 1)) }}
                        </div>
                        <div class="dashboard-list-item-content">
                            <div class="dashboard-list-item-title">
                                <a href="{{ route('issues.show', $issue) }}" style="text-decoration: none; color: inherit;">
                                    #{{ $issue->id }} {{ $issue->title }}
                                </a>
                            </div>
                            <div class="dashboard-list-item-meta">
                                {{ $issue->project->name }} • {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                            </div>
                        </div>
                        <div style="font-size: 12px; color: var(--trackit-muted); white-space: nowrap;">
                            {{ $issue->updated_at?->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div style="padding: 40px; text-align: center; color: var(--trackit-muted);">
                        <i class="bi bi-clock-history" style="font-size: 32px; display: block; margin-bottom: 12px;"></i>
                        <p style="margin: 0;">No recent activity yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="dashboard-section" style="padding: 0 32px;">
            <div class="dashboard-section-title">Recent Comments</div>
            <div class="dashboard-list">
                @forelse($recentComments as $comment)
                    <div class="dashboard-list-item">
                        <div class="dashboard-list-item-icon" style="background: var(--trackit-primary-soft); color: var(--trackit-primary);">
                            <i class="bi bi-chat-left-text"></i>
                        </div>
                        <div class="dashboard-list-item-content">
                            <div class="dashboard-list-item-title">{{ $comment->author_name }}</div>
                            <div class="dashboard-list-item-meta">
                                {{ $comment->issue?->project?->name ?? 'Project' }} •
                                <a href="{{ route('issues.show', $comment->issue) }}" style="color: var(--trackit-primary); text-decoration: none;">
                                    #{{ $comment->issue_id }}
                                </a>
                            </div>
                            <p style="margin: 6px 0 0; font-size: 13px; color: var(--trackit-muted);">
                                {{ \Illuminate\Support\Str::limit($comment->body, 110) }}
                            </p>
                        </div>
                        <div style="font-size: 12px; color: var(--trackit-muted); white-space: nowrap;">
                            {{ $comment->created_at?->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div style="padding: 40px; text-align: center; color: var(--trackit-muted);">
                        <i class="bi bi-chat-left-text" style="font-size: 32px; display: block; margin-bottom: 12px;"></i>
                        <p style="margin: 0;">No comments yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="dashboard-section" style="padding: 0 32px;">
            <div class="dashboard-section-title">Upcoming Deadlines</div>
            <div class="dashboard-list">
                @forelse($upcomingDeadlines as $issue)
                    @php
                        $daysLeft = $today->diffInDays($issue->due_date, false);
                        $barWidth = $daysLeft < 0 ? 100 : min(max((int) (100 - ($daysLeft * 10)), 15), 100);
                        $deadlineTone = $daysLeft < 0 ? 'danger' : ($daysLeft === 0 ? 'warning' : 'success');
                    @endphp

                    <div class="dashboard-list-item">
                        <div class="dashboard-list-item-icon" style="background: {{ $deadlineTone === 'danger' ? 'rgba(239, 68, 68, 0.1)' : ($deadlineTone === 'warning' ? 'rgba(245, 158, 11, 0.1)' : 'rgba(16, 185, 129, 0.1)') }}; color: {{ $deadlineTone === 'danger' ? '#dc2626' : ($deadlineTone === 'warning' ? '#d97706' : '#059669') }};">
                            <i class="bi bi-calendar"></i>
                        </div>
                        <div class="dashboard-list-item-content">
                            <div class="dashboard-list-item-title">
                                <a href="{{ route('issues.show', $issue) }}" style="text-decoration: none; color: inherit;">
                                    #{{ $issue->id }} {{ \Illuminate\Support\Str::limit($issue->title, 48) }}
                                </a>
                            </div>
                            <div class="dashboard-list-item-meta">
                                {{ $issue->project?->name ?? 'Project' }} • {{ $deadlineLabel($issue) }}
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-weight: 700; color: var(--trackit-text); font-size: 14px;">{{ $issue->due_date?->format('M d') }}</div>
                            <div style="font-size: 12px; color: var(--trackit-muted);">{{ $issue->due_date?->format('D') }}</div>
                        </div>
                    </div>
                @empty
                    <div style="padding: 40px; text-align: center; color: var(--trackit-muted);">
                        <i class="bi bi-calendar2-x" style="font-size: 32px; display: block; margin-bottom: 12px;"></i>
                        <p style="margin: 0;">No upcoming deadlines</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="dashboard-section" style="padding: 0 32px;">
            <div class="dashboard-section-title">Project Health</div>
            <div class="dashboard-list">
                @forelse($recentProjects as $project)
                    @php
                        $count = $project->issues_count ?: 0;
                        $projectRate = round(($count / $projectIssueMax) * 100);
                    @endphp
                    <div class="dashboard-list-item">
                        <div class="dashboard-list-item-icon" style="background: var(--trackit-primary-soft); color: var(--trackit-primary);">
                            <i class="bi bi-folder2"></i>
                        </div>
                        <div class="dashboard-list-item-content">
                            <div class="dashboard-list-item-title">
                                <a href="{{ route('projects.show', $project) }}" style="text-decoration: none; color: inherit;">
                                    {{ $project->name }}
                                </a>
                            </div>
                            <div class="dashboard-list-item-meta">
                                {{ $count }} {{ \Illuminate\Support\Str::plural('issue', $count) }} • {{ $project->updated_at?->diffForHumans() }}
                            </div>
                        </div>
                        <div style="font-weight: 700; color: var(--trackit-text); font-size: 14px;">{{ $projectRate }}%</div>
                    </div>
                @empty
                    <div style="padding: 40px; text-align: center; color: var(--trackit-muted);">
                        <i class="bi bi-folder2-open" style="font-size: 32px; display: block; margin-bottom: 12px;"></i>
                        <p style="margin: 0;">No projects yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="dashboard-section" style="padding: 0 32px;">
            <div class="dashboard-section-title">Status Breakdown</div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                @foreach($statusCounts as $status => $count)
                    @php
                        $percentage = $totalIssues > 0 ? round(($count / $totalIssues) * 100) : 0;
                        $statusColor = $status === 'open' ? '#3b82f6' : ($status === 'in_progress' ? '#f59e0b' : '#10b981');
                    @endphp
                    <div style="background: var(--trackit-surface); border: 1px solid var(--trackit-border); border-radius: 14px; padding: 20px; transition: all 200ms ease;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <div style="font-size: 12px; font-weight: 700; color: var(--trackit-muted); text-transform: uppercase; letter-spacing: 0.06em;">
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </div>
                            <div style="font-size: 14px; font-weight: 700; color: var(--trackit-text);">{{ $count }}</div>
                        </div>
                        <div style="background: var(--trackit-border); height: 6px; border-radius: 999px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $percentage }}%; background: {{ $statusColor }}; border-radius: 999px; transition: width 600ms ease;"></div>
                        </div>
                        <div style="margin-top: 8px; font-size: 12px; color: var(--trackit-muted);">{{ $percentage }}%</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="dashboard-section" style="padding: 0 32px; margin-bottom: 0;">
            <div class="dashboard-section-title">Priority Mix</div>
            <div class="dashboard-list">
                @foreach($priorityCounts as $priority => $count)
                    @php
                        $priorityRate = $priorityTotal > 0 ? round(($count / $priorityTotal) * 100) : 0;
                        $priorityColor = $priority === 'high' ? '#ef4444' : ($priority === 'medium' ? '#f59e0b' : '#64748b');
                    @endphp
                    <div class="dashboard-list-item">
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: var(--trackit-text); font-size: 14px; margin-bottom: 8px;">
                                {{ ucfirst($priority) }}
                            </div>
                            <div style="background: var(--trackit-border); height: 6px; border-radius: 999px; overflow: hidden;">
                                <div style="height: 100%; width: {{ $priorityTotal > 0 ? max($priorityRate, 6) : 0 }}%; background: {{ $priorityColor }}; border-radius: 999px; transition: width 600ms ease;"></div>
                            </div>
                        </div>
                        <div style="text-align: right; white-space: nowrap; margin-left: 16px;">
                            <div style="font-weight: 700; color: var(--trackit-text); font-size: 14px;">{{ $count }}</div>
                            <div style="font-size: 12px; color: var(--trackit-muted);">{{ $priorityRate }}%</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const counters = document.querySelectorAll('.counter');
                if (!counters.length) return;

                const animate = (el) => {
                    const target = Number(el.dataset.target || 0);
                    const duration = 700;
                    const start = performance.now();

                    const tick = (now) => {
                        const progress = Math.min((now - start) / duration, 1);
                        el.textContent = Math.floor(target * progress).toString();

                        if (progress < 1) {
                            requestAnimationFrame(tick);
                        } else {
                            el.textContent = target.toString();
                        }
                    };

                    requestAnimationFrame(tick);
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            animate(entry.target);
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.35 });

                counters.forEach((counter) => observer.observe(counter));
            })();
        </script>
    @endpush
@endsection
