<x-helpdesk-layout>
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h1>#{{ $issue->id }} - {{ $issue->title }}</h1>
            <p class="text-muted">{{ $issue->project->name }}</p>
        </div>
        <div class="gap-2 d-flex">
            <a href="{{ route('issues.edit', $issue) }}" class="btn btn-secondary">
                <i class="fas fa-edit me-2"></i>Edit
            </a>
            <button type="button" class="btn btn-danger" id="deleteBtn">
                <i class="fas fa-trash me-2"></i>Delete
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Section -->
        <div class="col-lg-8">
            <!-- Issue Details Card -->
            <div class="card mb-4">
                <div class="card-header border-bottom">
                    <h6 class="card-title mb-0">Description</h6>
                </div>
                <div class="card-body">
                    <p>{{ $issue->description }}</p>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card">
                <div class="card-header border-bottom">
                    <h6 class="card-title mb-0">Comments ({{ $issue->comments_count }})</h6>
                </div>
                <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                    <div id="commentsList">
                        @forelse ($issue->comments as $comment)
                            <div class="comment-item mb-3 pb-3 border-bottom" data-comment-id="{{ $comment->id }}">
                                <div class="d-flex gap-2 mb-2">
                                    <div class="avatar" style="width: 32px; height: 32px; background: linear-gradient(135deg, #2563eb, #0ea5e9); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700; flex-shrink: 0;">
                                        {{ substr($comment->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>{{ $comment->user->name }}</strong>
                                                <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                            @if(auth()->user()->id === $comment->user_id)
                                                <button type="button" class="btn btn-sm btn-link text-danger delete-comment" data-comment-id="{{ $comment->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                        <p class="mt-2 mb-0">{{ $comment->body }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-4">No comments yet. Be the first to comment!</p>
                        @endforelse
                    </div>
                </div>

                <!-- Comment Form -->
                <div class="card-footer border-top">
                    <form id="commentForm" class="d-flex gap-2">
                        @csrf
                        <input type="text" class="form-control" placeholder="Add a comment..." id="commentInput" required>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Section (Sticky) -->
        <div class="col-lg-4">
            <div style="position: sticky; top: 100px;">
                <!-- Status Card -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small text-muted">Status</label>
                            <select class="form-select form-select-sm ajax-field" data-field="status" data-issue-id="{{ $issue->id }}">
                                <option value="open" {{ $issue->status === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ $issue->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="closed" {{ $issue->status === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted">Priority</label>
                            <select class="form-select form-select-sm ajax-field" data-field="priority" data-issue-id="{{ $issue->id }}">
                                <option value="low" {{ $issue->priority === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $issue->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $issue->priority === 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small text-muted">Due Date</label>
                            <input type="date" class="form-control form-control-sm ajax-field" data-field="due_date" data-issue-id="{{ $issue->id }}" value="{{ $issue->due_date?->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <!-- Tags Card -->
                <div class="card mb-3">
                    <div class="card-header border-bottom">
                        <h6 class="card-title mb-0">Tags</h6>
                    </div>
                    <div class="card-body">
                        <div id="tagsList" class="mb-3">
                            @forelse ($issue->tags as $tag)
                                <span class="badge badge-primary me-2 mb-2" data-tag-id="{{ $tag->id }}">
                                    {{ $tag->name }}
                                    <button type="button" class="btn-close btn-close-white remove-tag ms-1" data-tag-id="{{ $tag->id }}" style="font-size: 0.65rem;"></button>
                                </span>
                            @empty
                                <p class="text-muted mb-0">No tags</p>
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tagsModal">
                            <i class="fas fa-tag me-1"></i>Add Tags
                        </button>
                    </div>
                </div>

                <!-- Members Card -->
                <div class="card mb-3">
                    <div class="card-header border-bottom">
                        <h6 class="card-title mb-0">Assigned To</h6>
                    </div>
                    <div class="card-body">
                        @if($issue->assignedTo)
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="avatar" style="width: 32px; height: 32px; background: linear-gradient(135deg, #2563eb, #0ea5e9); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700;">
                                    {{ substr($issue->assignedTo->name, 0, 1) }}
                                </div>
                                <div>
                                    <strong>{{ $issue->assignedTo->name }}</strong>
                                </div>
                            </div>
                        @endif
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignModal">
                            <i class="fas fa-user me-1"></i>Change Assignment
                        </button>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Created</small>
                            <strong>{{ $issue->created_at->format('M d, Y H:i') }}</strong>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block">Last Updated</small>
                            <strong>{{ $issue->updated_at->format('M d, Y H:i') }}</strong>
                        </div>
                        <div>
                            <small class="text-muted d-block">Project</small>
                            <strong>{{ $issue->project->name }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tags Modal -->
    <div class="modal fade" id="tagsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Tags</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="tagsCheckboxes">
                        @foreach ($allTags as $tag)
                            <div class="form-check">
                                <input class="form-check-input tag-checkbox" type="checkbox" id="tag{{ $tag->id }}" value="{{ $tag->id }}" {{ $issue->tags->contains($tag) ? 'checked' : '' }}>
                                <label class="form-check-label" for="tag{{ $tag->id }}">
                                    {{ $tag->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveTags">Save Tags</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Modal -->
    <div class="modal fade" id="assignModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign To</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select class="form-select" id="assignSelect">
                        <option value="">Unassigned</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $issue->assigned_to_id === $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveAssign">Assign</button>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
    const issueId = {{ $issue->id }};

    // Add comment
    $('#commentForm').on('submit', function(e) {
        e.preventDefault();
        const body = $('#commentInput').val();
        const $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: `/issues/${issueId}/comments`,
            method: 'POST',
            data: {
                body: body,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                const comment = response.comment;
                const html = `
                    <div class="comment-item mb-3 pb-3 border-bottom animate-fade" data-comment-id="${comment.id}">
                        <div class="d-flex gap-2 mb-2">
                            <div class="avatar" style="width: 32px; height: 32px; background: linear-gradient(135deg, #2563eb, #0ea5e9); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700; flex-shrink: 0;">
                                ${comment.user.name.charAt(0)}
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>${comment.user.name}</strong>
                                        <small class="text-muted ms-2">just now</small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-link text-danger delete-comment" data-comment-id="${comment.id}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <p class="mt-2 mb-0">${comment.body}</p>
                            </div>
                        </div>
                    </div>
                `;
                $('#commentsList').prepend(html);
                $('#commentInput').val('');
                showToast('success', 'Comment added successfully');
            },
            error: function() {
                showToast('error', 'Failed to add comment');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i>');
            }
        });
    });

    // Delete comment
    $(document).on('click', '.delete-comment', function() {
        const commentId = $(this).data('comment-id');
        Swal.fire({
            title: 'Delete Comment?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/issues/${issueId}/comments/${commentId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        $(`.comment-item[data-comment-id="${commentId}"]`).remove();
                        showToast('success', 'Comment deleted');
                    }
                });
            }
        });
    });

    // AJAX field updates
    $('.ajax-field').on('change', function() {
        const field = $(this).data('field');
        const value = $(this).val();
        const $field = $(this);

        $.ajax({
            url: `/issues/${issueId}`,
            method: 'PATCH',
            data: {
                [field]: value,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                showToast('success', `${field} updated`);
            },
            error: function() {
                showToast('error', 'Failed to update');
            }
        });
    });

    // Tags modal
    $('#saveTags').on('click', function() {
        const selectedTags = $('.tag-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        $.ajax({
            url: `/issues/${issueId}/tags`,
            method: 'POST',
            data: {
                tags: selectedTags,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                location.reload();
            }
        });
    });

    // Remove tag
    $(document).on('click', '.remove-tag', function(e) {
        e.preventDefault();
        const tagId = $(this).data('tag-id');

        $.ajax({
            url: `/issues/${issueId}/tags/${tagId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                $(`.badge[data-tag-id="${tagId}"]`).remove();
                showToast('success', 'Tag removed');
            }
        });
    });

    // Assign modal
    $('#saveAssign').on('click', function() {
        const userId = $('#assignSelect').val();

        $.ajax({
            url: `/issues/${issueId}`,
            method: 'PATCH',
            data: {
                assigned_to_id: userId || null,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                location.reload();
            }
        });
    });

    // Delete issue
    $('#deleteBtn').on('click', function() {
        Swal.fire({
            title: 'Delete Issue?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/issues/${issueId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        Swal.fire('Deleted!', 'Issue has been deleted.', 'success').then(() => {
                            window.location.href = '{{ route("issues.index") }}';
                        });
                    }
                });
            }
        });
    });

    function showToast(type, message) {
        const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
        const toast = `
            <div class="toast align-items-center ${bgClass} text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                </div>
            </div>
        `;
        const $toast = $(toast);
        const container = document.querySelector('.trackit-page');
        $(container).prepend($toast);
        new bootstrap.Toast($toast[0]).show();
        setTimeout(() => $toast.remove(), 3000);
    }
</script>

<style>
    .animate-fade {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush
</x-helpdesk-layout>
