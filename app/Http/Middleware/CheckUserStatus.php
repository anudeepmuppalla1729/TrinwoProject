<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
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
            
            // Check if user can access the application
            if (!$user->canLogin()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                if ($user->isBanned()) {
                    return redirect()->route('login')->withErrors([
                        'email' => 'Your account has been banned. Please contact the administrator for more information.'
                    ]);
                } elseif ($user->isInactive()) {
                    return redirect()->route('login')->withErrors([
                        'email' => 'Your account is currently inactive. Please contact the administrator to reactivate your account.'
                    ]);
                }
            }
        }

        return $next($request);
    }
}
