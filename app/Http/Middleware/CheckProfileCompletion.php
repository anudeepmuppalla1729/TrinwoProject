<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckProfileCompletion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user has completed their profile setup
            // Required fields: username, age, gender, studying_in, expert_in
            if (!$this->isProfileComplete($user)) {
                // Allow access to user information and interests pages
                if ($request->routeIs('user.information') || $request->routeIs('user.interests')) {
                    return $next($request);
                }
                
                // Redirect to user information page for incomplete profiles
                return redirect()->route('user.information')->with('info', 'Please complete your profile setup to continue.');
            }
        }

        return $next($request);
    }

    /**
     * Check if user profile is complete
     */
    private function isProfileComplete($user): bool
    {
        return $user->hasCompletedProfile();
    }
} 