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
        $buses = $query->get();
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
            'price' => 'required|numeric',
            'seats' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $validated['image'] = $imageName;
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
            'price' => 'required|numeric',
            'seats' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
}
