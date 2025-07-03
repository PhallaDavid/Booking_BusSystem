@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Create Offer</h1>
    @if ($errors->any())
    <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('offers.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="title" class="block font-medium text-sm text-gray-700">Title</label>
                <input type="text" name="title" id="title" class="block mt-1 w-full rounded-md border-gray-300" value="{{ old('title') }}" required>
            </div>
            <div>
                <label for="image" class="block font-medium text-sm text-gray-700">Image</label>
                <input type="file" name="image" id="image" class="block mt-1 w-full">
            </div>
            <div>
                <label for="code" class="block font-medium text-sm text-gray-700">Code</label>
                <div class="flex">
                    <input type="text" name="code" id="code" class="block mt-1 w-full rounded-md border-gray-300" value="{{ old('code') }}" required readonly>
                    <button type="button" onclick="generateOfferCode()" class="ml-2 px-3 py-2 bg-emerald-500 text-white rounded hover:bg-emerald-600">Generate</button>
                </div>
            </div>
            <div>
                <label for="valid_till" class="block font-medium text-sm text-gray-700">Valid Till</label>
                <input type="date" name="valid_till" id="valid_till" class="block mt-1 w-full rounded-md border-gray-300" value="{{ old('valid_till') }}" required>
            </div>
            <div>
                <label for="discount_percent" class="block font-medium text-sm text-gray-700">Discount Percent (%)</label>
                <input type="number" name="discount_percent" id="discount_percent" class="block mt-1 w-full rounded-md border-gray-300" value="{{ old('discount_percent') }}" min="0" max="100" step="0.01">
            </div>
            <div>
                <label for="start_date" class="block font-medium text-sm text-gray-700">Promotion Start Date</label>
                <input type="date" name="start_date" id="start_date" class="block mt-1 w-full rounded-md border-gray-300" value="{{ old('start_date') }}">
            </div>
            <div>
                <label for="end_date" class="block font-medium text-sm text-gray-700">Promotion End Date</label>
                <input type="date" name="end_date" id="end_date" class="block mt-1 w-full rounded-md border-gray-300" value="{{ old('end_date') }}">
            </div>
        </div>
        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 font-semibold shadow">Create</button>
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