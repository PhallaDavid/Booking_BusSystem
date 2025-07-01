<div class="bg-white p-4 rounded shadow">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Roles</h2>
        @can('role-create')
        <a href="{{ route('roles.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 font-semibold shadow">Create Role</a>
        @endcan
    </div>
    <form method="GET" action="{{ route('settings.index') }}" class="mb-4 flex items-center space-x-2">
        <input type="text" name="role_search" value="{{ request('role_search') }}" placeholder="Search roles..." class="border rounded px-3 py-2 w-64 focus:ring-emerald-400 focus:border-emerald-400">
        <input type="hidden" name="tab" value="roles">
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow px-4 py-2 transition">Search</button>
        @if(request('role_search'))
        <a href="{{ route('settings.index') }}" class="text-sm text-gray-500 hover:underline ml-2">Clear</a>
        @endif
    </form>
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="px-4 py-2 border">ID</th>
                <th class="px-4 py-2 border">Name</th>
                <th class="px-4 py-2 border">Permissions</th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td class="px-4 py-2 border">{{ $role->id }}</td>
                <td class="px-4 py-2 border">{{ $role->name }}</td>
                <td class="px-4 py-2 border">{{ $role->permissions->pluck('name')->join(', ') }}</td>
                <td class="px-4 py-2 border">
                    @can('role-edit')
                    <a href="{{ route('roles.edit', $role) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition-colors duration-200 mr-2">Edit</a>
                    @endcan
                    @can('role-delete')
                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition-colors duration-200" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>