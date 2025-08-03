<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "=== Testing System Setup ===\n";

// Check users
$superAdmin = User::where('email', 'admin@crm.com')->first();
$admin = User::where('email', 'manager@crm.com')->first();

echo "Users:\n";
if ($superAdmin) {
    echo "- Super Admin: {$superAdmin->name} ({$superAdmin->email})\n";
    echo "  Roles: " . $superAdmin->roles->pluck('name')->implode(', ') . "\n";
} else {
    echo "- Super Admin: NOT FOUND\n";
}

if ($admin) {
    echo "- Admin: {$admin->name} ({$admin->email})\n";
    echo "  Roles: " . $admin->roles->pluck('name')->implode(', ') . "\n";
} else {
    echo "- Admin: NOT FOUND\n";
}

// Check roles
$roles = Role::all();
echo "\nRoles:\n";
foreach ($roles as $role) {
    echo "- {$role->name}: " . $role->permissions->count() . " permissions\n";
}

// Check permissions
$permissions = Permission::all();
echo "\nTotal Permissions: " . $permissions->count() . "\n";

echo "\n=== Test Complete ===\n";
echo "You can now try logging in:\n";
echo "- Super Admin: admin@crm.com (password: 123456)\n";
echo "- Admin: manager@crm.com (password: 123456)\n";
