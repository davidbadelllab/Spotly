<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'venue_id',
        'start_time',
        'end_time',
        'number_of_people',
        'status',
        'total_price',
        'deposit_paid',
        'special_requests',
        'customer_details',
        'confirmation_code',
        'cancellation_reason',
        'cancelled_at',
        'payment_status',
        'payment_method',
        'transaction_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'cancelled_at' => 'datetime',
        'customer_details' => 'array',
        'total_price' => 'decimal:2',
        'deposit_paid' => 'decimal:2',
        'number_of_people' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function reservable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now())
                    ->whereIn('status', ['pending', 'confirmed']);
    }

    public function scopePast($query)
    {
        return $query->where('end_time', '<', now());
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('start_time', $date);
    }

    public function getDurationAttribute(): int
    {
        return $this->start_time->diffInMinutes($this->end_time);
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->start_time->isFuture();
    }

    public function getIsInProgressAttribute(): bool
    {
        return now()->between($this->start_time, $this->end_time);
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->end_time->isPast();
    }

    public function getBalanceDueAttribute(): float
    {
        return $this->total_price - $this->deposit_paid;
    }

    public function confirm(): void
    {
        $this->update([
            'status' => 'confirmed',
            'payment_status' => $this->balanceDue > 0 ? 'partially_paid' : 'paid'
        ]);
    }

    public function cancel(?string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now()
        ]);
    }

    public function markAsCompleted(): void
    {
        if ($this->isCompleted) {
            $this->update(['status' => 'completed']);
        }
    }

    public function generateConfirmationCode(): void
    {
        $this->confirmation_code = strtoupper(uniqid());
        $this->save();
    }
}
