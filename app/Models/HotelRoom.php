<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class HotelRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'venue_id',
        'room_number',
        'room_type',
        'capacity',
        'floor_number',
        'price_per_night',
        'bed_count',
        'bed_type',
        'bathroom_count',
        'has_balcony',
        'has_kitchen',
        'has_wifi',
        'amenities',
        'description',
        'policies',
        'is_smoking',
        'is_accessible',
        'is_active',
    ];

    protected $casts = [
        'amenities' => 'array',
        'has_balcony' => 'boolean',
        'has_kitchen' => 'boolean',
        'has_wifi' => 'boolean',
        'is_smoking' => 'boolean',
        'is_accessible' => 'boolean',
        'is_active' => 'boolean',
        'price_per_night' => 'decimal:2',
        'capacity' => 'integer',
        'floor_number' => 'integer',
        'bed_count' => 'integer',
        'bathroom_count' => 'integer',
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

    public function scopeOfType($query, string $type)
    {
        return $query->where('room_type', $type);
    }

    public function scopeWithinCapacity($query, int $guests)
    {
        return $query->where('capacity', '>=', $guests);
    }

    public function scopeAccessible($query)
    {
        return $query->where('is_accessible', true);
    }

    public function scopeNonSmoking($query)
    {
        return $query->where('is_smoking', false);
    }

    public function scopeWithAmenities($query, array $amenities)
    {
        return $query->where(function ($q) use ($amenities) {
            foreach ($amenities as $amenity) {
                $q->whereJsonContains('amenities', $amenity);
            }
        });
    }

    public function isAvailable($checkIn, $checkOut): bool
    {
        return !$this->reservations()
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('start_time', [$checkIn, $checkOut])
                    ->orWhereBetween('end_time', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('start_time', '<=', $checkIn)
                            ->where('end_time', '>=', $checkOut);
                    });
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
    }

    public function calculatePrice($checkIn, $checkOut): float
    {
        $nights = ceil((strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24));
        return $this->price_per_night * $nights;
    }

    public function getDisplayNameAttribute(): string
    {
        return "Room {$this->room_number} - {$this->room_type}";
    }
}
