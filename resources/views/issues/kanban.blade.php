@extends('layouts.app')

@section('title', 'Kanban Board')

@section('content')
    @php
        $statuses = $statuses ?? \App\Models\Issue::STATUSES;
        $issuesByStatus = $issuesByStatus ?? $issues ?? collect();

        $statusMeta = [
            'open' => [
                'label' => 'Open',
                'description' => 'New and unassigned work',
                'icon' => 'bi-exclamation-circle',
                'accent' => 'danger',
                'soft' => 'bg-danger-subtle text-danger-emphasis',
                'border' => 'border-danger-subtle',
            ],
            'in_progress' => [
                'label' => 'In Progress',
                'description' => 'Work actively being handled',
                'icon' => 'bi-arrow-repeat',
                'accent' => 'warning',
                'soft' => 'bg-warning-subtle text-warning-emphasis',
                'border' => 'border-warning-subtle',
            ],
            'closed' => [
                'label' => 'Closed',
                'description' => 'Resolved and completed',
                'icon' => 'bi-check2-circle',
                'accent' => 'success',
                'soft' => 'bg-success-subtle text-success-emphasis',
                'border' => 'border-success-subtle',
            ],
        ];

        $priorityMeta = [
            'high' => [
                'label' => 'High',
                'class' => 'kanban-priority-high',
                'icon' => 'bi-fire',
            ],
            'medium' => [
                'label' => 'Medium',
                'class' => 'kanban-priority-medium',
                'icon' => 'bi-lightning-charge',
            ],
            'low' => [
                'label' => 'Low',
                'class' => 'kanban-priority-low',
                'icon' => 'bi-dash-circle',
            ],
        ];

        $allIssues = collect($issuesByStatus)->flatten(1);
        $totalIssues = $allIssues->count();
        $openIssues = collect(data_get($issuesByStatus, 'open', []))->count();
        $inProgressIssues = collect(data_get($issuesByStatus, 'in_progress', []))->count();
        $closedIssues = collect(data_get($issuesByStatus, 'closed', []))->count();
    @endphp

    <style>
        .kanban-page {
            --kanban-card-radius: 0.8rem;
            --kanban-column-radius: 1rem;
            --kanban-surface: rgba(255, 255, 255, 0.92);
        }

        .kanban-hero {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(148, 163, 184, 0.12);
            border-radius: 1rem;
            background:
                linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
            padding: 2rem;
        }

        .kanban-hero::after {
            content: none;
        }

        .kanban-stat {
            border: 1px solid rgba(148, 163, 184, 0.12);
            border-radius: 0.8rem;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);
            padding: 1rem;
        }

        .kanban-column {
            position: relative;
            border-radius: var(--kanban-column-radius);
            border: 1px solid rgba(148, 163, 184, 0.12);
            background: var(--kanban-surface);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.03);
            min-height: 60vh;
            display: flex;
            flex-direction: column;
        }

        .kanban-column.open::before,
        .kanban-column.in_progress::before,
        .kanban-column.closed::before {
            content: "";
            position: absolute;
            inset: 0 0 auto 0;
            height: 3px;
            border-radius: var(--kanban-column-radius) var(--kanban-column-radius) 0 0;
        }

        .kanban-column.open::before {
            background: #fca5a5;
        }

        .kanban-column.in_progress::before {
            background: #fed7aa;
        }

        .kanban-column.closed::before {
            background: #a7f3d0;
        }

        .kanban-column-header {
            padding: 1rem 1rem 0.75rem;
        }

        .kanban-dropzone {
            flex: 1;
            min-height: 15rem;
            padding: 0 0.75rem 0.75rem;
        }

        .kanban-card {
            border: 1px solid rgba(148, 163, 184, 0.12);
            border-radius: var(--kanban-card-radius);
            background: #fff;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
            transition: transform 150ms ease, box-shadow 150ms ease, border-color 150ms ease;
        }

        .kanban-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06);
            border-color: rgba(148, 163, 184, 0.3);
        }

        .kanban-card.is-updating {
            opacity: 0.7;
            pointer-events: none;
        }

        .kanban-card + .kanban-card {
            margin-top: 1rem;
        }

        .kanban-drag-handle {
            cursor: grab;
            color: #94a3b8;
            line-height: 1;
            padding-top: 0.15rem;
            user-select: none;
        }

        .kanban-drag-handle:active {
            cursor: grabbing;
        }

        .kanban-title {
            font-size: 0.98rem;
            font-weight: 700;
            line-height: 1.35;
            color: #0f172a;
            text-decoration: none;
        }

        .kanban-title:hover {
            color: #000000;
        }

        .kanban-meta {
            color: #94a3b8;
            font-size: 0.82rem;
        }

        .kanban-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.38rem 0.6rem;
            border-radius: 999px;
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
            font-size: 0.74rem;
            font-weight: 600;
            line-height: 1;
        }

        .kanban-avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
            margin-left: -0.45rem;
            background: linear-gradient(135deg, #374151, #4b5563);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 700;
            box-shadow: 0 4px 8px rgba(15, 23, 42, 0.08);
        }

        .kanban-avatar:first-child {
            margin-left: 0;
        }

        .kanban-empty {
            border: 1px dashed rgba(148, 163, 184, 0.35);
            border-radius: 1rem;
            background: rgba(248, 250, 252, 0.7);
            min-height: 13rem;
        }

        .kanban-ghost {
            opacity: 0.45;
            transform: scale(0.98);
        }

        .kanban-chosen {
            box-shadow: 0 16px 34px rgba(15, 23, 42, 0.12) !important;
        }

        .kanban-priority-high {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.7rem;
            border-radius: 0.5rem;
            background: #fee2e2;
            color: #991b1b;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .kanban-priority-medium {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.7rem;
            border-radius: 0.5rem;
            background: #fef3c7;
            color: #92400e;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .kanban-priority-low {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.7rem;
            border-radius: 0.5rem;
            background: #e0e7ff;
            color: #3730a3;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .toast-container .toast {
            min-width: 280px;
        }

        @media (max-width: 991.98px) {
            .kanban-column {
                min-height: auto;
            }
        }
    </style>

    <div class="kanban-page">
        <div class="kanban-hero p-4 p-lg-5 mb-4">
            <div class="position-relative">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 rounded-pill bg-white px-3 py-2 shadow-sm border border-light-subtle mb-3">
                            <i class="bi bi-kanban text-primary"></i>
                            <span class="small fw-semibold text-secondary">Trackit</span>
                        </div>
                        <h1 class="display-6 fw-bold mb-2">Kanban Board</h1>
                        <p class="text-secondary mb-0">
                            Drag issues between columns to update the status without a page refresh.
                        </p>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('issues.create') }}" class="btn btn-primary btn-lg shadow-sm">
                            <i class="bi bi-plus-lg me-2"></i>Create Issue
                        </a>
                        <a href="{{ route('issues.index') }}" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-list-ul me-2"></i>Issue List
                        </a>
                    </div>
                </div>

                <div class="row g-3 mt-4">
                    <div class="col-6 col-xl-3">
                        <div class="kanban-stat p-3 h-100">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="small text-secondary">Total Issues</div>
                                    <div class="h3 fw-bold mb-0">{{ $totalIssues }}</div>
                                </div>
                                <span class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 3rem; height: 3rem;">
                                    <i class="bi bi-folder2-open"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="kanban-stat p-3 h-100">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="small text-secondary">Open Issues</div>
                                    <div class="h3 fw-bold mb-0">{{ $openIssues }}</div>
                                </div>
                                <span class="rounded-circle bg-danger-subtle text-danger d-inline-flex align-items-center justify-content-center" style="width: 3rem; height: 3rem;">
                                    <i class="bi bi-exclamation-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="kanban-stat p-3 h-100">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="small text-secondary">In Progress</div>
                                    <div class="h3 fw-bold mb-0">{{ $inProgressIssues }}</div>
                                </div>
                                <span class="rounded-circle bg-warning-subtle text-warning d-inline-flex align-items-center justify-content-center" style="width: 3rem; height: 3rem;">
                                    <i class="bi bi-arrow-repeat"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-xl-3">
                        <div class="kanban-stat p-3 h-100">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="small text-secondary">Closed Issues</div>
                                    <div class="h3 fw-bold mb-0">{{ $closedIssues }}</div>
                                </div>
                                <span class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center" style="width: 3rem; height: 3rem;">
                                    <i class="bi bi-check2-circle"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @foreach ($statuses as $status)
                @php
                    $meta = $statusMeta[$status] ?? [
                        'label' => \Illuminate\Support\Str::headline($status),
                        'description' => 'Issues in this lane',
                        'icon' => 'bi-inboxes',
                        'accent' => 'secondary',
                        'soft' => 'bg-secondary-subtle text-secondary-emphasis',
                        'border' => 'border-secondary-subtle',
                    ];
                    $columnIssues = collect(data_get($issuesByStatus, $status, []));
                    $columnCount = $columnIssues->count();
                @endphp

                <div class="col-12 col-lg-4">
                    <div class="kanban-column {{ $status }}" data-kanban-column="{{ $status }}">
                        <div class="kanban-column-header">
                            <div class="d-flex align-items-start justify-content-between gap-3">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge rounded-pill {{ $meta['soft'] }} border {{ $meta['border'] }} px-3 py-2">
                                            <i class="bi {{ $meta['icon'] }} me-1"></i>
                                            {{ $meta['label'] }}
                                        </span>
                                    </div>
                                    <p class="kanban-meta mb-0">{{ $meta['description'] }}</p>
                                </div>

                                <span class="badge rounded-pill bg-white text-dark border shadow-sm px-3 py-2" data-column-count>
                                    {{ $columnCount }}
                                </span>
                            </div>
                        </div>

                        <div class="kanban-dropzone" data-column-body>
                            @forelse ($columnIssues as $issue)
                                @php
                                    $priority = $priorityMeta[$issue->priority] ?? $priorityMeta['low'];
                                    $memberCount = collect($issue->members ?? [])->count();
                                    $tagCount = collect($issue->tags ?? [])->count();
                                    $dueDate = $issue->due_date;
                                    $isOverdue = $dueDate && $dueDate->isPast() && ! $dueDate->isToday() && $issue->status !== 'closed';
                                    $isDueToday = $dueDate && $dueDate->isToday();
                                @endphp

                                <article
                                    class="kanban-card card mb-3"
                                    data-issue-card
                                    data-issue-id="{{ $issue->id }}"
                                    data-issue-status="{{ $issue->status }}"
                                    data-update-url="{{ route('issues.kanban.status', $issue) }}"
                                >
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start gap-2 mb-3">
                                            <span class="kanban-drag-handle" title="Drag to move">
                                                <i class="bi bi-grip-vertical fs-5"></i>
                                            </span>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="d-flex align-items-center justify-content-between gap-3 mb-2">
                                                    <a href="{{ route('issues.show', $issue) }}" class="kanban-title stretched-link">
                                                        {{ $issue->title }}
                                                    </a>
                                                </div>

                                                @if ($issue->project)
                                                    <div class="kanban-meta d-flex align-items-center gap-1 mb-2">
                                                        <i class="bi bi-diagram-3"></i>
                                                        <span>{{ $issue->project->name }}</span>
                                                    </div>
                                                @endif

                                                <div class="d-flex flex-wrap gap-2">
                                                    <span class="badge text-bg-{{ $meta['accent'] }}">
                                                        <i class="bi {{ $meta['icon'] }} me-1"></i>{{ $meta['label'] }}
                                                    </span>
                                                    <span class="badge {{ $priority['class'] }}">
                                                        <i class="bi {{ $priority['icon'] }} me-1"></i>{{ $priority['label'] }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($tagCount > 0)
                                            <div class="d-flex flex-wrap gap-2 mb-3">
                                                @foreach (collect($issue->tags)->take(3) as $tag)
                                                    <span class="kanban-tag">
                                                        <span class="rounded-circle d-inline-block" style="width: 0.55rem; height: 0.55rem; background-color: {{ $tag->color ?? '#94a3b8' }};"></span>
                                                        {{ $tag->name }}
                                                    </span>
                                                @endforeach
                                                @if ($tagCount > 3)
                                                    <span class="kanban-tag">+{{ $tagCount - 3 }} more</span>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="d-flex align-items-center justify-content-between gap-3">
                                            <div class="d-flex align-items-center">
                                                @if ($memberCount > 0)
                                                    <div class="d-flex align-items-center" title="Assigned members">
                                                        @foreach (collect($issue->members)->take(4) as $member)
                                                            @php
                                                                $initials = collect(explode(' ', $member->name))
                                                                    ->filter()
                                                                    ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
                                                                    ->take(2)
                                                                    ->implode('');
                                                            @endphp
                                                            <span class="kanban-avatar" title="{{ $member->name }}">
                                                                {{ $initials ?: 'U' }}
                                                            </span>
                                                        @endforeach
                                                        @if ($memberCount > 4)
                                                            <span class="kanban-avatar bg-secondary" title="{{ $memberCount - 4 }} more members">
                                                                +{{ $memberCount - 4 }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="kanban-meta d-inline-flex align-items-center gap-1">
                                                        <i class="bi bi-person-dash"></i>
                                                        Unassigned
                                                    </span>
                                                @endif
                                            </div>

                                            @if ($dueDate)
                                                <span class="badge rounded-pill {{ $isOverdue ? 'text-bg-danger' : ($isDueToday ? 'text-bg-warning' : 'text-bg-light') }} border">
                                                    <i class="bi bi-calendar-event me-1"></i>
                                                    {{ $isOverdue ? 'Overdue' : ($isDueToday ? 'Due today' : 'Due ' . $dueDate->format('M d')) }}
                                                </span>
                                            @else
                                                <span class="badge rounded-pill text-bg-light border">
                                                    <i class="bi bi-calendar-x me-1"></i>No due date
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @empty
                                <div class="kanban-empty d-flex flex-column align-items-center justify-content-center text-center px-4 py-5" data-empty-state>
                                    <div class="rounded-circle bg-white shadow-sm border d-inline-flex align-items-center justify-content-center mb-3" style="width: 3.5rem; height: 3.5rem;">
                                        <i class="bi {{ $meta['icon'] }} text-{{ $meta['accent'] }} fs-4"></i>
                                    </div>
                                    <h3 class="h6 fw-semibold mb-1">No issues in {{ $meta['label'] }}</h3>
                                    <p class="text-secondary small mb-0">
                                        Drag an issue card here to move it into this stage.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex flex-wrap align-items-center justify-content-between gap-3 text-secondary small">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-info-circle"></i>
                <span>Drag and drop updates status instantly. Reorder within a column is visual only.</span>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <span class="badge text-bg-danger">Open = Red</span>
                <span class="badge text-bg-warning">In Progress = Orange</span>
                <span class="badge text-bg-success">Closed = Green</span>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3" data-toast-container style="z-index: 1080;"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const toastContainer = document.querySelector('[data-toast-container]');
            const columns = Array.from(document.querySelectorAll('[data-kanban-column]'));

            function showToast(message, variant = 'success') {
                if (!toastContainer) {
                    return;
                }

                const toastEl = document.createElement('div');
                toastEl.className = `toast align-items-center text-bg-${variant} border-0`;
                toastEl.setAttribute('role', 'alert');
                toastEl.setAttribute('aria-live', 'assertive');
                toastEl.setAttribute('aria-atomic', 'true');

                const toastBodyWrapper = document.createElement('div');
                toastBodyWrapper.className = 'd-flex';

                const toastBody = document.createElement('div');
                toastBody.className = 'toast-body fw-semibold';
                toastBody.textContent = message;

                const closeButton = document.createElement('button');
                closeButton.type = 'button';
                closeButton.className = 'btn-close btn-close-white me-2 m-auto';
                closeButton.setAttribute('data-bs-dismiss', 'toast');
                closeButton.setAttribute('aria-label', 'Close');

                toastBodyWrapper.appendChild(toastBody);
                toastBodyWrapper.appendChild(closeButton);
                toastEl.appendChild(toastBodyWrapper);

                toastContainer.appendChild(toastEl);
                const toast = new bootstrap.Toast(toastEl, { delay: 3200 });

                toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
                toast.show();
            }

            function refreshEmptyState(column) {
                const body = column.querySelector('[data-column-body]');
                const emptyState = column.querySelector('[data-empty-state]');
                const cards = body.querySelectorAll('[data-issue-card]');

                if (emptyState) {
                    emptyState.classList.toggle('d-none', cards.length > 0);
                } else if (cards.length === 0) {
                    const meta = column.dataset.kanbanColumn;
                    const emptyWrapper = document.createElement('div');
                    emptyWrapper.className = 'kanban-empty d-flex flex-column align-items-center justify-content-center text-center px-4 py-5';
                    emptyWrapper.setAttribute('data-empty-state', '');
                    emptyWrapper.innerHTML = `
                        <div class="rounded-circle bg-white shadow-sm border d-inline-flex align-items-center justify-content-center mb-3" style="width: 3.5rem; height: 3.5rem;">
                            <i class="bi bi-inboxes text-secondary fs-4"></i>
                        </div>
                        <h3 class="h6 fw-semibold mb-1">No issues here</h3>
                        <p class="text-secondary small mb-0">Drag an issue card here to move it into this stage.</p>
                    `;
                    body.appendChild(emptyWrapper);
                }
            }

            function refreshCounts() {
                columns.forEach((column) => {
                    const body = column.querySelector('[data-column-body]');
                    const count = body.querySelectorAll('[data-issue-card]').length;
                    const badge = column.querySelector('[data-column-count]');

                    if (badge) {
                        badge.textContent = count;
                    }
                });
            }

            function revertCard(card, sourceColumn, oldIndex) {
                const body = sourceColumn.querySelector('[data-column-body]');
                const emptyState = body.querySelector('[data-empty-state]');

                if (emptyState) {
                    emptyState.remove();
                }

                const referenceNode = body.querySelectorAll('[data-issue-card]')[oldIndex] || null;
                body.insertBefore(card, referenceNode);
                refreshEmptyState(sourceColumn);
                refreshCounts();
            }

            columns.forEach((column) => {
                const body = column.querySelector('[data-column-body]');

                Sortable.create(body, {
                    group: { name: 'issues', pull: true, put: true },
                    animation: 180,
                    ghostClass: 'kanban-ghost',
                    chosenClass: 'kanban-chosen',
                    dragClass: 'kanban-dragging',
                    handle: '.kanban-drag-handle',
                    onStart: () => {
                        body.classList.add('kanban-drag-active');
                    },
                    onEnd: async (evt) => {
                        body.classList.remove('kanban-drag-active');

                        const card = evt.item;
                        const sourceColumn = evt.from.closest('[data-kanban-column]');
                        const targetColumn = evt.to.closest('[data-kanban-column]');
                        const oldStatus = card.dataset.issueStatus;
                        const newStatus = targetColumn?.dataset.kanbanColumn || oldStatus;

                        refreshEmptyState(sourceColumn);
                        refreshEmptyState(targetColumn);
                        refreshCounts();

                        if (oldStatus === newStatus) {
                            return;
                        }

                        card.classList.add('is-updating');

                        try {
                            const response = await fetch(card.dataset.updateUrl, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                },
                                body: JSON.stringify({ status: newStatus }),
                            });

                            const payload = await response.json();

                            if (!response.ok || !payload.success) {
                                throw new Error(payload.message || 'Unable to update issue status.');
                            }

                            card.dataset.issueStatus = newStatus;
                            refreshEmptyState(sourceColumn);
                            refreshEmptyState(targetColumn);
                            refreshCounts();
                            showToast(payload.message || 'Issue updated successfully.', 'success');
                        } catch (error) {
                            revertCard(card, sourceColumn, evt.oldIndex);
                            showToast(error.message || 'Unable to update issue status.', 'danger');
                        } finally {
                            card.classList.remove('is-updating');
                        }
                    },
                });
            });

            refreshCounts();
        });
    </script>
@endsection
