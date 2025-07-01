@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Enter Passenger Information</h1>
    <div class="mb-4">
        <strong>Bus:</strong> {{ $bus->name }}<br>
        <strong>From:</strong> {{ $bus->departure }} <strong>To:</strong> {{ $bus->arrival }}<br>
        <strong>Departure:</strong> {{ $bus->departure_time }} <strong>Arrival:</strong> {{ $bus->arrival_time }}<br>
        <strong>Selected Seats:</strong> {{ implode(', ', $selectedSeats) }}
    </div>
    <form method="POST" action="{{ route('booking.store', $bus->id) }}">
        @csrf
        <input type="hidden" name="seats" value="{{ implode(',', $selectedSeats) }}">
        <div class="space-y-6">
            @foreach($selectedSeats as $i => $seat)
            <div class="border rounded p-4 bg-gray-50">
                <h2 class="font-semibold mb-2">Passenger for Seat {{ $seat }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Name</label>
                        <input type="text" name="passengers[{{ $i }}][name]" class="border rounded px-3 py-2 w-full" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Gender</label>
                        <select name="passengers[{{ $i }}][gender]" class="border rounded px-3 py-2 w-full">
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Age</label>
                        <input type="number" name="passengers[{{ $i }}][age]" class="border rounded px-3 py-2 w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                        <input type="email" name="passengers[{{ $i }}][email]" class="border rounded px-3 py-2 w-full">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Phone</label>
                        <input type="text" name="passengers[{{ $i }}][phone]" class="border rounded px-3 py-2 w-full">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-8 flex flex-col gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Discount Code (optional)</label>
                <input type="text" name="discount_code" class="border rounded px-3 py-2 w-60">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Payment Method</label>
                <select name="payment_method" class="border rounded px-3 py-2 w-60" required>
                    <option value="">Select</option>
                    <option value="credit_card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="cash">Cash</option>
                </select>
            </div>
        </div>
        <button type="submit" class="mt-6 bg-emerald-500 text-white px-6 py-2 rounded hover:bg-emerald-600">Confirm Booking</button>
    </form>
</div>
@endsection