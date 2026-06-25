<a href="{{ $href }}" class="group rounded-lg border border-slate-200/90 bg-white/95 p-6 shadow-sm shadow-slate-200/70 transition hover:shadow-md hover:shadow-slate-300/50">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-slate-600">{{ $title }}</p>
            <div class="mt-2 flex items-baseline gap-2">
                <p class="text-3xl font-bold text-slate-900">{{ $value }}</p>
                <span class="text-xs font-semibold {{ match($color) {
                    'blue' => 'text-blue-600',
                    'indigo' => 'text-indigo-600',
                    'orange' => 'text-orange-600',
                    'green' => 'text-green-600',
                    default => 'text-slate-600',
                } }}">
                    {{ $change }}
                </span>
            </div>
        </div>

        <div class="flex h-12 w-12 items-center justify-center rounded-lg {{ match($color) {
            'blue' => 'bg-blue-100',
            'indigo' => 'bg-indigo-100',
            'orange' => 'bg-orange-100',
            'green' => 'bg-green-100',
            default => 'bg-slate-100',
        } }}">
            <svg class="h-6 w-6 {{ match($color) {
                'blue' => 'text-blue-600',
                'indigo' => 'text-indigo-600',
                'orange' => 'text-orange-600',
                'green' => 'text-green-600',
                default => 'text-slate-600',
            } }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                @switch($icon)
                    @case('folder')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        @break
                    @case('check-circle')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        @break
                    @case('alert-circle')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        @break
                    @case('check')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        @break
                    @default
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                @endswitch
            </svg>
        </div>
    </div>

    <div class="mt-4 border-t border-slate-100 pt-4">
        <p class="text-xs font-semibold {{ match($color) {
            'blue' => 'text-blue-600',
            'indigo' => 'text-indigo-600',
            'orange' => 'text-orange-600',
            'green' => 'text-green-600',
            default => 'text-slate-600',
        } }}">
            View details →
        </p>
    </div>
</a>
