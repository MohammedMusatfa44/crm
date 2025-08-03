<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRolesToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminRole = Role::where('name', 'admin')->first();
        $employeeRole = Role::where('name', 'employee')->first();

        if (!$superAdminRole || !$adminRole || !$employeeRole) {
            $this->command->error('Roles not found. Please run PermissionSeeder first.');
            return;
        }

        $users = User::all();

        foreach ($users as $user) {
            // Remove any existing roles
            $user->syncRoles([]);

            // Assign role based on name or email
            if (str_contains(strtolower($user->name), 'مدير') ||
                str_contains(strtolower($user->email), 'admin') ||
                $user->name === 'مدير النظام') {
                $user->assignRole($superAdminRole);
                $this->command->info("Assigned super_admin role to: {$user->name}");
            } elseif (str_contains(strtolower($user->name), 'موظف') ||
                     str_contains(strtolower($user->email), 'employee')) {
                $user->assignRole($employeeRole);
                $this->command->info("Assigned employee role to: {$user->name}");
            } else {
                // Default to admin for other users
                $user->assignRole($adminRole);
                $this->command->info("Assigned admin role to: {$user->name}");
            }
        }
    }
}
