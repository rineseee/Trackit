@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <div class="mx-auto max-w-6xl space-y-8">
        <section class="page-banner animate-fade-in">
            <div class="page-banner-content">
                <span style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; background: var(--trackit-primary-soft); color: var(--trackit-primary); border-radius: 999px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 16px;">
                    <i class="bi bi-gear"></i>
                    Settings
                </span>
                <h1 style="margin: 0 0 12px; font-size: 32px; font-weight: 700; color: var(--trackit-text);">
                    Account settings
                </h1>
                <p style="margin: 0; font-size: 16px; color: var(--trackit-muted); line-height: 1.6;">
                    Update your profile, security settings, and preferences in one clean place.
                </p>
            </div>

            <div class="page-banner-meta">
                <div class="meta-tile">
                    <div class="meta-tile-label">Theme</div>
                    <div class="meta-tile-value">{{ ucfirst($user->preferences['theme'] ?? 'Light') }}</div>
                    <div style="font-size: 12px; color: var(--trackit-muted); margin-top: 4px;">Mode</div>
                </div>
                <div class="meta-tile">
                    <div class="meta-tile-label">Status</div>
                    <div class="meta-tile-value">Active</div>
                    <div style="font-size: 12px; color: var(--trackit-muted); margin-top: 4px;">Account</div>
                </div>
            </div>
        </section>

        <div class="grid gap-6 lg:grid-cols-[1fr_0.9fr]">
            <section class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-5">
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Profile</h2>
                        <p class="mt-1 text-sm text-slate-600">Keep your main account information up to date.</p>
                    </div>

                    <form action="{{ route('settings.updateProfile') }}" method="POST" class="grid gap-4 sm:grid-cols-2">
                        @csrf
                        @method('PUT')

                        <label class="block sm:col-span-1">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Full name</span>
                            <input type="text" name="name" value="{{ $user->name }}" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
                        </label>

                        <label class="block sm:col-span-1">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Email address</span>
                            <input type="email" name="email" value="{{ $user->email }}" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
                        </label>

                        <label class="block sm:col-span-1">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Phone</span>
                            <input type="tel" name="phone" value="{{ $user->phone ?? '' }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
                        </label>

                        <label class="block sm:col-span-1">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Company</span>
                            <input type="text" name="company" value="{{ $user->company ?? '' }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
                        </label>

                        <label class="block sm:col-span-2">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Bio</span>
                            <textarea name="bio" rows="4" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">{{ $user->bio ?? '' }}</textarea>
                        </label>

                        <div class="sm:col-span-2">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                                <i class="bi bi-check2"></i>
                                Save profile
                            </button>
                        </div>
                    </form>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-5">
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Password</h2>
                        <p class="mt-1 text-sm text-slate-600">Use a strong password to keep your account secure.</p>
                    </div>

                    <form action="{{ route('settings.updatePassword') }}" method="POST" class="grid gap-4 sm:grid-cols-2">
                        @csrf
                        @method('PUT')

                        <label class="block sm:col-span-2">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Current password</span>
                            <input type="password" name="current_password" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
                        </label>

                        <label class="block sm:col-span-1">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">New password</span>
                            <input type="password" name="password" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
                        </label>

                        <label class="block sm:col-span-1">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Confirm password</span>
                            <input type="password" name="password_confirmation" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
                        </label>

                        <div class="sm:col-span-2">
                            <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">
                                <i class="bi bi-lock"></i>
                                Update password
                            </button>
                        </div>
                    </form>
                </div>
            </section>

            <aside class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-5">
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Preferences</h2>
                        <p class="mt-1 text-sm text-slate-600">A few simple choices for how the app feels.</p>
                    </div>

                    <form action="{{ route('settings.updatePreferences') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Theme</span>
                            <select name="theme" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
                                <option value="light" {{ ($user->preferences['theme'] ?? 'light') === 'light' ? 'selected' : '' }}>Light</option>
                                <option value="dark" {{ ($user->preferences['theme'] ?? 'light') === 'dark' ? 'selected' : '' }}>Dark</option>
                            </select>
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Language</span>
                            <select name="language" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
                                <option value="en" {{ ($user->preferences['language'] ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                                <option value="sq" {{ ($user->preferences['language'] ?? 'en') === 'sq' ? 'selected' : '' }}>Shqip</option>
                                <option value="it" {{ ($user->preferences['language'] ?? 'en') === 'it' ? 'selected' : '' }}>Italiano</option>
                            </select>
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Timezone</span>
                            <select name="timezone" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100">
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
