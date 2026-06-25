<div class="rounded-lg border border-slate-200/90 bg-white/95 p-6 shadow-sm shadow-slate-200/70">
    <div class="h-6 w-40 rounded bg-slate-200 animate-pulse"></div>
    <div class="mt-6 space-y-4">
        @for ($i = 0; $i < 4; $i++)
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 animate-pulse">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1">
                        <div class="h-4 w-32 rounded bg-slate-200"></div>
                        <div class="mt-2 h-3 w-24 rounded bg-slate-200"></div>
                    </div>
                    <div class="flex gap-2">
                        <div class="h-6 w-16 rounded-full bg-slate-200"></div>
                        <div class="h-6 w-16 rounded-full bg-slate-200"></div>
                    </div>
                </div>
            </div>
        @endfor
    </div>
</div>
