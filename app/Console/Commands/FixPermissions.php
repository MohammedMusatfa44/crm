<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class FixPermissions extends Command
{
    protected $signature = 'fix:permissions';
    protected $description = 'Fix permissions and assign roles to users';

    public function handle()
    {
        $this->info('Starting permission fix...');

        // Clear permission cache
        app()['cache']->forget('spatie.permission.cache');

        // Define all permissions
        $permissions = [
            // Dashboard permissions
            'dashboard.view',
            'dashboard.add_section',

            // Clients permissions
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',
            'clients.update_status',

            // Users permissions
            'users.view',
            'users.create_admin',
            'users.create_employee',
            'users.edit',
            'users.delete',

            // Sections permissions
            'sections.view',
            'sections.create_main',
            'sections.create_sub',
            'sections.edit',
            'sections.delete',

            // Notifications permissions
            'notifications.view',
            'notifications.create',
            'notifications.edit',
            'notifications.delete',

            // Support permissions
            'support.send_ticket',
            'support.view_all_tickets',
            'support.view_my_team_tickets',
            'support.view_own_tickets',

            // Roles & Permissions
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.assign',
        ];

        $this->info('Creating permissions...');

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $this->line("Created permission: {$permission}");
        }

        // Define roles and their permissions
        $superAdminPermissions = $permissions; // Super admin gets all permissions

        $adminPermissions = [
            'dashboard.view',
            'dashboard.add_section',
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',
            'clients.update_status',
            'users.view',
            'users.create_employee',
            'users.edit',
            'users.delete',
            'sections.view',
            'sections.create_main',
            'sections.create_sub',
            'sections.edit',
            'sections.delete',
            'notifications.view',
            'notifications.create',
            'notifications.edit',
            'notifications.delete',
            'support.send_ticket',
            'support.view_my_team_tickets',
            'roles.view',
        ];

        $employeePermissions = [
            'dashboard.view',
            'clients.view',
            'clients.update_status',
            'sections.view',
            'notifications.view',
            'notifications.create',
            'support.send_ticket',
            'support.view_own_tickets',
        ];

        $this->info('Creating roles...');

        // Create roles and assign permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $employee = Role::firstOrCreate(['name' => 'employee']);

        $superAdmin->syncPermissions($superAdminPermissions);
        $admin->syncPermissions($adminPermissions);
        $employee->syncPermissions($employeePermissions);

        $this->info('Roles created and permissions assigned.');

        // Assign roles to users
        $users = User::all();

        foreach ($users as $user) {
            // Remove any existing roles
            $user->syncRoles([]);

            // Assign role based on name or email
            if (str_contains(strtolower($user->name), 'مدير') ||
                str_contains(strtolower($user->email), 'admin') ||
                $user->name === 'مدير النظام') {
                $user->assignRole($superAdmin);
                $this->info("Assigned super_admin role to: {$user->name}");
            } elseif (str_contains(strtolower($user->name), 'موظف') ||
                     str_contains(strtolower($user->email), 'employee')) {
                $user->assignRole($employee);
                $this->info("Assigned employee role to: {$user->name}");
            } else {
                // Default to admin for other users
                $user->assignRole($admin);
                $this->info("Assigned admin role to: {$user->name}");
            }
        }

        $this->info('Permission fix completed!');
        $this->info('Please clear your browser cache and try logging in again.');
    }
}
