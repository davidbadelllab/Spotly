<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireOnboarding
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Skip onboarding check for onboarding routes
        if ($request->routeIs('onboarding.*')) {
            return $next($request);
        }

        // If user hasn't completed onboarding, redirect to onboarding
        if (!$user->hasCompletedOnboarding()) {
            return redirect()->route('onboarding.show');
        }

        return $next($request);
    }
}
