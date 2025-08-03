<?php

// Quick fix for permissions
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

echo "=== Quick Permission Fix ===\n";

// Clear cache
app()['cache']->forget('spatie.permission.cache');

// Create basic permissions
$permissions = [
    'dashboard.view', 'dashboard.add_section',
    'clients.view', 'clients.create', 'clients.edit', 'clients.delete', 'clients.update_status',
    'users.view', 'users.create_admin', 'users.create_employee', 'users.edit', 'users.delete',
    'sections.view', 'sections.create_main', 'sections.create_sub', 'sections.edit', 'sections.delete',
    'notifications.view', 'notifications.create', 'notifications.edit', 'notifications.delete',
    'support.send_ticket', 'support.view_all_tickets', 'support.view_my_team_tickets', 'support.view_own_tickets',
    'roles.view', 'roles.create', 'roles.edit', 'roles.delete', 'permissions.assign'
];

foreach ($permissions as $permission) {
    Permission::firstOrCreate(['name' => $permission]);
}

// Create roles
$superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
$admin = Role::firstOrCreate(['name' => 'admin']);
$employee = Role::firstOrCreate(['name' => 'employee']);

// Assign all permissions to super admin
$superAdmin->syncPermissions($permissions);

// Assign admin permissions
$admin->syncPermissions([
    'dashboard.view', 'dashboard.add_section',
    'clients.view', 'clients.create', 'clients.edit', 'clients.delete', 'clients.update_status',
    'users.view', 'users.create_employee', 'users.edit', 'users.delete',
    'sections.view', 'sections.create_main', 'sections.create_sub', 'sections.edit', 'sections.delete',
    'notifications.view', 'notifications.create', 'notifications.edit', 'notifications.delete',
    'support.send_ticket', 'support.view_my_team_tickets', 'roles.view'
]);

// Assign employee permissions
$employee->syncPermissions([
    'dashboard.view', 'clients.view', 'clients.update_status', 'sections.view',
    'notifications.view', 'notifications.create', 'support.send_ticket', 'support.view_own_tickets'
]);

// Assign roles to users
$users = User::all();
foreach ($users as $user) {
    $user->syncRoles([]);

    if (str_contains(strtolower($user->email), 'admin') || $user->name === 'مدير النظام') {
        $user->assignRole($superAdmin);
        echo "Super Admin role assigned to: {$user->name} ({$user->email})\n";
    } elseif (str_contains(strtolower($user->email), 'employee')) {
        $user->assignRole($employee);
        echo "Employee role assigned to: {$user->name} ({$user->email})\n";
    } else {
        $user->assignRole($admin);
        echo "Admin role assigned to: {$user->name} ({$user->email})\n";
    }
}

echo "\n=== Fix Complete! ===\n";
echo "Please clear browser cache and login again.\n";
