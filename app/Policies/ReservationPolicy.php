<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reservation;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReservationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // Users can view their own reservations
        // Venue owners can view reservations for their venues
        return true;
    }

    public function view(User $user, Reservation $reservation): bool
    {
        // Users can view their own reservations
        if ($user->id === $reservation->user_id) {
            return true;
        }

        // Venue owners can view reservations for their venues
        return $user->isOwnerOf($reservation->venue);
    }

    public function create(User $user): bool
    {
        // Any authenticated user can create reservations
        return true;
    }

    public function update(User $user, Reservation $reservation): bool
    {
        // Users can update their own reservations if they're still pending
        if ($user->id === $reservation->user_id && $reservation->status === 'pending') {
            return true;
        }

        // Venue owners can update any reservation for their venues
        return $user->isOwnerOf($reservation->venue);
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        // Only venue owners can delete reservations
        return $user->isOwnerOf($reservation->venue);
    }

    public function cancel(User $user, Reservation $reservation): bool
    {
        // Users can cancel their own reservations if they're not already cancelled or completed
        if ($user->id === $reservation->user_id && !in_array($reservation->status, ['cancelled', 'completed'])) {
            return true;
        }

        // Venue owners can cancel any reservation for their venues
        return $user->isOwnerOf($reservation->venue);
    }

    public function confirm(User $user, Reservation $reservation): bool
    {
        // Only venue owners can confirm reservations
        return $user->isOwnerOf($reservation->venue);
    }

    public function viewPaymentDetails(User $user, Reservation $reservation): bool
    {
        // Users can view payment details for their own reservations
        if ($user->id === $reservation->user_id) {
            return true;
        }

        // Venue owners can view payment details for their venues' reservations
        return $user->isOwnerOf($reservation->venue);
    }

    public function managePayments(User $user, Reservation $reservation): bool
    {
        // Only venue owners can manage payments
        return $user->isOwnerOf($reservation->venue);
    }

    public function viewCustomerDetails(User $user, Reservation $reservation): bool
    {
        // Only venue owners can view customer details
        return $user->isOwnerOf($reservation->venue);
    }

    public function sendNotifications(User $user, Reservation $reservation): bool
    {
        // Only venue owners can send notifications
        return $user->isOwnerOf($reservation->venue);
    }
}
