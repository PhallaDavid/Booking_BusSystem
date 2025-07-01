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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Seats</th>
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
                    <td class="px-6 py-4">${{ $bus->price }}</td>
                    <td class="px-6 py-4">{{ $bus->seats }}</td>
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
                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">No buses found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection