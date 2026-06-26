@extends('layouts.app')

@section('title', 'Tags')

@section('content')
    @php
        $totalTags = $tags->total();
        $totalIssuesTagged = \App\Models\Issue::whereHas('tags')->count();
        $mostUsedTag = $tags->sortByDesc('issues_count')->first();
        $unusedTags = $tags->filter(fn($tag) => $tag->issues_count === 0)->count();
    @endphp

    <style>
        .tags-wrapper {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .tags-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            margin-bottom: 12px;
            border-bottom: 1px solid var(--trackit-border);
        }

        .tags-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .tags-header-actions {
            display: flex;
            gap: 8px;
        }

        .tags-header-actions .ui-button {
            padding: 8px 12px;
            font-size: 13px;
        }

        .tags-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
            gap: 10px;
            margin-bottom: 12px;
        }

        .metric-card {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 8px;
            padding: 10px;
            text-align: center;
        }

        .metric-icon {
            font-size: 16px;
            color: var(--trackit-primary);
            margin-bottom: 4px;
            display: block;
        }

        .metric-label {
            font-size: 9px;
            font-weight: 700;
            color: var(--trackit-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 3px;
        }

        .metric-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .tags-toolbar {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 12px;
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }

        .tags-toolbar input {
            flex: 1;
            min-width: 180px;
            height: 32px;
            padding: 6px 10px;
            border: 1px solid var(--trackit-border);
            background: var(--trackit-surface-soft);
            color: var(--trackit-text);
            border-radius: 6px;
            font-size: 13px;
        }

        .tags-toolbar input:focus {
            border-color: var(--trackit-primary);
            outline: none;
        }

        .tags-layout {
            display: grid;
            grid-template-columns: 35% 1fr;
            gap: 16px;
        }

        .tags-form-panel {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 10px;
            padding: 16px;
            height: fit-content;
            position: sticky;
            top: 0;
        }

        .form-header {
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--trackit-border);
        }

        .form-header h2 {
            margin: 0 0 2px;
            font-size: 16px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .form-header p {
            margin: 0;
            font-size: 12px;
            color: var(--trackit-muted);
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--trackit-text);
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid var(--trackit-border);
            background: var(--trackit-surface-soft);
            color: var(--trackit-text);
            border-radius: 6px;
            font-size: 13px;
            transition: all 150ms ease;
        }

        .form-input:focus {
            border-color: var(--trackit-primary);
            outline: none;
            background: var(--trackit-surface);
        }

        .color-picker-wrapper {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .color-presets {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .color-preset {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 150ms ease;
        }

        .color-preset:hover {
            border-color: var(--trackit-border);
            transform: scale(1.1);
        }

        .color-input-group {
            display: flex;
            gap: 8px;
        }

        .color-input {
            width: 70px;
            height: 32px;
            padding: 4px 8px;
            border: 1px solid var(--trackit-border);
            background: var(--trackit-surface-soft);
            border-radius: 6px;
            cursor: pointer;
        }

        .color-text-input {
            flex: 1;
            height: 32px;
            padding: 6px 10px;
            border: 1px solid var(--trackit-border);
            background: var(--trackit-surface-soft);
            color: var(--trackit-text);
            border-radius: 6px;
            font-size: 12px;
            font-family: monospace;
        }

        .color-preview {
            padding: 12px;
            background: var(--trackit-surface-soft);
            border-radius: 8px;
            margin-top: 8px;
        }

        .preview-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--trackit-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 6px;
        }

        .preview-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            background: #0ea5e9;
        }

        .form-actions {
            display: flex;
            gap: 8px;
            margin-top: 16px;
            padding-top: 12px;
            border-top: 1px solid var(--trackit-border);
        }

        .form-actions .ui-button {
            flex: 1;
            padding: 8px 12px;
            font-size: 12px;
        }

        .tags-list {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 10px;
            padding: 16px;
            overflow-y: auto;
        }

        .list-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            margin-bottom: 12px;
            border-bottom: 1px solid var(--trackit-border);
        }

        .list-header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .list-count {
            font-size: 12px;
            color: var(--trackit-muted);
            background: var(--trackit-surface-soft);
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
        }

        .tag-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            margin-bottom: 8px;
            background: var(--trackit-surface-soft);
            border: 1px solid var(--trackit-border);
            border-radius: 8px;
            transition: all 150ms ease;
        }

        .tag-item:hover {
            border-color: var(--trackit-primary);
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        }

        .tag-swatch {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            flex-shrink: 0;
            border: 2px solid rgba(15, 23, 42, 0.1);
        }

        .tag-info {
            flex: 1;
            min-width: 0;
        }

        .tag-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--trackit-text);
            margin: 0;
        }

        .tag-meta {
            font-size: 11px;
            color: var(--trackit-muted);
            margin: 2px 0 0;
        }

        .tag-stats {
            display: flex;
            gap: 12px;
            align-items: center;
            font-size: 11px;
        }

        .tag-stat {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 4px 6px;
            background: var(--trackit-surface);
            border-radius: 4px;
            color: var(--trackit-muted);
        }

        .tag-actions {
            display: flex;
            gap: 4px;
            flex-shrink: 0;
        }

        .icon-button {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            border: 1px solid transparent;
            background: var(--trackit-surface);
            color: var(--trackit-text);
            cursor: pointer;
            transition: all 150ms ease;
            font-size: 14px;
            text-decoration: none;
            padding: 0;
        }

        .icon-button:hover {
            background: var(--trackit-border);
            color: var(--trackit-primary);
        }

        .icon-button.danger:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .empty-state {
            padding: 32px 16px;
            text-align: center;
            color: var(--trackit-muted);
        }

        .empty-state i {
            font-size: 40px;
            display: block;
            margin-bottom: 12px;
            opacity: 0.5;
        }

        .empty-state h3 {
            margin: 0 0 8px;
            font-size: 16px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .empty-state p {
            margin: 0;
            font-size: 12px;
        }

        @media (max-width: 1024px) {
            .tags-layout {
                grid-template-columns: 1fr;
            }

            .tags-form-panel {
                position: relative;
            }
        }

        @media (max-width: 640px) {
            .tags-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .tags-header-actions {
                width: 100%;
            }

            .tags-header-actions .ui-button {
                flex: 1;
            }

            .tags-metrics {
                grid-template-columns: repeat(2, 1fr);
            }

            .tags-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .tags-toolbar input {
                flex: 1;
            }

            .tag-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .tag-stats {
                width: 100%;
                justify-content: space-between;
            }
        }

        html[data-theme='dark'] .tags-form-panel,
        html[data-theme='dark'] .tags-list,
        html[data-theme='dark'] .metric-card {
            background: var(--trackit-surface);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .form-input,
        html[data-theme='dark'] .color-input,
        html[data-theme='dark'] .color-text-input,
        html[data-theme='dark'] .tags-toolbar input {
            background: var(--trackit-surface-soft);
            border-color: rgba(148, 163, 184, 0.2);
            color: var(--trackit-text);
        }

        html[data-theme='dark'] .tag-item {
            background: var(--trackit-surface-soft);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .icon-button {
            background: var(--trackit-surface-soft);
            color: var(--trackit-text);
        }
    </style>

    <div class="tags-wrapper">
        <!-- HEADER -->
        <div class="tags-header">
            <h1>Tags</h1>
            <div class="tags-header-actions">
                @guest
                    <a href="{{ route('login') }}" class="ui-button primary">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Log In
                    </a>
                @endguest
            </div>
        </div>

        <!-- METRICS -->
        <div class="tags-metrics">
            <div class="metric-card">
                <i class="metric-icon bi bi-tag"></i>
                <div class="metric-label">Total Tags</div>
                <div class="metric-value">{{ $totalTags }}</div>
            </div>
            <div class="metric-card">
                <i class="metric-icon bi bi-list-check"></i>
                <div class="metric-label">Tagged Issues</div>
                <div class="metric-value">{{ $totalIssuesTagged }}</div>
            </div>
            <div class="metric-card">
                <i class="metric-icon bi bi-fire"></i>
                <div class="metric-label">Most Used</div>
                <div class="metric-value">{{ $mostUsedTag?->issues_count ?? 0 }}</div>
            </div>
            <div class="metric-card">
                <i class="metric-icon bi bi-inbox"></i>
                <div class="metric-label">Unused</div>
                <div class="metric-value">{{ $unusedTags }}</div>
            </div>
        </div>

        <!-- TOOLBAR -->
        <form method="GET" action="{{ route('tags.index') }}" class="tags-toolbar">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search tags...">
            <button type="submit" class="ui-button secondary">
                <i class="bi bi-search"></i>
            </button>
        </form>

        <!-- TWO COLUMN LAYOUT -->
        <div class="tags-layout">
            <!-- LEFT: FORM PANEL -->
            @auth
                <div class="tags-form-panel" id="scrollToForm">
                    <div class="form-header">
                        <h2>Create Tag</h2>
                        <p>Add a new reusable label</p>
                    </div>

                    <form method="POST" action="{{ route('tags.store') }}">
                        @csrf

                        @if ($errors->any())
                            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid #dc2626; border-radius: 6px; padding: 10px; margin-bottom: 12px;">
                                @foreach ($errors->all() as $error)
                                    <p style="margin: 4px 0; font-size: 12px; color: #dc2626;">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <div class="form-group">
                            <label class="form-label">Tag Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g., bug, feature, docs" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Color</label>
                            <div class="form-group">
                                <label class="form-label" style="font-weight: 500; font-size: 11px;">Presets</label>
                                <div class="color-presets">
                                    <input type="radio" name="color_preset" value="#dc2626" id="red" style="display: none;">
                                    <label for="red" class="color-preset" style="background: #dc2626;" title="Red"></label>

                                    <input type="radio" name="color_preset" value="#f97316" id="orange" style="display: none;">
                                    <label for="orange" class="color-preset" style="background: #f97316;" title="Orange"></label>

                                    <input type="radio" name="color_preset" value="#eab308" id="yellow" style="display: none;">
                                    <label for="yellow" class="color-preset" style="background: #eab308;" title="Yellow"></label>

                                    <input type="radio" name="color_preset" value="#22c55e" id="green" style="display: none;">
                                    <label for="green" class="color-preset" style="background: #22c55e;" title="Green"></label>

                                    <input type="radio" name="color_preset" value="#0ea5e9" id="blue" style="display: none;">
                                    <label for="blue" class="color-preset" style="background: #0ea5e9;" title="Blue"></label>

                                    <input type="radio" name="color_preset" value="#8b5cf6" id="purple" style="display: none;">
                                    <label for="purple" class="color-preset" style="background: #8b5cf6;" title="Purple"></label>

                                    <input type="radio" name="color_preset" value="#ec4899" id="pink" style="display: none;">
                                    <label for="pink" class="color-preset" style="background: #ec4899;" title="Pink"></label>

                                    <input type="radio" name="color_preset" value="#6b7280" id="gray" style="display: none;">
                                    <label for="gray" class="color-preset" style="background: #6b7280;" title="Gray"></label>
                                </div>
                            </div>

                            <div class="color-input-group" style="margin-top: 8px;">
                                <input type="color" id="colorPicker" name="color" value="{{ old('color', '#0ea5e9') }}" class="color-input">
                                <input type="text" id="colorHex" name="color_hex" value="{{ old('color', '#0ea5e9') }}" class="color-text-input" placeholder="#0ea5e9" maxlength="7">
                            </div>
                        </div>

                        <div class="color-preview">
                            <div class="preview-label">Preview</div>
                            <span class="preview-badge" id="previewBadge" style="background: #0ea5e9;">Example Tag</span>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="ui-button primary">
                                <i class="bi bi-check2"></i>
                                Create Tag
                            </button>
                            <button type="reset" class="ui-button secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="tags-form-panel">
                    <div class="empty-state">
                        <i class="bi bi-lock"></i>
                        <h3>Log in required</h3>
                        <p>You need to log in to create tags.</p>
                        <a href="{{ route('login') }}" class="ui-button primary" style="margin-top: 12px;">Log In</a>
                    </div>
                </div>
            @endauth

            <!-- RIGHT: TAG LIST -->
            <div class="tags-list">
                <div class="list-header">
                    <h2>All Tags</h2>
                    <span class="list-count">{{ $tags->total() }}</span>
                </div>

                @forelse ($tags as $tag)
                    <div class="tag-item">
                        <span class="tag-swatch" style="background-color: {{ $tag->color ?: '#8c959f' }}"></span>

                        <div class="tag-info">
                            <p class="tag-name">{{ $tag->name }}</p>
                            <p class="tag-meta">
                                {{ $tag->color ?: 'No color' }} •
                                <span>{{ $tag->issues_count }} {{ Str::plural('issue', $tag->issues_count) }}</span>
                            </p>
                        </div>

                        <div class="tag-stats">
                            <span class="tag-stat">
                                <i class="bi bi-list-check"></i>
                                {{ $tag->issues_count }}
                            </span>
                        </div>

                        <div class="tag-actions">
                            <a href="{{ route('tags.edit', $tag) }}" class="icon-button" title="Edit tag">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('tags.destroy', $tag) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this tag?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-button danger" title="Delete tag">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-tag"></i>
                        <h3>No tags yet</h3>
                        <p>Create the first tag to organize your issues</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        // Color picker sync
        const colorPicker = document.getElementById('colorPicker');
        const colorHex = document.getElementById('colorHex');
        const previewBadge = document.getElementById('previewBadge');
        const colorPresets = document.querySelectorAll('input[name="color_preset"]');

        function updateColor(color) {
            colorPicker.value = color;
            colorHex.value = color;
            previewBadge.style.background = color;
        }

        colorPicker?.addEventListener('input', (e) => {
            updateColor(e.target.value);
        });

        colorHex?.addEventListener('input', (e) => {
            if (e.target.value.match(/^#[0-9a-f]{6}$/i)) {
                updateColor(e.target.value);
            }
        });

        colorPresets.forEach(preset => {
            preset?.addEventListener('change', (e) => {
                updateColor(e.target.value);
            });
        });

        // Initialize preview with form value
        const initialColor = colorPicker?.value || '#0ea5e9';
        previewBadge.style.background = initialColor;
    </script>

@endsection
