<span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ match($priority) {
    'low' => 'bg-slate-100 text-slate-700',
    'medium' => 'bg-orange-100 text-orange-700',
    'high' => 'bg-red-100 text-red-700',
    default => 'bg-slate-100 text-slate-700',
} }}">
    {{ ucfirst($priority) }}
</span>
