@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Search Buses</h1>
    <form method="GET" action="{{ route('booking.search') }}" class="mb-6 flex flex-wrap gap-4 items-end">
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
        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Search</button>
        </div>
    </form>
    @if(isset($buses) && count($buses))
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Provider</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">From</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">To</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Departure</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Arrival</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($buses as $bus)
                <tr>
                    <td class="px-6 py-4">{{ $bus->name }}</td>
                    <td class="px-6 py-4">{{ $bus->provider }}</td>
                    <td class="px-6 py-4">{{ $bus->departure }}</td>
                    <td class="px-6 py-4">{{ $bus->arrival }}</td>
                    <td class="px-6 py-4">{{ $bus->departure_time }}</td>
                    <td class="px-6 py-4">{{ $bus->arrival_time }}</td>
                    <td class="px-6 py-4">${{ $bus->price }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('booking.selectSeats', $bus->id) }}" class="bg-emerald-500 text-white px-4 py-2 rounded hover:bg-emerald-600">Select</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @elseif(request()->all())
    <div class="text-gray-500 mt-4">No buses found for your search.</div>
    @endif
</div>
@endsection