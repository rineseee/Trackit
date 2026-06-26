<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Change application language
     */
    public function change(Request $request, $locale)
    {
        $validated = $request->validate([
            'locale' => 'required|in:en,sq,it',
        ]);

        // Set locale in session
        session(['locale' => $locale]);
        app()->setLocale($locale);

        // Store in user preferences if authenticated
        if (auth()->check()) {
            $preferences = auth()->user()->preferences ?? [];
            $preferences['language'] = $locale;
            auth()->user()->update(['preferences' => $preferences]);
        }

        // Store in cookie for persistence
        cookie()->queue('locale', $locale, 60 * 24 * 365); // 1 year

        return redirect()->back();
    }
}
