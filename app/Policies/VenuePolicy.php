<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Venue;
use Illuminate\Auth\Access\HandlesAuthorization;

class VenuePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // Anyone can view the list of venues
    }

    public function view(User $user, Venue $venue): bool
    {
        return true; // Anyone can view venue details
    }

    public function create(User $user): bool
    {
        return true; // Any authenticated user can create venues
    }

    public function update(User $user, Venue $venue): bool
    {
        return $user->isOwnerOf($venue);
    }

    public function delete(User $user, Venue $venue): bool
    {
        return $user->isOwnerOf($venue);
    }

    public function restore(User $user, Venue $venue): bool
    {
        return $user->isOwnerOf($venue);
    }

    public function forceDelete(User $user, Venue $venue): bool
    {
        return $user->isOwnerOf($venue);
    }

    public function manageReservations(User $user, Venue $venue): bool
    {
        return $user->isOwnerOf($venue);
    }

    public function updateSettings(User $user, Venue $venue): bool
    {
        return $user->isOwnerOf($venue);
    }

    public function viewAnalytics(User $user, Venue $venue): bool
    {
        return $user->isOwnerOf($venue);
    }
}
