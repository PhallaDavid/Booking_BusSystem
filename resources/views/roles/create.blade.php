@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Create Role</h1>
    @if ($errors->any())
    <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('roles.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Role Name</label>
            <input type="text" name="name" class="w-full border px-3 py-2 rounded" required value="{{ old('name') }}">
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Assign Permissions</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach($permissions as $permission)
                <label class="inline-flex items-center">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="form-checkbox" {{ (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) ? 'checked' : '' }}>
                    <span class="ml-2">{{ $permission->name }}</span>
                </label>
                @endforeach
            </div>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 font-semibold shadow">Create Role</button>
        </div>
    </form>
</div>
@endsection