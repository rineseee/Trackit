@php
    $statusColor = [
        'open' => '#dc2626',
        'in_progress' => '#d97706',
        'closed' => '#059669',
    ];

    $priorityColor = [
        'high' => '#dc2626',
        'medium' => '#d97706',
        'low' => '#6366f1',
    ];
@endphp

<div class="row g-2">
    @forelse ($issues as $issue)
        <div class="col-12">
            <div class="issue-card p-3" style="background: var(--trackit-surface); border: 1px solid var(--trackit-border); border-radius: 8px; transition: all 150ms ease; cursor: pointer;" onclick="window.location.href='{{ route('issues.show', $issue) }}';">
                <div class="d-flex align-items-start justify-content-between gap-3">
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
                            <span style="display: inline-flex; align-items: center; width: 8px; height: 8px; border-radius: 999px; background-color: {{ $statusColor[$issue->status] ?? '#6b7280' }}; flex-shrink: 0;"></span>
                            <span style="font-size: 11px; font-weight: 700; color: var(--trackit-muted); text-transform: uppercase;">
                                #{{ $issue->issue_number }}
                            </span>
                            <span style="font-size: 11px; color: var(--trackit-muted);">
                                {{ $issue->project->name }}
                            </span>
                        </div>
                        <h3 style="margin: 0 0 4px; font-size: 14px; font-weight: 600; color: var(--trackit-text);">
                            <a href="{{ route('issues.show', $issue) }}" style="color: var(--trackit-text); text-decoration: none;" onclick="event.stopPropagation();">
                                {{ \Illuminate\Support\Str::limit($issue->title, 60) }}
                            </a>
                        </h3>
                        <p style="margin: 0; font-size: 12px; color: var(--trackit-muted); line-height: 1.4;">
                            {{ \Illuminate\Support\Str::limit($issue->description, 80) }}
                        </p>
                    </div>
                    <div style="display: flex; align-items: center; gap: 6px; flex-shrink: 0;">
                        <span style="display: inline-block; padding: 3px 8px; background: rgba(99, 102, 241, 0.1); border-radius: 4px; font-size: 11px; font-weight: 600; color: #2563eb;">
                            {{ ucfirst($issue->priority) }}
                        </span>
                        <span style="display: inline-block; padding: 3px 8px; background: var(--trackit-surface-soft); border-radius: 4px; font-size: 11px; color: var(--trackit-muted);">
                            {{ $issue->due_date ? $issue->due_date->format('M d') : '—' }}
                        </span>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; margin-top: 8px; padding-top: 8px; border-top: 1px solid var(--trackit-border); font-size: 11px; color: var(--trackit-muted);">
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <i class="bi bi-tag" style="font-size: 12px;"></i>
                        {{ $issue->tags->count() }}
                    </div>
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <i class="bi bi-chat-dots" style="font-size: 12px;"></i>
                        {{ $issue->comments_count }}
                    </div>
                    <div style="display: flex; align-items: center; gap: 4px; margin-left: auto;">
                        {{ $issue->updated_at?->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div style="text-align: center; padding: 48px 24px; color: var(--trackit-muted);">
                <i class="bi bi-inbox" style="font-size: 32px; display: block; margin-bottom: 12px; opacity: 0.5;"></i>
                <h3 style="margin: 0 0 6px; font-size: 14px; font-weight: 600; color: var(--trackit-text);">No issues found</h3>
                <p style="margin: 0; font-size: 12px;">Try adjusting your filters</p>
            </div>
        </div>
    @endforelse
</div>