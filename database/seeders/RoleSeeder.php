<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // categories
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',

            // products
            'products.view',
            'products.create',
            'products.update',
            'products.delete',
            'products.restore',
            'products.force-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);

        $user = Role::firstOrCreate(['name' => 'user']);
        $user->syncPermissions([
            'categories.view',
            'products.view',
        ]);

        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        )->assignRole($admin);
    }
}
