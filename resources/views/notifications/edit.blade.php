@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 max-w-lg">
    <h1 class="text-2xl font-bold mb-6">Edit Notification</h1>
    <form method="POST" action="{{ route('notifications.update', $notification) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="image" class="block font-medium text-sm text-gray-700">Image</label>
            <input type="file" name="image" id="image" class="block mt-1 w-full rounded-md border-gray-300">
            @if($notification->image)
            <img src="{{ asset('images/' . $notification->image) }}" alt="Image" class="h-16 w-16 object-cover rounded-full mt-2">
            @endif
        </div>
        <div>
            <label for="title" class="block font-medium text-sm text-gray-700">Title / Content</label>
            <input type="text" name="title" id="title" class="block mt-1 w-full rounded-md border-gray-300" value="{{ old('title', $notification->title) }}" required>
        </div>
        <div>
            <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
            <textarea name="description" id="description" rows="3" class="block mt-1 w-full rounded-md border-gray-300" required>{{ old('description', $notification->description) }}</textarea>
        </div>
        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('notifications.index') }}" class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 font-semibold shadow">Cancel</a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 font-semibold shadow">Update Notification</button>
        </div>
    </form>
</div>
@endsection