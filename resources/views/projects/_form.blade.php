@php
    $isEdit = $project->exists;
@endphp

<form method="POST" action="{{ $isEdit ? route('projects.update', $project) : route('projects.store') }}"
    class="space-y-8 rounded-[28px] border border-slate-200 bg-white p-6 shadow-[0_20px_70px_rgba(15,23,42,0.06)] lg:p-8">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    @include('partials.form-errors')

    <div class="rounded-[24px] bg-slate-50 p-5 ring-1 ring-slate-100">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
            {{ $isEdit ? 'Edit project' : 'New project' }}
        </p>
        <h2 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">
            {{ $isEdit ? 'Refine the project details' : 'Set up a project the team can understand quickly' }}
        </h2>
        <p class="mt-2 max-w-2xl text-sm leading-7 text-slate-600">
            Keep the core planning fields visible so the team can see scope, timing, and ownership at a glance.
        </p>
    </div>

    <div class="grid gap-8 lg:grid-cols-[minmax(0,1.35fr)_minmax(340px,0.75fr)]">
        <div class="space-y-6">
            <section class="rounded-[24px] border border-slate-200 bg-slate-50 p-6 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-[0.16em] text-slate-500">Project details</h3>

                <div class="mt-5 grid gap-5">
                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold text-slate-700">Project name</span>
                        <input type="text" name="name" value="{{ old('name', $project->name) }}"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                            placeholder="Website redesign">
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold text-slate-700">Description</span>
                        <textarea name="description" rows="10"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                            placeholder="Add the scope, goals, and any notes for the team...">{{ old('description', $project->description) }}</textarea>
                    </label>
                </div>
            </section>
        </div>

        <aside class="space-y-6">
            <section class="rounded-[24px] border border-slate-200 bg-slate-50 p-6 shadow-sm">
                <h3 class="text-sm font-bold uppercase tracking-[0.16em] text-slate-500">Timeline</h3>

                <div class="mt-5 grid gap-4">
                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold text-slate-700">Start date</span>
                        <input type="date" name="start_date"
                            value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
                    </label>

                    <label class="block">
                        <span class="mb-2 block text-sm font-semibold text-slate-700">Deadline</span>
                        <input type="date" name="deadline"
                            value="{{ old('deadline', optional($project->deadline)->format('Y-m-d')) }}"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-slate-900 shadow-sm outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
                    </label>
                </div>
            </section>

            <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-5 shadow-sm">
                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">
                        <i class="bi bi-check2"></i>
                        {{ $isEdit ? 'Update project' : 'Create project' }}
                    </button>
                    <a href="{{ route('projects.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-50">
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>
                </div>
            </div>
        </aside>
    </div>
</form>