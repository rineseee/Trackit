@extends('layouts.app')

@section('title', 'Team Management')

@section('content')
    <div class="mx-auto max-w-5xl space-y-3">
        <section style="border-radius: 10px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); padding: 12px 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="display: flex; flex-direction: column; gap: 12px; align-items: flex-start;">
                <div>
                    <p style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--trackit-muted); margin: 0;">Team Management</p>
                    <h1 style="margin: 4px 0 0; font-size: 18px; font-weight: 700; color: var(--trackit-text); letter-spacing: -0.01em;">Manage your team</h1>
                    <p style="margin: 4px 0 0; font-size: 12px; color: var(--trackit-muted); line-height: 1.5;">Invite members and manage roles.</p>
                </div>

                <a href="#invite-member" style="display: inline-flex; align-items: center; gap: 6px; border-radius: 6px; background: var(--trackit-primary); color: white; padding: 8px 14px; font-size: 12px; font-weight: 600; text-decoration: none; transition: all 150ms ease;">
                    <i class="bi bi-person-plus" style="font-size: 13px;"></i>
                    Invite
                </a>
            </div>
        </section>

        <section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 2px;">
            <div style="border-radius: 8px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); padding: 10px 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--trackit-muted); margin: 0;">Members</p>
                <p style="margin: 4px 0 2px; font-size: 20px; font-weight: 700; color: var(--trackit-text);">{{ $totalMembers }}</p>
                <p style="margin: 0; font-size: 11px; color: var(--trackit-muted);">Total team</p>
            </div>

            <div style="border-radius: 8px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); padding: 10px 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--trackit-muted); margin: 0;">Active</p>
                <p style="margin: 4px 0 2px; font-size: 20px; font-weight: 700; color: var(--trackit-text);">{{ $activeMembers }}</p>
                <p style="margin: 0; font-size: 11px; color: var(--trackit-muted);">Active users</p>
            </div>

            <div style="border-radius: 8px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); padding: 10px 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--trackit-muted); margin: 0;">Pending</p>
                <p style="margin: 4px 0 2px; font-size: 20px; font-weight: 700; color: var(--trackit-text);">{{ count($pendingInvitations) }}</p>
                <p style="margin: 0; font-size: 11px; color: var(--trackit-muted);">Invites</p>
            </div>
        </section>

        <div style="display: grid; grid-template-columns: 1fr; gap: 2px; margin-top: 8px;" class="lg:grid-cols-[0.95fr_1.35fr]">
            <aside id="invite-member" style="display: flex; flex-direction: column; gap: 2px;">
                <div style="border-radius: 10px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); padding: 12px 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <div style="margin-bottom: 10px;">
                        <h2 style="font-size: 15px; font-weight: 700; color: var(--trackit-text); margin: 0 0 4px;">Invite member</h2>
                        <p style="font-size: 12px; color: var(--trackit-muted); margin: 0;">Add someone to workspace.</p>
                    </div>

                    <form action="{{ route('teams.invite') }}" method="POST" style="display: flex; flex-direction: column; gap: 8px;">
                        @csrf

                        <label style="display: block;">
                            <span style="display: block; margin-bottom: 4px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">Email</span>
                            <input type="email" name="email" style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease;" placeholder="user@example.com" required>
                            @error('email')
                                <span style="display: block; margin-top: 4px; font-size: 11px; color: #dc2626;">{{ $message }}</span>
                            @enderror
                        </label>

                        <label style="display: block;">
                            <span style="display: block; margin-bottom: 4px; font-size: 12px; font-weight: 600; color: var(--trackit-text);">Role</span>
                            <select name="role" style="width: 100%; border-radius: 6px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); color: var(--trackit-text); padding: 8px 10px; font-size: 12px; outline: none; transition: all 150ms ease;" required>
                                <option value="member">Member</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </label>

                        <button type="submit" style="display: inline-flex; align-items: center; justify-content: center; gap: 6px; border-radius: 6px; background: var(--trackit-primary); color: white; padding: 8px 14px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 150ms ease;">
                            <i class="bi bi-send" style="font-size: 13px;"></i>
                            Send
                        </button>
                    </form>

                    <div style="margin-top: 10px; border-radius: 6px; background: var(--trackit-surface-soft); padding: 8px 10px; border: 1px solid var(--trackit-border);">
                        <p style="font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--trackit-muted); margin: 0;">Roles</p>
                        <div style="margin-top: 6px; display: flex; flex-direction: column; gap: 4px;">
                            <div style="font-size: 11px; color: var(--trackit-text);"><strong>Member:</strong> View & comment</div>
                            <div style="font-size: 11px; color: var(--trackit-text);"><strong>Manager:</strong> Create & edit</div>
                            <div style="font-size: 11px; color: var(--trackit-text);"><strong>Admin:</strong> Full control</div>
                        </div>
                    </div>
                </div>
            </aside>

            <section id="members-list" style="display: flex; flex-direction: column; gap: 2px;">
                <div style="border-radius: 10px; border: 1px solid var(--trackit-border); background: var(--trackit-surface); padding: 12px 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 8px;">
                        <div>
                            <h2 style="font-size: 15px; font-weight: 700; color: var(--trackit-text); margin: 0 0 4px;">Team members</h2>
                            <p style="font-size: 12px; color: var(--trackit-muted); margin: 0;">Owner first, then team.</p>
                        </div>
                        <span style="display: inline-flex; align-items: center; border-radius: 999px; background: var(--trackit-surface-soft); padding: 4px 10px; font-size: 11px; font-weight: 600; color: var(--trackit-text); border: 1px solid var(--trackit-border);">
                            {{ $totalMembers }} total
                        </span>
                    </div>

                    <div style="margin-top: 10px; display: flex; flex-direction: column; gap: 8px;">
                        @if($currentUser)
                            <article style="border-radius: 8px; border: 1px solid var(--trackit-primary); background: var(--trackit-primary-soft); padding: 8px 10px;">
                                <div style="display: flex; flex-direction: column; gap: 8px; align-items: flex-start;">
                                    <div style="display: flex; align-items: center; gap: 8px; width: 100%; min-width: 0;">
                                        <div style="display: flex; width: 32px; height: 32px; flex-shrink: 0; align-items: center; justify-content: center; border-radius: 6px; background: var(--trackit-primary); color: white; font-size: 12px; font-weight: 700;">
                                            {{ strtoupper(substr($currentUser->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $currentUser->name)[1] ?? '', 0, 1)) }}
                                        </div>
                                        <div style="min-width: 0;">
                                            <h3 style="font-size: 13px; font-weight: 600; color: var(--trackit-text); margin: 0;">{{ $currentUser->name }}</h3>
                                            <p style="font-size: 11px; color: var(--trackit-muted); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $currentUser->email }}</p>
                                        </div>
                                    </div>
                                    <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                        <span style="display: inline-flex; align-items: center; border-radius: 999px; background: var(--trackit-primary); color: white; padding: 3px 8px; font-size: 10px; font-weight: 600;">Owner</span>
                                        <span style="display: inline-flex; align-items: center; border-radius: 999px; background: #d1fae5; color: #065f46; padding: 3px 8px; font-size: 10px; font-weight: 600;">You</span>
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
