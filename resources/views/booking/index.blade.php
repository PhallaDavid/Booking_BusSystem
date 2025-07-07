@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Bookings</h1>
    </div>
    <form method="GET" action="{{ route('bookings.index') }}" class="mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">User ID</label>
            <input type="text" name="user_id" value="{{ request('user_id') }}" class="border rounded px-3 py-2 w-40" placeholder="All">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
            <select name="status" class="border rounded px-3 py-2 w-20">
                <option value="">All</option>
                <option value="pending" @if(request('status')=='pending' ) selected @endif>Pending</option>
                <option value="confirmed" @if(request('status')=='confirmed' ) selected @endif>Confirmed</option>
                <option value="cancelled" @if(request('status')=='cancelled' ) selected @endif>Cancelled</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Bus</label>
            <select name="bus_id" class="border rounded px-3 py-2 w-40">
                <option value="">All</option>
                @foreach(\App\Models\Bus::all() as $bus)
                <option value="{{ $bus->id }}" @if(request('bus_id')==$bus->id) selected @endif>{{ $bus->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Travel Date</label>
            <input type="date" name="travel_date" value="{{ request('travel_date') }}" class="border rounded px-3 py-2 w-40">
        </div>
        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Search</button>
        </div>
    </form>
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full text-base font-normal rounded divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase">N.O</th>
                    <th class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase">Bus</th>
                    <th class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase">Original</th>
                    <th class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase">Discount</th>
                    <th class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase">Seats</th>
                    <th class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase">Payment</th>
                    <th class="px-6 py-3 text-left text-base font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                <tr>
                    <td class="px-6 py-4">{{ $booking->id }}</td>
                    <td class="px-6 py-4">{{ $booking->user ? $booking->user->name : 'Guest' }}</td>
                    <td class="px-6 py-4">{{ $booking->bus ? $booking->bus->name : '-' }}</td>
                    <td class="px-6 py-4">{{ $booking->travel_date }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($booking->status == 'confirmed') bg-emerald-100 text-emerald-800
                            @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                            @endif
                        ">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">${{ number_format(($booking->bus ? $booking->bus->price * $booking->seats->count() : 0), 2) }}</td>
                    <td class="px-6 py-4">${{ number_format($booking->discount_amount, 2) }}</td>
                    <td class="px-6 py-4">${{ number_format($booking->total_price, 2) }}</td>
                    <td class="px-6 py-4">{{ $booking->seats->pluck('seat_number')->join(', ') }}</td>
                    <td class="px-6 py-4">{{ ucfirst($booking->payment_method) ?? '-' }}</td>
                    <td class="px-6 py-4 relative">
                        <div x-data="{ open: false, showModal: false }" class="relative inline-block text-left">
                            <button type="button" @click="open = !open" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none" id="menu-button-{{ $booking->id }}" aria-expanded="true" aria-haspopup="true">
                                Actions
                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.584l3.71-3.354a.75.75 0 111.02 1.1l-4.25 3.84a.75.75 0 01-1.02 0l-4.25-3.84a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-28 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" role="menu" aria-orientation="vertical" aria-labelledby="menu-button-{{ $booking->id }}">
                                <div class="py-1" role="none">
                                    @can('booking-edit')
                                    @if($booking->status !== 'confirmed')
                                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" onsubmit="return confirm('Confirm this booking?');">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="confirm">
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-emerald-700 hover:bg-emerald-100 flex items-center gap-2">
                                            <i class="fas fa-edit"></i> Confirm
                                        </button>
                                    </form>
                                    @endif
                                    @if($booking->status !== 'cancelled')
                                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST" onsubmit="return confirm('Cancel this booking?');">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="cancel">
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-yellow-700 hover:bg-yellow-100 flex items-center gap-2">
                                            <i class="fas fa-times-circle"></i> Cancel
                                        </button>
                                    </form>
                                    @endif
                                    @endcan
                                    @can('booking-delete')
                                    <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Delete this booking?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-100 flex items-center gap-2">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                            <!-- Modal -->
                            <div x-show="showModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40" x-init="
                                $watch('showModal', value => {
                                    if(value) { document.body.classList.add('overflow-hidden'); } else { document.body.classList.remove('overflow-hidden'); }
                                });
                            " @keydown.window.escape="showModal = false">
                                <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative" @click.away="showModal = false">
                                    <button @click="showModal = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                                    <h2 class="text-xl font-bold mb-4">Booking Details (N.O: {{ $booking->id }})</h2>
                                    <div class="mb-2"><strong>User:</strong> {{ $booking->user ? $booking->user->name : 'Guest' }} (ID: {{ $booking->user_id }})</div>
                                    <div class="mb-2"><strong>Bus:</strong> {{ $booking->bus ? $booking->bus->name : '-' }}</div>
                                    <div class="mb-2"><strong>Date:</strong> {{ $booking->travel_date }}</div>
                                    <div class="mb-2"><strong>Status:</strong> {{ ucfirst($booking->status) }}</div>
                                    <div class="mb-2"><strong>Original Price:</strong> ${{ $booking->bus ? number_format($booking->bus->price * $booking->seats->count(), 2) : '0.00' }}</div>
                                    <div class="mb-2"><strong>Discount:</strong> ${{ number_format($booking->discount_amount, 2) }}</div>
                                    <div class="mb-2"><strong>Total:</strong> ${{ number_format($booking->total_price, 2) }}</div>
                                    <div class="mb-2"><strong>Discount Code:</strong> {{ $booking->discount_code ?? '-' }}</div>
                                    <div class="mb-2"><strong>Payment Method:</strong> {{ $booking->payment_method ?? '-' }}</div>
                                    <div class="mb-2"><strong>Ticket Number:</strong> {{ $booking->ticket ? $booking->ticket->ticket_number : '-' }}</div>
                                    <div class="mb-4"><strong>Seats:</strong> {{ $booking->seats->pluck('seat_number')->join(', ') }}</div>
                                    <div class="mb-4">
                                        <h3 class="font-semibold">Passengers</h3>
                                        <ul class="list-disc pl-6">
                                            @foreach($booking->passengers as $passenger)
                                            <li>{{ $passenger->name }} ({{ $passenger->gender ?? '-' }}, {{ $passenger->age ?? '-' }} years, {{ $passenger->email ?? '-' }}, {{ $passenger->phone ?? '-' }})</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <button @click="showModal = false" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Close</button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No bookings found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($bookings->count())
        <div class="flex justify-end bg-white border-t border-gray-200">
            <div class="px-6 py-4 font-bold text-lg">
                Gross Total: ${{ number_format($bookings->sum('total_price'), 2) }}<br>
                Net Total (after discount): ${{ number_format($bookings->sum(function($b) { return $b->total_price - $b->discount_amount; }), 2) }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection