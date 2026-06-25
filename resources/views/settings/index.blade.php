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

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-5">
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Notifications</h2>
                        <p class="mt-1 text-sm text-slate-600">Keep only the updates you want.</p>
                    </div>

                    <form action="{{ route('settings.updateNotifications') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <label class="flex items-center gap-3 rounded-2xl border border-slate-200 p-4">
                            <input type="checkbox" name="notifications_email" class="h-5 w-5 rounded border-slate-300 text-sky-600" {{ $notifications_email ? 'checked' : '' }}>
                            <span>
                                <span class="block font-semibold text-slate-900">Email</span>
                                <span class="block text-sm text-slate-500">Receive notifications via email</span>
                            </span>
                        </label>

                        <label class="flex items-center gap-3 rounded-2xl border border-slate-200 p-4">
                            <input type="checkbox" name="notifications_push" class="h-5 w-5 rounded border-slate-300 text-sky-600" {{ $notifications_push ? 'checked' : '' }}>
                            <span>
                                <span class="block font-semibold text-slate-900">Push</span>
                                <span class="block text-sm text-slate-500">Browser notifications</span>
                            </span>
                        </label>

                        <label class="flex items-center gap-3 rounded-2xl border border-slate-200 p-4">
                            <input type="checkbox" name="notifications_sms" class="h-5 w-5 rounded border-slate-300 text-sky-600" {{ $notifications_sms ? 'checked' : '' }}>
                            <span>
                                <span class="block font-semibold text-slate-900">SMS</span>
                                <span class="block text-sm text-slate-500">Text message alerts</span>
                            </span>
                        </label>

                        <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                            <i class="bi bi-check2-circle"></i>
                            Save notifications
                        </button>
                    </form>
                </div>

                <div class="rounded-3xl border border-red-200 bg-red-50 p-6 shadow-sm">
                    <div class="mb-4">
                        <h2 class="text-2xl font-bold tracking-tight text-red-700">Danger zone</h2>
                        <p class="mt-1 text-sm text-red-700/80">This action removes your account permanently.</p>
                    </div>

                    <form action="{{ route('settings.deleteAccount') }}" method="POST" onsubmit="return confirm('Are you sure? This will permanently delete your account and all data!');" class="space-y-4">
                        @csrf
                        @method('DELETE')

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-red-700">Confirm password</span>
                            <input type="password" name="password" placeholder="Enter your password" required class="w-full rounded-xl border border-red-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-red-400 focus:ring-4 focus:ring-red-100">
                        </label>

                        <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                            <i class="bi bi-trash"></i>
                            Delete account
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
@endsection
