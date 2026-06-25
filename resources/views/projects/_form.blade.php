@php
    $isEdit = $project->exists;
@endphp

<form method="POST" action="{{ $isEdit ? route('projects.update', $project) : route('projects.store') }}"
    style="display: flex; flex-direction: column; gap: 10px; border-radius: 10px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    @include('partials.form-errors')

    <div style="border-radius: 8px; background: var(--trackit-surface-soft); padding: 12px; border: 1px solid var(--trackit-border);">
        <p style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--trackit-muted); margin: 0;">{{ $isEdit ? 'Edit project' : 'New project' }}</p>
        <h2 style="font-size: 15px; font-weight: 700; color: var(--trackit-text); margin: 4px 0 0; letter-spacing: -0.01em;">
            {{ $isEdit ? 'Refine project details' : 'Set up the project' }}
        </h2>
        <p style="font-size: 12px; color: var(--trackit-muted); margin: 4px 0 0; line-height: 1.5;">
            Keep core fields visible for the team.
        </p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr; gap: 10px; margin-top: 4px;">
        <div style="display: flex; flex-direction: column; gap: 8px;">
            <section style="border-radius: 8px; border: 1px solid var(--trackit-border); background: var(--trackit-surface-soft); padding: 12px;">
                <h3 style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--trackit-muted); margin: 0;">Project details</h3>

                <div style="margin-top: 8px; display: flex; flex-direction: column; gap: 8px;">
                    <label style="display: block;">
                        <span style="display: block; margin-bottom: 4px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">Project name</span>
                        <input type="text" name="name" value="{{ old('name', $project->name) }}"
                            style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease;"
                            placeholder="Website redesign">
                    </label>

                    <label style="display: block;">
                        <span style="display: block; margin-bottom: 4px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">Description</span>
                        <textarea name="description" rows="4"
                            style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease; font-family: inherit; resize: vertical;"
                            placeholder="Add scope and goals...">{{ old('description', $project->description) }}</textarea>
                    </label>

                    <label style="display: block;">
                        <span style="display: block; margin-bottom: 4px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">Start date</span>
                        <input type="date" name="start_date"
                            value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}"
                            style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease;">
                    </label>

                    <label style="display: block;">
                        <span style="display: block; margin-bottom: 4px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">Deadline</span>
                        <input type="date" name="deadline"
                            value="{{ old('deadline', optional($project->deadline)->format('Y-m-d')) }}"
                            style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease;">
                    </label>
                </div>
            </section>

            <div style="border-radius: 8px; border: 1px solid var(--trackit-border); background: var(--trackit-surface-soft); padding: 12px; display: flex; flex-wrap: wrap; gap: 8px;">
                <button type="submit"
                    style="display: inline-flex; align-items: center; gap: 6px; border-radius: 6px; background: var(--trackit-primary); color: white; padding: 8px 14px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 150ms ease;">
                    <i class="bi bi-check2" style="font-size: 13px;"></i>
                    {{ $isEdit ? 'Update' : 'Create' }}
                </button>
                <a href="{{ route('projects.index') }}"
                    style="display: inline-flex; align-items: center; gap: 6px; border-radius: 6px; background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 14px; font-size: 12px; font-weight: 600; border: 1px solid var(--trackit-border); text-decoration: none; transition: all 150ms ease;">
                    <i class="bi bi-x-lg" style="font-size: 13px;"></i>
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>