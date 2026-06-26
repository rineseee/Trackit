<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApplyUserPreferences
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $preferences = $user->preferences ?? [];

            // Apply language
            if (isset($preferences['language'])) {
                app()->setLocale($preferences['language']);
                session(['locale' => $preferences['language']]);
            }

            // Apply timezone
            if (isset($preferences['timezone'])) {
                date_default_timezone_set($preferences['timezone']);
                session(['timezone' => $preferences['timezone']]);
            } else {
                date_default_timezone_set('Europe/Budapest'); // Default
            }

            // Apply theme
            if (isset($preferences['theme'])) {
                session(['theme' => $preferences['theme']]);
            }
        } else {
            // Default settings for guests
            app()->setLocale('en');
            date_default_timezone_set('Europe/Budapest');
        }

        return $next($request);
    }
}
