<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

echo "=== Fixing Admin Permissions ===\n";

// Clear permission cache
app()['cache']->forget('spatie.permission.cache');

// Get the admin role
$adminRole = Role::where('name', 'admin')->first();

if (!$adminRole) {
    echo "Admin role not found!\n";
    exit;
}

// Define admin permissions (including users.create_employee)
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

// Sync permissions to admin role
$adminRole->syncPermissions($adminPermissions);

echo "Admin permissions updated successfully!\n";
echo "Admin now has " . $adminRole->permissions->count() . " permissions\n";

// List admin permissions
echo "\nAdmin permissions:\n";
foreach ($adminRole->permissions as $permission) {
    echo "- {$permission->name}\n";
}

echo "\n=== Fix Complete! ===\n";
echo "Admin can now create employees. Please test again.\n";
