<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Spatie\Permission\Models\Role;

echo "Fixing users created_by field...\n";

try {
    // Check if created_by column exists
    if (!Schema::hasColumn('users', 'created_by')) {
        echo "Adding created_by column to users table...\n";
        Schema::table('users', function ($table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('id');
        });
        echo "Column added successfully!\n";
    } else {
        echo "created_by column already exists.\n";
    }

    // Get the first super admin user to set as created_by for existing users
    $superAdmin = User::whereHas('roles', function($query) {
        $query->where('name', 'super_admin');
    })->first();

    if (!$superAdmin) {
        echo "No super admin user found. Using first user.\n";
        $superAdmin = User::first();
    }

    if ($superAdmin) {
        // Update existing users that don't have created_by set
        $updatedCount = User::whereNull('created_by')->update(['created_by' => $superAdmin->id]);
        echo "Updated {$updatedCount} users with created_by = {$superAdmin->id} ({$superAdmin->name})\n";

        // Show total users count
        $totalUsers = User::count();
        echo "Total users in database: {$totalUsers}\n";

        // Show users by created_by
        $usersByCreator = User::select('created_by', DB::raw('count(*) as count'))
            ->groupBy('created_by')
            ->get();

        echo "Users by creator:\n";
        foreach ($usersByCreator as $item) {
            $creator = User::find($item->created_by);
            $creatorName = $creator ? $creator->name : 'Unknown';
            echo "- User {$creatorName} (ID: {$item->created_by}): {$item->count} users\n";
        }
    } else {
        echo "No users found in the system!\n";
    }

    echo "Users created_by field fix completed!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
