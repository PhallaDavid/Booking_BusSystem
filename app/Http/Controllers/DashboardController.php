<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $roleCount = Role::count();
        $permissionCount = Permission::count();

        return view('dashboard', compact('userCount', 'roleCount', 'permissionCount'));
    }
}
