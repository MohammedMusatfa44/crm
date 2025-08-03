<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define roles and their permissions
        $superAdminPermissions = $permissions; // Super admin gets all permissions

        $adminPermissions = [
            // Dashboard - Full access
            'dashboard.view',
            'dashboard.add_section',

            // Clients - Full access
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',
            'clients.update_status',

            // Users - Can manage employees but not create super admins
            'users.view',
            'users.create_employee',  // This was missing!
            'users.edit',
            'users.delete',

            // Sections - Full access
            'sections.view',
            'sections.create_main',
            'sections.create_sub',
            'sections.edit',
            'sections.delete',

            // Notifications - Full access
            'notifications.view',
            'notifications.create',
            'notifications.edit',
            'notifications.delete',

            // Support - Can view team tickets
            'support.send_ticket',
            'support.view_my_team_tickets',

            // Roles - Can view only
            'roles.view',
        ];

        $employeePermissions = [
            // Dashboard - View only
            'dashboard.view',

            // Clients - View and update status only
            'clients.view',
            'clients.update_status',

            // Sections - View only
            'sections.view',

            // Notifications - View and create own
            'notifications.view',
            'notifications.create',

            // Support - Send tickets and view own
            'support.send_ticket',
            'support.view_own_tickets',
        ];

        // Create roles and assign permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $employee = Role::firstOrCreate(['name' => 'employee']);

        $superAdmin->syncPermissions($superAdminPermissions);
        $admin->syncPermissions($adminPermissions);
        $employee->syncPermissions($employeePermissions);
    }
}
