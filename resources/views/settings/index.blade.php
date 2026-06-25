@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="mx-auto max-w-5xl space-y-3">
        <section style="border-radius: 10px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; flex-direction: column; gap: 12px; align-items: flex-start;">
                <div>
                    <p style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--trackit-muted); margin: 0;">Settings</p>
                    <h1 style="margin: 4px 0 0; font-size: 18px; font-weight: 700; color: var(--trackit-text); letter-spacing: -0.01em;">Account settings</h1>
                    <p style="margin: 4px 0 0; font-size: 12px; color: var(--trackit-muted); line-height: 1.5;">Update your profile, security, and preferences.</p>
                </div>
            </div>
        </section>

        <div style="display: grid; grid-template-columns: 1fr; gap: 3px;" class="lg:grid-cols-[1fr_0.9fr]">
            <section class="space-y-6">
                <div style="border-radius: 10px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); padding: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <div style="margin-bottom: 14px;">
                        <h2 style="font-size: 16px; font-weight: 700; color: var(--trackit-text); margin: 0 0 4px;">Profile</h2>
                        <p style="font-size: 12px; color: var(--trackit-muted); margin: 0;">Keep your information up to date.</p>
                    </div>

                    <form action="{{ route('settings.updateProfile') }}" method="POST" class="grid gap-3 sm:grid-cols-2">
                        @csrf
                        @method('PUT')

                        <label class="block sm:col-span-1">
                            <span style="display: block; margin-bottom: 6px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">Full name</span>
                            <input type="text" name="name" value="{{ $user->name }}" required style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease;">
                        </label>

                        <label class="block sm:col-span-1">
                            <span style="display: block; margin-bottom: 6px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">Email</span>
                            <input type="email" name="email" value="{{ $user->email }}" required style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease;">
                        </label>

                        <label class="block sm:col-span-1">
                            <span style="display: block; margin-bottom: 6px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">Phone</span>
                            <input type="tel" name="phone" value="{{ $user->phone ?? '' }}" style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease;">
                        </label>

                        <label class="block sm:col-span-1">
                            <span style="display: block; margin-bottom: 6px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">Company</span>
                            <input type="text" name="company" value="{{ $user->company ?? '' }}" style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease;">
                        </label>

                        <label class="block sm:col-span-2">
                            <span style="display: block; margin-bottom: 6px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">Bio</span>
                            <textarea name="bio" rows="3" style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease; font-family: inherit;">{{ $user->bio ?? '' }}</textarea>
                        </label>

                        <div class="sm:col-span-2">
                            <button type="submit" style="display: inline-flex; align-items: center; gap: 6px; border-radius: 6px; background: var(--trackit-primary); color: white; padding: 8px 14px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 150ms ease;">
                                <i class="bi bi-check2" style="font-size: 13px;"></i>
                                Save
                            </button>
                        </div>
                    </form>
                </div>

                <div style="border-radius: 10px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); padding: 12px 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <div style="margin-bottom: 10px;">
                        <h2 style="font-size: 15px; font-weight: 700; color: var(--trackit-text); margin: 0 0 4px;">Password</h2>
                        <p style="font-size: 12px; color: var(--trackit-muted); margin: 0;">Keep your account secure.</p>
                    </div>

                    <form action="{{ route('settings.updatePassword') }}" method="POST" class="grid gap-3 sm:grid-cols-2">
                        @csrf
                        @method('PUT')

                        <label class="block sm:col-span-2">
                            <span style="display: block; margin-bottom: 4px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">Current password</span>
                            <input type="password" name="current_password" required style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease;">
                        </label>

                        <label class="block sm:col-span-1">
                            <span style="display: block; margin-bottom: 4px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">New password</span>
                            <input type="password" name="password" required style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease;">
                        </label>

                        <label class="block sm:col-span-1">
                            <span style="display: block; margin-bottom: 4px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">Confirm</span>
                            <input type="password" name="password_confirmation" required style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease;">
                        </label>

                        <div class="sm:col-span-2">
                            <button type="submit" style="display: inline-flex; align-items: center; gap: 6px; border-radius: 6px; background: var(--trackit-primary); color: white; padding: 8px 14px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 150ms ease;">
                                <i class="bi bi-lock" style="font-size: 13px;"></i>
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <aside class="space-y-6">
                <div style="border-radius: 12px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <div style="margin-bottom: 20px;">
                        <h2 style="font-size: 20px; font-weight: 700; color: var(--trackit-text); margin: 0 0 8px;">Preferences</h2>
                        <p style="font-size: 14px; color: var(--trackit-muted); margin: 0;">A few simple choices for how the app feels.</p>
                    </div>

                    <form action="{{ route('settings.updatePreferences') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <label class="block">
                            <span style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--trackit-text);">Theme</span>
                            <select name="theme" style="width: 100%; border-radius: 8px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 10px 12px; font-size: 13px; outline: none; transition: all 150ms ease;">
                                <option value="light" {{ ($user->preferences['theme'] ?? 'light') === 'light' ? 'selected' : '' }}>Light</option>
                                <option value="dark" {{ ($user->preferences['theme'] ?? 'light') === 'dark' ? 'selected' : '' }}>Dark</option>
                            </select>
                        </label>

                        <label class="block">
                            <span style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--trackit-text);">Language</span>
                            <select name="language" style="width: 100%; border-radius: 8px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 10px 12px; font-size: 13px; outline: none; transition: all 150ms ease;">
                                <option value="en" {{ ($user->preferences['language'] ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                                <option value="sq" {{ ($user->preferences['language'] ?? 'en') === 'sq' ? 'selected' : '' }}>Shqip</option>
                                <option value="it" {{ ($user->preferences['language'] ?? 'en') === 'it' ? 'selected' : '' }}>Italiano</option>
                            </select>
                        </label>

                        <label class="block">
                            <span style="display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: var(--trackit-text);">Timezone</span>
                            <select name="timezone" style="width: 100%; border-radius: 8px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 10px 12px; font-size: 13px; outline: none; transition: all 150ms ease;">
                                <option value="Europe/Budapest" {{ ($user->preferences['timezone'] ?? 'Europe/Budapest') === 'Europe/Budapest' ? 'selected' : '' }}>Europe/Budapest</option>
                                <option value="Europe/Tirana" {{ ($user->preferences['timezone'] ?? '') === 'Europe/Tirana' ? 'selected' : '' }}>Europe/Tirana</option>
                                <option value="Europe/Rome" {{ ($user->preferences['timezone'] ?? '') === 'Europe/Rome' ? 'selected' : '' }}>Europe/Rome</option>
                                <option value="America/New_York" {{ ($user->preferences['timezone'] ?? '') === 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                            </select>
                        </label>

                        <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                            <i class="bi bi-check2"></i>
                            Save preferences
                        </button>
                    </form>
                </div>

                <div style="border-radius: 10px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); padding: 12px 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <div style="margin-bottom: 10px;">
                        <h2 style="font-size: 15px; font-weight: 700; color: var(--trackit-text); margin: 0 0 4px;">Notifications</h2>
                        <p style="font-size: 12px; color: var(--trackit-muted); margin: 0;">Choose what to receive.</p>
                    </div>

                    <form action="{{ route('settings.updateNotifications') }}" method="POST" style="display: flex; flex-direction: column; gap: 6px;">
                        @csrf
                        @method('PUT')

                        <label style="display: flex; align-items: center; gap: 8px; border-radius: 6px; border: 1px solid var(--trackit-border); padding: 8px 10px; cursor: pointer; transition: all 150ms ease; background: var(--trackit-surface-soft);">
                            <input type="checkbox" name="notifications_email" style="width: 16px; height: 16px; cursor: pointer; accent-color: #000000;" {{ $notifications_email ? 'checked' : '' }}>
                            <span style="flex: 1;">
                                <span style="display: block; font-weight: 600; color: var(--trackit-text); font-size: 12px;">Email</span>
                                <span style="display: block; font-size: 11px; color: var(--trackit-muted);">Via email</span>
                            </span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 8px; border-radius: 6px; border: 1px solid var(--trackit-border); padding: 8px 10px; cursor: pointer; transition: all 150ms ease; background: var(--trackit-surface-soft);">
                            <input type="checkbox" name="notifications_push" style="width: 16px; height: 16px; cursor: pointer; accent-color: #000000;" {{ $notifications_push ? 'checked' : '' }}>
                            <span style="flex: 1;">
                                <span style="display: block; font-weight: 600; color: var(--trackit-text); font-size: 12px;">Push</span>
                                <span style="display: block; font-size: 11px; color: var(--trackit-muted);">Browser</span>
                            </span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 8px; border-radius: 6px; border: 1px solid var(--trackit-border); padding: 8px 10px; cursor: pointer; transition: all 150ms ease; background: var(--trackit-surface-soft);">
                            <input type="checkbox" name="notifications_sms" style="width: 16px; height: 16px; cursor: pointer; accent-color: #000000;" {{ $notifications_sms ? 'checked' : '' }}>
                            <span style="flex: 1;">
                                <span style="display: block; font-weight: 600; color: var(--trackit-text); font-size: 12px;">SMS</span>
                                <span style="display: block; font-size: 11px; color: var(--trackit-muted);">Text alerts</span>
                            </span>
                        </label>

                        <button type="submit" style="display: inline-flex; align-items: center; gap: 6px; border-radius: 6px; background: var(--trackit-primary); color: white; padding: 8px 14px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 150ms ease; align-self: flex-start;">
                            <i class="bi bi-check2" style="font-size: 13px;"></i>
                            Save
                        </button>
                    </form>
                </div>

                <div style="border-radius: 10px; border: 1px solid #fca5a5; background: #fef2f2; padding: 12px 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <div style="margin-bottom: 8px;">
                        <h2 style="font-size: 15px; font-weight: 700; color: #991b1b; margin: 0 0 2px;">Danger</h2>
                        <p style="font-size: 12px; color: #b91c1c; margin: 0;">Permanently delete account.</p>
                    </div>

                    <form action="{{ route('settings.deleteAccount') }}" method="POST" onsubmit="return confirm('Are you sure? This will permanently delete your account!');" style="display: flex; flex-direction: column; gap: 6px;">
                        @csrf
                        @method('DELETE')

                        <label style="display: block;">
                            <span style="display: block; margin-bottom: 4px; font-size: 12px; font-weight: 600; color: #991b1b;">Confirm password</span>
                            <input type="password" name="password" placeholder="Enter password" required style="width: 100%; border-radius: 6px; border: 1px solid #fca5a5; background: #fef2f2; color: #991b1b; padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease;">
                        </label>

                        <button type="submit" style="display: inline-flex; align-items: center; gap: 6px; border-radius: 6px; background: #dc2626; color: white; padding: 8px 14px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 150ms ease; align-self: flex-start;">
                            <i class="bi bi-trash" style="font-size: 13px;"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
@endsection
