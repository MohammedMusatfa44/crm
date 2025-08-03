<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== CRM System Setup ===\n";

// Clear all caches
app()['cache']->flush();
echo "Cache cleared.\n";

// Clear permission cache
app()['cache']->forget('spatie.permission.cache');
echo "Permission cache cleared.\n";

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

// Clear existing users and role assignments
DB::table('model_has_roles')->delete();

// Clear related tables first
DB::table('customer_comments')->delete();
DB::table('user_sessions')->delete();
DB::table('support_tickets')->delete();
DB::table('notifications')->delete();

// Now delete users
User::query()->delete();

echo "Cleared existing users and role assignments.\n";

// Create Super Admin
$superAdminUser = User::create([
    'name' => 'مدير النظام',
    'email' => 'admin@crm.com',
    'password' => Hash::make('123456'),
    'phone' => '0501234567',
    'is_active' => true,
]);

// Create Admin
$adminUser = User::create([
    'name' => 'مدير',
    'email' => 'manager@crm.com',
    'password' => Hash::make('123456'),
    'phone' => '0501234568',
    'is_active' => true,
]);

// Assign roles
$superAdminUser->assignRole($superAdmin);
$adminUser->assignRole($admin);

echo "\n=== Users Created ===\n";
echo "Super Admin: admin@crm.com (password: 123456)\n";
echo "Admin: manager@crm.com (password: 123456)\n";

echo "\n=== Permission Summary ===\n";
echo "Super Admin permissions: " . count($superAdminPermissions) . "\n";
echo "Admin permissions: " . count($adminPermissions) . "\n";
echo "Employee permissions: " . count($employeePermissions) . "\n";

echo "\n=== Setup Complete! ===\n";
echo "You can now:\n";
echo "1. Login as Super Admin (admin@crm.com) to manage everything\n";
echo "2. Login as Admin (manager@crm.com) to create employees and manage customers\n";
echo "3. Create employees through the admin interface\n";
echo "4. Assign customers to employees\n";
echo "5. Test the hierarchical access control\n";
