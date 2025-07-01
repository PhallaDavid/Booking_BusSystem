<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\BookingSeat;
use App\Models\Ticket;

class BookingController extends Controller
{
    // Step 1: Search Bus
    public function search(Request $request)
    {
        $query = Bus::query();
        if ($request->filled('from')) {
            $query->where('departure', 'like', "%" . $request->input('from') . "%");
        }
        if ($request->filled('to')) {
            $query->where('arrival', 'like', "%" . $request->input('to') . "%");
        }
        if ($request->filled('departure_date')) {
            $query->whereDate('departure_time', $request->input('departure_date'));
        }
        $buses = $query->get();
        return view('booking.search', compact('buses'));
    }

    // Step 2: View Bus List & Select Bus
    public function showBus($busId)
    {
        $bus = Bus::findOrFail($busId);
        return view('booking.select_seats', compact('bus'));
    }

    // Step 3: Select Seats (show seat selection UI)
    public function selectSeats($busId)
    {
        $bus = Bus::findOrFail($busId);
        // Optionally, fetch already booked seats
        $bookedSeats = BookingSeat::where('bus_id', $busId)->pluck('seat_number')->toArray();
        return view('booking.select_seats', compact('bus', 'bookedSeats'));
    }

    // Step 4: Enter Passenger Info
    public function passengerInfo(Request $request, $busId)
    {
        $bus = Bus::findOrFail($busId);
        $selectedSeats = $request->input('seats', []);
        return view('booking.passenger_info', compact('bus', 'selectedSeats'));
    }

    // Step 5-8: Store Booking (apply discount, payment, confirm, generate ticket)
    public function store(Request $request, $busId)
    {
        // Validate and process booking
        // ... (to be implemented)
    }

    // Show all bookings
    public function index(Request $request)
    {
        $query = \App\Models\Booking::with(['user', 'bus'])->latest();
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->input('bus_id'));
        }
        if ($request->filled('travel_date')) {
            $query->whereDate('travel_date', $request->input('travel_date'));
        }
        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('name') . '%');
            });
        }
        $bookings = $query->get();
        return view('booking.index', compact('bookings'));
    }

    public function update(Request $request, $id)
    {
        $booking = \App\Models\Booking::findOrFail($id);
        $this->authorize('booking-edit');
        $action = $request->input('action');
        if ($action === 'confirm') {
            $booking->status = 'confirmed';
        } elseif ($action === 'cancel') {
            $booking->status = 'cancelled';
        }
        $booking->save();
        return redirect()->route('bookings.index')->with('success', 'Booking updated.');
    }

    public function destroy($id)
    {
        $booking = \App\Models\Booking::findOrFail($id);
        $this->authorize('booking-delete');
        $booking->delete();
        return redirect()->route('bookings.index')->with('success', 'Booking deleted.');
    }

    public function show($id)
    {
        $booking = \App\Models\Booking::with(['user', 'bus', 'passengers', 'seats', 'ticket'])->findOrFail($id);
        return view('booking.show', compact('booking'));
    }
}
