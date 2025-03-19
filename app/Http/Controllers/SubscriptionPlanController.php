<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        $user = request()->user();
        $currentPlan = $user->getCurrentPlan();

        return Inertia::render('Plans/Index', [
            'plans' => $plans,
            'currentPlan' => $currentPlan,
            'company' => $user->company,
        ]);
    }

    public function show(SubscriptionPlan $plan)
    {
        return Inertia::render('Plans/Show', [
            'plan' => $plan,
            'company' => request()->user()->company,
        ]);
    }

    public function subscribe(Request $request, SubscriptionPlan $plan)
    {
        $user = request()->user();
        $company = $user->company;

        // Cancel current subscription if exists
        if ($company->subscription) {
            $company->subscription->update([
                'status' => 'canceled',
                'ends_at' => now(),
            ]);
        }

        // Create new subscription
        Subscription::create([
            'company_id' => $company->id,
            'subscription_plan_id' => $plan->id,
            'starts_at' => now(),
            'ends_at' => $plan->billing_period === 'monthly' ? now()->addMonth() : now()->addYear(),
            'status' => 'active',
            'stripe_id' => 'sub_' . uniqid(), // In a real app, this would come from Stripe
            'stripe_status' => 'active',
            'stripe_price' => $plan->price,
        ]);

        return redirect()->route('plans.index')
            ->with('success', 'Successfully subscribed to ' . $plan->name);
    }

    public function cancel()
    {
        $user = request()->user();
        $company = $user->company;
        $subscription = $company->subscription;

        if ($subscription) {
            $subscription->update([
                'status' => 'canceled',
                'ends_at' => now(),
            ]);
        }

        return back()->with('success', 'Subscription canceled successfully.');
    }
}
