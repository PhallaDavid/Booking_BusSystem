@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Buses</h1>
        <a href="{{ route('buses.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Bus</a>
    </div>
    @if(session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif
    <form method="GET" action="{{ route('buses.index') }}" class="mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">From</label>
            <input type="text" name="from" value="{{ request('from') }}" class="border rounded px-3 py-2 w-40" placeholder="Departure city">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">To</label>
            <input type="text" name="to" value="{{ request('to') }}" class="border rounded px-3 py-2 w-40" placeholder="Destination city">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Departure Date</label>
            <input type="date" name="departure_date" value="{{ request('departure_date') }}" class="border rounded px-3 py-2 w-40">
        </div>
        <div class="flex items-center">
            <label class="flex items-center">
                <input type="checkbox" name="active_promotions" value="1" {{ request('active_promotions') ? 'checked' : '' }} class="mr-2">
                <span class="text-sm font-medium text-gray-600">Active Promotions Only</span>
            </label>
        </div>
        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Search</button>
        </div>
    </form>
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Provider</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">From</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Departure</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Arrival</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Promotion</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Seats</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Available Seats</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($buses as $bus)
                <tr>
                    <td class="px-6 py-4">{{ $bus->id }}</td>
                    <td class="px-6 py-4">
                        @if(isset($bus->image) && $bus->image)
                        <img src="{{ asset('images/' . $bus->image) }}" alt="Bus Image" class="w-20 h-14 object-cover rounded" />
                        @else
                        <span class="text-gray-400">No Image</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $bus->name }}</td>
                    <td class="px-6 py-4">{{ $bus->provider }}</td>
                    <td class="px-6 py-4">{{ $bus->departure }}</td>
                    <td class="px-6 py-4">{{ $bus->arrival }}</td>
                    <td class="px-6 py-4">{{ $bus->departure_time }}</td>
                    <td class="px-6 py-4">{{ $bus->arrival_time }}</td>
                    <td class="px-6 py-4">
                        @if($bus->isPromotionActive())
                        <div class="text-sm">
                            <span class="line-through text-gray-500">${{ number_format($bus->price, 2) }}</span>
                            <br>
                            <span class="text-red-600 font-bold">${{ number_format($bus->getCurrentPrice(), 2) }}</span>
                            <br>
                            <span class="text-xs text-green-600">{{ $bus->promotion_discount }}% OFF</span>
                        </div>
                        @else
                        <span>${{ number_format($bus->price, 2) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($bus->is_promotion)
                        @if($bus->isPromotionActive())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $bus->promotion_start_date->format('M d') }} - {{ $bus->promotion_end_date->format('M d') }}
                        </div>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Inactive
                        </span>
                        @endif
                        @else
                        <span class="text-gray-400">No Promotion</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $bus->seats }}</td>
                    <td class="px-6 py-4">
                        @if(isset($bus->available_seats) && is_array($bus->available_seats))
                            {{ implode(', ', $bus->available_seats) }}
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('buses.edit', $bus) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Edit</a>
                        <form action="{{ route('buses.destroy', $bus) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="px-6 py-4 text-center text-gray-500">No buses found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection