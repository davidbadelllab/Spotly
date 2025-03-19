<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'has_completed_onboarding',
        'user_type',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'has_completed_onboarding' => 'boolean',
    ];

    public function isBusiness(): bool
    {
        return $this->user_type === 'business';
    }

    public function isCustomer(): bool
    {
        return $this->user_type === 'customer';
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function venues(): HasMany
    {
        return $this->hasMany(Venue::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function isOwnerOf(Venue $venue): bool
    {
        return $this->id === $venue->user_id;
    }

    public function hasCompany(): bool
    {
        return $this->company()->exists();
    }

    public function hasActiveCompany(): bool
    {
        return $this->company && $this->company->isActive();
    }

    public function hasCompletedOnboarding(): bool
    {
        \Log::info('Checking if user has completed onboarding:', [
            'user_id' => $this->id,
            'has_company' => (bool)$this->company,
            'company_onboarding_status' => $this->company ? $this->company->onboarding_completed : null
        ]);
        
        return $this->company && $this->company->hasCompletedOnboarding();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->company && $this->company->hasActiveSubscription();
    }

    public function getCurrentPlan(): ?SubscriptionPlan
    {
        if (!$this->hasActiveSubscription()) {
            return null;
        }

        return $this->company->subscription->plan;
    }
}
