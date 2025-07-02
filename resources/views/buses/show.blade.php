@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Bus Details</h1>
    <div class="bg-white p-6 rounded shadow-md">
        <div class="mb-2"><strong>Name:</strong> {{ $bus->name }}</div>
        <div class="mb-2"><strong>Provider:</strong> {{ $bus->provider }}</div>
        <div class="mb-2"><strong>Departure:</strong> {{ $bus->departure }}</div>
        <div class="mb-2"><strong>Arrival:</strong> {{ $bus->arrival }}</div>
        <div class="mb-2"><strong>Departure Time:</strong> {{ $bus->departure_time }}</div>
        <div class="mb-2"><strong>Arrival Time:</strong> {{ $bus->arrival_time }}</div>

        <!-- Price Section -->
        <div class="mb-4">
            <strong>Price:</strong>
            @if($bus->isPromotionActive())
            <div class="ml-4">
                <div class="line-through text-gray-500">Original: ${{ number_format($bus->price, 2) }}</div>
                <div class="text-red-600 font-bold text-lg">Current: ${{ number_format($bus->getCurrentPrice(), 2) }}</div>
                <div class="text-green-600 font-semibold">{{ $bus->promotion_discount }}% OFF</div>
            </div>
            @else
            <span class="ml-2">${{ number_format($bus->price, 2) }}</span>
            @endif
        </div>

        <!-- Promotion Section -->
        <div class="mb-4">
            <strong>Promotion Status:</strong>
            @if($bus->is_promotion)
            @if($bus->isPromotionActive())
            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                Active Promotion
            </span>
            @else
            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                Inactive Promotion
            </span>
            @endif
            @else
            <span class="ml-2 text-gray-400">No Promotion</span>
            @endif
        </div>

        @if($bus->is_promotion)
        <div class="mb-4 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-semibold mb-2">Promotion Details:</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <strong>Start Date:</strong> {{ $bus->promotion_start_date ? $bus->promotion_start_date->format('F d, Y') : 'Not set' }}
                </div>
                <div>
                    <strong>End Date:</strong> {{ $bus->promotion_end_date ? $bus->promotion_end_date->format('F d, Y') : 'Not set' }}
                </div>
                <div>
                    <strong>Discount:</strong> {{ $bus->promotion_discount }}%
                </div>
                <div>
                    <strong>Promotion Price:</strong> ${{ number_format($bus->promotion_price ?? 0, 2) }}
                </div>
            </div>
        </div>
        @endif

        <div class="mb-2"><strong>Seats:</strong> {{ $bus->seats }}</div>

        <div class="mt-4">
            <a href="{{ route('buses.edit', $bus) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit</a>
            <a href="{{ route('buses.index') }}" class="ml-2 text-gray-600 hover:underline">Back to List</a>
        </div>
    </div>
</div>
@endsection