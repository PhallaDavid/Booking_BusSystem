@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Bus Details</h1>
    <div class="bg-white p-6 rounded shadow-md">
        <div class="mb-2"><strong>Name:</strong> {{ $bus->name }}</div>
        <div class="mb-2"><strong>Provider:</strong> {{ $bus->provider }}</div>
        <div class="mb-2"><strong>Departure:</strong> {{ $bus->departure }}</div>
        <div class="mb-2"><strong>Arrival:</strong> {{ $bus->arrival }}</div>
        <div class="mb-2"><strong>Departure Time:</strong> {{ $bus->departure_time }}</div>
        <div class="mb-2"><strong>Arrival Time:</strong> {{ $bus->arrival_time }}</div>
        <div class="mb-2"><strong>Price:</strong> ${{ $bus->price }}</div>
        <div class="mb-2"><strong>Seats:</strong> {{ $bus->seats }}</div>
        <div class="mt-4">
            <a href="{{ route('buses.edit', $bus) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit</a>
            <a href="{{ route('buses.index') }}" class="ml-2 text-gray-600 hover:underline">Back to List</a>
        </div>
    </div>
</div>
@endsection