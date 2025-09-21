<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastUserActivity
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
            // Update last activity if more than 5 minutes have passed
            if ($user->last_login < now()->subMinutes(5)) {
                $user->forceFill(['last_login_at' => now()])->save();
            }
        }

        return $next($request);
    }
}
