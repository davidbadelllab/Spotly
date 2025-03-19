<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOnboarding
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->routeIs('onboarding.*')) {
            $user = $request->user();
            
            \Log::info('CheckOnboarding middleware:', [
                'user_id' => $user->id,
                'current_route' => $request->route()->getName(),
                'has_completed_onboarding' => $user->hasCompletedOnboarding(),
                'company_id' => $user->company?->id,
                'company_status' => $user->company?->status,
            ]);

            // If user hasn't completed onboarding
            if (!$user->hasCompletedOnboarding() && !$request->routeIs(['onboarding.*', 'logout'])) {
                \Log::info('Redirecting to onboarding:', [
                    'user_id' => $user->id,
                    'from_route' => $request->route()->getName()
                ]);
                return redirect()->route('onboarding.show');
            }
        }

        return $next($request);
    }
}
