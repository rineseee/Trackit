<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HelpdeskDashboardController;
use App\Http\Controllers\HelpdeskIssueController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| STRICT AUTHENTICATION SYSTEM
|--------------------------------------------------------------------------
| CRITICAL: All routes are protected except Login, Register, and Password Reset
| Guests see NOTHING except auth pages
| Any attempt to access protected routes redirects to /login
|--------------------------------------------------------------------------
*/

// ============================================================================
// PUBLIC ROUTES (GUESTS ONLY) - Only Authentication Pages
// ============================================================================
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')  // 5 attempts per minute
        ->name('login.store');

    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:3,1')  // 3 attempts per minute
        ->name('register.store');

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
        ->middleware('throttle:3,1')  // 3 attempts per minute
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
        ->middleware('throttle:5,1')  // 5 attempts per minute
        ->name('password.update');
});

// ============================================================================
// EMAIL VERIFICATION ROUTES (Accessible to all with signed URL)
// ============================================================================
Route::get('/verify-email/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);
    if (hash_equals((string) $hash, hash('sha256', $user->getEmailForVerification()))) {
        $user->markEmailAsVerified();
        if (auth()->check()) {
            return redirect('/dashboard')->with('status', 'Email verified!');
        }
        return redirect('/login')->with('status', 'Email verified! You can now log in.');
    }
    return back()->withErrors(['email' => 'Invalid verification link']);
})->middleware('signed')->name('verification.verify');

// ============================================================================
// PUBLIC APPLICATION ROUTES (READ-ONLY)
// ============================================================================
// ============================================================================
// PROTECTED ROUTES (AUTHENTICATED USERS ONLY) - PUBLIC READ-ONLY ENDPOINTS
// ============================================================================
Route::middleware('auth')->group(function () {
    Route::get('/issues', [IssueController::class, 'index'])->name('issues.index');
    Route::get('/issues/kanban', [IssueController::class, 'kanban'])->name('issues.kanban');
    Route::get('/issues/create', [IssueController::class, 'create'])->name('issues.create');
    Route::get('/issues/{issue}/edit', [IssueController::class, 'edit'])->name('issues.edit');
    Route::get('/issues/{issue}', [IssueController::class, 'show'])->name('issues.show');
});

// ============================================================================
// AI CHATBOT ENDPOINTS (AUTHENTICATED + RATE LIMITED)
// ============================================================================
Route::middleware(['auth', 'throttle:30,1'])->group(function () {
    Route::post('/chatbot/send', [\App\Http\Controllers\ChatBotController::class, 'sendMessage'])
        ->name('chatbot.sendMessage');
    Route::post('/chatbot/clear', [\App\Http\Controllers\ChatBotController::class, 'clearHistory'])
        ->name('chatbot.clearHistory');
});

