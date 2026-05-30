<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (Auth::viaRemember()) {
            // Log::info('User auto-logged in via remember_token', ['user_id' => Auth::id()]);

            // Update session_id so future requests pass the check
            $user = Auth::user();
            $user->update(['session_id' => session()->getId()]);

            return $next($request);
        }


        // Check if the user's session ID matches the current session
        if ($user && $user->session_id !== session()->getId()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('message', 'You have been logged out because your session was invalidated.');
        }
        return $next($request);
    }
}
