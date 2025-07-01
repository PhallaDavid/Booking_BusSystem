@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Edit Permission</h1>
    @if ($errors->any())
    <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('permissions.update', $permission->id) }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Permission Name</label>
            <input type="text" name="name" class="w-full border px-3 py-2 rounded" required value="{{ old('name', $permission->name) }}">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-gradient-to-r from-indigo-500 to-emerald-500 text-white px-6 py-2 rounded-lg shadow-md font-semibold transition hover:from-indigo-600 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-400">Update Permission</button>
        </div>
    </form>
</div>
@endsection