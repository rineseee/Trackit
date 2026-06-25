<x-helpdesk-layout>
    <!-- Page Header -->
    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Welcome back! Here's your support desk overview.</p>
    </div>

    <!-- Statistics Grid -->
    <div class="stat-grid">
        <!-- Total Issues -->
        <div class="card stat-card stat-primary">
            <div class="stat-icon primary">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="stat-label">Total Issues</div>
            <div class="stat-value counter" data-target="{{ $totalIssues }}">0</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> {{ $issuesTrendPercent }}% this month
            </div>
        </div>

        <!-- Open Issues -->
        <div class="card stat-card stat-danger">
            <div class="stat-icon danger">
                <i class="fas fa-circle-exclamation"></i>
            </div>
            <div class="stat-label">Open Issues</div>
            <div class="stat-value counter" data-target="{{ $openIssues }}">0</div>
            <div class="stat-change">
                <i class="fas fa-clock"></i> Need attention
            </div>
        </div>

        <!-- In Progress -->
        <div class="card stat-card stat-warning">
            <div class="stat-icon warning">
                <i class="fas fa-spinner"></i>
            </div>
            <div class="stat-label">In Progress</div>
            <div class="stat-value counter" data-target="{{ $inProgressIssues }}">0</div>
            <div class="stat-change">
                <i class="fas fa-hourglass-half"></i> Being handled
            </div>
        </div>

        <!-- Closed Issues -->
        <div class="card stat-card stat-success">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-label">Closed Issues</div>
            <div class="stat-value counter" data-target="{{ $closedIssues }}">0</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i> {{ $resolutionRate }}% resolution
            </div>
        </div>

        <!-- High Priority -->
        <div class="card stat-card stat-danger">
            <div class="stat-icon danger">
                <i class="fas fa-fire"></i>
            </div>
            <div class="stat-label">High Priority</div>
            <div class="stat-value counter" data-target="{{ $highPriorityIssues }}">0</div>
            <div class="stat-change">
                <i class="fas fa-alert-triangle"></i> Critical
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Status Distribution -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Issues by Status</h6>
                    <canvas id="statusChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Priority Distribution -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Issues by Priority</h6>
                    <canvas id="priorityChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Trends -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Created per Month</h6>
                    <canvas id="trendChart" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Issues -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <h6 class="card-title mb-0">Recent Issues</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Assigned</th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentIssues as $issue)
                                <tr>
                                    <td>#{{ $issue->id }}</td>
                                    <td>
                                        <a href="{{ route('issues.show', $issue) }}" class="text-decoration-none">
                                            {{ Str::limit($issue->title, 40) }}
                                        </a>
                                    </td>
                                    <td>{{ $issue->project->name }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $issue->status }}">
                                            {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="priority-badge priority-{{ $issue->priority }}">
                                            {{ ucfirst($issue->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($issue->assignedTo)
                                            <span class="badge badge-primary">{{ $issue->assignedTo->name }}</span>
                                        @else
                                            <span class="text-muted">Unassigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $issue->updated_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        No issues yet. <a href="{{ route('issues.create') }}">Create one</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
    // Animate counters
    document.querySelectorAll('.counter').forEach(element => {
        const target = parseInt(element.getAttribute('data-target'));
        let current = 0;
        const increment = Math.ceil(target / 30);

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = current.toLocaleString();
        }, 30);
    });

    // Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Open', 'In Progress', 'Closed'],
            datasets: [{
                data: [{{ $openIssues }}, {{ $inProgressIssues }}, {{ $closedIssues }}],
                backgroundColor: ['#ef4444', '#f59e0b', '#10b981'],
                borderColor: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#1e293b' : '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Priority Chart
    const priorityCtx = document.getElementById('priorityChart').getContext('2d');
    new Chart(priorityCtx, {
        type: 'pie',
        data: {
            labels: ['Low', 'Medium', 'High'],
            datasets: [{
                data: [{{ $lowPriorityIssues }}, {{ $mediumPriorityIssues }}, {{ $highPriorityIssues }}],
                backgroundColor: ['#64748b', '#2563eb', '#ef4444'],
                borderColor: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#1e293b' : '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($monthlyTrend)) !!},
            datasets: [{
                label: 'Issues Created',
                data: {!! json_encode(array_values($monthlyTrend)) !!},
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderColor: '#2563eb',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
</script>
@endpush
</x-helpdesk-layout>
