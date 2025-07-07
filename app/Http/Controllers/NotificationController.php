<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        }

        Notification::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return back()->with('success', 'Notification created successfully.');
    }

    public function index(Request $request)
    {
        $query = \App\Models\Notification::query();
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }
        $notifications = $query->orderBy('created_at', 'desc')->get();
        return view('notifications.index', compact('notifications'));
    }

    public function apiIndex()
    {
        $notifications = \App\Models\Notification::orderBy('created_at', 'desc')->get();
        $data = $notifications->map(function ($n) {
            return [
                'id' => $n->id,
                'image' => $n->image ? asset('images/' . $n->image) : null,
                'title' => $n->title,
                'description' => $n->description,
                'created_at' => $n->created_at->toDateTimeString(),
            ];
        });
        return response()->json($data);
    }

    public function edit(\App\Models\Notification $notification)
    {
        return view('notifications.edit', compact('notification'));
    }

    public function update(Request $request, \App\Models\Notification $notification)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = $notification->image;
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            if ($notification->image) {
                @unlink(public_path('images/' . $notification->image));
            }
        }

        $notification->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return redirect()->route('notifications.index')->with('success', 'Notification updated successfully.');
    }

    public function destroy(\App\Models\Notification $notification)
    {
        if ($notification->image) {
            @unlink(public_path('images/' . $notification->image));
        }
        $notification->delete();
        return redirect()->route('notifications.index')->with('success', 'Notification deleted successfully.');
    }
}
