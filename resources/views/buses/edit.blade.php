@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Edit Bus</h1>
    <form action="{{ route('buses.update', $bus) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow-md">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Name</label>
            <input type="text" name="name" class="w-full border px-3 py-2 rounded" required value="{{ old('name', $bus->name) }}">
            @error('name')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Provider</label>
            <input type="text" name="provider" class="w-full border px-3 py-2 rounded" required value="{{ old('provider', $bus->provider) }}">
            @error('provider')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Departure</label>
            <input type="text" name="departure" class="w-full border px-3 py-2 rounded" required value="{{ old('departure', $bus->departure) }}">
            @error('departure')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Arrival</label>
            <input type="text" name="arrival" class="w-full border px-3 py-2 rounded" required value="{{ old('arrival', $bus->arrival) }}">
            @error('arrival')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Departure Time</label>
            <input type="datetime-local" name="departure_time" class="w-full border px-3 py-2 rounded" required value="{{ old('departure_time', $bus->departure_time ? date('Y-m-d\TH:i', strtotime($bus->departure_time)) : '') }}">
            @error('departure_time')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Arrival Time</label>
            <input type="datetime-local" name="arrival_time" class="w-full border px-3 py-2 rounded" required value="{{ old('arrival_time', $bus->arrival_time ? date('Y-m-d\TH:i', strtotime($bus->arrival_time)) : '') }}">
            @error('arrival_time')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Price</label>
            <input type="number" step="0.01" name="price" class="w-full border px-3 py-2 rounded" required value="{{ old('price', $bus->price) }}">
            @error('price')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Seats</label>
            <input type="number" name="seats" class="w-full border px-3 py-2 rounded" required value="{{ old('seats', $bus->seats) }}">
            @error('seats')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Image</label>
            <input type="file" name="image" class="w-full border px-3 py-2 rounded">
            @if($bus->image)
            <img src="{{ asset('images/' . $bus->image) }}" class="w-20 h-20 mt-2">
            @endif
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-gradient-to-r from-indigo-500 to-emerald-500 text-white px-6 py-2 rounded-lg shadow-md font-semibold transition hover:from-indigo-600 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-400">Update Bus</button>
        </div>
    </form>
</div>
@endsection