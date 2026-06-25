@extends('layouts.app')

@section('title', 'Team Management')

@section('content')
    <div class="mx-auto max-w-6xl space-y-8">
        <section class="page-banner animate-fade-in">
            <div class="page-banner-content">
                <span style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 12px; background: var(--trackit-primary-soft); color: var(--trackit-primary); border-radius: 999px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 16px;">
                    <i class="bi bi-people"></i>
                    Team management
                </span>
                <h1 style="margin: 0 0 12px; font-size: 32px; font-weight: 700; color: var(--trackit-text);">
                    Manage your team
                </h1>
                <p style="margin: 0; font-size: 16px; color: var(--trackit-muted); line-height: 1.6;">
                    Invite members, manage roles, and keep your team organized and productive.
                </p>
            </div>

            <div class="page-banner-meta">
                <div class="meta-tile">
                    <div class="meta-tile-label">Members</div>
                    <div class="meta-tile-value">{{ $totalMembers }}</div>
                    <div style="font-size: 12px; color: var(--trackit-muted); margin-top: 4px;">Total team</div>
                </div>
                <div class="meta-tile">
                    <div class="meta-tile-label">Pending</div>
                    <div class="meta-tile-value">{{ count($pendingInvitations) }}</div>
                    <div style="font-size: 12px; color: var(--trackit-muted); margin-top: 4px;">Invites</div>
                </div>
            </div>

            <div class="page-banner-actions">
                <a href="#invite-member" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-person-plus"></i>
                    Invite member
                </a>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Total members</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $totalMembers }}</p>
                <p class="mt-1 text-sm text-slate-500">Including the owner</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Active users</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $activeMembers }}</p>
                <p class="mt-1 text-sm text-slate-500">Marked as active</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Pending invites</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ count($pendingInvitations) }}</p>
                <p class="mt-1 text-sm text-slate-500">Waiting for acceptance</p>
            </div>
        </section>

        <div class="grid gap-6 lg:grid-cols-[0.95fr_1.35fr]">
            <aside id="invite-member" class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="mb-5">
                        <h2 class="text-2xl font-bold tracking-tight text-slate-900">Invite member</h2>
                        <p class="mt-1 text-sm text-slate-600">Send an invitation to add someone to the workspace.</p>
                    </div>

                    <form action="{{ route('teams.invite') }}" method="POST" class="space-y-4">
                        @csrf

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Email address</span>
                            <input type="email" name="email" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:ring-4 focus:ring-sky-100" placeholder="user@example.com" required>
                            @error('email')
                                <span class="mt-2 block text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">Role</span>
                            <select name="role" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100" required>
                                <option value="member">Member</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </label>

                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                            <i class="bi bi-send"></i>
                            Send invitation
                        </button>
                    </form>

                    <div class="mt-5 rounded-2xl bg-slate-50 p-4 ring-1 ring-slate-100">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Role guide</p>
                        <div class="mt-3 space-y-2 text-sm text-slate-600">
                            <div><strong class="text-slate-900">Member:</strong> View and comment</div>
                            <div><strong class="text-slate-900">Manager:</strong> Create and edit</div>
                            <div><strong class="text-slate-900">Admin:</strong> Full control</div>
                        </div>
                    </div>
                </div>
            </aside>

            <section id="members-list" class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Team members</h2>
                            <p class="mt-1 text-sm text-slate-600">The owner is highlighted first, followed by the rest of the team.</p>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">
                            {{ $totalMembers }} total
                        </span>
                    </div>

                    <div class="mt-6 space-y-4">
                        @if($currentUser)
                            <article class="rounded-2xl border border-sky-200 bg-sky-50 p-4">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-center gap-4 min-w-0">
                                        <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl bg-slate-900 text-lg font-bold text-white">
                                            {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                                            {{ strtoupper(substr(explode(' ', $currentUser->name)[1] ?? '', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <h3 class="text-lg font-bold text-slate-900">{{ $currentUser->name }}</h3>
                                            <p class="truncate text-sm text-slate-600">{{ $currentUser->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center rounded-full bg-slate-900 px-3 py-1 text-xs font-semibold text-white">Owner</span>
                                        <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">You</span>
                                    </div>
                                </div>
                            </article>
                        @endif

                        @forelse($teamMembers as $member)
                            <article class="team-member-item">
                                <div class="team-member-avatar">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $member->name)[1] ?? '', 0, 1)) }}
                                </div>
                                <div class="team-member-info">
                                    <div class="team-member-name">{{ $member->name }}</div>
                                    <div class="team-member-email">{{ $member->email }}</div>
                                    <div class="team-member-meta">
                                        <span class="team-role-badge">{{ ucfirst($member->role ?? 'member') }}</span>
                                        @if($member->is_active ?? true)
                                            <span class="team-status-badge">Active</span>
                                        @else
                                            <span class="team-status-badge inactive">Inactive</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="team-member-actions">
                                    <form action="{{ route('teams.updateRole', $member->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" onchange="this.form.submit()" title="Change role">
                                            <option value="member" {{ ($member->role ?? 'member') === 'member' ? 'selected' : '' }}>Member</option>
                                            <option value="manager" {{ ($member->role ?? 'member') === 'manager' ? 'selected' : '' }}>Manager</option>
                                            <option value="admin" {{ ($member->role ?? 'member') === 'admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </form>

                                    <form action="{{ route('teams.remove', $member->id) }}" method="POST" onsubmit="return confirm('Remove {{ $member->name }}?')" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Remove member">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </article>
                        @empty
                            @if(!$currentUser)
                                <div class="rounded-2xl border border-dashed border-slate-300 px-6 py-10 text-center text-slate-600">
                                    No team members yet.
                                </div>
                            @endif
                        @endforelse
                    </div>
                </div>

                @if(count($pendingInvitations) > 0)
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h2 class="text-2xl font-bold tracking-tight text-slate-900">Pending invitations</h2>
                                <p class="mt-1 text-sm text-slate-600">Waiting invites that can still be cancelled.</p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">
                                {{ count($pendingInvitations) }} pending
                            </span>
                        </div>

                        <div class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
                            <table class="w-full">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Email</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Role</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Invited by</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Sent</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 bg-white">
                                    @foreach($pendingInvitations as $inv)
                                        <tr>
                                            <td class="px-4 py-4 text-sm text-slate-900">{{ $inv['email'] }}</td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700">
                                                    {{ ucfirst($inv['role']) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-slate-600">{{ $inv['invited_by'] }}</td>
                                            <td class="px-4 py-4 text-sm text-slate-600">{{ $inv['sent_at'] }}</td>
                                            <td class="px-4 py-4 text-right">
                                                <form action="{{ route('teams.cancelInvitation', $inv['email']) }}" method="POST" onsubmit="return confirm('Cancel this invitation?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                                        <i class="bi bi-x-lg"></i>
                                                        Cancel
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection
