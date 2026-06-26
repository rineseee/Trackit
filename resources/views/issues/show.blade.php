@extends('layouts.app')

@section('title', $issue->title)

@push('styles')
    <style>
        .issue-detail-shell {
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --line: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --brand: #2563eb;
            --brand-2: #0ea5e9;
            --shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
        }

        .hero-card,
        .section-card,
        .side-card,
        .modal-content {
            border: 1px solid var(--line);
            border-radius: 22px;
            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .hero-card {
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, 0.08), transparent 28%),
                radial-gradient(circle at bottom left, rgba(14, 165, 233, 0.05), transparent 22%),
                linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            overflow: hidden;
        }

        .hero-title {
            color: var(--text-main);
            letter-spacing: -0.04em;
            font-weight: 800;
        }

        .hero-kicker,
        .chip-pill,
        .status-pill,
        .priority-pill,
        .project-pill,
        .date-pill,
        .muted-pill {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 700;
        }

        .hero-kicker {
            padding: .45rem .8rem;
            background: rgba(37, 99, 235, 0.08);
            color: var(--brand);
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .status-pill {
            padding: .4rem .75rem;
        }

        .status-open {
            background: rgba(239, 68, 68, 0.12);
            color: #dc2626;
        }

        .status-in_progress {
            background: rgba(245, 158, 11, 0.14);
            color: #d97706;
        }

        .status-closed {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
        }

        .priority-pill {
            padding: .4rem .75rem;
        }

        .priority-high {
            background: rgba(220, 38, 38, 0.12);
            color: #b91c1c;
        }

        .priority-medium {
            background: rgba(245, 158, 11, 0.14);
            color: #d97706;
        }

        .priority-low {
            background: rgba(100, 116, 139, 0.12);
            color: #475569;
        }

        .project-pill {
            padding: .4rem .75rem;
            background: rgba(37, 99, 235, 0.08);
            color: #1d4ed8;
        }

        .date-pill {
            padding: .4rem .75rem;
            background: rgba(14, 165, 233, 0.1);
            color: #0284c7;
        }

        .muted-pill {
            padding: .35rem .65rem;
            background: #f1f5f9;
            color: var(--text-muted);
        }

        .section-title {
            color: var(--text-main);
            letter-spacing: -.03em;
            font-weight: 800;
        }

        .detail-label {
            color: var(--text-muted);
            font-size: .78rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .detail-value {
            color: var(--text-main);
            font-weight: 700;
        }

        .timeline {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .comment-bubble {
            display: flex;
            gap: .875rem;
            align-items: flex-start;
        }

        .comment-avatar,
        .member-avatar {
            width: 40px;
            height: 40px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 800;
            font-size: .8rem;
            flex-shrink: 0;
            background: linear-gradient(135deg, #2563eb, #0ea5e9);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.14);
        }

        .comment-body {
            position: relative;
            padding: 1rem 1rem 1rem 1.125rem;
            background: #f8fbff;
            border: 1px solid #dbeafe;
            border-radius: 18px 18px 18px 6px;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04);
            width: 100%;
        }

        .comment-body::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 14px;
            width: 12px;
            height: 12px;
            background: #f8fbff;
            border-left: 1px solid #dbeafe;
            border-bottom: 1px solid #dbeafe;
            transform: rotate(45deg);
        }

        .comment-bubble.meta-right .comment-body {
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .comment-bubble.meta-right .comment-body::before {
            background: #eff6ff;
            border-left-color: #bfdbfe;
            border-bottom-color: #bfdbfe;
        }

        .tag-chip,
        .member-chip {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .45rem .7rem;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 700;
            border: 1px solid #e2e8f0;
            background: #fff;
        }

        .tag-chip .dot {
            width: .55rem;
            height: .55rem;
            border-radius: 999px;
            flex-shrink: 0;
        }

        .tag-action {
            border: 0;
            width: 32px;
            height: 32px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #e2e8f0;
            color: #334155;
            transition: all .2s ease;
        }

        .tag-action:hover {
            background: #cbd5e1;
        }

        .tag-action.attached {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
        }

        .tag-action.detached {
            background: rgba(37, 99, 235, 0.12);
            color: #2563eb;
        }

        .search-field {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #fff;
        }

        .search-field .form-control {
            border: 0;
            box-shadow: none;
        }

        .search-field .form-control:focus {
            box-shadow: none;
        }

        .scroll-area {
            max-height: 420px;
            overflow: auto;
        }

        .sticky-column {
            position: sticky;
            top: 1.25rem;
        }

        .empty-state {
            border: 1px dashed #cbd5e1;
            border-radius: 18px;
            background: linear-gradient(180deg, #ffffff, #f8fafc);
        }

        .toast-container {
            z-index: 1080;
        }

        @media (max-width: 991.98px) {
            .sticky-column {
                position: static;
            }
        }

        /* Dark mode support */
        html[data-theme='dark'] .issue-detail-shell {
            --surface: #1e293b;
            --surface-soft: #0f172a;
            --line: #334155;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --brand: #2563eb;
            --brand-2: #0ea5e9;
            --shadow: 0 16px 40px rgba(0, 0, 0, 0.3);
        }

        html[data-theme='dark'] .text-dark {
            color: #f1f5f9 !important;
        }

        html[data-theme='dark'] .text-secondary {
            color: #94a3b8 !important;
        }

        html[data-theme='dark'] .bg-light {
            background-color: #0f172a !important;
        }

        html[data-theme='dark'] .border {
            border-color: #334155 !important;
        }

        html[data-theme='dark'] .form-control {
            background-color: #0f172a;
            color: #f1f5f9;
            border-color: #334155;
        }

        html[data-theme='dark'] .form-control:focus {
            background-color: #0f172a;
            color: #f1f5f9;
            border-color: #2563eb;
        }

        html[data-theme='dark'] .btn-outline-primary {
            color: #2563eb;
            border-color: #2563eb;
        }

        html[data-theme='dark'] .btn-outline-primary:hover {
            background-color: #2563eb;
            color: #fff;
        }

        html[data-theme='dark'] .btn-outline-secondary {
            color: #94a3b8;
            border-color: #334155;
        }

        html[data-theme='dark'] .btn-outline-secondary:hover {
            background-color: #334155;
            color: #f1f5f9;
        }

        html[data-theme='dark'] .btn-outline-danger {
            color: #ef4444;
            border-color: #ef4444;
        }

        html[data-theme='dark'] .btn-outline-danger:hover {
            background-color: #ef4444;
            color: #fff;
        }

        html[data-theme='dark'] .modal-content {
            background: #1e293b;
        }

        html[data-theme='dark'] .modal-header {
            border-bottom-color: #334155 !important;
        }

        html[data-theme='dark'] .modal-footer {
            border-top-color: #334155 !important;
        }
    </style>
@endpush

@section('content')
    @php
        $statusClass = 'status-' . $issue->status;
        $priorityClass = 'priority-' . $issue->priority;
        $initials = function (string $name): string {
            return collect(explode(' ', $name))
                ->filter()
                ->map(fn(string $part): string => strtoupper(substr($part, 0, 1)))
                ->take(2)
                ->implode('');
        };
    @endphp

    @php
        $allTagsPayload = $allTags->map(fn($tag) => [
            'id' => $tag->id,
            'name' => $tag->name,
            'color' => $tag->color,
        ])->values();

        $allUsersPayload = $users->map(fn($user) => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'initials' => collect(explode(' ', $user->name))
                ->filter()
                ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                ->take(2)
                ->implode(''),
        ])->values();

        $attachedTagIdsPayload = $issue->tags->pluck('id')->values();
        $attachedMemberIdsPayload = $issue->members->pluck('id')->values();
    @endphp

    <div class="issue-detail-shell container-fluid py-4" data-issue-id="{{ $issue->id }}"
        data-comments-url="{{ route('issues.comments.index', $issue) }}"
        data-comment-store-url="{{ route('issues.comments.store', $issue) }}"
        data-tag-base-url="{{ url('/issues/' . $issue->id . '/tags') }}"
        data-member-base-url="{{ url('/issues/' . $issue->id . '/members') }}"
        data-tag-create-url="{{ route('tags.store') }}" data-users-url="{{ route('issues.show', $issue) }}">
        <div class="hero-card p-4 p-lg-5 mb-4">
            <div class="d-flex flex-column flex-xl-row justify-content-between gap-4">
                <div class="flex-grow-1">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="hero-kicker">
                            <i class="bi bi-journal-text"></i>
                            Issue #{{ $issue->id }}
                        </span>
                        <span class="status-pill {{ $statusClass }}">
                            <i class="bi bi-circle-fill" style="font-size: .55rem;"></i>
                            {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                        </span>
                        <span class="priority-pill {{ $priorityClass }}">
                            <i class="bi bi-flag-fill" style="font-size: .55rem;"></i>
                            {{ ucfirst($issue->priority) }}
                        </span>
                        <span class="project-pill">
                            <i class="bi bi-folder2"></i>
                            {{ $issue->project->name }}
                        </span>
                        <span class="date-pill">
                            <i class="bi bi-calendar3"></i>
                            {{ $issue->due_date ? $issue->due_date->format('M d, Y') : 'No due date' }}
                        </span>
                    </div>

                    <h1 class="hero-title display-6 mb-2">{{ $issue->title }}</h1>
                </div>

                @auth
                    <div class="d-flex flex-wrap gap-2 align-items-start">
                        <a href="{{ route('issues.edit', $issue) }}" class="btn btn-primary px-4">
                            <i class="bi bi-pencil-square me-2"></i>Edit
                        </a>
                        <form method="POST" action="{{ route('issues.destroy', $issue) }}"
                            onsubmit="return confirm('Delete this issue?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger px-4">
                                <i class="bi bi-trash3 me-2"></i>Delete
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="section-card p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h2 class="section-title h5 mb-1">Description</h2>
                            <p class="text-secondary mb-0">Issue context and full details.</p>
                        </div>
                    </div>
                    <div class="rounded-4 bg-light border p-4">
                        <p class="mb-0 text-dark lh-lg whitespace-pre-line">
                            {{ $issue->description ?: 'No description provided.' }}
                        </p>
                    </div>
                </div>

                <div class="section-card p-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4">
                        <div>
                            <h2 class="section-title h5 mb-1">Comments</h2>
                            <p class="text-secondary mb-0">{{ $issue->comments->count() }} message timeline</p>
                        </div>
                        <span class="muted-pill">
                            <i class="bi bi-chat-dots"></i>
                            Chat timeline
                        </span>
                    </div>

                    <div class="timeline" id="commentsTimeline">
                        @forelse ($issue->comments as $comment)
                            @php
                                $rowClass = $loop->odd ? '' : 'meta-right';
                            @endphp
                            <div class="comment-bubble {{ $rowClass }}" data-comment-id="{{ $comment->id }}">
                                <div class="comment-avatar">
                                    {{ $initials($comment->author_name) }}
                                </div>
                                <div class="comment-body">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                        <div class="fw-bold text-dark">{{ $comment->author_name }}</div>
                                        <div class="text-secondary small">{{ $comment->created_at?->diffForHumans() }}</div>
                                    </div>
                                    <p class="mb-0 text-dark lh-lg whitespace-pre-line">{{ $comment->body }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state p-4 text-center">
                                <i class="bi bi-chat-left-text fs-3 text-secondary d-block mb-2"></i>
                                <h3 class="h6 fw-bold text-dark mb-1">No comments yet</h3>
                                <p class="text-secondary mb-0">Start the conversation with the first update.</p>
                            </div>
                        @endforelse
                    </div>

                    @auth
                        <div class="mt-4 pt-4 border-top">
                            <form id="commentForm" class="row g-3">
                                @csrf
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-dark" for="author_name">Your name</label>
                                    <input type="text" id="author_name" name="author_name" class="form-control"
                                        value="{{ auth()->user()->name }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark" for="body">Comment</label>
                                    <textarea id="body" name="body" rows="5" class="form-control"
                                        placeholder="Write an update, ask a question, or leave a handoff note..."
                                        required></textarea>
                                </div>
                                <div class="col-12 d-flex align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-send me-2"></i>Add comment
                                    </button>
                                    <span class="text-secondary small" id="commentStatus"></span>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="mt-4 rounded-4 bg-light border p-4 text-secondary">
                            Log in to add a comment.
                        </div>
                    @endauth
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="sticky-column">
                    <div class="side-card p-4 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h2 class="section-title h5 mb-0">Details</h2>
                            <span class="muted-pill">
                                <i class="bi bi-sliders"></i>
                                At a glance
                            </span>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="rounded-4 bg-light border p-3 h-100">
                                    <div class="detail-label mb-1">Status</div>
                                    <div class="detail-value">{{ ucfirst(str_replace('_', ' ', $issue->status)) }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rounded-4 bg-light border p-3 h-100">
                                    <div class="detail-label mb-1">Priority</div>
                                    <div class="detail-value">{{ ucfirst($issue->priority) }}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="rounded-4 bg-light border p-3">
                                    <div class="detail-label mb-1">Project</div>
                                    <div class="detail-value">{{ $issue->project->name }}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="rounded-4 bg-light border p-3">
                                    <div class="detail-label mb-1">Due Date</div>
                                    <div class="detail-value">
                                        {{ $issue->due_date ? $issue->due_date->format('M d, Y') : 'No due date' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="side-card p-4 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h2 class="section-title h5 mb-0">Tags</h2>
                            <span class="muted-pill" id="tagCount">{{ $issue->tags->count() }} attached</span>
                        </div>

                        <div class="d-flex flex-wrap gap-2" id="tagsList">
                            @forelse ($issue->tags as $tag)
                                <span class="tag-chip" data-tag-chip="{{ $tag->id }}">
                                    <span class="dot" style="background-color: {{ $tag->color ?: '#94a3b8' }}"></span>
                                    {{ $tag->name }}
                                    <button type="button" class="tag-action detached" data-tag-detach="{{ $tag->id }}"
                                        title="Detach tag">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </span>
                            @empty
                                <span class="text-secondary small" id="tagsEmpty">No tags attached yet.</span>
                            @endforelse
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="button" class="btn btn-outline-primary flex-grow-1" data-bs-toggle="modal"
                                data-bs-target="#tagModal">
                                <i class="bi bi-tag me-2"></i>Manage tags
                            </button>
                        </div>
                    </div>

                    <div class="side-card p-4 mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h2 class="section-title h5 mb-0">Assigned Members</h2>
                            <span class="muted-pill" id="memberCount">{{ $issue->members->count() }} assigned</span>
                        </div>

                        <div class="d-flex flex-wrap gap-2" id="membersList">
                            @forelse ($issue->members as $member)
                                <span class="member-chip" data-member-chip="{{ $member->id }}">
                                    <span class="member-avatar" style="width: 30px; height: 30px; font-size: .7rem;">
                                        {{ $initials($member->name) }}
                                    </span>
                                    {{ $member->name }}
                                    <button type="button" class="tag-action detached" data-member-detach="{{ $member->id }}"
                                        title="Remove member">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </span>
                            @empty
                                <span class="text-secondary small" id="membersEmpty">No members assigned yet.</span>
                            @endforelse
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="button" class="btn btn-outline-primary flex-grow-1" data-bs-toggle="modal"
                                data-bs-target="#memberModal">
                                <i class="bi bi-people me-2"></i>Manage members
                            </button>
                        </div>
                    </div>

                    <div class="side-card p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h2 class="section-title h5 mb-0">Activity</h2>
                            <span class="muted-pill">
                                <i class="bi bi-clock-history"></i>
                                Issue meta
                            </span>
                        </div>

                        <div class="d-grid gap-3">
                            <div class="rounded-4 bg-light border p-3">
                                <div class="detail-label mb-1">Created</div>
                                <div class="detail-value">{{ $issue->created_at->format('M d, Y H:i') }}</div>
                            </div>
                            <div class="rounded-4 bg-light border p-3">
                                <div class="detail-label mb-1">Updated</div>
                                <div class="detail-value">{{ $issue->updated_at->format('M d, Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="tagModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-1">Manage Tags</h5>
                            <p class="text-secondary mb-0">Search, attach, detach, or create a new tag without refreshing.
                            </p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="col-md-7">
                                <div class="search-field d-flex align-items-center gap-2 px-3 py-2 mb-3">
                                    <i class="bi bi-search text-secondary"></i>
                                    <input type="text" class="form-control" id="tagSearch" placeholder="Search tags...">
                                </div>

                                <div class="scroll-area d-grid gap-2" id="tagModalList"></div>
                            </div>

                            <div class="col-md-5">
                                <div class="rounded-4 border bg-light p-4 h-100">
                                    <h6 class="fw-bold text-dark mb-2">Create new tag</h6>
                                    <p class="text-secondary small mb-3">Create a tag and attach it immediately.</p>

                                    <form id="createTagForm" class="d-grid gap-3">
                                        @csrf
                                        <div>
                                            <label for="newTagName" class="form-label fw-semibold">Tag name</label>
                                            <input type="text" id="newTagName" class="form-control" placeholder="Backend"
                                                required>
                                        </div>
                                        <div>
                                            <label for="newTagColor" class="form-label fw-semibold">Color</label>
                                            <input type="color" id="newTagColor"
                                                class="form-control form-control-color w-100" value="#2563eb"
                                                title="Choose a color">
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-plus-lg me-2"></i>Create &amp; attach
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="memberModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-1">Manage Members</h5>
                            <p class="text-secondary mb-0">Attach or remove teammates instantly.</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="search-field d-flex align-items-center gap-2 px-3 py-2 mb-3">
                            <i class="bi bi-search text-secondary"></i>
                            <input type="text" class="form-control" id="memberSearch" placeholder="Search members...">
                        </div>
                        <div class="scroll-area d-grid gap-2" id="memberModalList"></div>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="toast-container position-fixed top-0 end-0 p-3"></div>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const issueRoot = document.querySelector('[data-issue-id]');
            if (!issueRoot) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
            const issueId = issueRoot.dataset.issueId;
            const commentsUrl = issueRoot.dataset.commentsUrl;
            const commentStoreUrl = issueRoot.dataset.commentStoreUrl;
            const tagBaseUrl = issueRoot.dataset.tagBaseUrl;
            const memberBaseUrl = issueRoot.dataset.memberBaseUrl;
            const tagCreateUrl = issueRoot.dataset.tagCreateUrl;

            const allTags = @json($allTagsPayload);
            const allUsers = @json($allUsersPayload);
            const attachedTagIds = new Set(@json($attachedTagIdsPayload));
            const attachedMemberIds = new Set(@json($attachedMemberIdsPayload));

            const commentsTimeline = document.getElementById('commentsTimeline');
            const commentForm = document.getElementById('commentForm');
            const commentStatus = document.getElementById('commentStatus');
            const tagsList = document.getElementById('tagsList');
            const tagCount = document.getElementById('tagCount');
            const tagsEmpty = document.getElementById('tagsEmpty');
            const membersList = document.getElementById('membersList');
            const memberCount = document.getElementById('memberCount');
            const membersEmpty = document.getElementById('membersEmpty');
            const tagModal = document.getElementById('tagModal');
            const memberModal = document.getElementById('memberModal');
            const tagSearch = document.getElementById('tagSearch');
            const memberSearch = document.getElementById('memberSearch');
            const tagModalList = document.getElementById('tagModalList');
            const memberModalList = document.getElementById('memberModalList');
            const createTagForm = document.getElementById('createTagForm');
            const newTagName = document.getElementById('newTagName');
            const newTagColor = document.getElementById('newTagColor');
            const toastContainer = document.querySelector('.toast-container');

            const tagModalInstance = new bootstrap.Modal(tagModal);
            const memberModalInstance = new bootstrap.Modal(memberModal);

            const escapeHtml = (value) => String(value)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');

            const initialsFromName = (name) => String(name)
                .split(' ')
                .filter(Boolean)
                .map((part) => part[0]?.toUpperCase() ?? '')
                .slice(0, 2)
                .join('');

            const showToast = (type, message) => {
                if (!toastContainer) return;

                const variant = type === 'success' ? 'success' : 'danger';
                const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
                const toastId = `toast-${Date.now()}`;

                const toastHtml = `
                        <div id="${toastId}" class="toast align-items-center text-bg-${variant} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <i class="bi ${icon} me-2"></i>${escapeHtml(message)}
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    `;

                toastContainer.insertAdjacentHTML('beforeend', toastHtml);
                const toastEl = document.getElementById(toastId);
                const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
                toast.show();
                toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
            };

            const fetchJson = async (url, options = {}) => {
                const response = await fetch(url, {
                    ...options,
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                        ...(options.headers ?? {}),
                    },
                });

                const contentType = response.headers.get('content-type') || '';
                const payload = contentType.includes('application/json')
                    ? await response.json()
                    : await response.text();

                if (!response.ok) {
                    const error = new Error('Request failed');
                    error.payload = payload;
                    throw error;
                }

                return payload;
            };

            const renderTagsInCard = () => {
                const chips = Array.from(tagsList.querySelectorAll('[data-tag-chip]'));
                if (chips.length === 0 && tagsEmpty) {
                    tagsEmpty.classList.remove('d-none');
                } else if (tagsEmpty) {
                    tagsEmpty.classList.add('d-none');
                }

                if (tagCount) {
                    tagCount.textContent = `${chips.length} attached`;
                }
            };

            const renderMembersInCard = () => {
                const chips = Array.from(membersList.querySelectorAll('[data-member-chip]'));
                if (chips.length === 0 && membersEmpty) {
                    membersEmpty.classList.remove('d-none');
                } else if (membersEmpty) {
                    membersEmpty.classList.add('d-none');
                }

                if (memberCount) {
                    memberCount.textContent = `${chips.length} assigned`;
                }
            };

            const renderComment = (comment) => {
                const rowClass = commentsTimeline.children.length % 2 === 0 ? '' : 'meta-right';
                return `
                        <div class="comment-bubble ${rowClass}" data-comment-id="${comment.id}">
                            <div class="comment-avatar">${escapeHtml(initialsFromName(comment.author_name))}</div>
                            <div class="comment-body">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                    <div class="fw-bold text-dark">${escapeHtml(comment.author_name)}</div>
                                    <div class="text-secondary small">just now</div>
                                </div>
                                <p class="mb-0 text-dark lh-lg whitespace-pre-line">${escapeHtml(comment.body)}</p>
                            </div>
                        </div>
                    `;
            };

            const renderTagRow = (tag) => {
                const attached = attachedTagIds.has(Number(tag.id));
                const actionClass = attached ? 'attached' : 'detached';
                const actionIcon = attached ? 'bi-x-lg' : 'bi-plus-lg';
                const actionLabel = attached ? 'Detach' : 'Attach';

                return `
                        <div class="d-flex align-items-center justify-content-between gap-3 rounded-4 border p-3 bg-white" data-tag-row="${tag.id}">
                            <div class="d-flex align-items-center gap-3 min-w-0">
                                <span class="tag-chip border-0 p-0">
                                    <span class="dot" style="background-color: ${escapeHtml(tag.color || '#94a3b8')}"></span>
                                </span>
                                <div class="min-w-0">
                                    <div class="fw-bold text-dark">${escapeHtml(tag.name)}</div>
                                    <div class="text-secondary small">${attached ? 'Attached to this issue' : 'Not attached'}</div>
                                </div>
                            </div>
                            <button type="button" class="tag-action ${actionClass}" data-tag-toggle="${tag.id}" title="${actionLabel} tag">
                                <i class="bi ${actionIcon}"></i>
                            </button>
                        </div>
                    `;
            };

            const renderMemberRow = (user) => {
                const attached = attachedMemberIds.has(Number(user.id));
                const actionClass = attached ? 'attached' : 'detached';
                const actionIcon = attached ? 'bi-x-lg' : 'bi-plus-lg';
                const actionLabel = attached ? 'Remove' : 'Assign';

                return `
                        <div class="d-flex align-items-center justify-content-between gap-3 rounded-4 border p-3 bg-white" data-member-row="${user.id}">
                            <div class="d-flex align-items-center gap-3 min-w-0">
                                <span class="member-avatar" style="width: 36px; height: 36px; font-size: .75rem;">${escapeHtml(user.initials || initialsFromName(user.name))}</span>
                                <div class="min-w-0">
                                    <div class="fw-bold text-dark">${escapeHtml(user.name)}</div>
                                    <div class="text-secondary small text-truncate">${escapeHtml(user.email || '')}</div>
                                </div>
                            </div>
                            <button type="button" class="tag-action ${actionClass}" data-member-toggle="${user.id}" title="${actionLabel}">
                                <i class="bi ${actionIcon}"></i>
                            </button>
                        </div>
                    `;
            };

            const renderTagModal = (query = '') => {
                const normalized = query.trim().toLowerCase();
                const filtered = allTags.filter((tag) => {
                    if (!normalized) return true;
                    return String(tag.name).toLowerCase().includes(normalized);
                });

                tagModalList.innerHTML = filtered.length
                    ? filtered.map(renderTagRow).join('')
                    : `
                            <div class="empty-state p-4 text-center">
                                <i class="bi bi-tag fs-3 text-secondary d-block mb-2"></i>
                                <div class="fw-bold text-dark">No tags found</div>
                                <div class="text-secondary small">Try another search or create a new tag.</div>
                            </div>
                        `;
            };

            const renderMemberModal = (query = '') => {
                const normalized = query.trim().toLowerCase();
                const filtered = allUsers.filter((user) => {
                    if (!normalized) return true;
                    return String(user.name).toLowerCase().includes(normalized) || String(user.email).toLowerCase().includes(normalized);
                });

                memberModalList.innerHTML = filtered.length
                    ? filtered.map(renderMemberRow).join('')
                    : `
                            <div class="empty-state p-4 text-center">
                                <i class="bi bi-people fs-3 text-secondary d-block mb-2"></i>
                                <div class="fw-bold text-dark">No members found</div>
                                <div class="text-secondary small">Try a different search term.</div>
                            </div>
                        `;
            };

            const attachTag = async (tagId, silent = false) => {
                const response = await fetchJson(`${tagBaseUrl}/${tagId}`, { method: 'POST' });
                attachedTagIds.add(Number(tagId));
                updateTagUI(response.tags);
                if (!silent) {
                    showToast('success', response.message || 'Tag attached.');
                }
            };

            const detachTag = async (tagId, silent = false) => {
                const response = await fetchJson(`${tagBaseUrl}/${tagId}`, { method: 'DELETE' });
                attachedTagIds.delete(Number(tagId));
                updateTagUI(response.tags);
                if (!silent) {
                    showToast('success', response.message || 'Tag detached.');
                }
            };

            const attachMember = async (userId) => {
                const response = await fetchJson(`${memberBaseUrl}/${userId}`, { method: 'POST' });
                attachedMemberIds.add(Number(userId));
                updateMemberUI(response.members);
                showToast('success', response.message || 'Member assigned.');
            };

            const detachMember = async (userId) => {
                const response = await fetchJson(`${memberBaseUrl}/${userId}`, { method: 'DELETE' });
                attachedMemberIds.delete(Number(userId));
                updateMemberUI(response.members);
                showToast('success', response.message || 'Member removed.');
            };

            const updateTagUI = (tags) => {
                tagsList.innerHTML = tags.length
                    ? tags.map((tag) => `
                            <span class="tag-chip" data-tag-chip="${tag.id}">
                                <span class="dot" style="background-color: ${escapeHtml(tag.color || '#94a3b8')}"></span>
                                ${escapeHtml(tag.name)}
                                <button type="button" class="tag-action detached" data-tag-detach="${tag.id}" title="Detach tag">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </span>
                        `).join('')
                    : '<span class="text-secondary small" id="tagsEmpty">No tags attached yet.</span>';

                renderTagsInCard();
                renderTagModal(tagSearch?.value || '');
            };

            const updateMemberUI = (members) => {
                membersList.innerHTML = members.length
                    ? members.map((member) => `
                            <span class="member-chip" data-member-chip="${member.id}">
                                <span class="member-avatar" style="width: 30px; height: 30px; font-size: .7rem;">${escapeHtml(member.initials || initialsFromName(member.name))}</span>
                                ${escapeHtml(member.name)}
                                <button type="button" class="tag-action detached" data-member-detach="${member.id}" title="Remove member">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </span>
                        `).join('')
                    : '<span class="text-secondary small" id="membersEmpty">No members assigned yet.</span>';

                renderMembersInCard();
                renderMemberModal(memberSearch?.value || '');
            };

            commentForm?.addEventListener('submit', async (event) => {
                event.preventDefault();
                if (commentStatus) commentStatus.textContent = 'Sending...';

                try {
                    const formData = new FormData(commentForm);
                    const response = await fetchJson(commentStoreUrl, {
                        method: 'POST',
                        body: formData,
                    });

                    if (!commentsTimeline.querySelector('[data-comment-id]')) {
                        commentsTimeline.innerHTML = '';
                    }

                    commentsTimeline.insertAdjacentHTML('beforeend', renderComment(response.comment));
                    commentForm.reset();
                    document.getElementById('author_name').value = @json(auth()->user()?->name ?? '');
                    if (commentStatus) commentStatus.textContent = 'Comment added.';
                    showToast('success', response.message || 'Comment added successfully.');
                } catch (error) {
                    const errors = error.payload?.errors ? Object.values(error.payload.errors).flat() : [error.payload?.message || 'Unable to add comment.'];
                    if (commentStatus) commentStatus.textContent = '';
                    showToast('danger', errors[0]);
                }
            });

            document.getElementById('commentsTimeline')?.addEventListener('click', (event) => {
                const button = event.target.closest('[data-comment-delete]');
                if (!button) return;
            });

            document.getElementById('tagsList')?.addEventListener('click', async (event) => {
                const button = event.target.closest('[data-tag-detach]');
                if (!button) return;

                try {
                    button.disabled = true;
                    await detachTag(button.dataset.tagDetach);
                } catch (error) {
                    const errors = error.payload?.errors ? Object.values(error.payload.errors).flat() : [error.payload?.message || 'Unable to detach tag.'];
                    showToast('danger', errors[0]);
                } finally {
                    button.disabled = false;
                }
            });

            document.getElementById('membersList')?.addEventListener('click', async (event) => {
                const button = event.target.closest('[data-member-detach]');
                if (!button) return;

                try {
                    button.disabled = true;
                    await detachMember(button.dataset.memberDetach);
                } catch (error) {
                    const errors = error.payload?.errors ? Object.values(error.payload.errors).flat() : [error.payload?.message || 'Unable to remove member.'];
                    showToast('danger', errors[0]);
                } finally {
                    button.disabled = false;
                }
            });

            document.getElementById('tagModalList')?.addEventListener('click', async (event) => {
                const button = event.target.closest('[data-tag-toggle]');
                if (!button) return;

                const tagId = button.dataset.tagToggle;
                try {
                    button.disabled = true;
                    if (attachedTagIds.has(Number(tagId))) {
                        await detachTag(tagId);
                    } else {
                        await attachTag(tagId);
                    }
                } catch (error) {
                    const errors = error.payload?.errors ? Object.values(error.payload.errors).flat() : [error.payload?.message || 'Unable to update tag.'];
                    showToast('danger', errors[0]);
                } finally {
                    button.disabled = false;
                }
            });

            document.getElementById('memberModalList')?.addEventListener('click', async (event) => {
                const button = event.target.closest('[data-member-toggle]');
                if (!button) return;

                const userId = button.dataset.memberToggle;
                try {
                    button.disabled = true;
                    if (attachedMemberIds.has(Number(userId))) {
                        await detachMember(userId);
                    } else {
                        await attachMember(userId);
                    }
                } catch (error) {
                    const errors = error.payload?.errors ? Object.values(error.payload.errors).flat() : [error.payload?.message || 'Unable to update member.'];
                    showToast('danger', errors[0]);
                } finally {
                    button.disabled = false;
                }
            });

            tagSearch?.addEventListener('input', () => renderTagModal(tagSearch.value));
            memberSearch?.addEventListener('input', () => renderMemberModal(memberSearch.value));

            tagModal?.addEventListener('show.bs.modal', () => {
                tagSearch.value = '';
                renderTagModal();
            });

            memberModal?.addEventListener('show.bs.modal', () => {
                memberSearch.value = '';
                renderMemberModal();
            });

            createTagForm?.addEventListener('submit', async (event) => {
                event.preventDefault();

                const name = newTagName.value.trim();
                const color = newTagColor.value;

                if (!name) {
                    showToast('danger', 'Please enter a tag name.');
                    return;
                }

                try {
                    const created = await fetchJson(tagCreateUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                        },
                        body: new URLSearchParams({ name, color }),
                    });

                    const createdTag = created.tag;
                    if (createdTag) {
                        allTags.push(createdTag);
                        await attachTag(createdTag.id, true);
                        showToast('success', created.message || 'Tag created and attached.');
                        createTagForm.reset();
                        newTagColor.value = '#2563eb';
                        tagSearch.value = '';
                        renderTagModal();
                    } else {
                        showToast('danger', 'Tag was created but could not be attached.');
                    }
                } catch (error) {
                    const errors = error.payload?.errors ? Object.values(error.payload.errors).flat() : [error.payload?.message || 'Unable to create tag.'];
                    showToast('danger', errors[0]);
                }
            });

            // Initial render for modal lists.
            renderTagModal();
            renderMemberModal();
            renderTagsInCard();
            renderMembersInCard();
        })();
    </script>
@endpush
