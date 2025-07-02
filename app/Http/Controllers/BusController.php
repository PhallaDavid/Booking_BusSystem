<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:bus-list')->only(['index', 'show']);
        $this->middleware('can:bus-create')->only(['create', 'store']);
        $this->middleware('can:bus-edit')->only(['edit', 'update']);
        $this->middleware('can:bus-delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Bus::query();
        if ($request->filled('from')) {
            $query->where('departure', 'like', "%" . $request->input('from') . "%");
        }
        if ($request->filled('to')) {
            $query->where('arrival', 'like', "%" . $request->input('to') . "%");
        }
        if ($request->filled('departure_date')) {
            $departureDate = $request->input('departure_date');
            if (strlen($departureDate) === 10) { // Format: YYYY-MM-DD
                $query->whereDate('departure_time', $departureDate);
            } else {
                $query->where('departure_time', $departureDate);
            }
        }
        if ($request->filled('active_promotions')) {
            $query->withActivePromotions();
        }
        $buses = $query->get();
        // Add available_seats to each bus for the Blade view
        foreach ($buses as $bus) {
            $bus->available_seats = method_exists($bus, 'getAvailableSeats') ? $bus->getAvailableSeats() : [];
        }
        return view('buses.index', compact('buses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('buses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'departure' => 'required|string|max:255',
            'arrival' => 'required|string|max:255',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date',
            'price' => 'required|numeric|min:0',
            'seats' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_promotion' => 'boolean',
            'promotion_start_date' => 'nullable|date|required_if:is_promotion,1',
            'promotion_end_date' => 'nullable|date|required_if:is_promotion,1|after:promotion_start_date',
            'promotion_discount' => 'nullable|numeric|min:0|max:100|required_if:is_promotion,1',
            'type' => 'nullable|in:simple,premium',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validated['image'] = $imageName;
        }

        // Calculate promotion price if promotion is enabled
        if (isset($validated['is_promotion']) && $validated['is_promotion'] && isset($validated['promotion_discount'])) {
            $validated['promotion_price'] = $validated['price'] * (1 - $validated['promotion_discount'] / 100);
        }

        Bus::create($validated);
        return redirect()->route('buses.index')->with('success', 'Bus created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bus $bus)
    {
        return view('buses.show', compact('bus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bus $bus)
    {
        return view('buses.edit', compact('bus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'departure' => 'required|string|max:255',
            'arrival' => 'required|string|max:255',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date',
            'price' => 'required|numeric|min:0',
            'seats' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_promotion' => 'boolean',
            'promotion_start_date' => 'nullable|date|required_if:is_promotion,1',
            'promotion_end_date' => 'nullable|date|required_if:is_promotion,1|after:promotion_start_date',
            'promotion_discount' => 'nullable|numeric|min:0|max:100|required_if:is_promotion,1',
            'type' => 'nullable|in:simple,premium',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($bus->image && file_exists(public_path('images/' . $bus->image))) {
                unlink(public_path('images/' . $bus->image));
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validated['image'] = $imageName;
        }

        // Calculate promotion price if promotion is enabled
        if (isset($validated['is_promotion']) && $validated['is_promotion'] && isset($validated['promotion_discount'])) {
            $validated['promotion_price'] = $validated['price'] * (1 - $validated['promotion_discount'] / 100);
        } else {
            // Clear promotion fields if promotion is disabled
            $validated['is_promotion'] = false;
            $validated['promotion_start_date'] = null;
            $validated['promotion_end_date'] = null;
            $validated['promotion_discount'] = null;
            $validated['promotion_price'] = null;
        }

        $bus->update($validated);
        return redirect()->route('buses.index')->with('success', 'Bus updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bus $bus)
    {
        $bus->delete();
        return redirect()->route('buses.index')->with('success', 'Bus deleted successfully.');
    }

    /**
     * Return a listing of buses as JSON for API.
     */
    public function apiIndex(Request $request)
    {
        $query = Bus::query();

        // All search fields are optional
        if ($request->filled('from')) {
            $query->where('departure', 'like', "%" . $request->input('from') . "%");
        }
        if ($request->filled('to')) {
            $query->where('arrival', 'like', "%" . $request->input('to') . "%");
        }
        if ($request->filled('departure_date')) {
            $departureDate = $request->input('departure_date');
            if (strlen($departureDate) === 10) { // Format: YYYY-MM-DD
                $query->whereDate('departure_time', $departureDate);
            } else {
                $query->where('departure_time', $departureDate);
            }
        }
        if ($request->filled('return_date')) {
            $query->where('arrival_time', $request->input('return_date'));
        }
        if ($request->filled('active_promotions')) {
            $query->withActivePromotions();
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('provider', 'like', "%$search%")
                    ->orWhere('departure', 'like', "%$search%")
                    ->orWhere('arrival', 'like', "%$search%")
                    ->orWhere('price', 'like', "%$search%")
                    ->orWhere('seats', 'like', "%$search%")
                    ->orWhere('departure_time', 'like', "%$search%")
                    ->orWhere('arrival_time', 'like', "%$search%")
                ;
            });
        }

        $buses = $query->get()->map(function ($bus) {
            $bus->image_url = $bus->image ? asset('images/' . $bus->image) : null;
            $bus->current_price = $bus->getCurrentPrice();
            $bus->is_promotion_active = $bus->isPromotionActive();
            $bus->promotion_info = $bus->is_promotion ? [
                'start_date' => $bus->promotion_start_date?->format('Y-m-d'),
                'end_date' => $bus->promotion_end_date?->format('Y-m-d'),
                'discount_percentage' => $bus->promotion_discount,
                'promotion_price' => $bus->promotion_price,
                'is_active' => $bus->isPromotionActive()
            ] : null;
            $bus->available_seats = $bus->getAvailableSeats();
            return $bus;
        });
        return response()->json([
            'data' => $buses
        ]);
    }

    /**
     * Return unique departure and arrival cities for select options.
     */
    public function cities()
    {
        // Get all unique city names from both departure and arrival columns
        $departureCities = Bus::query()->distinct()->pluck('departure')->toArray();
        $arrivalCities = Bus::query()->distinct()->pluck('arrival')->toArray();
        $allCities = array_unique(array_merge($departureCities, $arrivalCities));
        $allCities = array_values($allCities); // reindex

        // Format as array of objects with id and name
        $cities = collect($allCities)->map(function ($city, $index) {
            return [
                'id' => $index + 1,
                'name' => $city
            ];
        })->values();

        return response()->json([
            'cities' => $cities
        ]);
    }

    /**
     * Get a single bus by ID as JSON for API.
     */
    public function apiShow($id)
    {
        $bus = Bus::findOrFail($id);
        $bus->image_url = $bus->image ? asset('images/' . $bus->image) : null;
        $bus->current_price = $bus->getCurrentPrice();
        $bus->is_promotion_active = $bus->isPromotionActive();
        $bus->promotion_info = $bus->is_promotion ? [
            'start_date' => $bus->promotion_start_date?->format('Y-m-d'),
            'end_date' => $bus->promotion_end_date?->format('Y-m-d'),
            'discount_percentage' => $bus->promotion_discount,
            'promotion_price' => $bus->promotion_price,
            'is_active' => $bus->isPromotionActive()
        ] : null;
        $bus->available_seats = $bus->getAvailableSeats();
        return response()->json(['data' => $bus]);
    }

    /**
     * Get buses by type as JSON for API.
     */
    public function apiByType($type)
    {
        $buses = Bus::where('type', $type)->get()->map(function ($bus) {
            $bus->image_url = $bus->image ? asset('images/' . $bus->image) : null;
            $bus->current_price = $bus->getCurrentPrice();
            $bus->is_promotion_active = $bus->isPromotionActive();
            $bus->promotion_info = $bus->is_promotion ? [
                'start_date' => $bus->promotion_start_date?->format('Y-m-d'),
                'end_date' => $bus->promotion_end_date?->format('Y-m-d'),
                'discount_percentage' => $bus->promotion_discount,
                'promotion_price' => $bus->promotion_price,
                'is_active' => $bus->isPromotionActive()
            ] : null;
            $bus->available_seats = $bus->getAvailableSeats();
            return $bus;
        });
        return response()->json(['data' => $buses]);
    }
}
