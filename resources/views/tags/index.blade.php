@extends('layouts.app')

@section('title', 'Tags')

@section('content')
    <div class="workbench tags-workbench">
        <header class="workbench-header">
            <div>
                <p class="workbench-kicker">Tags</p>
                <h1>Organize issues with labels</h1>
                <p>Keep labels short, predictable, and easy to scan across issue lists and project pages.</p>
            </div>

            <a href="{{ route('tags.create') }}" class="ui-button secondary">
                <i class="bi bi-plus-lg"></i>
                Dedicated create page
            </a>
        </header>

        <div class="tags-layout">
            <aside class="workbench-panel tag-create-panel">
                <div class="panel-heading">
                    <div>
                        <h2>Create tag</h2>
                        <p>Add a reusable label</p>
                    </div>
                </div>

                <div class="panel-body">
                    @auth
                        @include('tags._form', ['tag' => new \App\Models\Tag()])
                    @else
                        <div class="ui-empty compact">
                            <i class="bi bi-lock"></i>
                            <h2>Log in required</h2>
                            <p>You need an account to create tags.</p>
                            <a href="{{ route('login') }}" class="ui-button primary">Log in</a>
                        </div>
                    @endauth
                </div>
            </aside>

            <section class="workbench-panel">
                <div class="panel-heading">
                    <div>
                        <h2>All tags</h2>
                        <p>{{ $tags->total() }} {{ Str::plural('tag', $tags->total()) }}</p>
                    </div>
                </div>

                <div class="tag-list">
                    @forelse ($tags as $tag)
                        <article class="tag-row">
                            <div class="tag-main">
                                <span class="tag-swatch" style="background-color: {{ $tag->color ?: '#8c959f' }}"></span>
                                <div>
                                    <h3>{{ $tag->name }}</h3>
                                    <p>
                                        {{ $tag->issues_count }} {{ Str::plural('issue', $tag->issues_count) }}
                                        <span>{{ $tag->color ?: 'No color set' }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="row-actions">
                                <a href="{{ route('tags.edit', $tag) }}" class="icon-button" title="Edit tag" aria-label="Edit tag">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Delete this tag?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-button danger" title="Delete tag" aria-label="Delete tag">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="ui-empty">
                            <i class="bi bi-tag"></i>
                            <h2>No tags yet</h2>
                            <p>Create the first tag to make issues easier to group.</p>
                        </div>
                    @endforelse
                </div>

                @if ($tags->hasPages())
                    <div class="panel-footer">
                        {{ $tags->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>

@endsection
