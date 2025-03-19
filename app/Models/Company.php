<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'address',
        'city',
        'state',
        'country',
        'phone',
        'website',
        'description',
        'status',
        'onboarding_completed',
    ];

    protected $casts = [
        'onboarding_completed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->latest();
    }

    public function venues(): HasMany
    {
        return $this->hasMany(Venue::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function hasCompletedOnboarding(): bool
    {
        return (bool) $this->onboarding_completed;
    }

    public function markOnboardingComplete(): void
    {
        try {
            \Log::info('Attempting to mark onboarding as complete for company:', [
                'company_id' => $this->id,
                'current_status' => $this->onboarding_completed
            ]);
            
            $result = $this->update(['onboarding_completed' => true]);
            
            \Log::info('Onboarding completion status updated:', [
                'company_id' => $this->id,
                'success' => $result,
                'new_status' => $this->onboarding_completed
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to mark onboarding as complete:', [
                'company_id' => $this->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function markOnboardingIncomplete(): void
    {
        $this->update(['onboarding_completed' => false]);
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscription && $this->subscription->isActive();
    }
}
