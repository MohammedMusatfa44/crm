<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage users',
            'manage departments',
            'manage customers',
            'manage support tickets',
            'manage notifications',
            'manage permissions',
            'view reports',
        ];
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }
        $roles = [
            'super_admin' => $permissions,
            'admin' => ['manage users', 'manage departments', 'manage customers', 'manage support tickets', 'manage notifications', 'view reports'],
            'employee' => ['manage customers', 'manage support tickets', 'view reports'],
        ];
        foreach ($roles as $role => $perms) {
            $roleObj = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role]);
            $roleObj->syncPermissions($perms);
        }
    }
}
