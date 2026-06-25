
<aside class="trackit-sidebar sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="trackit-sidebar-brand sidebar-brand" style="color: var(--trackit-primary); gap: 10px; padding: 0 20px 16px; margin-bottom: 12px;">
        <i class="bi bi-kanban" style="font-size: 20px;"></i>
        <span>Trackit</span>
    </a>

    <nav class="trackit-sidebar-nav sidebar-nav">
        <a href="{{ route('dashboard') }}"
            class="trackit-sidebar-link nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 icon"></i>
            <span class="label">Dashboard</span>
        </a>

        <a href="{{ route('issues.index') }}"
            class="trackit-sidebar-link nav-link {{ request()->routeIs('issues.index') || request()->routeIs('issues.show') || request()->routeIs('issues.edit') || request()->routeIs('issues.create') ? 'active' : '' }}">
            <i class="bi bi-list-check icon"></i>
            <span class="label">Issues</span>
        </a>

        <a href="{{ route('issues.kanban') }}"
            class="trackit-sidebar-link nav-link {{ request()->routeIs('issues.kanban') ? 'active' : '' }}">
            <i class="bi bi-kanban icon"></i>
            <span class="label">Kanban Board</span>
        </a>

        <a href="{{ route('projects.index') }}"
            class="trackit-sidebar-link nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
            <i class="bi bi-folder2-open icon"></i>
            <span class="label">Projects</span>
        </a>

        <div class="trackit-sidebar-section sidebar-section">
            <div class="trackit-sidebar-section-title sidebar-section-title">Management</div>

            <a href="{{ route('tags.index') }}"
                class="trackit-sidebar-link nav-link {{ request()->routeIs('tags.*') ? 'active' : '' }}">
                <i class="bi bi-tags icon"></i>
                <span class="label">Tags</span>
            </a>

            <a href="{{ route('teams.index') }}"
                class="trackit-sidebar-link nav-link {{ request()->routeIs('teams.*') ? 'active' : '' }}">
                <i class="bi bi-people icon"></i>
                <span class="label">Team</span>
            </a>
        </div>

        <div class="trackit-sidebar-section sidebar-section">
            <div class="trackit-sidebar-section-title sidebar-section-title">Settings</div>

            <a href="{{ route('settings.index') }}"
                class="trackit-sidebar-link nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear icon"></i>
                <span class="label">Settings</span>
            </a>
        </div>
    </nav>
</aside>
