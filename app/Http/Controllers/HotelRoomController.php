<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\HotelRoom;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HotelRoomController extends Controller
{
    use AuthorizesRequests;

    public function index(Venue $venue)
    {
        $this->authorize('view', $venue);

        $rooms = $venue->hotelRooms()
            ->with(['reservations' => fn($query) => $query->upcoming()])
            ->get();

        return inertia('HotelRooms/Index', [
            'venue' => $venue,
            'rooms' => $rooms
        ]);
    }

    public function create(Venue $venue)
    {
        $this->authorize('update', $venue);

        return inertia('HotelRooms/Create', [
            'venue' => $venue
        ]);
    }

    public function store(Request $request, Venue $venue)
    {
        $this->authorize('update', $venue);

        $validated = $request->validate([
            'room_number' => ['required', 'string', Rule::unique('hotel_rooms')->where('venue_id', $venue->id)],
            'room_type' => ['required', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'floor_number' => ['required', 'integer', 'min:0'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'bed_count' => ['required', 'integer', 'min:1'],
            'bed_type' => ['required', 'string'],
            'bathroom_count' => ['required', 'integer', 'min:1'],
            'has_balcony' => ['required', 'boolean'],
            'has_kitchen' => ['required', 'boolean'],
            'has_wifi' => ['required', 'boolean'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['string'],
            'description' => ['nullable', 'string'],
            'policies' => ['nullable', 'string'],
            'is_smoking' => ['required', 'boolean'],
            'is_accessible' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
        ]);

        $room = $venue->hotelRooms()->create($validated);

        return redirect()->route('venues.hotel-rooms.show', [$venue, $room])
            ->with('success', 'Hotel room created successfully.');
    }

    public function show(Venue $venue, HotelRoom $room)
    {
        $this->authorize('view', $venue);

        $room->load([
            'reservations' => fn($query) => $query->upcoming()->with('user')
        ]);

        return inertia('HotelRooms/Show', [
            'venue' => $venue,
            'room' => $room
        ]);
    }

    public function edit(Venue $venue, HotelRoom $room)
    {
        $this->authorize('update', $venue);

        return inertia('HotelRooms/Edit', [
            'venue' => $venue,
            'room' => $room
        ]);
    }

    public function update(Request $request, Venue $venue, HotelRoom $room)
    {
        $this->authorize('update', $venue);

        $validated = $request->validate([
            'room_number' => ['required', 'string', Rule::unique('hotel_rooms')->where('venue_id', $venue->id)->ignore($room->id)],
            'room_type' => ['required', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'floor_number' => ['required', 'integer', 'min:0'],
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'bed_count' => ['required', 'integer', 'min:1'],
            'bed_type' => ['required', 'string'],
            'bathroom_count' => ['required', 'integer', 'min:1'],
            'has_balcony' => ['required', 'boolean'],
            'has_kitchen' => ['required', 'boolean'],
            'has_wifi' => ['required', 'boolean'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['string'],
            'description' => ['nullable', 'string'],
            'policies' => ['nullable', 'string'],
            'is_smoking' => ['required', 'boolean'],
            'is_accessible' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
        ]);

        $room->update($validated);

        return redirect()->route('venues.hotel-rooms.show', [$venue, $room])
            ->with('success', 'Hotel room updated successfully.');
    }

    public function destroy(Venue $venue, HotelRoom $room)
    {
        $this->authorize('update', $venue);

        $room->delete();

        return redirect()->route('venues.hotel-rooms.index', $venue)
            ->with('success', 'Hotel room deleted successfully.');
    }

    public function checkAvailability(Request $request, Venue $venue, HotelRoom $room)
    {
        $request->validate([
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
        ]);

        $isAvailable = $room->isAvailable(
            $request->check_in,
            $request->check_out
        );

        return response()->json([
            'available' => $isAvailable,
            'price' => $isAvailable ? $room->calculatePrice($request->check_in, $request->check_out) : null
        ]);
    }

    public function toggleStatus(Venue $venue, HotelRoom $room)
    {
        $this->authorize('update', $venue);

        $room->update(['is_active' => !$room->is_active]);

        return back()->with('success', 
            $room->is_active ? 'Room activated successfully.' : 'Room deactivated successfully.'
        );
    }
}
