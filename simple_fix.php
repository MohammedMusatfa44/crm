<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== Simple Permission Fix ===\n";

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
    echo "Created: {$permission}\n";
}

// Create roles
$superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
$admin = Role::firstOrCreate(['name' => 'admin']);
$employee = Role::firstOrCreate(['name' => 'employee']);

// Assign all permissions to super admin
$superAdmin->syncPermissions($permissions);
echo "Super admin gets all permissions\n";

// Assign admin permissions
$admin->syncPermissions([
    'dashboard.view', 'dashboard.add_section',
    'clients.view', 'clients.create', 'clients.edit', 'clients.delete', 'clients.update_status',
    'users.view', 'users.create_employee', 'users.edit', 'users.delete',
    'sections.view', 'sections.create_main', 'sections.create_sub', 'sections.edit', 'sections.delete',
    'notifications.view', 'notifications.create', 'notifications.edit', 'notifications.delete',
    'support.send_ticket', 'support.view_my_team_tickets', 'roles.view'
]);
echo "Admin permissions assigned\n";

// Assign employee permissions
$employee->syncPermissions([
    'dashboard.view', 'clients.view', 'clients.update_status', 'sections.view',
    'notifications.view', 'notifications.create', 'support.send_ticket', 'support.view_own_tickets'
]);
echo "Employee permissions assigned\n";

// Clear existing role assignments
DB::table('model_has_roles')->delete();
echo "Cleared existing role assignments\n";

// Assign roles to users
$users = User::all();
foreach ($users as $user) {
    if (str_contains(strtolower($user->email), 'admin') || $user->name === 'مدير النظام') {
        DB::table('model_has_roles')->insert([
            'role_id' => $superAdmin->id,
            'model_type' => User::class,
            'model_id' => $user->id
        ]);
        echo "Super Admin role assigned to: {$user->name} ({$user->email})\n";
    } elseif (str_contains(strtolower($user->email), 'employee')) {
        DB::table('model_has_roles')->insert([
            'role_id' => $employee->id,
            'model_type' => User::class,
            'model_id' => $user->id
        ]);
        echo "Employee role assigned to: {$user->name} ({$user->email})\n";
    } else {
        DB::table('model_has_roles')->insert([
            'role_id' => $admin->id,
            'model_type' => User::class,
            'model_id' => $user->id
        ]);
        echo "Admin role assigned to: {$user->name} ({$user->email})\n";
    }
}

echo "\n=== Fix Complete! ===\n";
echo "Please clear browser cache and login again.\n";
