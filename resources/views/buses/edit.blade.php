@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Edit Bus</h1>
    <form action="{{ route('buses.update', $bus) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Name</label>
            <input type="text" name="name" class="w-full border px-3 py-2 rounded" required value="{{ old('name', $bus->name) }}">
            @error('name')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Provider</label>
            <input type="text" name="provider" class="w-full border px-3 py-2 rounded" required value="{{ old('provider', $bus->provider) }}">
            @error('provider')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Departure</label>
            <input type="text" name="departure" class="w-full border px-3 py-2 rounded" required value="{{ old('departure', $bus->departure) }}">
            @error('departure')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Arrival</label>
            <input type="text" name="arrival" class="w-full border px-3 py-2 rounded" required value="{{ old('arrival', $bus->arrival) }}">
            @error('arrival')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Departure Time</label>
            <input type="datetime-local" name="departure_time" class="w-full border px-3 py-2 rounded" required value="{{ old('departure_time', $bus->departure_time ? date('Y-m-d\TH:i', strtotime($bus->departure_time)) : '') }}">
            @error('departure_time')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Arrival Time</label>
            <input type="datetime-local" name="arrival_time" class="w-full border px-3 py-2 rounded" required value="{{ old('arrival_time', $bus->arrival_time ? date('Y-m-d\TH:i', strtotime($bus->arrival_time)) : '') }}">
            @error('arrival_time')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Price</label>
            <input type="number" step="0.01" name="price" id="price" class="w-full border px-3 py-2 rounded" required value="{{ old('price', $bus->price) }}">
            @error('price')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Seats</label>
            <input type="number" name="seats" class="w-full border px-3 py-2 rounded" required value="{{ old('seats', $bus->seats) }}">
            @error('seats')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Type</label>
            <select name="type" class="w-full border px-3 py-2 rounded" required>
                @foreach($types as $type)
                <option value="{{ $type }}" {{ old('type', $bus->type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
            @error('type')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Image</label>
            <input type="file" name="image" class="w-full border px-3 py-2 rounded">
            @if($bus->image)
            <img src="{{ asset('images/' . $bus->image) }}" class="w-20 h-20 mt-2">
            @endif
        </div>

        <!-- Promotion Section -->
        <div class="mb-6 p-4 border rounded-lg bg-gray-50">
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_promotion" id="is_promotion" value="1" class="mr-2" {{ old('is_promotion', $bus->is_promotion) ? 'checked' : '' }}>
                    <span class="font-semibold text-lg">Enable Promotion</span>
                </label>
            </div>

            <div id="promotion-fields" class="space-y-4" style="display: none;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 font-semibold">Promotion Start Date</label>
                        <input type="date" name="promotion_start_date" class="w-full border px-3 py-2 rounded" value="{{ old('promotion_start_date', $bus->promotion_start_date ? $bus->promotion_start_date->format('Y-m-d') : '') }}">
                        @error('promotion_start_date')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block mb-1 font-semibold">Promotion End Date</label>
                        <input type="date" name="promotion_end_date" class="w-full border px-3 py-2 rounded" value="{{ old('promotion_end_date', $bus->promotion_end_date ? $bus->promotion_end_date->format('Y-m-d') : '') }}">
                        @error('promotion_end_date')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Discount Percentage (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="promotion_discount" id="promotion_discount" class="w-full border px-3 py-2 rounded" value="{{ old('promotion_discount', $bus->promotion_discount) }}" placeholder="e.g., 20 for 20% off">
                    @error('promotion_discount')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                </div>
                <div class="bg-blue-100 p-3 rounded">
                    <p class="text-sm"><strong>Promotion Price:</strong> <span id="promotion-price-display">${{ number_format($bus->promotion_price ?? 0, 2) }}</span></p>
                    <p class="text-xs text-gray-600">This will be calculated automatically based on the original price and discount percentage.</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 font-semibold shadow">Update Bus</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const promotionCheckbox = document.getElementById('is_promotion');
        const promotionFields = document.getElementById('promotion-fields');
        const priceInput = document.getElementById('price');
        const discountInput = document.getElementById('promotion_discount');
        const promotionPriceDisplay = document.getElementById('promotion-price-display');

        function togglePromotionFields() {
            if (promotionCheckbox.checked) {
                promotionFields.style.display = 'block';
            } else {
                promotionFields.style.display = 'none';
            }
            calculatePromotionPrice();
        }

        function calculatePromotionPrice() {
            const price = parseFloat(priceInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;

            if (promotionCheckbox.checked && discount > 0) {
                const promotionPrice = price * (1 - discount / 100);
                promotionPriceDisplay.textContent = '$' + promotionPrice.toFixed(2);
            } else {
                promotionPriceDisplay.textContent = '$0.00';
            }
        }

        promotionCheckbox.addEventListener('change', togglePromotionFields);
        priceInput.addEventListener('input', calculatePromotionPrice);
        discountInput.addEventListener('input', calculatePromotionPrice);

        // Initialize on page load
        togglePromotionFields();
    });
</script>
@endsection