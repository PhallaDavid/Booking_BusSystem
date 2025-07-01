<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        // Users search
        $userQuery = User::with('roles');
        if ($request->filled('user_search')) {
            $search = $request->input('user_search');
            $userQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }
        $users = $userQuery->get();

        // Roles search
        $roleQuery = Role::with('permissions');
        if ($request->filled('role_search')) {
            $search = $request->input('role_search');
            $roleQuery->where('name', 'like', "%$search%");
        }
        $roles = $roleQuery->get();

        // Permissions search
        $permissionQuery = Permission::query();
        if ($request->filled('permission_search')) {
            $search = $request->input('permission_search');
            $permissionQuery->where('name', 'like', "%$search%");
        }
        $permissions = $permissionQuery->get();

        return view('settings.index', compact('users', 'roles', 'permissions'));
    }
}
