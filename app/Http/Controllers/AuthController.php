<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'min:8'],
        ]);

        // Sanitize email
        $email = strtolower(trim($credentials['email']));

        // Find user
        $user = User::where('email', $email)->first();

        // Check if account exists and credentials match
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            // Increment failed attempts if user exists
            if ($user) {
                $attempts = $user->failed_login_attempts + 1;
                $user->update(['failed_login_attempts' => $attempts]);

                // Log failed attempt
                \Log::warning("Failed login attempt for user {$user->email}", [
                    'ip' => $request->ip(),
                    'attempt' => $attempts,
                    'timestamp' => now(),
                ]);

                // Lock after 5 attempts
                if ($attempts >= 5) {
                    \Log::critical("Account {$user->email} locked after {$attempts} failed attempts");
                }
            }

            // Generic error to prevent user enumeration
            return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
        }

        // Check account is active
        if (!$user->is_active) {
            \Log::warning("Login attempt on inactive account {$user->email}");
            return back()->withErrors(['email' => 'Account deactivated. Contact support.']);
        }

        // Check account is not locked
        if ($user->failed_login_attempts >= 5) {
            \Log::warning("Login attempt on locked account {$user->email}");
            return back()->withErrors(['email' => 'Account locked due to too many failed attempts. Please reset your password.'])->onlyInput('email');
        }

        // Update successful login
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'failed_login_attempts' => 0,  // Reset failed attempts
        ]);

        \Log::info("Successful login for user {$user->email}", [
            'ip' => $request->ip(),
            'timestamp' => now(),
        ]);

        // Login user with remember me option
        Auth::login($user, $request->boolean('remember'));

        // CRITICAL: Regenerate session to prevent session fixation
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'))->with('status', 'Welcome!');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'confirmed',
                Password::min(12)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'is_active' => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('projects.index')->with('status', 'Account created! Welcome aboard.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Logged out.');
    }
}
