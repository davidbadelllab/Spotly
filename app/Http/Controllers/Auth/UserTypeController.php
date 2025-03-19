<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserTypeController extends Controller
{
    public function show()
    {
        return Inertia::render('auth/UserType');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_type' => ['required', 'string', 'in:business,customer'],
        ]);

        $user = $request->user();
        $user->user_type = $validated['user_type'];
        $user->save();

        if ($user->user_type === 'business') {
            return redirect()->route('onboarding.show');
        }

        return redirect()->route('dashboard');
    }
}
