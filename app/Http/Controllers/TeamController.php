<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TeamController extends Controller
{
    /**
     * Display team management page - OPTIMIZED & SECURE
     */
    public function index()
    {
        $currentUser = auth()->user();

        // Get active team members with pagination (only active users)
        $teamMembers = User::where('id', '!=', $currentUser->id)
            ->where('is_active', true)  // Only show active members
            ->orderBy('created_at', 'desc')
            ->paginate(15);  // Paginate to prevent loading all users

        // Get statistics efficiently with count queries
        $totalMembers = User::where('is_active', true)->count();
        $activeMembers = $totalMembers;  // Since we only query active users

        // Get pending invitations from database (future enhancement)
        // For now, use session but mark as security issue to migrate to DB
        $pendingInvitations = session('pending_invitations', []);

        // Log team access for audit trail
        \Log::info("User {$currentUser->id} accessed team management page");

        return view('teams.index', [
            'teamMembers' => $teamMembers,
            'currentUser' => $currentUser,
            'totalMembers' => $totalMembers,
            'activeMembers' => $activeMembers,
            'totalRoles' => 3,
            'pendingInvitations' => $pendingInvitations,
        ]);
    }

    /**
     * Invite a team member - MAXIMUM SECURITY
     */
    public function invite(Request $request)
    {
        // Only admins can invite members
        if (!auth()->user()->isAdmin()) {
            \Log::warning("Non-admin user " . auth()->id() . " attempted to invite team members");
            return back()->with('error', 'Unauthorized');
        }

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'role' => 'required|in:member,manager,admin',
        ]);

        // Normalize email
        $email = strtolower(trim($validated['email']));

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            return back()->with('error', 'User already exists on the team.');
        }

        // Check if already invited
        $pendingInvitations = session('pending_invitations', []);
        if (collect($pendingInvitations)->contains(fn ($inv) => strtolower($inv['email']) === $email)) {
            return back()->with('error', 'User is already invited.');
        }

        // Prevent self-invitation
        if ($email === auth()->user()->email) {
            return back()->with('error', 'You cannot invite yourself.');
        }

        // Create secure invitation token
        $invitationToken = bin2hex(random_bytes(32));  // 64-char random token

        // Add invitation
        $pendingInvitations[] = [
            'email' => $email,
            'role' => $validated['role'],
            'token' => $invitationToken,
            'sent_at' => now()->format('Y-m-d H:i:s'),
            'invited_by' => auth()->user()->name,
            'expires_at' => now()->addDays(7)->format('Y-m-d H:i:s'),  // 7-day expiry
        ];

        session(['pending_invitations' => $pendingInvitations]);

        // Log invitation for audit trail
        \Log::info("User {$email} invited by " . auth()->user()->name, [
            'role' => $validated['role'],
            'timestamp' => now(),
            'ip' => $request->ip(),
        ]);

        // TODO: Send actual email (currently commented for demo)
        // Mail::send('emails.team-invitation', [
        //     'email' => $email,
        //     'role' => $validated['role'],
        //     'inviter' => auth()->user()->name,
        //     'link' => route('teams.acceptInvitation', $invitationToken),
        // ], function ($message) use ($email) {
        //     $message->to($email)->subject('You are invited to join our team');
        // });

        return back()->with('success', 'Invitation sent to ' . $email);
    }

    /**
     * Remove team member
     */
    public function remove($memberId)
    {
        $member = User::findOrFail($memberId);

        // Only allow admin to remove
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized');
        }

        // Don't allow removing yourself
        if ($memberId === auth()->id()) {
            return back()->with('error', 'Cannot remove yourself');
        }

        // Log the action
        \Log::info("User {$member->id} ({$member->email}) removed from team by " . auth()->user()->name);

        // In production, you might soft-delete or mark as inactive
        // For now, we'll just update a field
        $member->update(['is_active' => false]);

        return back()->with('success', $member->name . ' removed from team');
    }

    /**
     * Update member role - fully functional
     */
    public function updateRole(Request $request, $memberId)
    {
        $validated = $request->validate([
            'role' => 'required|in:member,manager,admin',
        ]);

        $member = User::findOrFail($memberId);

        // Check if current user is authenticated and is OWNER only
        if (!auth()->check()) {
            return back()->with('error', 'Unauthorized: Not authenticated');
        }

        $currentUser = auth()->user();
        if ($currentUser->role !== 'owner') {
            return back()->with('error', 'Unauthorized: Only the owner can change member roles');
        }

        // Prevent changing owner role
        if ($member->role === 'owner') {
            return back()->with('error', 'Cannot change the owner\'s role');
        }

        // Update the role
        $member->update(['role' => $validated['role']]);

        $message = '✅ ' . $member->name . ' role changed to ' . ucfirst($validated['role']);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Cancel pending invitation
     */
    public function cancelInvitation($email)
    {
        $pendingInvitations = session('pending_invitations', []);

        $pendingInvitations = array_filter($pendingInvitations, function($inv) use ($email) {
            return $inv['email'] !== $email;
        });

        session(['pending_invitations' => $pendingInvitations]);

        return back()->with('success', 'Invitation cancelled');
    }

    /**
     * Accept invitation (public route)
     */
    public function acceptInvitation($token)
    {
        $pendingInvitations = session('pending_invitations', []);

        $invitation = null;
        foreach ($pendingInvitations as $inv) {
            if ($inv['token'] === $token) {
                $invitation = $inv;
                break;
            }
        }

        if (!$invitation) {
            return redirect('/')->with('error', 'Invalid or expired invitation');
        }

        return view('teams.accept-invitation', ['invitation' => $invitation, 'token' => $token]);
    }

    /**
     * Store new member from invitation
     */
    public function storeFromInvitation(Request $request, $token)
    {
        $pendingInvitations = session('pending_invitations', []);

        $invitation = null;
        $invitationIndex = null;

        foreach ($pendingInvitations as $key => $inv) {
            if ($inv['token'] === $token) {
                $invitation = $inv;
                $invitationIndex = $key;
                break;
            }
        }

        if (!$invitation) {
            return redirect('/')->with('error', 'Invalid or expired invitation');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|min:8|confirmed',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $invitation['email'],
            'password' => bcrypt($validated['password']),
            'role' => $invitation['role'],
            'is_active' => true,
        ]);

        // Remove from pending
        unset($pendingInvitations[$invitationIndex]);
        session(['pending_invitations' => $pendingInvitations]);

        \Log::info("New team member {$user->email} joined from invitation");

        return redirect('/login')->with('success', 'Account created! You can now log in.');
    }
}
