<span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ match($status) {
    'open' => 'bg-blue-100 text-blue-700',
    'in_progress' => 'bg-amber-100 text-amber-700',
    'closed' => 'bg-green-100 text-green-700',
    default => 'bg-slate-100 text-slate-700',
} }}">
    {{ str_replace('_', ' ', ucfirst($status)) }}
</span>
