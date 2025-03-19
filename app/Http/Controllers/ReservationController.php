<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Reservation;
use App\Models\SportsFacility;
use App\Models\HotelRoom;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReservationController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $reservations = Reservation::query()
            ->when($request->user()->cannot('viewAny', Reservation::class), function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->when($request->status, fn($query, $status) => $query->where('status', $status))
            ->when($request->venue_id, fn($query, $venueId) => $query->where('venue_id', $venueId))
            ->when($request->date, fn($query, $date) => $query->whereDate('start_time', $date))
            ->with(['venue', 'reservable', 'user'])
            ->latest()
            ->paginate(10);

        return inertia('Reservations/Index', [
            'reservations' => $reservations,
            'filters' => $request->only(['status', 'venue_id', 'date'])
        ]);
    }

    public function store(Request $request, Venue $venue)
    {
        $validated = $request->validate([
            'reservable_type' => ['required', Rule::in(['sports_facility', 'hotel_room', 'restaurant_table'])],
            'reservable_id' => ['required', 'integer'],
            'start_time' => ['required', 'date', 'after:now'],
            'end_time' => ['required', 'date', 'after:start_time'],
            'number_of_people' => ['required', 'integer', 'min:1'],
            'special_requests' => ['nullable', 'string'],
            'customer_details' => ['required', 'array'],
            'customer_details.name' => ['required', 'string'],
            'customer_details.email' => ['required', 'email'],
            'customer_details.phone' => ['required', 'string'],
        ]);

        // Map the reservable type to the correct model
        $reservableType = match ($validated['reservable_type']) {
            'sports_facility' => SportsFacility::class,
            'hotel_room' => HotelRoom::class,
            'restaurant_table' => RestaurantTable::class,
        };

        // Find the reservable item
        $reservable = $reservableType::findOrFail($validated['reservable_id']);

        // Check if the reservable belongs to the venue
        if ($reservable->venue_id !== $venue->id) {
            abort(404);
        }

        // Check availability
        if (!$reservable->isAvailable($validated['start_time'], $validated['end_time'])) {
            return back()->withErrors(['availability' => 'The selected time slot is no longer available.']);
        }

        // Calculate price based on reservable type
        $totalPrice = match ($validated['reservable_type']) {
            'sports_facility' => $reservable->calculatePrice($validated['start_time'], $validated['end_time']),
            'hotel_room' => $reservable->calculatePrice($validated['start_time'], $validated['end_time']),
            'restaurant_table' => 0, // Restaurants might not have a fixed price
        };

        // Create the reservation
        $reservation = new Reservation([
            'user_id' => $request->user()->id,
            'venue_id' => $venue->id,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'number_of_people' => $validated['number_of_people'],
            'status' => 'pending',
            'total_price' => $totalPrice,
            'special_requests' => $validated['special_requests'],
            'customer_details' => $validated['customer_details'],
            'confirmation_code' => strtoupper(Str::random(8)),
        ]);

        $reservable->reservations()->save($reservation);

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Reservation created successfully.');
    }

    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);

        $reservation->load(['venue', 'reservable', 'user']);

        return inertia('Reservations/Show', [
            'reservation' => $reservation
        ]);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled', 'completed'])],
            'payment_status' => ['required', Rule::in(['pending', 'paid', 'refunded'])],
            'special_requests' => ['nullable', 'string'],
            'cancellation_reason' => ['required_if:status,cancelled', 'nullable', 'string'],
        ]);

        $reservation->update($validated);

        if ($validated['status'] === 'cancelled') {
            $reservation->cancelled_at = now();
            $reservation->save();
        }

        return back()->with('success', 'Reservation updated successfully.');
    }

    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);

        $reservation->delete();

        return redirect()->route('reservations.index')
            ->with('success', 'Reservation deleted successfully.');
    }

    public function confirm(Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $reservation->confirm();

        return back()->with('success', 'Reservation confirmed successfully.');
    }

    public function cancel(Request $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        $validated = $request->validate([
            'cancellation_reason' => ['required', 'string'],
        ]);

        $reservation->cancel($validated['cancellation_reason']);

        return back()->with('success', 'Reservation cancelled successfully.');
    }
}
