@php
    use App\Models\Issue;

    $isEdit = $issue->exists;
    $selectedTags = array_map('intval', (array) old('tags', $isEdit ? $issue->tags->pluck('id')->all() : []));
    $selectedProject = old('project_id', request('project_id', $issue->project_id));
@endphp

<form
    method="POST"
    action="{{ $isEdit ? route('issues.update', $issue) : route('issues.store') }}"
    class="space-y-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/60 lg:p-8"
>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    @include('partials.form-errors')

    <div class="rounded-2xl bg-slate-50 p-5 ring-1 ring-slate-100">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                    {{ $isEdit ? 'Edit issue' : 'New issue' }}
                </p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
                    {{ $isEdit ? 'Refine the work item' : 'Create a clear issue from the start' }}
                </h2>
                <p class="mt-2 max-w-2xl text-sm leading-7 text-slate-600">
                    Keep the essentials easy to scan: project, title, status, priority, due date, and tags.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-2 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">
                    <i class="bi bi-lightning-charge text-amber-500"></i>
                    Fast capture
                </span>
                <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-2 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">
                    <i class="bi bi-phone text-sky-500"></i>
                    Mobile friendly
                </span>
            </div>
        </div>
    </div>

    <div class="grid gap-8 xl:grid-cols-[minmax(0,1.35fr)_minmax(340px,0.85fr)]">
        <div class="space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-[0.16em] text-slate-500">Issue details</h3>

                <div class="mt-5 grid gap-5">
                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold text-slate-700">Project</span>
                        <select
                            name="project_id"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                        >
                            <option value="">Choose a project</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @selected((string) $selectedProject === (string) $project->id)>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold text-slate-700">Title</span>
                        <input
                            type="text"
                            name="title"
                            value="{{ old('title', $issue->title) }}"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                            placeholder="Login flow breaks on mobile"
                        >
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold text-slate-700">Description</span>
                        <textarea
                            name="description"
                            rows="10"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                            placeholder="Describe the bug, request, or implementation details..."
                        >{{ old('description', $issue->description) }}</textarea>
                    </label>
                </div>
            </section>
        </div>

        <aside class="space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-[0.16em] text-slate-500">Workflow</h3>

                <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold text-slate-700">Status</span>
                        <select
                            name="status"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                        >
                            @foreach (Issue::STATUSES as $status)
                                <option value="{{ $status }}" @selected(old('status', $issue->status ?: 'open') === $status)>
                                    {{ str_replace('_', ' ', ucfirst($status)) }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold text-slate-700">Priority</span>
                        <select
                            name="priority"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                        >
                            @foreach (Issue::PRIORITIES as $priority)
                                <option value="{{ $priority }}" @selected(old('priority', $issue->priority ?: 'medium') === $priority)>
                                    {{ ucfirst($priority) }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold text-slate-700">Due date</span>
                        <input
                            type="date"
                            name="due_date"
                            value="{{ old('due_date', optional($issue->due_date)->format('Y-m-d')) }}"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                        >
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold text-slate-700">Tags</span>
                        <select
                            name="tags[]"
                            multiple
                            size="7"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                        >
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}" @selected(in_array($tag->id, $selectedTags, true))>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="mt-2 block text-xs leading-5 text-slate-500">
                            Hold Ctrl on Windows or Cmd on Mac to select multiple tags.
                        </span>
                    </label>
                </div>
            </section>

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">
                        <i class="bi bi-check2"></i>
                        {{ $isEdit ? 'Update issue' : 'Create issue' }}
                    </button>
                    <a
                        href="{{ $isEdit ? route('issues.show', $issue) : route('issues.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-50"
                    >
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>
                </div>
                <p class="mt-3 text-xs leading-5 text-slate-500">
                    Keep titles short, use a clear status, and pick a due date only when the work is time sensitive.
                </p>
            </div>
        </aside>
    </div>
</form>
