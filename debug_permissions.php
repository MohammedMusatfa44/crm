<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

echo "=== Debugging Permissions ===\n";

// Check if permissions exist
$createEmployeePermission = Permission::where('name', 'users.create_employee')->first();
echo "users.create_employee permission exists: " . ($createEmployeePermission ? 'YES' : 'NO') . "\n";

// Check admin role
$adminRole = Role::where('name', 'admin')->first();
if ($adminRole) {
    echo "Admin role exists with " . $adminRole->permissions->count() . " permissions\n";

    // Check if admin role has the permission
    $hasPermission = $adminRole->hasPermissionTo('users.create_employee');
    echo "Admin role has users.create_employee: " . ($hasPermission ? 'YES' : 'NO') . "\n";

    echo "Admin role permissions:\n";
    foreach ($adminRole->permissions as $permission) {
        echo "- {$permission->name}\n";
    }
} else {
    echo "Admin role not found!\n";
}

// Check admin user
$adminUser = User::where('email', 'manager@crm.com')->first();
if ($adminUser) {
    echo "\nAdmin user exists: {$adminUser->name}\n";

    // Check user roles
    echo "User roles: " . $adminUser->roles->pluck('name')->implode(', ') . "\n";

    // Check if user has the permission through role
    $hasPermission = $adminUser->hasPermissionTo('users.create_employee');
    echo "User has users.create_employee: " . ($hasPermission ? 'YES' : 'NO') . "\n";

    // Check if user can do the action
    $canCreate = $adminUser->can('users.create_employee');
    echo "User can create employee: " . ($canCreate ? 'YES' : 'NO') . "\n";

    // List all user permissions
    echo "User permissions:\n";
    foreach ($adminUser->getAllPermissions() as $permission) {
        echo "- {$permission->name}\n";
    }
} else {
    echo "Admin user not found!\n";
}

echo "\n=== Debug Complete ===\n";
