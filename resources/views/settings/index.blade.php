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
    </div>
</div>
@endsection