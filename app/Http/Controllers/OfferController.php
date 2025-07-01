<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:offer-list|offer-create|offer-edit|offer-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:offer-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:offer-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:offer-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Offer::query();
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('code', 'like', "%$search%")
                    ->orWhere('valid_till', 'like', "%$search%");
            });
        }
        $offers = $query->latest()->paginate(5);
        return view('offers.index', compact('offers'));
    }

    public function apiIndex()
    {
        $offers = Offer::latest()->get();
        return response()->json($offers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('offers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'code' => 'required',
            'valid_till' => 'required|date',
        ]);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        $offer = new Offer;
        $offer->title = $request->title;
        $offer->image = $imageName;
        $offer->code = $request->code;
        $offer->valid_till = $request->valid_till;
        $offer->save();

        return redirect()->route('offers.index')
            ->with('success', 'Offer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offer $offer)
    {
        return view('offers.edit', compact('offer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offer $offer)
    {
        request()->validate([
            'title' => 'required',
            'code' => 'required',
            'valid_till' => 'required|date',
        ]);

        $imageName = $offer->image;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            if ($offer->image) {
                unlink(public_path('images/' . $offer->image));
            }
        }

        $offer->title = $request->title;
        $offer->image = $imageName;
        $offer->code = $request->code;
        $offer->valid_till = $request->valid_till;
        $offer->save();

        return redirect()->route('offers.index')
            ->with('success', 'Offer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $offer)
    {
        if ($offer->image) {
            unlink(public_path('images/' . $offer->image));
        }
        $offer->delete();

        return redirect()->route('offers.index')
            ->with('success', 'Offer deleted successfully');
    }
}
