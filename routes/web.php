<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\SportsFacilityController;
use App\Http\Controllers\HotelRoomController;
use App\Http\Controllers\RestaurantTableController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SubscriptionPlanController;

Route::get('/', function () {
    return inertia('Welcome');
})->name('home');

// Guest routes
Route::get('/venues', [VenueController::class, 'index'])->name('venues.index');
Route::get('/venues/{venue}', [VenueController::class, 'show'])->name('venues.show');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return inertia('Dashboard');
    })->name('dashboard');

    // Subscription Plans
    Route::prefix('plans')->name('plans.')->group(function () {
        Route::get('/', [SubscriptionPlanController::class, 'index'])->name('index');
        Route::get('/{plan}', [SubscriptionPlanController::class, 'show'])->name('show');
        Route::post('/subscribe/{plan}', [SubscriptionPlanController::class, 'subscribe'])->name('subscribe');
        Route::post('/cancel', [SubscriptionPlanController::class, 'cancel'])->name('cancel');
    });

    // Venue management
    Route::prefix('venues')->name('venues.')->group(function () {
        Route::get('/create', [VenueController::class, 'create'])->name('create');
        Route::post('/', [VenueController::class, 'store'])->name('store');
        Route::get('/{venue}/edit', [VenueController::class, 'edit'])->name('edit');
        Route::put('/{venue}', [VenueController::class, 'update'])->name('update');
        Route::delete('/{venue}', [VenueController::class, 'destroy'])->name('destroy');
        Route::patch('/{venue}/toggle-status', [VenueController::class, 'toggleStatus'])->name('toggle-status');

        // Sports facilities
        Route::prefix('{venue}/sports-facilities')->name('sports-facilities.')->group(function () {
            Route::get('/', [SportsFacilityController::class, 'index'])->name('index');
            Route::get('/create', [SportsFacilityController::class, 'create'])->name('create');
            Route::post('/', [SportsFacilityController::class, 'store'])->name('store');
            Route::get('/{facility}', [SportsFacilityController::class, 'show'])->name('show');
            Route::get('/{facility}/edit', [SportsFacilityController::class, 'edit'])->name('edit');
            Route::put('/{facility}', [SportsFacilityController::class, 'update'])->name('update');
            Route::delete('/{facility}', [SportsFacilityController::class, 'destroy'])->name('destroy');
            Route::post('/{facility}/check-availability', [SportsFacilityController::class, 'checkAvailability'])->name('check-availability');
            Route::patch('/{facility}/toggle-status', [SportsFacilityController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Hotel rooms
        Route::prefix('{venue}/hotel-rooms')->name('hotel-rooms.')->group(function () {
            Route::get('/', [HotelRoomController::class, 'index'])->name('index');
            Route::get('/create', [HotelRoomController::class, 'create'])->name('create');
            Route::post('/', [HotelRoomController::class, 'store'])->name('store');
            Route::get('/{room}', [HotelRoomController::class, 'show'])->name('show');
            Route::get('/{room}/edit', [HotelRoomController::class, 'edit'])->name('edit');
            Route::put('/{room}', [HotelRoomController::class, 'update'])->name('update');
            Route::delete('/{room}', [HotelRoomController::class, 'destroy'])->name('destroy');
            Route::post('/{room}/check-availability', [HotelRoomController::class, 'checkAvailability'])->name('check-availability');
            Route::patch('/{room}/toggle-status', [HotelRoomController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Restaurant tables
        Route::prefix('{venue}/restaurant-tables')->name('restaurant-tables.')->group(function () {
            Route::get('/', [RestaurantTableController::class, 'index'])->name('index');
            Route::get('/create', [RestaurantTableController::class, 'create'])->name('create');
            Route::post('/', [RestaurantTableController::class, 'store'])->name('store');
            Route::get('/{table}', [RestaurantTableController::class, 'show'])->name('show');
            Route::get('/{table}/edit', [RestaurantTableController::class, 'edit'])->name('edit');
            Route::put('/{table}', [RestaurantTableController::class, 'update'])->name('update');
            Route::delete('/{table}', [RestaurantTableController::class, 'destroy'])->name('destroy');
            Route::post('/{table}/check-availability', [RestaurantTableController::class, 'checkAvailability'])->name('check-availability');
            Route::patch('/{table}/toggle-status', [RestaurantTableController::class, 'toggleStatus'])->name('toggle-status');
        });

        // Venue reservations
        Route::post('/{venue}/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    });

    // Reservations management
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/{reservation}', [ReservationController::class, 'show'])->name('show');
        Route::put('/{reservation}', [ReservationController::class, 'update'])->name('update');
        Route::delete('/{reservation}', [ReservationController::class, 'destroy'])->name('destroy');
        Route::post('/{reservation}/confirm', [ReservationController::class, 'confirm'])->name('confirm');
        Route::post('/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('cancel');
    });

    // User settings and profile
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/profile', [App\Http\Controllers\Settings\ProfileController::class, 'edit'])->name('profile');
        Route::patch('/profile', [App\Http\Controllers\Settings\ProfileController::class, 'update'])->name('profile.update');
        
        Route::get('/password', [App\Http\Controllers\Settings\PasswordController::class, 'edit'])->name('password');
        Route::put('/password', [App\Http\Controllers\Settings\PasswordController::class, 'update'])->name('password.update');
        
        Route::get('/theme', function () {
            return inertia('settings/Theme');
        })->name('theme');
        
        Route::get('/billing', function () {
            $user = request()->user();
            return inertia('settings/Billing', [
                'company' => $user->company,
                'subscription' => $user->company?->subscription,
                'currentPlan' => $user->getCurrentPlan(),
            ]);
        })->name('billing');
    });

    // Locations
    Route::get('/locations', function () {
        return inertia('Locations');
    })->name('locations');

    // My Reservations
    Route::get('/my-reservations', function () {
        return inertia('MyReservations');
    })->name('my-reservations');
});

// Include auth and onboarding routes
require __DIR__.'/auth.php';
require __DIR__.'/onboarding.php';
