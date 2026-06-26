@extends('layouts.app')

@section('title', 'Team Management')

@section('content')
    <style>
        .teams-wrapper {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .teams-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--trackit-border);
        }

        .teams-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .teams-metrics {
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

        .metric-label {
            font-size: 9px;
            font-weight: 700;
            color: var(--trackit-muted);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 3px;
        }

        .metric-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--trackit-text);
            margin-bottom: 2px;
        }

        .metric-desc {
            font-size: 10px;
            color: var(--trackit-muted);
        }

        .teams-layout {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 16px;
        }

        .form-panel {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 10px;
            padding: 16px;
            height: fit-content;
            position: sticky;
            top: 0;
        }

        .form-header {
            margin-bottom: 12px;
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
            margin-bottom: 10px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--trackit-text);
            margin-bottom: 4px;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid var(--trackit-border);
            background: var(--trackit-surface-soft);
            color: var(--trackit-text);
            border-radius: 6px;
            font-size: 12px;
            transition: all 150ms ease;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: var(--trackit-primary);
            outline: none;
            background: var(--trackit-surface);
        }

        .form-submit {
            width: 100%;
            padding: 8px 14px;
            background: var(--trackit-primary);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 150ms ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .form-submit:hover {
            opacity: 0.9;
        }

        .roles-info {
            margin-top: 12px;
            padding: 10px;
            background: var(--trackit-surface-soft);
            border-radius: 6px;
            border: 1px solid var(--trackit-border);
        }

        .roles-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--trackit-muted);
            margin-bottom: 6px;
            display: block;
        }

        .role-item {
            font-size: 11px;
            color: var(--trackit-text);
            margin-bottom: 3px;
        }

        .role-item strong {
            font-weight: 600;
        }

        .members-panel {
            background: var(--trackit-surface);
            border: 1px solid var(--trackit-border);
            border-radius: 10px;
            padding: 16px;
        }

        .members-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--trackit-border);
        }

        .members-header h2 {
            margin: 0 0 2px;
            font-size: 16px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .members-header p {
            margin: 0;
            font-size: 12px;
            color: var(--trackit-muted);
        }

        .member-count {
            display: inline-flex;
            align-items: center;
            background: var(--trackit-surface-soft);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            color: var(--trackit-muted);
            border: 1px solid var(--trackit-border);
        }

        .members-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 16px;
        }

        .member-card {
            background: var(--trackit-surface-soft);
            border: 1px solid var(--trackit-border);
            border-radius: 8px;
            padding: 12px;
            transition: all 150ms ease;
        }

        .member-card:hover {
            border-color: var(--trackit-primary);
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06);
        }

        .member-card.owner {
            border-color: var(--trackit-primary);
            background: var(--trackit-primary-soft);
        }

        .member-header {
            display: flex;
            gap: 8px;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .member-avatar {
            width: 32px;
            height: 32px;
            min-width: 32px;
            border-radius: 6px;
            background: var(--trackit-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }

        .member-card.owner .member-avatar {
            background: var(--trackit-primary);
        }

        .member-info {
            flex: 1;
            min-width: 0;
        }

        .member-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--trackit-text);
            margin: 0;
            word-break: break-word;
        }

        .member-email {
            font-size: 11px;
            color: var(--trackit-muted);
            margin: 2px 0 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .member-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-top: 6px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .badge-owner {
            background: var(--trackit-primary);
            color: white;
        }

        .badge-you {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-active {
            background: #dcfce7;
            color: #166534;
        }

        .badge-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .member-actions {
            display: flex;
            gap: 6px;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid var(--trackit-border);
        }

        .role-select {
            flex: 1;
            padding: 4px 6px;
            font-size: 11px;
            border-radius: 4px;
            border: 1px solid var(--trackit-border);
            background: var(--trackit-surface);
            color: var(--trackit-text);
            cursor: pointer;
        }

        .role-select:focus {
            border-color: var(--trackit-primary);
            outline: none;
        }

        .delete-btn {
            width: 28px;
            height: 28px;
            padding: 0;
            border: 1px solid var(--trackit-border);
            background: var(--trackit-surface);
            color: var(--trackit-text);
            border-radius: 4px;
            cursor: pointer;
            transition: all 150ms ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .delete-btn:hover {
            border-color: #dc2626;
            color: #dc2626;
        }

        .pending-section {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--trackit-border);
        }

        .pending-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .pending-header h3 {
            margin: 0 0 2px;
            font-size: 14px;
            font-weight: 700;
            color: var(--trackit-text);
        }

        .pending-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .pending-table thead {
            background: var(--trackit-surface-soft);
            border-bottom: 1px solid var(--trackit-border);
        }

        .pending-table th {
            padding: 8px 10px;
            text-align: left;
            font-weight: 700;
            color: var(--trackit-muted);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .pending-table td {
            padding: 8px 10px;
            border-bottom: 1px solid var(--trackit-border);
            color: var(--trackit-text);
        }

        .pending-table tr:last-child td {
            border-bottom: none;
        }

        .pending-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: 700;
            background: rgba(59, 130, 246, 0.1);
            color: #2563eb;
        }

        .cancel-btn {
            padding: 4px 8px;
            font-size: 11px;
            border-radius: 4px;
            border: 1px solid var(--trackit-border);
            background: var(--trackit-surface);
            color: var(--trackit-text);
            cursor: pointer;
            transition: all 150ms ease;
        }

        .cancel-btn:hover {
            background: #fee2e2;
            border-color: #dc2626;
            color: #dc2626;
        }

        .empty-state {
            padding: 20px;
            text-align: center;
            color: var(--trackit-muted);
            font-size: 12px;
        }

        @media (max-width: 1024px) {
            .teams-layout {
                grid-template-columns: 1fr;
            }

            .form-panel {
                position: relative;
            }

            .members-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .teams-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .pending-table {
                font-size: 11px;
            }

            .pending-table th,
            .pending-table td {
                padding: 6px 8px;
            }
        }

        html[data-theme='dark'] .form-panel,
        html[data-theme='dark'] .members-panel,
        html[data-theme='dark'] .metric-card,
        html[data-theme='dark'] .member-card {
            background: var(--trackit-surface);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .form-input,
        html[data-theme='dark'] .form-select,
        html[data-theme='dark'] .role-select {
            background: var(--trackit-surface-soft);
            border-color: rgba(148, 163, 184, 0.2);
            color: var(--trackit-text);
        }

        html[data-theme='dark'] .roles-info {
            background: var(--trackit-surface-soft);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .member-card {
            background: var(--trackit-surface-soft);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .pending-table thead {
            background: var(--trackit-surface-soft);
            border-color: rgba(148, 163, 184, 0.2);
        }

        html[data-theme='dark'] .pending-table td,
        html[data-theme='dark'] .pending-table th {
            border-color: rgba(148, 163, 184, 0.2);
        }
    </style>

    <div class="teams-wrapper">
        <!-- HEADER -->
        <div class="teams-header">
            <h1>Team Management</h1>
        </div>

        <!-- METRICS -->
        <div class="teams-metrics">
            <div class="metric-card">
                <div class="metric-label">Members</div>
                <div class="metric-value">{{ $totalMembers }}</div>
                <div class="metric-desc">Total team</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Active</div>
                <div class="metric-value">{{ $activeMembers }}</div>
                <div class="metric-desc">Active users</div>
            </div>
            <div class="metric-card">
                <div class="metric-label">Pending</div>
                <div class="metric-value">{{ count($pendingInvitations) }}</div>
                <div class="metric-desc">Invites</div>
            </div>
        </div>

        <!-- TWO COLUMN LAYOUT -->
        <div class="teams-layout">
            <!-- LEFT: FORM PANEL -->
            <div class="form-panel" id="invite-member">
                <div class="form-header">
                    <h2>Invite member</h2>
                    <p>Add someone to your team</p>
                </div>

                <form action="{{ route('teams.invite') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid #dc2626; border-radius: 6px; padding: 8px; margin-bottom: 10px;">
                            @foreach ($errors->all() as $error)
                                <p style="margin: 4px 0; font-size: 11px; color: #dc2626;">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" placeholder="user@example.com" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="member">Member</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="form-submit">
                        <i class="bi bi-person-plus"></i>
                        Invite
                    </button>

                    <div class="roles-info">
                        <span class="roles-label">Roles</span>
                        <div class="role-item"><strong>Member:</strong> View & comment</div>
                        <div class="role-item"><strong>Manager:</strong> Create & edit</div>
                        <div class="role-item"><strong>Admin:</strong> Full control</div>
                    </div>
                </form>
            </div>

            <!-- RIGHT: MEMBERS PANEL -->
            <div class="members-panel">
                <div class="members-header">
                    <div>
                        <h2>Team members</h2>
                        <p>Owner first, then team</p>
                    </div>
                    <span class="member-count">{{ $totalMembers }} total</span>
                </div>

                <div class="members-grid">
                    @if($currentUser)
                        <div class="member-card owner">
                            <div class="member-header">
                                <div class="member-avatar">
                                    {{ strtoupper(substr($currentUser->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $currentUser->name)[1] ?? '', 0, 1)) }}
                                </div>
                                <div class="member-info">
                                    <p class="member-name">{{ $currentUser->name }}</p>
                                    <p class="member-email">{{ $currentUser->email }}</p>
                                </div>
                            </div>
                            <div class="member-badges">
                                <span class="badge badge-owner">Owner</span>
                                <span class="badge badge-you">You</span>
                            </div>
                        </div>
                    @endif

                    @forelse($teamMembers as $member)
                        <div class="member-card">
                            <div class="member-header">
                                <div class="member-avatar" style="background: #6366f1;">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $member->name)[1] ?? '', 0, 1)) }}
                                </div>
                                <div class="member-info">
                                    <p class="member-name">{{ $member->name }}</p>
                                    <p class="member-email">{{ $member->email }}</p>
                                </div>
                            </div>
                            <div class="member-badges">
                                <span class="badge" style="background: #dbeafe; color: #1e40af;">{{ ucfirst($member->role ?? 'member') }}</span>
                                @if($member->is_active ?? true)
                                    <span class="badge badge-active">Active</span>
                                @else
                                    <span class="badge badge-inactive">Inactive</span>
                                @endif
                            </div>
                            <div class="member-actions">
                                <form action="{{ route('teams.updateRole', $member->id) }}" method="POST" style="flex: 1;" class="role-form" data-member-id="{{ $member->id }}" data-member-name="{{ $member->name }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="role" class="role-select" onchange="updateMemberRole(event)">
                                        <option value="member" {{ ($member->role ?? 'member') === 'member' ? 'selected' : '' }}>Member</option>
                                        <option value="manager" {{ ($member->role ?? 'member') === 'manager' ? 'selected' : '' }}>Manager</option>
                                        <option value="admin" {{ ($member->role ?? 'member') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </form>
                                <form action="{{ route('teams.remove', $member->id) }}" method="POST" onsubmit="return confirm('Remove {{ $member->name }}?')" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Remove member" class="delete-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        @if($currentUser)
                            <div style="grid-column: 1 / -1;">
                                <div class="empty-state">No additional team members yet.</div>
                            </div>
                        @endif
                    @endforelse
                </div>

                @if(count($pendingInvitations) > 0)
                    <div class="pending-section">
                        <div class="pending-header">
                            <div>
                                <h3>Pending invitations</h3>
                                <p style="margin: 0; font-size: 11px; color: var(--trackit-muted);">Waiting invites that can be cancelled</p>
                            </div>
                            <span class="member-count">{{ count($pendingInvitations) }} pending</span>
                        </div>

                        <table class="pending-table">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Invited by</th>
                                    <th>Sent</th>
                                    <th style="text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingInvitations as $inv)
                                    <tr>
                                        <td>{{ $inv['email'] }}</td>
                                        <td><span class="pending-badge">{{ ucfirst($inv['role']) }}</span></td>
                                        <td>{{ $inv['invited_by'] }}</td>
                                        <td>{{ $inv['sent_at'] }}</td>
                                        <td style="text-align: right;">
                                            <form action="{{ route('teams.cancelInvitation', $inv['email']) }}" method="POST" onsubmit="return confirm('Cancel this invitation?')" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="cancel-btn">Cancel</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function updateMemberRole(event) {
            const select = event.target;
            const form = select.closest('.role-form');
            const newRole = select.value;
            const memberId = form.dataset.memberId;
            const memberName = form.dataset.memberName;

            // Store old role before any changes
            let oldRole = null;
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].defaultSelected) {
                    oldRole = select.options[i].value;
                    break;
                }
            }

            // If we couldn't find old role, keep current
            if (!oldRole) {
                for (let i = 0; i < select.options.length; i++) {
                    if (select.options[i].selected && select.options[i].value !== newRole) {
                        oldRole = select.options[i].value;
                        break;
                    }
                }
            }

            if (!oldRole) oldRole = newRole;

            // Show loading state
            select.disabled = true;
            select.style.opacity = '0.6';

            // Submit via AJAX
            const formData = new FormData(form);

            console.log('Submitting role change for member', memberId, 'to role', newRole);
            console.log('Form action:', form.action);

            // Create form data with all fields
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            formData.append('_method', 'PUT');
            formData.append('role', newRole);

            console.log('FormData contents:');
            for (let [key, value] of formData.entries()) {
                console.log(key, ':', value);
            }

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Raw response object:', response);
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);

                return response.text().then(responseText => {
                    console.log('Response text:', responseText);
                    return { status: response.status, ok: response.ok, text: responseText };
                });
            })
            .then(result => {
                console.log('Processing result:', result);

                let data;
                try {
                    data = JSON.parse(result.text);
                    console.log('Parsed JSON:', data);
                } catch (e) {
                    console.error('JSON parse failed. Response was:', result.text);
                    throw new Error('Server returned invalid JSON: ' + result.text.substring(0, 100));
                }

                if (data.success) {
                    console.log('Success! Role updated');
                    select.value = newRole;
                    showRoleChangeSuccess(memberName, newRole);
                    select.disabled = false;
                    select.style.opacity = '1';
                } else {
                    console.log('Server returned error:', data.message);
                    showRoleChangeError(data.message || 'Failed to update role');
                    select.value = oldRole;
                    select.disabled = false;
                    select.style.opacity = '1';
                }
            })
            .catch(error => {
                console.error('Catch block error:', error);
                console.error('Error message:', error.message);
                console.error('Error stack:', error.stack);
                showRoleChangeError('Error: ' + error.message);
                select.value = oldRole;
                select.disabled = false;
                select.style.opacity = '1';
            });
        }

        function showRoleChangeSuccess(memberName, newRole) {
            const message = document.createElement('div');
            message.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #f0fdf4;
                border-left: 4px solid #22c55e;
                color: #166534;
                padding: 12px 16px;
                border-radius: 4px;
                z-index: 9999;
                font-size: 13px;
                font-weight: 600;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            `;
            message.innerHTML = `✅ ${memberName} is now ${newRole}`;
            document.body.appendChild(message);

            setTimeout(() => {
                message.remove();
            }, 3000);
        }

        function showRoleChangeError(errorMessage) {
            const message = document.createElement('div');
            message.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #fef2f2;
                border-left: 4px solid #dc2626;
                color: #991b1b;
                padding: 12px 16px;
                border-radius: 4px;
                z-index: 9999;
                font-size: 13px;
                font-weight: 600;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            `;
            message.innerHTML = `❌ ${errorMessage}`;
            document.body.appendChild(message);

            setTimeout(() => {
                message.remove();
            }, 4000);
        }
    </script>

@endsection
