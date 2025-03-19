<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RestaurantTableController extends Controller
{
    use AuthorizesRequests;

    public function index(Venue $venue)
    {
        $this->authorize('view', $venue);

        $tables = $venue->restaurantTables()
            ->with(['reservations' => fn($query) => $query->upcoming()])
            ->get();

        return inertia('RestaurantTables/Index', [
            'venue' => $venue,
            'tables' => $tables
        ]);
    }

    public function create(Venue $venue)
    {
        $this->authorize('update', $venue);

        return inertia('RestaurantTables/Create', [
            'venue' => $venue
        ]);
    }

    public function store(Request $request, Venue $venue)
    {
        $this->authorize('update', $venue);

        $validated = $request->validate([
            'table_number' => ['required', 'string', Rule::unique('restaurant_tables')->where('venue_id', $venue->id)],
            'location' => ['required', 'string'],
            'min_capacity' => ['required', 'integer', 'min:1'],
            'max_capacity' => ['required', 'integer', 'gte:min_capacity'],
            'table_type' => ['required', 'string'],
            'is_accessible' => ['required', 'boolean'],
            'is_smoking' => ['required', 'boolean'],
            'default_reservation_duration' => ['required', 'integer', 'min:30'],
            'seating_options' => ['nullable', 'array'],
            'seating_options.*' => ['string'],
            'notes' => ['nullable', 'string'],
            'requires_deposit' => ['required', 'boolean'],
            'deposit_amount' => ['nullable', 'required_if:requires_deposit,true', 'numeric', 'min:0'],
            'minimum_spend' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);

        $table = $venue->restaurantTables()->create($validated);

        return redirect()->route('venues.restaurant-tables.show', [$venue, $table])
            ->with('success', 'Restaurant table created successfully.');
    }

    public function show(Venue $venue, RestaurantTable $table)
    {
        $this->authorize('view', $venue);

        $table->load([
            'reservations' => fn($query) => $query->upcoming()->with('user')
        ]);

        return inertia('RestaurantTables/Show', [
            'venue' => $venue,
            'table' => $table
        ]);
    }

    public function edit(Venue $venue, RestaurantTable $table)
    {
        $this->authorize('update', $venue);

        return inertia('RestaurantTables/Edit', [
            'venue' => $venue,
            'table' => $table
        ]);
    }

    public function update(Request $request, Venue $venue, RestaurantTable $table)
    {
        $this->authorize('update', $venue);

        $validated = $request->validate([
            'table_number' => ['required', 'string', Rule::unique('restaurant_tables')->where('venue_id', $venue->id)->ignore($table->id)],
            'location' => ['required', 'string'],
            'min_capacity' => ['required', 'integer', 'min:1'],
            'max_capacity' => ['required', 'integer', 'gte:min_capacity'],
            'table_type' => ['required', 'string'],
            'is_accessible' => ['required', 'boolean'],
            'is_smoking' => ['required', 'boolean'],
            'default_reservation_duration' => ['required', 'integer', 'min:30'],
            'seating_options' => ['nullable', 'array'],
            'seating_options.*' => ['string'],
            'notes' => ['nullable', 'string'],
            'requires_deposit' => ['required', 'boolean'],
            'deposit_amount' => ['nullable', 'required_if:requires_deposit,true', 'numeric', 'min:0'],
            'minimum_spend' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);

        $table->update($validated);

        return redirect()->route('venues.restaurant-tables.show', [$venue, $table])
            ->with('success', 'Restaurant table updated successfully.');
    }

    public function destroy(Venue $venue, RestaurantTable $table)
    {
        $this->authorize('update', $venue);

        $table->delete();

        return redirect()->route('venues.restaurant-tables.index', $venue)
            ->with('success', 'Restaurant table deleted successfully.');
    }

    public function checkAvailability(Request $request, Venue $venue, RestaurantTable $table)
    {
        $request->validate([
            'start_time' => ['required', 'date'],
            'end_time' => ['required', 'date', 'after:start_time'],
            'party_size' => ['required', 'integer', 'min:1'],
        ]);

        if ($request->party_size < $table->min_capacity || $request->party_size > $table->max_capacity) {
            return response()->json([
                'available' => false,
                'message' => 'Table capacity does not match party size.'
            ]);
        }

        $isAvailable = $table->isAvailable(
            $request->start_time,
            $request->end_time
        );

        return response()->json([
            'available' => $isAvailable,
            'deposit_required' => $isAvailable ? $table->deposit_required : null,
            'deposit_amount' => $isAvailable ? $table->deposit_amount : null,
            'minimum_spend' => $isAvailable ? $table->minimum_spend : null,
        ]);
    }

    public function toggleStatus(Venue $venue, RestaurantTable $table)
    {
        $this->authorize('update', $venue);

        $table->update(['is_active' => !$table->is_active]);

        return back()->with('success', 
            $table->is_active ? 'Table activated successfully.' : 'Table deactivated successfully.'
        );
    }
}
