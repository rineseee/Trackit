<nav class="trackit-topbar" aria-label="Primary navigation">
    <div class="trackit-topbar-left">
        <button type="button" class="trackit-icon-button trackit-sidebar-toggle" id="sidebarToggle"
            aria-label="Open navigation" aria-controls="sidebar" aria-expanded="false">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <div class="trackit-topbar-right">
        <button type="button" class="trackit-icon-button" id="darkModeToggle" title="Toggle dark mode"
            aria-label="Toggle dark mode">
            <i class="bi bi-moon-stars"></i>
        </button>

        @auth
            <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;">
                @csrf
                <button type="submit" class="trackit-icon-button" title="Logout" aria-label="Logout" style="color: #ef4444;">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        @endauth

        <div class="trackit-profile-menu">
            <button type="button" class="trackit-avatar" id="profileToggle" aria-label="Open user menu"
                aria-expanded="false" aria-controls="profileDropdown">
                {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
            </button>

            <div class="trackit-profile-dropdown" id="profileDropdown">
                <div class="trackit-profile-header">
                    <div class="trackit-profile-name">{{ auth()->user()?->name ?? 'User' }}</div>
                    <div class="trackit-profile-email">{{ auth()->user()?->email ?? '' }}</div>
                </div>

                <a href="{{ route('settings.index') }}" class="trackit-profile-item">
                    <i class="bi bi-gear"></i>
                    Settings
                </a>
            </div>
        </div>
    </div>
</nav>