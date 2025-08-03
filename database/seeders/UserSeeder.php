<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@crm.com'],
            [
                'name' => 'مدير النظام',
                'password' => Hash::make('123456'),
                'phone' => '0501234567',
                'is_active' => true,
            ]
        );

        // Create or update Admin
        $admin = User::firstOrCreate(
            ['email' => 'manager@crm.com'],
            [
                'name' => 'مدير',
                'password' => Hash::make('123456'),
                'phone' => '0501234568',
                'is_active' => true,
            ]
        );

        // Get roles
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();

        // Clear existing roles and assign new ones
        if ($superAdminRole) {
            $superAdmin->syncRoles([$superAdminRole->name]);
        }

        if ($adminRole) {
            $admin->syncRoles([$adminRole->name]);
        }

        echo "Users created/updated successfully:\n";
        echo "- Super Admin: admin@crm.com (password: 123456)\n";
        echo "- Admin: manager@crm.com (password: 123456)\n";
    }
}
