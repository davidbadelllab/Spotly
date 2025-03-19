<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SportsFacility extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'venue_id',
        'name',
        'sport_type',
        'capacity',
        'equipment_provided',
        'rules',
        'duration_minutes',
        'price_per_hour',
        'amenities',
        'is_indoor',
        'has_lighting',
        'is_active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'is_indoor' => 'boolean',
        'has_lighting' => 'boolean',
        'is_active' => 'boolean',
        'price_per_hour' => 'decimal:2',
        'duration_minutes' => 'integer',
        'capacity' => 'integer',
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

    public function scopeOfSportType($query, string $type)
    {
        return $query->where('sport_type', $type);
    }

    public function scopeIndoor($query)
    {
        return $query->where('is_indoor', true);
    }

    public function scopeOutdoor($query)
    {
        return $query->where('is_indoor', false);
    }

    public function scopeWithLighting($query)
    {
        return $query->where('has_lighting', true);
    }

    public function scopeWithinCapacity($query, int $people)
    {
        return $query->where('capacity', '>=', $people);
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

    public function calculatePrice($startTime, $endTime): float
    {
        $hours = (strtotime($endTime) - strtotime($startTime)) / 3600;
        return $this->price_per_hour * $hours;
    }
}
