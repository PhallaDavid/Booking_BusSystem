@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Manage Recurring Schedules for Bus: {{ $bus->name }}</h1>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-2">Add New Schedule</h2>
        <form action="{{ route('buses.storeSchedule', $bus->id) }}" method="POST" class="bg-white p-4 rounded shadow-md">
            @csrf
            <div class="mb-2">
                <label class="block font-semibold">Recurrence Type</label>
                <select name="recurrence_type" class="border rounded px-3 py-2 w-full" required>
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="custom">Custom</option>
                </select>
                @error('recurrence_type')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-2">
                <label class="block font-semibold">Days of Week (for weekly/custom)</label>
                <select name="days_of_week[]" class="border rounded px-3 py-2 w-full" multiple>
                    <option value="monday">Monday</option>
                    <option value="tuesday">Tuesday</option>
                    <option value="wednesday">Wednesday</option>
                    <option value="thursday">Thursday</option>
                    <option value="friday">Friday</option>
                    <option value="saturday">Saturday</option>
                    <option value="sunday">Sunday</option>
                </select>
                @error('days_of_week')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-2">
                <label class="block font-semibold">Start Time</label>
                <input type="time" name="start_time" class="border rounded px-3 py-2 w-full" required>
                @error('start_time')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-2">
                <label class="block font-semibold">End Time</label>
                <input type="time" name="end_time" class="border rounded px-3 py-2 w-full" required>
                @error('end_time')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-2">
                <label class="block font-semibold">Start Date</label>
                <input type="date" name="start_date" class="border rounded px-3 py-2 w-full" required>
                @error('start_date')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="mb-2">
                <label class="block font-semibold">End Date</label>
                <input type="date" name="end_date" class="border rounded px-3 py-2 w-full">
                @error('end_date')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Schedule</button>
            </div>
        </form>
    </div>

    <div>
        <h2 class="text-lg font-semibold mb-2">Existing Schedules</h2>
        <table class="min-w-full bg-white rounded shadow overflow-x-auto">
            <thead>
                <tr>
                    <th class="px-4 py-2">Type</th>
                    <th class="px-4 py-2">Days</th>
                    <th class="px-4 py-2">Start Time</th>
                    <th class="px-4 py-2">End Time</th>
                    <th class="px-4 py-2">Start Date</th>
                    <th class="px-4 py-2">End Date</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bus->schedules as $schedule)
                <tr>
                    <td class="px-4 py-2">{{ ucfirst($schedule->recurrence_type) }}</td>
                    <td class="px-4 py-2">{{ is_array($schedule->days_of_week) ? implode(', ', $schedule->days_of_week) : '-' }}</td>
                    <td class="px-4 py-2">{{ $schedule->start_time }}</td>
                    <td class="px-4 py-2">{{ $schedule->end_time }}</td>
                    <td class="px-4 py-2">{{ $schedule->start_date }}</td>
                    <td class="px-4 py-2">{{ $schedule->end_date ?? '-' }}</td>
                    <td class="px-4 py-2">
                        <form action="{{ route('buses.destroySchedule', [$bus->id, $schedule->id]) }}" method="POST" onsubmit="return confirm('Delete this schedule?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-2 text-center text-gray-500">No schedules found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection