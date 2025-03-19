<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\SportsFacility;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SportsFacilityController extends Controller
{
    use AuthorizesRequests;

    public function index(Venue $venue)
    {
        $this->authorize('view', $venue);

        $facilities = $venue->sportsFacilities()
            ->with(['reservations' => fn($query) => $query->upcoming()])
            ->get();

        return inertia('SportsFacilities/Index', [
            'venue' => $venue,
            'facilities' => $facilities
        ]);
    }

    public function create(Venue $venue)
    {
        $this->authorize('update', $venue);

        return inertia('SportsFacilities/Create', [
            'venue' => $venue
        ]);
    }

    public function store(Request $request, Venue $venue)
    {
        $this->authorize('update', $venue);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sport_type' => ['required', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'equipment_provided' => ['nullable', 'string'],
            'rules' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:30'],
            'price_per_hour' => ['required', 'numeric', 'min:0'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['string'],
            'is_indoor' => ['required', 'boolean'],
            'has_lighting' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
        ]);

        $facility = $venue->sportsFacilities()->create($validated);

        return redirect()->route('venues.sports-facilities.show', [$venue, $facility])
            ->with('success', 'Sports facility created successfully.');
    }

    public function show(Venue $venue, SportsFacility $facility)
    {
        $this->authorize('view', $venue);

        $facility->load([
            'reservations' => fn($query) => $query->upcoming()->with('user')
        ]);

        return inertia('SportsFacilities/Show', [
            'venue' => $venue,
            'facility' => $facility
        ]);
    }

    public function edit(Venue $venue, SportsFacility $facility)
    {
        $this->authorize('update', $venue);

        return inertia('SportsFacilities/Edit', [
            'venue' => $venue,
            'facility' => $facility
        ]);
    }

    public function update(Request $request, Venue $venue, SportsFacility $facility)
    {
        $this->authorize('update', $venue);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sport_type' => ['required', 'string'],
            'capacity' => ['required', 'integer', 'min:1'],
            'equipment_provided' => ['nullable', 'string'],
            'rules' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:30'],
            'price_per_hour' => ['required', 'numeric', 'min:0'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['string'],
            'is_indoor' => ['required', 'boolean'],
            'has_lighting' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
        ]);

        $facility->update($validated);

        return redirect()->route('venues.sports-facilities.show', [$venue, $facility])
            ->with('success', 'Sports facility updated successfully.');
    }

    public function destroy(Venue $venue, SportsFacility $facility)
    {
        $this->authorize('update', $venue);

        $facility->delete();

        return redirect()->route('venues.sports-facilities.index', $venue)
            ->with('success', 'Sports facility deleted successfully.');
    }

    public function checkAvailability(Request $request, Venue $venue, SportsFacility $facility)
    {
        $request->validate([
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after:start_time'],
        ]);

        $isAvailable = $facility->isAvailable(
            $request->start_time,
            $request->end_time
        );

        return response()->json([
            'available' => $isAvailable,
            'price' => $isAvailable ? $facility->calculatePrice($request->start_time, $request->end_time) : null
        ]);
    }

    public function toggleStatus(Venue $venue, SportsFacility $facility)
    {
        $this->authorize('update', $venue);

        $facility->update(['is_active' => !$facility->is_active]);

        return back()->with('success', 
            $facility->is_active ? 'Facility activated successfully.' : 'Facility deactivated successfully.'
        );
    }
}
