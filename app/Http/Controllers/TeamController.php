<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TeamController extends Controller
{
    /**
     * Display team management page
     */
    public function index()
    {
        $currentUser = auth()->user();

        // Get all users (team members)
        $teamMembers = User::where('id', '!=', $currentUser->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistics
        $totalMembers = $teamMembers->count() + 1; // +1 for current user
        $activeMembers = $teamMembers->where('is_active', true)->count() + 1;

        // Get pending invitations (from session or cache)
        $pendingInvitations = session('pending_invitations', []);

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
     * Invite a team member
     */
    public function invite(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'role' => 'required|in:member,manager,admin',
        ]);

        // Create pending invitation
        $pendingInvitations = session('pending_invitations', []);
        $invitationToken = bin2hex(random_bytes(32));

        $pendingInvitations[] = [
            'email' => $validated['email'],
            'role' => $validated['role'],
            'token' => $invitationToken,
            'sent_at' => now()->format('Y-m-d H:i:s'),
            'invited_by' => auth()->user()->name,
        ];

        session(['pending_invitations' => $pendingInvitations]);

        // In production, you would send an email here:
        // Mail::send('emails.team-invitation', [
        //     'email' => $validated['email'],
        //     'role' => $validated['role'],
        //     'inviter' => auth()->user()->name,
        //     'link' => route('team.accept-invitation', $invitationToken),
        // ], function ($message) use ($validated) {
        //     $message->to($validated['email'])->subject('You are invited to join our team');
        // });

        return back()->with('success', 'Invitation sent to ' . $validated['email']);
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
     * Update member role
     */
    public function updateRole(Request $request, $memberId)
    {
        $validated = $request->validate([
            'role' => 'required|in:member,manager,admin',
        ]);

        $member = User::findOrFail($memberId);

        // Only allow admin to update roles
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized');
        }

        // Update role (assuming you have a role field or use a package)
        $member->update(['role' => $validated['role']]);

        return back()->with('success', $member->name . ' role updated to ' . $validated['role']);
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