// ============================================================================
// PROTECTED ROUTES (AUTHENTICATED USERS ONLY)
// ============================================================================
Route::middleware('auth')->group(function () {

    // Redirect root to dashboard (but will be intercepted by verified middleware if needed)
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/issues-data', [\App\Http\Controllers\DashboardController::class, 'issuesData'])->name('dashboard.issues.data');
    Route::post('/dashboard/issues', [\App\Http\Controllers\DashboardController::class, 'store'])->name('dashboard.issues.store');
    Route::get('/dashboard/issues/{issue}', [\App\Http\Controllers\DashboardController::class, 'showIssue'])->name('dashboard.issues.show');
    Route::put('/dashboard/issues/{issue}', [\App\Http\Controllers\DashboardController::class, 'update'])->name('dashboard.issues.update');
    Route::delete('/dashboard/issues/{issue}', [\App\Http\Controllers\DashboardController::class, 'destroy'])->name('dashboard.issues.destroy');

    // Helpdesk
    Route::get('/helpdesk', [HelpdeskDashboardController::class, 'index'])->name('helpdesk.dashboard');
    Route::get('/helpdesk/issues', [HelpdeskIssueController::class, 'index'])->name('helpdesk.issues.index');
    Route::get('/helpdesk/issues/{issue}', [HelpdeskIssueController::class, 'show'])->name('helpdesk.issues.show');
    Route::patch('/helpdesk/issues/{issue}', [HelpdeskIssueController::class, 'update'])->name('helpdesk.issues.update');
    Route::delete('/helpdesk/issues/{issue}', [HelpdeskIssueController::class, 'destroy'])->name('helpdesk.issues.destroy');
    Route::post('/helpdesk/issues/{issue}/comments', [HelpdeskIssueController::class, 'addComment'])->name('helpdesk.comments.store');
    Route::delete('/helpdesk/issues/{issue}/comments/{comment}', [HelpdeskIssueController::class, 'deleteComment'])->name('helpdesk.comments.destroy');
    Route::post('/helpdesk/issues/{issue}/tags', [HelpdeskIssueController::class, 'updateTags'])->name('helpdesk.tags.update');
    Route::delete('/helpdesk/issues/{issue}/tags/{tag}', [HelpdeskIssueController::class, 'removeTag'])->name('helpdesk.tags.remove');

    // Projects
    Route::resource('projects', ProjectController::class);

    // Issues
    Route::resource('issues', IssueController::class)->except(['index', 'show']);
    Route::patch('/issues/{issue}/kanban/status', [\App\Http\Controllers\KanbanController::class, 'updateStatus'])->name('issues.kanban.status');

    // Issue comments (AJAX)
    Route::get('/issues/{issue}/comments', [\App\Http\Controllers\IssueCommentController::class, 'index'])->name('issues.comments.index');
    Route::post('/issues/{issue}/comments', [\App\Http\Controllers\IssueCommentController::class, 'store'])->name('issues.comments.store');

    // Issue tags (attach/detach via AJAX)
    Route::post('/issues/{issue}/tags/{tag}', [\App\Http\Controllers\IssueTagController::class, 'store'])->name('issues.tags.store');
    Route::delete('/issues/{issue}/tags/{tag}', [\App\Http\Controllers\IssueTagController::class, 'destroy'])->name('issues.tags.destroy');

    // Issue members (attach/detach via AJAX)
    Route::post('/issues/{issue}/members/{user}', [\App\Http\Controllers\IssueMemberController::class, 'store'])->name('issues.members.store');
    Route::delete('/issues/{issue}/members/{user}', [\App\Http\Controllers\IssueMemberController::class, 'destroy'])->name('issues.members.destroy');

    // Tags
    Route::resource('tags', TagController::class);

    // Teams
    Route::get('/teams', [\App\Http\Controllers\TeamController::class, 'index'])->name('teams.index');
    Route::post('/teams/invite', [\App\Http\Controllers\TeamController::class, 'invite'])->name('teams.invite');
    Route::delete('/teams/{member}', [\App\Http\Controllers\TeamController::class, 'remove'])->name('teams.remove');
    Route::put('/teams/{member}/role', [\App\Http\Controllers\TeamController::class, 'updateRole'])->name('teams.updateRole');
    Route::delete('/teams/invitation/{email}', [\App\Http\Controllers\TeamController::class, 'cancelInvitation'])->name('teams.cancelInvitation');
    Route::get('/team/accept-invitation/{token}', [\App\Http\Controllers\TeamController::class, 'acceptInvitation'])->name('teams.acceptInvitation');
    Route::post('/team/accept-invitation/{token}', [\App\Http\Controllers\TeamController::class, 'storeFromInvitation'])->name('teams.storeFromInvitation');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [\App\Http\Controllers\SettingsController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::put('/settings/password', [\App\Http\Controllers\SettingsController::class, 'updatePassword'])->name('settings.updatePassword');
    Route::put('/settings/preferences', [\App\Http\Controllers\SettingsController::class, 'updatePreferences'])->name('settings.updatePreferences');
    Route::put('/settings/notifications', [\App\Http\Controllers\SettingsController::class, 'updateNotifications'])->name('settings.updateNotifications');
    Route::put('/settings/security', [\App\Http\Controllers\SettingsController::class, 'updateSecurity'])->name('settings.updateSecurity');
    Route::delete('/settings/account', [\App\Http\Controllers\SettingsController::class, 'deleteAccount'])->name('settings.deleteAccount');

    // Email Verification (for unverified users)
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::post('/email/verification-notification', function () {
        auth()->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ============================================================================
// FALLBACK - Redirect Any Unauthorized Access to Login
// ============================================================================
Route::fallback(function () {
    if (auth()->guest()) {
        return redirect()->route('login');
    }
    return abort(404);
});
