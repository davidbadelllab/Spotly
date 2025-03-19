<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'billing_period',
        'features',
        'max_venues',
        'max_users',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'max_venues' => 'integer',
        'max_users' => 'integer',
        'is_active' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function isMonthly(): bool
    {
        return $this->billing_period === 'monthly';
    }

    public function isYearly(): bool
    {
        return $this->billing_period === 'yearly';
    }

    public function getMonthlyPriceAttribute(): float
    {
        if ($this->isMonthly()) {
            return $this->price;
        }

        return $this->price / 12;
    }

    public function getYearlyPriceAttribute(): float
    {
        if ($this->isYearly()) {
            return $this->price;
        }

        return $this->price * 12;
    }

    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    public function getFormattedMonthlyPriceAttribute(): string
    {
        return '$' . number_format($this->monthly_price, 2);
    }

    public function getFormattedYearlyPriceAttribute(): string
    {
        return '$' . number_format($this->yearly_price, 2);
    }
}
