<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $user = auth()->user();

        return view('settings.index', [
            'user' => $user,
            'themes' => ['light', 'dark'],
            'languages' => ['en', 'sq', 'it'],
            'notifications_email' => $user ? $user->preferences['notifications_email'] ?? true : false,
            'notifications_push' => $user ? $user->preferences['notifications_push'] ?? true : false,
            'notifications_sms' => $user ? $user->preferences['notifications_sms'] ?? false : false,
        ]);
    }

    /**
     * Update profile settings
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
        ]);

        auth()->user()->update($validated);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => bcrypt($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Update preferences
     */
    public function updatePreferences(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:light,dark',
            'language' => 'required|in:en,sq,it',
            'timezone' => 'required|timezone',
        ]);

        $preferences = auth()->user()->preferences ?? [];
        $preferences = array_merge($preferences, $validated);

        auth()->user()->update([
            'preferences' => $preferences,
        ]);

        return back()->with('success', 'Preferences updated successfully!');
    }

    /**
     * Update notification settings
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'notifications_email' => 'boolean',
            'notifications_push' => 'boolean',
            'notifications_sms' => 'boolean',
            'notifications_issues' => 'boolean',
            'notifications_comments' => 'boolean',
            'notifications_mentions' => 'boolean',
        ]);

        $preferences = auth()->user()->preferences ?? [];
        $preferences = array_merge($preferences, $validated);

        auth()->user()->update([
            'preferences' => $preferences,
        ]);

        return back()->with('success', 'Notification settings updated!');
    }

    /**
     * Update security settings
     */
    public function updateSecurity(Request $request)
    {
        $validated = $request->validate([
            'two_factor' => 'boolean',
            'login_notifications' => 'boolean',
            'device_approval' => 'boolean',
        ]);

        $preferences = auth()->user()->preferences ?? [];
        $preferences = array_merge($preferences, $validated);

        auth()->user()->update([
            'preferences' => $preferences,
        ]);

        return back()->with('success', 'Security settings updated!');
    }

    /**
     * Get active sessions
     */
    public function getSessions()
    {
        // This would fetch from a sessions table
        $sessions = [
            [
                'id' => 1,
                'device' => 'Chrome on Windows',
                'ip' => '192.168.1.1',
                'last_active' => now()->subHours(2),
                'is_current' => true,
            ],
        ];

        return response()->json($sessions);
    }

    /**
     * Delete account
     */
    public function deleteAccount(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = auth()->user();

        // Log the deletion
        \Log::info("User {$user->id} ({$user->email}) account deleted");

        // Delete user
        $user->delete();

        auth()->logout();

        return redirect('/')->with('success', 'Account deleted successfully');
    }
}
