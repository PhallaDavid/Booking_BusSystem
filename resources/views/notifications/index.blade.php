@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Notifications</h1>
    @if(session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            <h2 class="text-lg font-semibold mb-4">All Notifications</h2>
            <form method="GET" action="{{ route('notifications.index') }}" class="mb-4 flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search notifications..." class="border rounded px-3 py-2 w-64">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Search</button>
                @if(request('search'))
                <a href="{{ route('notifications.index') }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 font-semibold shadow">Clear</a>
                @endif
            </form>
            <div class="bg-white rounded-lg shadow p-4 overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border">Image</th>
                            <th class="px-4 py-2 border">Title</th>
                            <th class="px-4 py-2 border">Description</th>
                            <th class="px-4 py-2 border">Created At</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="notifications-tbody">
                        @forelse($notifications as $notification)
                        <tr>
                            <td class="px-4 py-2 border">
                                @if($notification->image)
                                <img src="{{ asset('images/' . $notification->image) }}" alt="Image" class="h-10 w-10 object-cover rounded-full">
                                @else
                                <span class="text-gray-400">No Image</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border">{{ $notification->title }}</td>
                            <td class="px-4 py-2 border">{{ $notification->description }}</td>
                            <td class="px-4 py-2 border text-xs text-gray-500">{{ $notification->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2 border text-center">
                                <a href="{{ route('notifications.edit', $notification) }}" class="inline-block px-2 py-1 text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this notification?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1 text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 border text-center text-gray-400">No notifications found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <h2 class="text-lg font-semibold mb-4">Create Notification</h2>
            <div class="bg-white rounded-lg shadow p-6">
                <form id="create-notification-form" method="POST" action="{{ route('notifications.store') }}" enctype="multipart/form-data" class="space-y-4">
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

@section('scripts')
<script>
    function renderNotificationsTable(notifications) {
        const tbody = document.getElementById('notifications-tbody');
        tbody.innerHTML = '';
        if (notifications.length === 0) {
            tbody.innerHTML = `<tr><td colspan='5' class='px-4 py-2 border text-center text-gray-400'>No notifications found.</td></tr>`;
            return;
        }
        notifications.forEach(n => {
            tbody.innerHTML += `
        <tr>
            <td class='px-4 py-2 border'>${n.image ? `<img src='${n.image}' alt='Image' class='h-10 w-10 object-cover rounded-full'>` : `<span class='text-gray-400'>No Image</span>`}</td>
            <td class='px-4 py-2 border'>${n.title}</td>
            <td class='px-4 py-2 border'>${n.description}</td>
            <td class='px-4 py-2 border text-xs text-gray-500'>${n.created_at}</td>
            <td class='px-4 py-2 border text-center'>
                <a href='#' class='inline-block px-2 py-1 text-blue-600 hover:underline' onclick='openEditModal(${n.id})'>Edit</a>
                <button onclick='deleteNotification(${n.id})' class='px-2 py-1 text-red-600 hover:underline'>Delete</button>
            </td>
        </tr>
        `;
        });
    }

    async function fetchNotifications() {
        try {
            const res = await fetch('/api/notifications');
            const data = await res.json();
            renderNotificationsTable(data);
        } catch (e) {}
    }

    setInterval(fetchNotifications, 10000);
    window.addEventListener('DOMContentLoaded', fetchNotifications);

    // AJAX create notification
    const createForm = document.getElementById('create-notification-form');
    if (createForm) {
        createForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(createForm);
            const btn = createForm.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.textContent = 'Creating...';
            try {
                const res = await fetch(createForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData
                });
                if (res.ok) {
                    createForm.reset();
                    fetchNotifications();
                    showSuccess('Notification created successfully.');
                } else {
                    const data = await res.json();
                    showError(data.message || 'Failed to create notification.');
                }
            } catch (e) {
                showError('Failed to create notification.');
            }
            btn.disabled = false;
            btn.textContent = 'Create Notification';
        });
    }

    function showSuccess(msg) {
        let el = document.getElementById('notif-success');
        if (!el) {
            el = document.createElement('div');
            el.id = 'notif-success';
            el.className = 'bg-green-100 text-green-800 px-4 py-2 rounded mb-4';
            document.querySelector('.container').prepend(el);
        }
        el.textContent = msg;
        setTimeout(() => el.remove(), 3000);
    }

    function showError(msg) {
        let el = document.getElementById('notif-error');
        if (!el) {
            el = document.createElement('div');
            el.id = 'notif-error';
            el.className = 'bg-red-100 text-red-800 px-4 py-2 rounded mb-4';
            document.querySelector('.container').prepend(el);
        }
        el.textContent = msg;
        setTimeout(() => el.remove(), 3000);
    }
</script>
@endsection