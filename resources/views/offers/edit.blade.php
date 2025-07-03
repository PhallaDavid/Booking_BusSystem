@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="flex justify-between mb-6">
        <h1 class="text-2xl font-bold">Edit Offer</h1>
        <a href="{{ route('offers.index') }}" class="bg-slate-500 p-2 rounded-md text-white">Back</a>
    </div>
    @if ($errors->any())
    <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form method="POST" action="{{ route('offers.update', $offer->id) }}" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="title" class="block font-medium text-sm text-gray-700">Title</label>
                <input id="title" class="block mt-1 w-full rounded-md border-gray-300" type="text" name="title" value="{{ old('title', $offer->title) }}" required autofocus />
            </div>
            <div>
                <label for="image" class="block font-medium text-sm text-gray-700">Image</label>
                <input id="image" class="block mt-1 w-full" type="file" name="image" />
                @if ($offer->image)
                <img src="{{ asset('images/' . $offer->image) }}" class="w-20 h-20 mt-2">
                @endif
            </div>
            <div>
                <label for="code" class="block font-medium text-sm text-gray-700">Code</label>
                <div class="flex">
                    <input id="code" class="block mt-1 w-full rounded-md border-gray-300" type="text" name="code" value="{{ old('code', $offer->code) }}" required readonly />
                    <button type="button" onclick="generateOfferCode()" class="ml-2 px-3 py-2 bg-emerald-500 text-white rounded hover:bg-emerald-600">Generate</button>
                </div>
            </div>
            <div>
                <label for="valid_till" class="block font-medium text-sm text-gray-700">Valid Till</label>
                <input id="valid_till" class="block mt-1 w-full rounded-md border-gray-300" type="date" name="valid_till" value="{{ old('valid_till', $offer->valid_till instanceof \Illuminate\Support\Carbon ? $offer->valid_till->format('Y-m-d') : $offer->valid_till) }}" required />
            </div>
            <div>
                <label for="discount_percent" class="block font-medium text-sm text-gray-700">Discount Percent (%)</label>
                <input id="discount_percent" class="block mt-1 w-full rounded-md border-gray-300" type="number" name="discount_percent" value="{{ old('discount_percent', $offer->discount_percent) }}" min="0" max="100" step="0.01" />
            </div>
            <div>
                <label for="start_date" class="block font-medium text-sm text-gray-700">Promotion Start Date</label>
                <input id="start_date" class="block mt-1 w-full rounded-md border-gray-300" type="date" name="start_date" value="{{ old('start_date', $offer->start_date ?? '') }}" />
            </div>
            <div>
                <label for="end_date" class="block font-medium text-sm text-gray-700">Promotion End Date</label>
                <input id="end_date" class="block mt-1 w-full rounded-md border-gray-300" type="date" name="end_date" value="{{ old('end_date', $offer->end_date ?? '') }}" />
            </div>
        </div>
        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-block">Update</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function generateOfferCode() {
        const length = 8;
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let code = '';
        for (let i = 0; i < length; i++) {
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('code').value = code;
    }
</script>
@endsection