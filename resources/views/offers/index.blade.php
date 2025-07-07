@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold mb-0">Offers</h1>
        <a href="{{ route('offers.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-block">Create Offer</a>
    </div>
    <form method="GET" action="" class="mb-4 flex items-center space-x-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search offers..." class="border rounded px-3 py-2 w-64 focus:ring-emerald-400 focus:border-emerald-400">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-block">Search</button>
        @if(request('search'))
        <a href="{{ url()->current() }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 font-semibold shadow">Clear</a>
        @endif
    </form>
    <div class="mt-4">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Title</th>
                    <th class="px-4 py-2 border">Image</th>
                    <th class="px-4 py-2 border">Code</th>
                    <th class="px-4 py-2 border">Promotion Start Date</th>
                    <th class="px-4 py-2 border">Promotion End Date</th>
                    <th class="px-4 py-2 border">Valid Till</th>
                    <th class="px-4 py-2 border">Promotion %</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($offers as $offer)
                <tr>
                    <td class="px-4 py-2 border">{{ $offer->id }}</td>
                    <td class="px-4 py-2 border">{{ $offer->title }}</td>
                    <td class="px-4 py-2 border">
                        @if($offer->image)
                        <div x-data="{ show: false }">
                            <img src="{{ asset('images/' . $offer->image) }}" alt="{{ $offer->title }}" class="h-10 w-10 object-cover rounded-full cursor-pointer" @click="show = true">
                            <div x-show="show" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-80" style="display: none;" @click.away="show = false">
                                <div class="relative">
                                    <button @click="show = false" class="absolute top-2 right-2 text-white text-3xl font-bold z-10 bg-transparent p-0 border-none shadow-none focus:outline-none">&times;</button>
                                    <img src="{{ asset('images/' . $offer->image) }}" alt="{{ $offer->title }} Full" class="max-w-full max-h-screen rounded shadow-lg">
                                </div>
                            </div>
                        </div>
                        @endif
                    </td>
                    <td class="px-4 py-2 border">{{ $offer->code }}</td>
                    <td class="px-4 py-2 border">{{ $offer->start_date ?? '-' }}</td>
                    <td class="px-4 py-2 border">{{ $offer->end_date ?? '-' }}</td>
                    <td class="px-4 py-2 border">{{ $offer->valid_till }}</td>
                    <td class="px-4 py-2 border">{{ $offer->discount_percent ?? '-' }}%</td>
                    <td class="px-6 py-4 relative">
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            <button type="button" @click="open = !open" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                Actions
                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.584l3.71-3.354a.75.75 0 111.02 1.1l-4.25 3.84a.75.75 0 01-1.02 0l-4.25-3.84a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-32 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" style="display: none;" x-transition>
                                <div class="py-1" role="none">
                                    @can('offer-show')
                                    <a href="{{ route('offers.show', $offer->id) }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition rounded">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    @endcan
                                    @can('offer-edit')
                                    <a href="{{ route('offers.edit', $offer->id) }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 transition rounded">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @endcan
                                    @can('offer-delete')
                                    <form action="{{ route('offers.destroy', $offer->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-700 transition rounded">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function deleteOffer(offerId) {
        if (!confirm('Are you sure you want to delete this offer?')) return;
        axios.delete('/offers/' + offerId)
            .then(response => {
                location.reload();
            })
            .catch(error => {
                alert('Failed to delete offer.');
            });
    }

    function editOffer(offerId) {
        window.location.href = '/offers/' + offerId + '/edit';
    }
</script>
@endpush