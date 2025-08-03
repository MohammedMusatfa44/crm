<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use App\Models\Customer;
use Spatie\Permission\Models\Role;

echo "Testing Notifications System...\n\n";

try {
    // Check if notifications table exists and has data
    $notificationCount = Notification::count();
    echo "Total notifications in database: {$notificationCount}\n";

    // Get users with their roles
    $users = User::with('roles')->get();
    echo "\nUsers and their roles:\n";
    foreach ($users as $user) {
        $roles = $user->roles->pluck('name')->implode(', ');
        echo "- {$user->name} ({$user->email}): {$roles}\n";
    }

    // Get customers count
    $customerCount = Customer::count();
    echo "\nTotal customers in database: {$customerCount}\n";

    // Test creating a notification
    $testUser = User::first();
    if ($testUser) {
        echo "\nCreating test notification for user: {$testUser->name}\n";

        $notification = Notification::create([
            'title' => 'تنبيه تجريبي',
            'message' => 'هذا تنبيه تجريبي لاختبار النظام',
            'user_id' => $testUser->id,
            'customer_id' => null,
            'remind_at' => now()->addHours(2),
            'is_read' => false,
            'is_triggered' => false,
        ]);

        echo "Test notification created with ID: {$notification->id}\n";

        // Show notifications by user
        echo "\nNotifications by user:\n";
        foreach ($users as $user) {
            $userNotifications = Notification::where('user_id', $user->id)->count();
            echo "- {$user->name}: {$userNotifications} notifications\n";
        }

        // Clean up test notification
        $notification->delete();
        echo "\nTest notification cleaned up.\n";
    }

    echo "\nNotifications system test completed successfully!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
