<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

echo "=== Checking Current Permissions ===\n";

// Get the admin role
$adminRole = Role::where('name', 'admin')->first();

if ($adminRole) {
    echo "Admin role found. Current permissions:\n";
    foreach ($adminRole->permissions as $permission) {
        echo "- {$permission->name}\n";
    }

    // Check if users.create_employee exists
    $createEmployeePermission = Permission::where('name', 'users.create_employee')->first();
    if ($createEmployeePermission) {
        echo "\nusers.create_employee permission exists.\n";

        // Add the permission to admin role
        $adminRole->givePermissionTo('users.create_employee');
        echo "Added users.create_employee permission to admin role.\n";
    } else {
        echo "\nusers.create_employee permission does not exist!\n";
    }
} else {
    echo "Admin role not found!\n";
}

echo "\n=== Check Complete ===\n";
