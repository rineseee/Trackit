@php
    $isEdit = $project->exists;
@endphp

<form method="POST" action="{{ $isEdit ? route('projects.update', $project) : route('projects.store') }}" class="form-card">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    @include('partials.form-errors')

    <div class="form-header">
        <p>{{ $isEdit ? 'Edit project' : 'New project' }}</p>
        <h2>{{ $isEdit ? 'Update project details' : 'Set up a new project' }}</h2>
    </div>

    <div class="form-grid">
        <!-- Project Name and Start Date on same row -->
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Project Name</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $project->name) }}"
                    class="form-input"
                    placeholder="Website redesign"
                    required>
            </div>
            <div class="form-group">
                <label class="form-label">Start Date</label>
                <input
                    type="date"
                    name="start_date"
                    value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}"
                    class="form-input">
            </div>
        </div>

        <!-- Deadline on its own -->
        <div class="form-group">
            <label class="form-label">Deadline</label>
            <input
                type="date"
                name="deadline"
                value="{{ old('deadline', optional($project->deadline)->format('Y-m-d')) }}"
                class="form-input">
        </div>

        <!-- Description full width -->
        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea
                name="description"
                class="form-textarea"
                placeholder="Add scope and goals...">{{ old('description', $project->description) }}</textarea>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="bi bi-check2"></i>
            {{ $isEdit ? 'Update' : 'Create' }}
        </button>
        <a href="{{ route('projects.index') }}" class="btn-secondary">
            <i class="bi bi-x-lg"></i>
            Cancel
        </a>
    </div>
</form>
