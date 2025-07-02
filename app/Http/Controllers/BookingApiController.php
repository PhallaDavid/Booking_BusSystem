<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bus;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\BookingSeat;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BookingApiController extends Controller
{
    // POST /api/bookings
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'travel_date' => 'required|date',
            'seats' => 'required|array|min:1',
            'seats.*' => 'required|string',
            'passengers' => 'required|array|min:1',
            'passengers.*.name' => 'required|string',
            'passengers.*.gender' => 'nullable|string',
            'passengers.*.age' => 'nullable|integer',
            'passengers.*.email' => 'nullable|email',
            'passengers.*.phone' => 'nullable|string',
            'discount_code' => 'nullable|string',
            'payment_method' => 'required|string',
        ]);

        $user = $request->user();
        $bus = Bus::findOrFail($validated['bus_id']);
        $seatNumbers = $validated['seats'];
        $passengers = $validated['passengers'];

        // Check if seats are already booked
        $alreadyBooked = BookingSeat::where('bus_id', $bus->id)
            ->whereIn('seat_number', $seatNumbers)
            ->exists();
        if ($alreadyBooked) {
            return response()->json(['message' => 'One or more seats are already booked.'], 422);
        }

        // Calculate total price
        $originalPrice = $bus->price * count($seatNumbers);
        $pricePerSeat = $bus->getCurrentPrice();
        $totalPrice = $pricePerSeat * count($seatNumbers);
        $discountAmount = 0;
        $appliedOffer = null;
        if (!empty($validated['discount_code'])) {
            // Check for valid offer
            $offer = \App\Models\Offer::where('code', $validated['discount_code'])
                ->whereDate('valid_till', '>=', now())
                ->first();
            if (
                $offer &&
                (!$offer->start_date || $offer->start_date <= now()->toDateString()) &&
                (!$offer->end_date || $offer->end_date >= now()->toDateString())
            ) {
                // Use offer's discount_percent if set, else default to 10%
                $percent = $offer->discount_percent ?? 10;
                $discountAmount = ($percent / 100) * $totalPrice;
                $totalPrice -= $discountAmount;
                $appliedOffer = $offer;
            } else {
                // fallback: old logic (e.g. 10% off for any code)
                $discountAmount = 0.1 * $totalPrice;
                $totalPrice -= $discountAmount;
            }
        }

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'user_id' => $user ? $user->id : null,
                'bus_id' => $bus->id,
                'travel_date' => $validated['travel_date'],
                'total_price' => $totalPrice,
                'discount_code' => $validated['discount_code'] ?? null,
                'discount_amount' => $discountAmount,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
            ]);

            // Create passengers and seats
            $passengerIds = [];
            foreach ($passengers as $i => $pdata) {
                $passenger = BookingPassenger::create([
                    'booking_id' => $booking->id,
                    'name' => $pdata['name'],
                    'gender' => $pdata['gender'] ?? null,
                    'age' => $pdata['age'] ?? null,
                    'email' => $pdata['email'] ?? null,
                    'phone' => $pdata['phone'] ?? null,
                ]);
                $passengerIds[] = $passenger->id;
            }
            foreach ($seatNumbers as $i => $seatNum) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'bus_id' => $bus->id,
                    'passenger_id' => $passengerIds[$i] ?? null,
                    'seat_number' => $seatNum,
                ]);
            }

            // Generate ticket
            $ticket = Ticket::create([
                'booking_id' => $booking->id,
                'ticket_number' => strtoupper(Str::random(10)),
                'status' => 'confirmed',
                'issued_at' => Carbon::now(),
                'delivery_method' => 'email', // or from request
            ]);

            DB::commit();

            // Send Telegram notification
            try {
                $botToken = env('TELEGRAM_BOT_TOKEN', 'YOUR_BOT_TOKEN');
                $chatId = env('TELEGRAM_CHAT_ID', 'YOUR_CHAT_ID');
                $userName = $user ? ($user->name ?? 'User#' . $user->id) : 'Guest';
                $message = "N.O: {$booking->id}\n" .
                    "New Booking\n" .
                    "Customer: {$userName}\n" .
                    "Company: {$bus->name}\n" .
                    "From: {$bus->departure}\n" .
                    " To: {$bus->arrival}\n" .
                    "Date: {$booking->travel_date}\n" .
                    "Seats: " . implode(', ', $seatNumbers) . "\n" .
                    "Passengers: " . implode(', ', array_map(fn($p) => $p['name'], $passengers)) . "\n" .
                    "Original: $" . number_format($originalPrice, 2) . "\n" .
                    ($bus->isPromotionActive() ? ("Promotion: $" . number_format($pricePerSeat, 2) . " per seat\n") : "") .
                    ($discountAmount > 0 ? ("Discount: -$" . number_format($discountAmount, 2) . "\n") : "") .
                    (isset($appliedOffer) ? ("Offer: {$appliedOffer->code}\n") : "") .
                    "Total: $" . number_format($totalPrice, 2) . "\n" .
                    "Ticket: {$ticket->ticket_number}";
                $response = Http::withoutVerifying()->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $message,
                    // 'parse_mode' => 'Markdown', // removed for plain text
                ]);
                Log::info('Telegram response: ' . $response->body());
            } catch (\Exception $e) {
                Log::error('Telegram error: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Booking successful',
                'booking' => $booking,
                'ticket' => $ticket,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking failed: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return response()->json(['message' => 'Booking failed', 'error' => $e->getMessage()], 500);
        }
    }
}
