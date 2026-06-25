<div class="rounded-lg border border-slate-200/90 bg-white/95 p-6 shadow-sm shadow-slate-200/70">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-900">Recent Projects</h2>
        <a href="{{ route('projects.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">View all →</a>
    </div>

    <div class="mt-6 space-y-3">
        @forelse ($recentProjects as $project)
            <a href="{{ route('projects.show', $project) }}" class="block rounded-lg border border-slate-200 bg-slate-50 p-3 transition hover:bg-slate-100">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-slate-900">{{ $project->name }}</h3>
                        <p class="mt-1 text-xs text-slate-600">
                            By {{ $project->owner?->name ?? 'Unknown' }}
                        </p>
                    </div>
                    <span class="flex h-6 w-6 items-center justify-center rounded bg-blue-100 text-xs font-semibold text-blue-700">
                        {{ $project->issues_count ?? 0 }}
                    </span>
                </div>
            </a>
        @empty
            <div class="rounded-lg border border-dashed border-slate-300 p-4 text-center">
                <p class="text-sm text-slate-600">No projects yet</p>
            </div>
        @endforelse
    </div>

    @auth
        <a href="{{ route('projects.create') }}" class="mt-4 block w-full rounded-lg bg-blue-50 px-4 py-2 text-center text-sm font-semibold text-blue-700 ring-1 ring-blue-100 transition hover:bg-blue-100">
            + New Project
        </a>
    @endauth
</div>
