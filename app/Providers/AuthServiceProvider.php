<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Venue;
use App\Models\Reservation;
use App\Policies\VenuePolicy;
use App\Policies\ReservationPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Venue::class => VenuePolicy::class,
        Reservation::class => ReservationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define a super-admin role that can do everything
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
        });

        // Define venue owner abilities
        Gate::define('manage-venue', function ($user, $venue) {
            return $user->isOwnerOf($venue);
        });

        // Define customer abilities
        Gate::define('make-reservation', function ($user) {
            return !$user->isBanned;
        });
    }
}
