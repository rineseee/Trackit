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

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
        }

        if (!$user->is_active) {
            return back()->withErrors(['email' => 'Account deactivated. Contact support.']);
        }

        if ($user->failed_login_attempts >= 5) {
            return back()->withErrors(['email' => 'Account locked. Reset your password.'])->onlyInput('email');
        }

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'failed_login_attempts' => 0,
        ]);

        Auth::login($user, $request->boolean('remember'));
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
