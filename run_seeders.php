<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Running Seeders ===\n";

// Clear permission cache
app()['cache']->forget('spatie.permission.cache');

// Run the seeders
$permissionSeeder = new \Database\Seeders\PermissionSeeder();
$permissionSeeder->run();

$userSeeder = new \Database\Seeders\UserSeeder();
$userSeeder->run();

echo "\n=== Seeders Complete! ===\n";
echo "Super Admin: admin@crm.com (password: 123456)\n";
echo "Admin: manager@crm.com (password: 123456)\n";
echo "Please test the login now.\n";
