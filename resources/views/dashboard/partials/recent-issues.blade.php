<div class="rounded-lg border border-slate-200/90 bg-white/95 p-6 shadow-sm shadow-slate-200/70">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-900">Recent Issues</h2>
        <a href="{{ route('issues.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">View all →</a>
    </div>

    <div class="mt-6 space-y-4">
        @forelse ($recentIssues as $issue)
            <a href="{{ route('issues.show', $issue) }}" class="block rounded-lg border border-slate-200 bg-slate-50 p-4 transition hover:bg-slate-100">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-slate-900 truncate">{{ $issue->title }}</h3>
                        <p class="mt-1 text-xs text-slate-600">
                            {{ $issue->project->name }}
                        </p>
                    </div>

                    <div class="flex flex-shrink-0 items-center gap-2">
                        @include('dashboard.partials.status-badge', [
                            'status' => $issue->status,
                        ])

                        @include('dashboard.partials.priority-badge', [
                            'priority' => $issue->priority,
                        ])
                    </div>
                </div>

                @if($issue->members->count() > 0)
                    <div class="mt-3 flex -space-x-2">
                        @foreach ($issue->members->take(3) as $member)
                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-300 text-[10px] font-bold text-white ring-2 ring-white" title="{{ $member->name }}">
                                {{ collect(explode(' ', $member->name))->filter()->map(fn ($part) => strtoupper(substr($part, 0, 1)))->take(2)->implode('') }}
                            </div>
                        @endforeach
                        @if($issue->members->count() > 3)
                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-400 text-[10px] font-bold text-white ring-2 ring-white">
                                +{{ $issue->members->count() - 3 }}
                            </div>
                        @endif
                    </div>
                @endif
            </a>
        @empty
            @include('dashboard.partials.empty-state', [
                'title' => 'No issues yet',
                'description' => 'Create your first issue to get started.',
            ])
        @endforelse
    </div>
</div>
