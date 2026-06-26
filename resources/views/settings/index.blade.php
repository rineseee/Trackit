@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <style>
        .settings-wrapper {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .settings-header {
            padding-bottom: 12px;
            border-bottom: 1px solid var(--trackit-border);
        }

        .settings-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .settings-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .settings-panel {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 10px;
            padding: 16px;
        }

        .panel-header {
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--trackit-border);
        }

        .panel-header h2 {
            margin: 0 0 2px;
            font-size: 15px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .panel-header p {
            margin: 0;
            font-size: 12px;
            color: var(--trackit-muted);
        }

        .form-grid {
            display: grid;
            gap: 10px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .form-col-full {
            grid-column: 1 / -1;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--trackit-text);
        }

        .form-input,
        .form-select,
        .form-textarea {
            padding: 8px 10px;
            border: 1px solid var(--trackit-border);
            background: var(--trackit-surface-soft);
            color: var(--trackit-text);
            border-radius: 6px;
            font-size: 12px;
            transition: all 150ms ease;
            font-family: inherit;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: var(--trackit-primary);
            outline: none;
            background: var(--trackit-surface);
        }

        .form-textarea {
            resize: vertical;
            min-height: 60px;
        }

        .form-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--trackit-border);
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 16px;
            background: var(--trackit-primary);
            color: white !important;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 150ms ease;
            text-decoration: none;
            outline: none;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            opacity: 1;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary:focus {
            outline: 2px solid var(--trackit-primary);
            outline-offset: 2px;
        }

        .preferences-form {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .pref-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .pref-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--trackit-text);
        }

        .pref-select {
            padding: 8px 10px;
            border: 1px solid var(--trackit-border);
            background: var(--trackit-surface-soft);
            color: var(--trackit-text);
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 150ms ease;
        }

        .pref-select:focus {
            border-color: var(--trackit-primary);
            outline: none;
            background: var(--trackit-surface);
        }

        .notification-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px;
            border: 1px solid var(--trackit-border);
            background: var(--trackit-surface-soft);
            border-radius: 6px;
            cursor: pointer;
            transition: all 150ms ease;
        }

        .notification-item:hover {
            border-color: var(--trackit-primary);
        }

        .notification-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--trackit-primary);
            flex-shrink: 0;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            display: block;
            font-weight: 600;
            color: var(--trackit-text);
            font-size: 12px;
            margin-bottom: 2px;
        }

        .notification-desc {
            display: block;
            font-size: 11px;
            color: var(--trackit-muted);
        }

        .danger-panel {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-radius: 10px;
            padding: 16px;
            grid-column: 1 / -1;
            margin-top: 8px;
        }

        .danger-header h2 {
            margin: 0 0 2px;
            font-size: 15px;
            font-weight: 700;
            color: #991b1b;
        }

        .danger-header p {
            margin: 0;
            font-size: 12px;
            color: #b91c1c;
        }

        .danger-form {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #fca5a5;
        }

        .danger-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .danger-label {
            font-size: 12px;
            font-weight: 600;
            color: #991b1b;
        }

        .danger-input {
            padding: 8px 10px;
            border: 1px solid #fca5a5;
            background: #fef2f2;
            color: #991b1b;
            border-radius: 6px;
            font-size: 12px;
        }

        .danger-input::placeholder {
            color: #b91c1c;
        }

        .btn-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 16px;
            background: #dc2626;
            color: white !important;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 150ms ease;
            text-decoration: none;
            outline: none;
        }

        .btn-danger:hover {
            background: #b91c1c;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
        }

        .btn-danger:active {
            transform: translateY(0);
        }

        .btn-danger:focus {
            outline: 2px solid #dc2626;
            outline-offset: 2px;
        }

        .error-message {
            display: block;
            padding: 8px;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid #dc2626;
            border-radius: 6px;
            font-size: 11px;
            color: #dc2626;
            margin-bottom: 10px;
        }

        @media (max-width: 1024px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }

            .preferences-form {
                grid-template-columns: repeat(2, 1fr);
            }

            .danger-panel {
                grid-column: 1;
            }
        }

        @media (max-width: 640px) {
            .settings-header {
                margin-bottom: 8px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .preferences-form {
                grid-template-columns: 1fr;
            }

            .danger-panel {
                grid-column: 1;
            }
        }

        html[data-theme='dark'] .settings-panel,
        html[data-theme='dark'] .danger-panel {
            background: var(--trackit-surface);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .form-input,
        html[data-theme='dark'] .form-select,
        html[data-theme='dark'] .form-textarea,
        html[data-theme='dark'] .pref-select {
            background: var(--trackit-surface-soft);
            border-color: rgba(148, 163, 184, 0.2);
            color: var(--trackit-text);
        }

        html[data-theme='dark'] .notification-item {
            background: var(--trackit-surface-soft);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .danger-input {
            background: #7f1d1d;
            border-color: #991b1b;
            color: #fca5a5;
        }

        html[data-theme='dark'] .panel-header,
        html[data-theme='dark'] .danger-header {
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .form-actions,
        html[data-theme='dark'] .danger-form {
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .btn-primary {
            background: var(--trackit-primary);
            color: #ffffff;
        }

        html[data-theme='dark'] .btn-primary:hover {
            background: #1d4ed8;
            opacity: 1;
        }

        html[data-theme='dark'] .btn-danger {
            background: #ef4444;
            color: #ffffff;
        }

        html[data-theme='dark'] .btn-danger:hover {
            background: #dc2626;
        }

        html[data-theme='dark'] .danger-panel {
            background: #7f1d1d;
            border-color: #991b1b;
        }

        html[data-theme='dark'] .danger-header h2 {
            color: #fca5a5;
        }

        html[data-theme='dark'] .danger-header p {
            color: #fca5a5;
        }

        html[data-theme='dark'] .danger-label {
            color: #fca5a5;
        }
    </style>

    <div class="settings-wrapper">
        <!-- HEADER -->
        <div class="settings-header">
            <h1>Settings</h1>
        </div>

        <!-- MAIN GRID -->
        <div class="settings-grid">
            <!-- LEFT COLUMN: Profile & Password -->
            <div>
                <!-- PROFILE PANEL -->
                <div class="settings-panel">
                    <div class="panel-header">
                        <h2>Profile</h2>
                        <p>Keep your information up to date</p>
                    </div>

                    @if ($errors->any() && request()->getPathInfo() === route('settings.updateProfile', [], false))
                        <div class="error-message">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('settings.updateProfile') }}" method="POST" class="form-grid">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" value="{{ $user->name }}" required class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ $user->email }}" required class="form-input">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <input type="tel" name="phone" value="{{ $user->phone ?? '' }}" class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Company</label>
                                <input type="text" name="company" value="{{ $user->company ?? '' }}" class="form-input">
                            </div>
                        </div>

                        <div class="form-group form-col-full">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" class="form-textarea">{{ $user->bio ?? '' }}</textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary">
                                <i class="bi bi-check2"></i>
                                Save
                            </button>
                        </div>
                    </form>
                </div>

                <!-- PASSWORD PANEL -->
                <div class="settings-panel" style="margin-top: 16px;">
                    <div class="panel-header">
                        <h2>Password</h2>
                        <p>Keep your account secure</p>
                    </div>

                    @if ($errors->has('current_password'))
                        <div class="error-message">{{ $errors->first('current_password') }}</div>
                    @endif

                    <form action="{{ route('settings.updatePassword') }}" method="POST" class="form-grid">
                        @csrf
                        @method('PUT')

                        <div class="form-group form-col-full">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" required class="form-input">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" required class="form-input">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" required class="form-input">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary">
                                <i class="bi bi-lock"></i>
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RIGHT COLUMN: Preferences & Notifications -->
            <div>
                <!-- PREFERENCES PANEL -->
                <div class="settings-panel">
                    <div class="panel-header">
                        <h2>Preferences</h2>
                        <p>Customize your experience</p>
                    </div>

                    @if (session('success'))
                        <div class="success-message" style="margin-bottom: 12px; padding: 10px 12px; background: #f0fdf4; border-left: 4px solid #22c55e; border-radius: 4px; color: #166534;">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="error-message" style="margin-bottom: 12px;">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('settings.updatePreferences') }}" method="POST" class="form-grid" id="preferencesForm">
                        @csrf
                        @method('PUT')

                        <div class="preferences-form">
                            <div class="pref-group">
                                <label class="pref-label">Theme</label>
                                <select name="theme" class="pref-select" id="themeSelect">
                                    <option value="light" {{ ($user->preferences['theme'] ?? 'light') === 'light' ? 'selected' : '' }}>Light</option>
                                    <option value="dark" {{ ($user->preferences['theme'] ?? 'light') === 'dark' ? 'selected' : '' }}>Dark</option>
                                </select>
                            </div>

                            <div class="pref-group">
                                <label class="pref-label">Language</label>
                                <select name="language" class="pref-select" id="languageSelect">
                                    <option value="en" {{ ($user->preferences['language'] ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                                    <option value="sq" {{ ($user->preferences['language'] ?? 'en') === 'sq' ? 'selected' : '' }}>Shqip</option>
                                    <option value="it" {{ ($user->preferences['language'] ?? 'en') === 'it' ? 'selected' : '' }}>Italiano</option>
                                </select>
                            </div>

                            <div class="pref-group">
                                <label class="pref-label">Timezone</label>
                                <select name="timezone" class="pref-select" id="timezoneSelect">
                                    <option value="Europe/Budapest" {{ ($user->preferences['timezone'] ?? 'Europe/Budapest') === 'Europe/Budapest' ? 'selected' : '' }}>Budapest</option>
                                    <option value="Europe/Tirana" {{ ($user->preferences['timezone'] ?? '') === 'Europe/Tirana' ? 'selected' : '' }}>Tirana</option>
                                    <option value="Europe/Rome" {{ ($user->preferences['timezone'] ?? '') === 'Europe/Rome' ? 'selected' : '' }}>Rome</option>
                                    <option value="America/New_York" {{ ($user->preferences['timezone'] ?? '') === 'America/New_York' ? 'selected' : '' }}>New York</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-actions" style="grid-column: 1 / -1;">
                            <button type="submit" class="btn-primary" id="prefSaveBtn">
                                <i class="bi bi-check2"></i>
                                Save Preferences
                            </button>
                        </div>
                    </form>

                    <script>
                        document.getElementById('preferencesForm').addEventListener('submit', function(e) {
                            const theme = document.getElementById('themeSelect').value;
                            if (theme === 'dark') {
                                document.documentElement.setAttribute('data-theme', 'dark');
                                localStorage.setItem('theme', 'dark');
                            } else {
                                document.documentElement.removeAttribute('data-theme');
                                localStorage.setItem('theme', 'light');
                            }

                            const btn = document.getElementById('prefSaveBtn');
                            btn.disabled = true;
                            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Saving...';
                        });

                        const savedTheme = localStorage.getItem('theme') || 'light';
                        if (savedTheme === 'dark') {
                            document.documentElement.setAttribute('data-theme', 'dark');
                        }
                    </script>
                </div>

                <!-- NOTIFICATIONS PANEL -->
                <div class="settings-panel" style="margin-top: 16px;">
                    <div class="panel-header">
                        <h2>Notifications</h2>
                        <p>Choose what to receive</p>
                    </div>

                    <form action="{{ route('settings.updateNotifications') }}" method="POST" class="form-grid">
                        @csrf
                        @method('PUT')

                        <label class="notification-item">
                            <input type="checkbox" name="notifications_email" class="notification-checkbox" {{ $notifications_email ? 'checked' : '' }}>
                            <span class="notification-content">
                                <span class="notification-title">Email</span>
                                <span class="notification-desc">Receive via email</span>
                            </span>
                        </label>

                        <label class="notification-item">
                            <input type="checkbox" name="notifications_push" class="notification-checkbox" {{ $notifications_push ? 'checked' : '' }}>
                            <span class="notification-content">
                                <span class="notification-title">Push</span>
                                <span class="notification-desc">Browser notifications</span>
                            </span>
                        </label>

                        <label class="notification-item">
                            <input type="checkbox" name="notifications_sms" class="notification-checkbox" {{ $notifications_sms ? 'checked' : '' }}>
                            <span class="notification-content">
                                <span class="notification-title">SMS</span>
                                <span class="notification-desc">Text alerts</span>
                            </span>
                        </label>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary">
                                <i class="bi bi-check2"></i>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- DANGER ZONE: Full Width -->
            <div class="danger-panel">
                <div class="danger-header">
                    <h2>Danger Zone</h2>
                    <p>Permanently delete your account and all data</p>
                </div>

                <form action="{{ route('settings.deleteAccount') }}" method="POST" class="danger-form" onsubmit="return confirm('Are you absolutely sure? This will permanently delete your account and cannot be undone!');">
                    @csrf
                    @method('DELETE')

                    <div class="danger-group">
                        <label class="danger-label">Confirm Password</label>
                        <input type="password" name="password" placeholder="Enter your password" required class="danger-input">
                    </div>

                    <button type="submit" class="btn-danger">
                        <i class="bi bi-trash"></i>
                        Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection
