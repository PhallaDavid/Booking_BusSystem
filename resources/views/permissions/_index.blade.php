<div class="bg-white p-4 rounded shadow">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Permissions</h2>
        @can('permission-create')
        <a href="{{ route('permissions.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 font-semibold shadow">Create Permission</a>
        @endcan
    </div>
    <form method="GET" action="{{ route('settings.index') }}" class="mb-4 flex items-center space-x-2">
        <input type="text" name="permission_search" value="{{ request('permission_search') }}" placeholder="Search permissions..." class="border rounded px-3 py-2 w-64 focus:ring-emerald-400 focus:border-emerald-400">
        <input type="hidden" name="tab" value="permissions">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 font-semibold shadow">Search</button>
        @if(request('permission_search'))
        <a href="{{ route('settings.index', ['tab' => 'permissions']) }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 font-semibold shadow">Clear</a>
        @endif
    </form>
    <table class="min-w-full bg-white border border-gray-200">
        <thead>
            <tr>
                <th class="px-4 py-2 border">ID</th>
                <th class="px-4 py-2 border">Name</th>
                <th class="px-4 py-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permissions as $permission)
            <tr>
                <td class="px-4 py-2 border">{{ $permission->id }}</td>
                <td class="px-4 py-2 border">{{ $permission->name }}</td>
                <td class="px-4 py-2 border">
                    @can('permission-edit')
                    <a href="{{ route('permissions.edit', $permission) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition-colors duration-200 mr-2">Edit</a>
                    @endcan
                    @can('permission-delete')
                    <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="inline">
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