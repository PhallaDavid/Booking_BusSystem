@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Add New Bus</h1>
    <form action="{{ route('buses.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Name</label>
            <input type="text" name="name" class="w-full border px-3 py-2 rounded" required value="{{ old('name') }}">
            @error('name')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Provider</label>
            <input type="text" name="provider" class="w-full border px-3 py-2 rounded" required value="{{ old('provider') }}">
            @error('provider')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Departure</label>
            <input type="text" name="departure" class="w-full border px-3 py-2 rounded" required value="{{ old('departure') }}">
            @error('departure')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Arrival</label>
            <input type="text" name="arrival" class="w-full border px-3 py-2 rounded" required value="{{ old('arrival') }}">
            @error('arrival')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Departure Time</label>
            <input type="datetime-local" name="departure_time" class="w-full border px-3 py-2 rounded" required value="{{ old('departure_time') }}">
            @error('departure_time')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Arrival Time</label>
            <input type="datetime-local" name="arrival_time" class="w-full border px-3 py-2 rounded" required value="{{ old('arrival_time') }}">
            @error('arrival_time')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Price</label>
            <input type="number" step="0.01" name="price" class="w-full border px-3 py-2 rounded" required value="{{ old('price') }}">
            @error('price')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Seats</label>
            <input type="number" name="seats" class="w-full border px-3 py-2 rounded" required value="{{ old('seats') }}">
            @error('seats')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Image</label>
            <input type="file" name="image" class="w-full border px-3 py-2 rounded">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow px-6 py-2 transition">Create Bus</button>
        </div>
    </form>
</div>
@endsection