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
        <a href="{{ url()->current() }}" class="text-sm text-gray-500 hover:underline ml-2">Clear</a>
        @endif
    </form>
    <div class="overflow-x-auto mt-4">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Title</th>
                    <th class="px-4 py-2 border">Image</th>
                    <th class="px-4 py-2 border">Code</th>
                    <th class="px-4 py-2 border">Valid Till</th>
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
                        <img src="{{ asset('images/' . $offer->image) }}" alt="{{ $offer->title }}" class="h-10 w-10 object-cover rounded-full">
                        @endif
                    </td>
                    <td class="px-4 py-2 border">{{ $offer->code }}</td>
                    <td class="px-4 py-2 border">{{ $offer->valid_till }}</td>
                    <td class="px-4 py-2 border">
                        <button onclick="editOffer({{ $offer->id }})" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 mr-2">Edit</button>
                        <button onclick="deleteOffer({{ $offer->id }})" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
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