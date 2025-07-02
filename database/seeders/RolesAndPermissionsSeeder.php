<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',
            'offer-list',
            'offer-create',
            'offer-edit',
            'offer-delete',
            'offer-show',
            'bus-list',
            'bus-create',
            'bus-edit',
            'bus-delete',
            'bus-show',
            'booking-list',
            'booking-create',
            'booking-edit',
            'booking-delete',
            'booking-show',
            'dashboard-view',
            'settings-view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create roles and assign created permissions

        $role = Role::firstOrCreate(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::firstOrCreate(['name' => 'admin']);
        $role->givePermissionTo([
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'permission-list',
            'permission-create',
            'permission-edit',
            'permission-delete',
            'offer-list',
            'offer-create',
            'offer-edit',
            'offer-delete',
            'offer-show',
            'bus-list',
            'bus-create',
            'bus-edit',
            'bus-delete',
            'bus-show',
            'booking-list',
            'booking-create',
            'booking-edit',
            'booking-delete',
            'booking-show',
            'dashboard-view',
            'settings-view',
        ]);

        $role = Role::firstOrCreate(['name' => 'sale']);
        $role->givePermissionTo([
            'user-list',
            'role-list',
            'permission-list',
            'bus-list',
            'bus-show',
            'booking-list',
            'booking-show',
            'offer-list',
            'offer-show',
            'dashboard-view',
        ]);

        $role = Role::firstOrCreate(['name' => 'customer']);

        // assign roles to users
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $user->assignRole('super-admin');

        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $user->assignRole('admin');

        $user = User::firstOrCreate(
            ['email' => 'sale@example.com'],
            [
                'name' => 'Sale User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $user->assignRole('sale');
    }
}
