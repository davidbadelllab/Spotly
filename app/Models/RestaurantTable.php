<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class RestaurantTable extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'venue_id',
        'table_number',
        'location',
        'min_capacity',
        'max_capacity',
        'table_type',
        'is_accessible',
        'is_smoking',
        'default_reservation_duration',
        'seating_options',
        'notes',
        'requires_deposit',
        'deposit_amount',
        'minimum_spend',
        'is_active',
    ];

    protected $casts = [
        'seating_options' => 'array',
        'is_accessible' => 'boolean',
        'is_smoking' => 'boolean',
        'requires_deposit' => 'boolean',
        'is_active' => 'boolean',
        'deposit_amount' => 'decimal:2',
        'minimum_spend' => 'integer',
        'min_capacity' => 'integer',
        'max_capacity' => 'integer',
        'default_reservation_duration' => 'integer',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function reservations(): MorphMany
    {
        return $this->morphMany(Reservation::class, 'reservable');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLocation($query, string $location)
    {
        return $query->where('location', $location);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('table_type', $type);
    }

    public function scopeWithinCapacityRange($query, int $guests)
    {
        return $query->where('min_capacity', '<=', $guests)
                    ->where('max_capacity', '>=', $guests);
    }

    public function scopeAccessible($query)
    {
        return $query->where('is_accessible', true);
    }

    public function scopeNonSmoking($query)
    {
        return $query->where('is_smoking', false);
    }

    public function isAvailable($startTime, $endTime): bool
    {
        return !$this->reservations()
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
    }

    public function getDisplayNameAttribute(): string
    {
        return "Table {$this->table_number} ({$this->location})";
    }

    public function getDepositRequiredAttribute(): bool
    {
        return $this->requires_deposit && $this->deposit_amount > 0;
    }

    public function hasMinimumSpend(): bool
    {
        return $this->minimum_spend > 0;
    }

    public function getDefaultEndTime($startTime): string
    {
        return date('Y-m-d H:i:s', strtotime($startTime) + ($this->default_reservation_duration * 60));
    }
}
