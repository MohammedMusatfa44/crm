<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

echo "Setting up Notification Alert System...\n\n";

try {
    // Test the notification checking command
    echo "Testing notification checking command...\n";

    // Create a test notification that should be triggered
    $testUser = User::first();
    if ($testUser) {
        echo "Creating test notification for user: {$testUser->name}\n";

        // Create a notification that should trigger in 1 minute
        $notification = Notification::create([
            'title' => 'تنبيه تجريبي للاختبار',
            'message' => 'هذا تنبيه تجريبي لاختبار نظام التنبيهات الصوتية',
            'user_id' => $testUser->id,
            'customer_id' => null,
            'remind_at' => now()->addMinute(), // Trigger in 1 minute
            'is_read' => false,
            'is_triggered' => false,
        ]);

        echo "Test notification created with ID: {$notification->id}\n";
        echo "Will trigger at: " . $notification->remind_at->format('Y-m-d H:i:s') . "\n";

        // Show current notifications
        echo "\nCurrent notifications:\n";
        $notifications = Notification::with('user')->get();
        foreach ($notifications as $notif) {
            $status = $notif->is_triggered ? 'مفعل' : 'غير مفعل';
            $read = $notif->is_read ? 'مقروء' : 'غير مقروء';
            echo "- ID: {$notif->id} | {$notif->title} | {$notif->user->name} | {$status} | {$read}\n";
        }

        echo "\nTo test the alert system:\n";
        echo "1. Keep the notifications page open in your browser\n";
        echo "2. Wait for the notification to trigger (in about 1 minute)\n";
        echo "3. You should hear a sound and see a popup alert\n";
        echo "4. The notification will also appear as a desktop notification\n";

        echo "\nTo manually trigger the check, run:\n";
        echo "php artisan notifications:check\n";

        echo "\nTo set up automatic checking, add this to your crontab:\n";
        echo "* * * * * cd " . getcwd() . " && php artisan notifications:check >> /dev/null 2>&1\n";

    } else {
        echo "No users found in the system!\n";
    }

    echo "\nNotification alert system setup completed!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
