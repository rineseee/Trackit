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

    $dueTone = function ($issue) {
        if (!$issue->due_date) {
            return ['icon' => 'bi-calendar3', 'class' => 'due-upcoming', 'label' => 'No due date'];
        }

        $daysLeft = now()->startOfDay()->diffInDays($issue->due_date->copy()->startOfDay(), false);

        if ($daysLeft < 0) {
            return [
                'icon' => 'bi-exclamation-triangle-fill',
                'class' => 'due-overdue',
                'label' => abs($daysLeft) . ' days overdue',
            ];
        }

        if ($daysLeft === 0) {
            return [
                'icon' => 'bi-bell-fill',
                'class' => 'due-today',
                'label' => 'Due today',
            ];
        }

        return [
            'icon' => 'bi-calendar-check',
            'class' => 'due-upcoming',
            'label' => 'Due in ' . $daysLeft . ' days',
        ];
    };

    $avatarInitials = function ($name) {
        return collect(explode(' ', $name))
            ->filter()
            ->map(fn($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('');
    };
@endphp

<div class="row g-3 g-xl-4">
    @forelse ($issues as $issue)
        @php
            $dueState = $dueTone($issue);
        @endphp
        <div class="col-12">
            <article class="issue-card p-4">
                <div class="d-flex flex-column flex-xl-row justify-content-between gap-4">
                    <div class="flex-grow-1">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            <span class="badge-soft {{ $statusTone[$issue->status] ?? 'status-open' }}">
                                <i class="bi bi-circle-fill" style="font-size: .55rem;"></i>
                                {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                            </span>
                            <span class="badge-soft {{ $priorityTone[$issue->priority] ?? 'priority-low' }}">
                                <i class="bi bi-flag-fill" style="font-size: .55rem;"></i>
                                {{ ucfirst($issue->priority) }}
                            </span>
                            <span class="badge-soft project-pill">
                                <i class="bi bi-folder2"></i>
                                {{ $issue->project->name }}
                            </span>
                            <span class="due-indicator {{ $dueState['class'] }}">
                                <i class="bi {{ $dueState['icon'] }}"></i>
                                {{ $dueState['label'] }}
                            </span>
                        </div>

                        <div class="d-flex align-items-start justify-content-between gap-3">
                            <div class="min-w-0">
                                <h3 class="h5 fw-bold text-dark mb-2">
                                    <a href="{{ route('issues.show', $issue) }}"
                                        class="text-decoration-none text-dark stretched-link">
                                        {{ $issue->title }}
                                    </a>
                                </h3>
                                <p class="text-secondary mb-0 lh-lg">
                                    {{ $issue->description ?: 'No description yet.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column align-items-start align-items-xl-end gap-3">
                        <div class="d-flex gap-2">
                            <a href="{{ route('issues.show', $issue) }}" class="btn btn-outline-primary btn-sm px-3">
                                <i class="bi bi-eye me-1"></i>View
                            </a>
                            @auth
                                <a href="{{ route('issues.edit', $issue) }}" class="btn btn-outline-secondary btn-sm px-3">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </a>
                            @endauth
                        </div>

                        <div class="d-flex flex-column align-items-start align-items-xl-end gap-2">
                            <div class="small text-secondary fw-semibold text-uppercase">Assignees</div>
                            <div class="avatar-stack" aria-label="Assigned members">
                                @forelse ($issue->members->take(3) as $member)
                                    <span class="avatar" title="{{ $member->name }}">
                                        {{ $avatarInitials($member->name) }}
                                    </span>
                                @empty
                                    <span class="text-secondary small">Unassigned</span>
                                @endforelse
                                @if ($issue->members->count() > 3)
                                    <span class="avatar more" title="More assignees">
                                        +{{ $issue->members->count() - 3 }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mt-4 pt-3 border-top">
                    <div class="d-flex flex-wrap gap-2">
                        @forelse ($issue->tags as $tag)
                            <span class="badge rounded-pill text-bg-light border px-3 py-2">
                                <span class="me-1"
                                    style="display:inline-block;width:.55rem;height:.55rem;border-radius:999px;background-color: {{ $tag->color ?: '#94a3b8' }};"></span>
                                {{ $tag->name }}
                            </span>
                        @empty
                            <span class="text-secondary small">No tags attached.</span>
                        @endforelse
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-3 text-secondary small">
                        <span class="d-inline-flex align-items-center gap-2">
                            <i class="bi bi-chat-dots"></i>
                            {{ $issue->comments_count }} comments
                        </span>
                        <span class="d-inline-flex align-items-center gap-2">
                            <i class="bi bi-clock"></i>
                            Updated {{ $issue->updated_at?->diffForHumans() }}
                        </span>
                        <span class="d-inline-flex align-items-center gap-2">
                            <i class="bi bi-calendar3"></i>
                            {{ $issue->due_date ? $issue->due_date->format('M j, Y') : 'No due date' }}
                        </span>
                    </div>
                </div>
            </article>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state p-5 text-center">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle bg-light"
                    style="width: 64px; height: 64px;">
                    <i class="bi bi-inbox fs-3 text-secondary"></i>
                </div>
                <h3 class="h5 fw-bold text-dark mb-2">No issues match the current filters</h3>
                <p class="text-secondary mb-0">Try a broader search or clear the active filters to see more results.</p>
            </div>
        </div>
    @endforelse
</div>