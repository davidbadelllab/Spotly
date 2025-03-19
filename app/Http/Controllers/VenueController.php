<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VenueController extends Controller
{
    public function index(Request $request)
    {
        $venues = Venue::query()
            ->when($request->type, fn($query, $type) => $query->ofType($type))
            ->when($request->city, fn($query, $city) => $query->where('city', $city))
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                });
            })
            ->active()
            ->with(['sportsFacilities', 'hotelRooms', 'restaurantTables'])
            ->paginate(10);

        return inertia('Venues/Index', [
            'venues' => $venues,
            'filters' => $request->only(['type', 'city', 'search'])
        ]);
    }

    public function create()
    {
        return inertia('Venues/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', Rule::in(['sports', 'hotel', 'restaurant'])],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'country' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'phone' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'website' => ['nullable', 'url'],
            'business_hours' => ['nullable', 'array'],
            'business_hours.*' => ['array:open,close'],
        ]);

        $venue = Auth::user()->venues()->create($validated);

        return redirect()->route('venues.show', $venue)
            ->with('success', 'Venue created successfully.');
    }

    public function show(Venue $venue)
    {
        $venue->load([
            'sportsFacilities',
            'hotelRooms',
            'restaurantTables',
            'reservations' => fn($query) => $query->upcoming()->with('user')
        ]);

        return inertia('Venues/Show', [
            'venue' => $venue
        ]);
    }

    public function edit(Venue $venue)
    {
        $this->authorize('update', $venue);

        return inertia('Venues/Edit', [
            'venue' => $venue
        ]);
    }

    public function update(Request $request, Venue $venue)
    {
        $this->authorize('update', $venue);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'country' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'phone' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'website' => ['nullable', 'url'],
            'business_hours' => ['nullable', 'array'],
            'business_hours.*' => ['array:open,close'],
            'is_active' => ['boolean'],
        ]);

        $venue->update($validated);

        return redirect()->route('venues.show', $venue)
            ->with('success', 'Venue updated successfully.');
    }

    public function destroy(Venue $venue)
    {
        $this->authorize('delete', $venue);

        $venue->delete();

        return redirect()->route('venues.index')
            ->with('success', 'Venue deleted successfully.');
    }

    public function toggleStatus(Venue $venue)
    {
        $this->authorize('update', $venue);

        $venue->update(['is_active' => !$venue->is_active]);

        return back()->with('success', 
            $venue->is_active ? 'Venue activated successfully.' : 'Venue deactivated successfully.'
        );
    }
}
