<div class="bg-white p-4 rounded shadow">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Users</h2>
        @can('user-create')
        <a href="{{ route('users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 font-semibold shadow">Create User</a>
        @endcan
    </div>
    <form method="GET" action="{{ route('settings.index') }}" class="mb-4 flex items-center space-x-2">
        <input type="text" name="user_search" value="{{ request('user_search') }}" placeholder="Search users..." class="border rounded px-3 py-2 w-64 focus:ring-emerald-400 focus:border-emerald-400">
        <input type="hidden" name="tab" value="users">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 font-semibold shadow">Search</button>
        @if(request('user_search'))
        <a href="{{ route('settings.index') }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 font-semibold shadow">Clear</a>
        @endif
    </form>
    @php
    // Filter users to exclude those with the 'customer' role
    $nonCustomerUsers = $users->filter(function($user) {
    return !$user->hasRole('customer');
    });
    @endphp
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="px-4 py-2 border">ID</th>
                <th class="px-4 py-2 border">Name</th>
                <th class="px-4 py-2 border">Email</th>
                <th class="px-4 py-2 border">Role</th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($nonCustomerUsers as $user)
            <tr>
                <td class="px-4 py-2 border">{{ $user->id }}</td>
                <td class="px-4 py-2 border">{{ $user->name }}</td>
                <td class="px-4 py-2 border">{{ $user->email }}</td>
                <td class="px-4 py-2 border">{{ $user->roles->pluck('name')->join(', ') }}</td>
                <td class="px-4 py-2 border">
                    @can('user-show')
                    <a href="{{ route('users.show', $user->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View</a>
                    @endcan
                    @can('user-edit')
                    <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Edit</a>
                    @endcan
                    @can('user-delete')
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>