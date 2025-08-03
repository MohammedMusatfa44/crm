<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "Fixing imported customers created_by field...\n";

try {
    // Get the current logged-in user (or first admin)
    $currentUser = auth()->user();

    if (!$currentUser) {
        // If no user is logged in, get the first admin
        $currentUser = User::whereHas('roles', function($query) {
            $query->where('name', 'admin');
        })->first();

        if (!$currentUser) {
            $currentUser = User::whereHas('roles', function($query) {
                $query->where('name', 'super_admin');
            })->first();
        }

        if (!$currentUser) {
            $currentUser = User::first();
        }
    }

    if ($currentUser) {
        // Update customers that don't have created_by set
        $updatedCount = Customer::whereNull('created_by')->update(['created_by' => $currentUser->id]);
        echo "Updated {$updatedCount} customers with created_by = {$currentUser->id} ({$currentUser->name})\n";

        // Show total customers count
        $totalCustomers = Customer::count();
        echo "Total customers in database: {$totalCustomers}\n";

        // Show customers by created_by
        $customersByUser = Customer::select('created_by', DB::raw('count(*) as count'))
            ->groupBy('created_by')
            ->get();

        echo "Customers by user:\n";
        foreach ($customersByUser as $item) {
            $user = User::find($item->created_by);
            $userName = $user ? $user->name : 'Unknown';
            echo "- User {$userName} (ID: {$item->created_by}): {$item->count} customers\n";
        }
    } else {
        echo "No users found in the system!\n";
    }

    echo "Fix completed!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
