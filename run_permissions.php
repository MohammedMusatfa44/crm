<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== Running Permission Seeder ===\n";

// Clear permission cache
app()['cache']->forget('spatie.permission.cache');

// Run the permission seeder
$seeder = new \Database\Seeders\PermissionSeeder();
$seeder->run();

echo "Permission seeder completed.\n";

// Clear existing role assignments
DB::table('model_has_roles')->delete();

// Assign roles to users
$users = User::all();
$superAdmin = Role::where('name', 'super_admin')->first();
$admin = Role::where('name', 'admin')->first();
$employee = Role::where('name', 'employee')->first();

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
echo "Super Admin permissions: " . $superAdmin->permissions->count() . "\n";
echo "Admin permissions: " . $admin->permissions->count() . "\n";
echo "Employee permissions: " . $employee->permissions->count() . "\n";

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

echo "=== Setup Complete! ===\n";
echo "Please clear your browser cache and test with different user roles.\n";
