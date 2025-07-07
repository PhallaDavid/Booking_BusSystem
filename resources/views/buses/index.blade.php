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
            <label class="block text-sm font-medium text-gray-600 mb-1">Type</label>
            <select name="type" class="border rounded px-3 py-2 w-40">
                <option value="">All</option>
                <option value="Premium" {{ request('type') == 'Premium' ? 'selected' : '' }}>Premium</option>
                <option value="VIP" {{ request('type') == 'VIP' ? 'selected' : '' }}>VIP</option>
                <option value="Minivan" {{ request('type') == 'Minivan' ? 'selected' : '' }}>Minivan</option>
            </select>
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($buses as $bus)
                @php $modalId = 'bus-detail-modal-' . $bus->id; @endphp
                <tr>
                    <td class="px-6 py-4">{{ $bus->id }}</td>
                    <td class="px-6 py-4">
                        @if(isset($bus->image) && $bus->image)
                        <div x-data="{ show: false }">
                            <img src="{{ asset('images/' . $bus->image) }}" alt="Bus Image" class="w-20 h-14 object-cover rounded cursor-pointer" @click="show = true">
                            <div x-show="show" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80" style="display: none;" @click.away="show = false">
                                <div class="relative">
                                    <button @click="show = false" class="absolute top-2 right-2 text-white text-3xl font-bold z-10 bg-transparent p-0 border-none shadow-none focus:outline-none">&times;</button>
                                    <img src="{{ asset('images/' . $bus->image) }}" alt="Bus Image Full" class="max-w-full max-h-screen rounded shadow-lg">
                                </div>
                            </div>
                        </div>
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
                    <td class="px-6 py-4 capitalize">{{ $bus->type }}</td>
                    <td class="px-6 py-4 relative">
                        <div x-data="{ open: false, showDetail: false }" class="relative inline-block text-left">
                            <button type="button" @click="open = !open" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                Actions
                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.584l3.71-3.354a.75.75 0 111.02 1.1l-4.25 3.84a.75.75 0 01-1.02 0l-4.25-3.84a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-32 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" style="display: none;" x-transition>
                                <div class="py-1" role="none">
                                    @can('bus-show')
                                    <button type="button" @click="showDetail = true; open = false" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition rounded w-full text-left">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    @endcan
                                    @can('bus-edit')
                                    <a href="{{ route('buses.edit', $bus->id) }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 transition rounded">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @endcan
                                    @can('bus-delete')
                                    <form action="{{ route('buses.destroy', $bus->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-700 transition rounded">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                            <!-- Bus Detail Modal -->
                            <div x-show="showDetail" x-transition class="fixed inset-0 z-50 w-screen h-screen flex items-center justify-center backdrop-blur-md bg-white/30" style="display: none;">
                                <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative">
                                    <button @click="showDetail = false" class="absolute top-2 right-2 text-gray-600 text-2xl font-bold bg-transparent p-0 border-none shadow-none focus:outline-none">&times;</button>
                                    <h3 class="text-xl font-bold mb-4">Bus Details</h3>
                                    <div class="flex flex-col md:flex-row gap-4">
                                        <div class="flex-shrink-0 flex flex-col items-center">
                                            @if(isset($bus->image) && $bus->image)
                                            <img src="{{ asset('images/' . $bus->image) }}" alt="Bus Image" class="w-32 h-24 object-cover rounded mb-2">
                                            @else
                                            <span class="text-gray-400">No Image</span>
                                            @endif
                                            <span class="text-xs text-gray-500">ID: {{ $bus->id }}</span>
                                        </div>
                                        <div class="flex-1 grid grid-cols-1 gap-2">
                                            <div><span class="font-semibold">Name:</span> {{ $bus->name }}</div>
                                            <div><span class="font-semibold">Provider:</span> {{ $bus->provider }}</div>
                                            <div><span class="font-semibold">From:</span> {{ $bus->departure }}</div>
                                            <div><span class="font-semibold">To:</span> {{ $bus->arrival }}</div>
                                            <div><span class="font-semibold">Departure Time:</span> {{ $bus->departure_time }}</div>
                                            <div><span class="font-semibold">Arrival Time:</span> {{ $bus->arrival_time }}</div>
                                            <div><span class="font-semibold">Seats:</span> {{ $bus->seats }}</div>
                                            <div><span class="font-semibold">Available Seats:</span> @if(isset($bus->available_seats) && is_array($bus->available_seats)){{ implode(', ', $bus->available_seats) }}@else-@endif</div>
                                            <div><span class="font-semibold">Type:</span> {{ $bus->type }}</div>
                                            <div><span class="font-semibold">Price:</span> ${{ number_format($bus->price, 2) }}</div>
                                            <div><span class="font-semibold">Promotion:</span> @if($bus->is_promotion) {{ $bus->promotion_discount }}% ({{ $bus->promotion_start_date ? $bus->promotion_start_date->format('M d') : '' }} - {{ $bus->promotion_end_date ? $bus->promotion_end_date->format('M d') : '' }}) @else No @endif</div>
                                            <div><span class="font-semibold">Current Price:</span> @if($bus->isPromotionActive()) ${{ number_format($bus->getCurrentPrice(), 2) }} @else - @endif</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Bus Detail Modal -->
                        </div>
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