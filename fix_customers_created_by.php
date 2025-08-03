<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\User;

echo "Fixing customers created_by field...\n";

try {
    // Check if created_by column exists
    if (!Schema::hasColumn('customers', 'created_by')) {
        echo "Adding created_by column to customers table...\n";
        Schema::table('customers', function ($table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('id');
        });
        echo "Column added successfully!\n";
    } else {
        echo "created_by column already exists.\n";
    }

    // Get the first admin user to set as created_by for existing customers
    $adminUser = User::whereHas('roles', function($query) {
        $query->where('name', 'admin');
    })->first();

    if (!$adminUser) {
        echo "No admin user found. Using first super_admin user.\n";
        $adminUser = User::whereHas('roles', function($query) {
            $query->where('name', 'super_admin');
        })->first();
    }

    if (!$adminUser) {
        echo "No admin or super_admin user found. Using first user.\n";
        $adminUser = User::first();
    }

    if ($adminUser) {
        // Update existing customers that don't have created_by set
        $updatedCount = Customer::whereNull('created_by')->update(['created_by' => $adminUser->id]);
        echo "Updated {$updatedCount} customers with created_by = {$adminUser->id} ({$adminUser->name})\n";
    } else {
        echo "No users found in the system!\n";
    }

    echo "Customers created_by field fix completed!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
