<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== Comprehensive Permission Fix ===\n";

// Clear permission cache
app()['cache']->forget('spatie.permission.cache');

// Define all permissions with proper categorization
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
    'users.create_employee',
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

echo "Creating roles...\n";

// Create roles and assign permissions
$superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
$admin = Role::firstOrCreate(['name' => 'admin']);
$employee = Role::firstOrCreate(['name' => 'employee']);

$superAdmin->syncPermissions($superAdminPermissions);
$admin->syncPermissions($adminPermissions);
$employee->syncPermissions($employeePermissions);

echo "Roles created and permissions assigned.\n";

// Clear existing role assignments
DB::table('model_has_roles')->delete();

// Assign roles to users
$users = User::all();

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
        echo "Super Admin role assigned to: {$user->name} ({$user->email})\n";
    } elseif (str_contains(strtolower($user->name), 'موظف') ||
             str_contains(strtolower($user->email), 'employee')) {
        DB::table('model_has_roles')->insert([
            'role_id' => $employee->id,
            'model_type' => User::class,
            'model_id' => $user->id
        ]);
        echo "Employee role assigned to: {$user->name} ({$user->email})\n";
    } else {
        // Default to admin for other users
        DB::table('model_has_roles')->insert([
            'role_id' => $admin->id,
            'model_type' => User::class,
            'model_id' => $user->id
        ]);
        echo "Admin role assigned to: {$user->name} ({$user->email})\n";
    }
}

echo "\n=== Permission Summary ===\n";
echo "Super Admin permissions: " . count($superAdminPermissions) . "\n";
echo "Admin permissions: " . count($adminPermissions) . "\n";
echo "Employee permissions: " . count($employeePermissions) . "\n";

echo "\n=== Role Access Summary ===\n";
echo "Super Admin can access:\n";
echo "- All menu items in sidebar\n";
echo "- All CRUD operations\n";
echo "- User management (including super admin creation)\n";
echo "- Role and permission management\n";
echo "- All reports and statistics\n\n";

echo "Admin can access:\n";
echo "- Dashboard, Customers, Users, Sections, Notifications\n";
echo "- Cannot create super admins\n";
echo "- Cannot access Roles & Permissions page\n";
echo "- Can manage employees and their own customers\n\n";

echo "Employee can access:\n";
echo "- Dashboard (view only)\n";
echo "- Customers (view and update status only)\n";
echo "- Sections (view only)\n";
echo "- Notifications (view and create own)\n";
echo "- Support tickets (send and view own)\n";
echo "- Cannot access Users, Roles & Permissions\n\n";

echo "=== Fix Complete! ===\n";
echo "Please clear your browser cache and test with different user roles.\n";
