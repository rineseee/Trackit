<form
    method="POST"
    action="{{ route('tags.store') }}"
    class="space-y-6 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm shadow-slate-200/60 lg:p-6"
>
    @csrf

    @include('partials.form-errors')

    <div class="rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-100">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Tag setup</p>
        <h2 class="mt-1 text-xl font-bold tracking-tight text-slate-900">Create a reusable label</h2>
        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
            Use short, readable labels to help people scan work faster across lists, cards, and dashboards.
        </p>
    </div>

    <div class="grid gap-5">
        <label class="block">
            <span class="mb-2 block text-sm font-semibold text-slate-700">Tag name</span>
            <input
                type="text"
                name="name"
                value="{{ old('name', $tag->name) }}"
                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                placeholder="backend"
            >
        </label>

        <label class="block">
            <span class="mb-2 block text-sm font-semibold text-slate-700">Color</span>
            <input
                type="text"
                name="color"
                value="{{ old('color', $tag->color) }}"
                class="w-full rounded-xl border border-slate-300 bg-white px-3 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
                placeholder="#0ea5e9"
            >
            <span class="mt-2 block text-xs leading-5 text-slate-500">
                Hex colors work best, but a simple label is also accepted.
            </span>
        </label>
    </div>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-sky-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">
            <i class="bi bi-check2"></i>
            Create tag
        </button>
        <a href="{{ route('tags.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
            <i class="bi bi-x-lg"></i>
            Cancel
        </a>
    </div>
</form>
