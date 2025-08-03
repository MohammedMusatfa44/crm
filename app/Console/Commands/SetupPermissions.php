<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class SetupPermissions extends Command
{
    protected $signature = 'crm:setup-permissions';
    protected $description = 'Set up permissions and roles for the CRM system';

    public function handle()
    {
        $this->info('Setting up permissions and roles...');

        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
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

            // Support Tickets permissions
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

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $this->line("Created permission: {$permission}");
        }

        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $employee = Role::firstOrCreate(['name' => 'employee']);

        $this->info('Created roles: super_admin, admin, employee');

        // Assign permissions to Super Admin
        $superAdmin->syncPermissions($permissions);
        $this->info('Assigned all permissions to super_admin');

        // Assign permissions to Admin
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
            'sections.view',
            'notifications.view',
            'notifications.create',
            'notifications.edit',
            'notifications.delete',
            'support.send_ticket',
            'support.view_my_team_tickets',
        ];
        $admin->syncPermissions($adminPermissions);
        $this->info('Assigned admin permissions');

        // Assign permissions to Employee
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
        $employee->syncPermissions($employeePermissions);
        $this->info('Assigned employee permissions');

        // Assign roles to existing users
        $users = User::all();
        foreach ($users as $user) {
            $user->syncRoles([]);

            if (str_contains(strtolower($user->name), 'مدير') ||
                str_contains(strtolower($user->email), 'admin') ||
                $user->name === 'مدير النظام') {
                $user->assignRole($superAdmin);
                $this->line("Assigned super_admin role to: {$user->name}");
            } elseif (str_contains(strtolower($user->name), 'موظف') ||
                     str_contains(strtolower($user->email), 'employee')) {
                $user->assignRole($employee);
                $this->line("Assigned employee role to: {$user->name}");
            } else {
                $user->assignRole($admin);
                $this->line("Assigned admin role to: {$user->name}");
            }
        }

        $this->info('Permissions and roles setup completed successfully!');
    }
}
