<div class="rounded-lg border border-slate-200/90 bg-white/95 p-6 shadow-sm shadow-slate-200/70">
    <div class="flex items-center justify-between">
        <h3 class="font-semibold text-slate-900">{{ $label }}</h3>
        <div class="flex h-8 w-8 items-center justify-center rounded-full {{ match($color) {
            'blue' => 'bg-blue-100',
            'amber' => 'bg-amber-100',
            'green' => 'bg-green-100',
            default => 'bg-slate-100',
        } }}">
            <span class="text-sm font-bold {{ match($color) {
                'blue' => 'text-blue-700',
                'amber' => 'text-amber-700',
                'green' => 'text-green-700',
                default => 'text-slate-700',
            } }}">{{ $count }}</span>
        </div>
    </div>

    <div class="mt-4">
        <div class="h-1.5 w-full overflow-hidden rounded-full {{ match($color) {
            'blue' => 'bg-blue-100',
            'amber' => 'bg-amber-100',
            'green' => 'bg-green-100',
            default => 'bg-slate-100',
        } }}">
            <div class="h-full {{ match($color) {
                'blue' => 'bg-blue-600',
                'amber' => 'bg-amber-600',
                'green' => 'bg-green-600',
                default => 'bg-slate-600',
            } }}" style="width: {{ $count > 0 ? min(100, ($count / 50) * 100) : 0 }}%"></div>
        </div>
    </div>

    <a href="{{ route('issues.index') }}?status={{ $status }}" class="mt-4 inline-block text-sm font-semibold {{ match($color) {
        'blue' => 'text-blue-600 hover:text-blue-700',
        'amber' => 'text-amber-600 hover:text-amber-700',
        'green' => 'text-green-600 hover:text-green-700',
        default => 'text-slate-600 hover:text-slate-700',
    } }}">
        View issues →
    </a>
</div>
