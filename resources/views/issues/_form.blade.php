@php
    use App\Models\Issue;

    $isEdit = $issue->exists;
    $selectedTags = array_map('intval', (array) old('tags', $isEdit ? $issue->tags->pluck('id')->all() : []));
    $selectedProject = old('project_id', request('project_id', $issue->project_id));
@endphp

<form
    method="POST"
    action="{{ $isEdit ? route('issues.update', $issue) : route('issues.store') }}"
    class="form-card">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    @include('partials.form-errors')

    <div class="form-header">
        <p>{{ $isEdit ? 'Edit issue' : 'New issue' }}</p>
        <h2>{{ $isEdit ? 'Update issue details' : 'Create a new issue' }}</h2>
    </div>

    <div class="form-grid">
        <!-- Row 1: Project, Status, Priority -->
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Project</label>
                <select name="project_id" class="form-select" required>
                    <option value="">Choose a project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" @selected((string) $selectedProject === (string) $project->id)>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach (Issue::STATUSES as $status)
                        <option value="{{ $status }}" @selected(old('status', $issue->status ?: 'open') === $status)>
                            {{ str_replace('_', ' ', ucfirst($status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Priority</label>
                <select name="priority" class="form-select">
                    @foreach (Issue::PRIORITIES as $priority)
                        <option value="{{ $priority }}" @selected(old('priority', $issue->priority ?: 'medium') === $priority)>
                            {{ ucfirst($priority) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Row 2: Title (Full Width) -->
        <div class="form-row-full">
            <div class="form-group">
                <label class="form-label">Title</label>
                <input
                    type="text"
                    name="title"
                    value="{{ old('title', $issue->title) }}"
                    class="form-input"
                    placeholder="Login flow breaks on mobile"
                    required>
            </div>
        </div>

        <!-- Row 3: Due Date, Tags -->
        <div class="form-row-2col">
            <div class="form-group">
                <label class="form-label">Due Date</label>
                <input
                    type="date"
                    name="due_date"
                    value="{{ old('due_date', optional($issue->due_date)->format('Y-m-d')) }}"
                    class="form-input">
            </div>

            <div class="form-group">
                <label class="form-label">Tags</label>
                <select
                    name="tags[]"
                    multiple
                    size="4"
                    class="form-select">
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" @selected(in_array($tag->id, $selectedTags, true))>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
                <span class="form-help">Ctrl/Cmd + Click to select multiple</span>
            </div>
        </div>

        <!-- Row 4: Description (Full Width) -->
        <div class="form-row-full">
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea
                    name="description"
                    class="form-textarea form-textarea-compact"
                    placeholder="Describe the bug, request, or implementation details...">{{ old('description', $issue->description) }}</textarea>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="bi bi-check2"></i>
            {{ $isEdit ? 'Update Issue' : 'Create Issue' }}
        </button>
        <a href="{{ $isEdit ? route('issues.show', $issue) : route('issues.index') }}" class="btn-secondary">
            <i class="bi bi-x-lg"></i>
            Cancel
        </a>
    </div>
</form>
