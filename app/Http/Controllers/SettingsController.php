<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
     * Update password with MAXIMUM SECURITY
     */
    public function updatePassword(Request $request)
    {
        // Strong password validation
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                Password::min(12)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),  // Check against breached passwords database
            ],
        ]);

        $user = auth()->user();

        // Prevent reusing the same password
        if (Hash::check($validated['password'], $user->password)) {
            return back()->withErrors(['password' => 'New password must be different from current password.']);
        }

        // Update password using Hash::make() consistently
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Log password change for audit trail
        \Log::info("Password changed for user {$user->id} ({$user->email})", [
            'ip' => $request->ip(),
            'timestamp' => now(),
        ]);

        // Invalidate all other sessions for security
        $request->session()->invalidateOtherSessions();

        // Clear all tokens/remember tokens
        $user->update(['remember_token' => null]);

        // Force re-authentication for sensitive operations
        return redirect()->route('dashboard')
            ->with('success', 'Password updated! Please log in again for security.');
    }

    /**
     * Update preferences with form request validation
     */
    public function updatePreferences(\App\Http\Requests\UpdatePreferencesRequest $request)
    {
        $validated = $request->validated();

        $preferences = auth()->user()->preferences ?? [];
        $preferences = array_merge($preferences, $validated);

        auth()->user()->update([
            'preferences' => $preferences,
        ]);

        // Apply theme immediately via session
        session(['theme' => $validated['theme']]);

        return back()->with('success', '✅ Preferences saved! Theme, language, and timezone updated.');
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
