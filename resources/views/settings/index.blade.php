@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Settings</h1>
    <div x-data="{ tab: '{{ request('tab', 'users') }}' }">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button @click="tab = 'users'" :class="{'border-indigo-500 text-indigo-600': tab === 'users', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'users'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Users</button>
                <button @click="tab = 'roles'" :class="{'border-indigo-500 text-indigo-600': tab === 'roles', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'roles'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Roles</button>
                <button @click="tab = 'permissions'" :class="{'border-indigo-500 text-indigo-600': tab === 'permissions', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'permissions'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Permissions</button>
                <button @click="tab = 'notifications'" :class="{'border-indigo-500 text-indigo-600': tab === 'notifications', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'notifications'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">Notifications</button>
            </nav>
        </div>
        <div x-show="tab === 'users'" class="mt-8">
            @include('users._index')
        </div>
        <div x-show="tab === 'roles'" class="mt-8">
            @include('roles._index')
        </div>
        <div x-show="tab === 'permissions'" class="mt-8">
            @include('permissions._index')
        </div>
        <div x-show="tab === 'notifications'" class="mt-8">
            <div class="bg-white rounded-lg shadow p-6 max-w-lg mx-auto">
                <h2 class="text-xl font-bold mb-4">Create Notification</h2>
                <form method="POST" action="{{ route('notifications.store') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="image" class="block font-medium text-sm text-gray-700">Image</label>
                        <input type="file" name="image" id="image" class="block mt-1 w-full rounded-md border-gray-300">
                    </div>
                    <div>
                        <label for="title" class="block font-medium text-sm text-gray-700">Title / Content</label>
                        <input type="text" name="title" id="title" class="block mt-1 w-full rounded-md border-gray-300" required>
                    </div>
                    <div>
                        <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" class="block mt-1 w-full rounded-md border-gray-300" required></textarea>
                    </div>
                    <div class="flex items-center justify-end">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 font-semibold shadow">Create Notification</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection