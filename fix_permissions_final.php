<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

echo "=== Final Permission Fix ===\n";

// Clear permission cache
app()['cache']->forget('spatie.permission.cache');

// Ensure all permissions exist
$permissions = [
    'dashboard.view', 'dashboard.add_section',
    'clients.view', 'clients.create', 'clients.edit', 'clients.delete', 'clients.update_status',
    'users.view', 'users.create_admin', 'users.create_employee', 'users.edit', 'users.delete',
    'sections.view', 'sections.create_main', 'sections.create_sub', 'sections.edit', 'sections.delete',
    'notifications.view', 'notifications.create', 'notifications.edit', 'notifications.delete',
    'support.send_ticket', 'support.view_all_tickets', 'support.view_my_team_tickets', 'support.view_own_tickets',
    'roles.view', 'roles.create', 'roles.edit', 'roles.delete', 'permissions.assign',
];

foreach ($permissions as $permission) {
    Permission::firstOrCreate(['name' => $permission]);
}

echo "All permissions created.\n";

// Fix admin role permissions
$adminRole = Role::where('name', 'admin')->first();
if ($adminRole) {
    $adminPermissions = [
        'dashboard.view', 'dashboard.add_section',
        'clients.view', 'clients.create', 'clients.edit', 'clients.delete', 'clients.update_status',
        'users.view', 'users.create_employee', 'users.edit', 'users.delete',
        'sections.view', 'sections.create_main', 'sections.create_sub', 'sections.edit', 'sections.delete',
        'notifications.view', 'notifications.create', 'notifications.edit', 'notifications.delete',
        'support.send_ticket', 'support.view_my_team_tickets',
        'roles.view',
    ];

    $adminRole->syncPermissions($adminPermissions);
    echo "Admin role permissions updated.\n";
}

// Fix super admin role permissions
$superAdminRole = Role::where('name', 'super_admin')->first();
if ($superAdminRole) {
    $superAdminRole->syncPermissions($permissions);
    echo "Super admin role permissions updated.\n";
}

// Fix employee role permissions
$employeeRole = Role::where('name', 'employee')->first();
if ($employeeRole) {
    $employeePermissions = [
        'dashboard.view',
        'clients.view', 'clients.update_status',
        'sections.view',
        'notifications.view', 'notifications.create',
        'support.send_ticket', 'support.view_own_tickets',
    ];

    $employeeRole->syncPermissions($employeePermissions);
    echo "Employee role permissions updated.\n";
}

// Ensure admin user has admin role
$adminUser = User::where('email', 'manager@crm.com')->first();
if ($adminUser) {
    $adminUser->syncRoles(['admin']);
    echo "Admin user role updated.\n";
}

// Ensure super admin user has super_admin role
$superAdminUser = User::where('email', 'admin@crm.com')->first();
if ($superAdminUser) {
    $superAdminUser->syncRoles(['super_admin']);
    echo "Super admin user role updated.\n";
}

echo "\n=== Fix Complete! ===\n";
echo "All permissions and roles have been properly set up.\n";
echo "Admin should now be able to create employees.\n";
