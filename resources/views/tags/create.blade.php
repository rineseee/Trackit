@extends('layouts.app')

@section('title', 'Create Tag')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm shadow-slate-200/60 lg:p-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Tags</p>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-900">Create tag</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                        Add a small, reusable label that helps the team sort and scan work faster.
                    </p>
                </div>

                <a href="{{ route('tags.index') }}" class="inline-flex items-center gap-2 self-start rounded-xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                    <i class="bi bi-arrow-left"></i>
                    Back to tags
                </a>
            </div>
        </div>

        @include('tags._form', ['tag' => $tag])
    </div>
@endsection
