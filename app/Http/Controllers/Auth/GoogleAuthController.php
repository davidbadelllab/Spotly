<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        $redirectUrl = Socialite::driver('google')
            ->stateless()
            ->redirect()
            ->getTargetUrl();
        
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['redirect_url' => $redirectUrl]);
        }
        
        return redirect()->away($redirectUrl);
    }
    
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            Log::info('Google user data:', [
                'email' => $googleUser->email,
                'name' => $googleUser->name,
                'id' => $googleUser->id,
            ]);
            
            // Check if user exists
            $existingUser = User::where('email', $googleUser->email)->first();
            
            if ($existingUser) {
                // Update existing user
                $existingUser->update([
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);
                
                $user = $existingUser;
            } else {
                // Create new user with a random password to satisfy the constraint
                $user = User::create([
                    'email' => $googleUser->email,
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'has_completed_onboarding' => false,
                    'password' => Hash::make(md5(rand(1, 10000))), // Random password
                ]);
            }
            
            Log::info('User created/updated:', [
                'id' => $user->id,
                'has_completed_onboarding' => $user->has_completed_onboarding,
            ]);
            
            Auth::login($user);
            
            Log::info('User authenticated');
            
            // If user type is not set, redirect to user type selection
            if (!$user->user_type) {
                Log::info('Redirecting to user type selection');
                return redirect()->route('auth.user-type');
            }

            // If business user needs onboarding
            if ($user->isBusiness() && !$user->has_completed_onboarding) {
                Log::info('Redirecting to onboarding');
                return redirect()->route('onboarding.show');
            }

            Log::info('Redirecting to dashboard');
            return redirect()->route('dashboard');
            
        } catch (\Exception $e) {
            Log::error('Google auth error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('login')->with('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }
}
