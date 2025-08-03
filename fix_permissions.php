<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "Starting permission fix...\n";

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

echo "Creating permissions...\n";

// Create permissions
foreach ($permissions as $permission) {
    Permission::firstOrCreate(['name' => $permission]);
    echo "Created permission: {$permission}\n";
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

echo "Creating roles...\n";

// Create roles and assign permissions
$superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
$admin = Role::firstOrCreate(['name' => 'admin']);
$employee = Role::firstOrCreate(['name' => 'employee']);

$superAdmin->syncPermissions($superAdminPermissions);
$admin->syncPermissions($adminPermissions);
$employee->syncPermissions($employeePermissions);

echo "Roles created and permissions assigned.\n";

// Assign roles to users using direct database operations
$users = User::all();

// Clear existing role assignments
DB::table('model_has_roles')->delete();

foreach ($users as $user) {
    // Assign role based on name or email
    if (str_contains(strtolower($user->name), 'مدير') ||
        str_contains(strtolower($user->email), 'admin') ||
        $user->name === 'مدير النظام') {
        DB::table('model_has_roles')->insert([
            'role_id' => $superAdmin->id,
            'model_type' => User::class,
            'model_id' => $user->id
        ]);
        echo "Assigned super_admin role to: {$user->name}\n";
    } elseif (str_contains(strtolower($user->name), 'موظف') ||
             str_contains(strtolower($user->email), 'employee')) {
        DB::table('model_has_roles')->insert([
            'role_id' => $employee->id,
            'model_type' => User::class,
            'model_id' => $user->id
        ]);
        echo "Assigned employee role to: {$user->name}\n";
    } else {
        // Default to admin for other users
        DB::table('model_has_roles')->insert([
            'role_id' => $admin->id,
            'model_type' => User::class,
            'model_id' => $user->id
        ]);
        echo "Assigned admin role to: {$user->name}\n";
    }
}

echo "Permission fix completed!\n";
echo "Please clear your browser cache and try logging in again.\n";
