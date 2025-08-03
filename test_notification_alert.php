<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

echo "🔔 Testing Notification Alert System\n";
echo "===================================\n\n";

try {
    $testUser = User::first();
    if ($testUser) {
        echo "Current user: {$testUser->name}\n";
        echo "Current time: " . Carbon::now()->format('Y-m-d H:i:s') . "\n\n";

        // Check for notifications that should be triggered
        echo "Checking for notifications that should be triggered...\n";
        $notifications = Notification::where('remind_at', '<=', now())
            ->where('is_triggered', false)
            ->where('is_read', false)
            ->with(['user', 'customer'])
            ->get();

        if ($notifications->count() > 0) {
            echo "Found {$notifications->count()} notifications that should be triggered:\n\n";

            foreach ($notifications as $notification) {
                echo "🔔 Notification ID: {$notification->id}\n";
                echo "   Title: {$notification->title}\n";
                echo "   Message: {$notification->message}\n";
                echo "   Remind at: {$notification->remind_at}\n";
                echo "   User: {$notification->user->name}\n";
                echo "   Status: Not triggered yet\n\n";

                // Mark as triggered
                $notification->update(['is_triggered' => true]);
                echo "   ✅ Marked as triggered!\n\n";
            }

            echo "🎯 These notifications should now appear in the frontend!\n";
            echo "📋 To see the alerts:\n";
            echo "1. Go to /notifications page in your browser\n";
            echo "2. Keep the page open\n";
            echo "3. The alerts should appear automatically\n";
            echo "4. You should hear sound and see popup alerts\n\n";

        } else {
            echo "No notifications found that need to be triggered.\n\n";
        }

        // Show all notifications for debugging
        echo "All notifications in database:\n";
        $allNotifications = Notification::with('user')->get();
        foreach ($allNotifications as $notif) {
            $status = $notif->is_triggered ? 'مفعل' : 'غير مفعل';
            $read = $notif->is_read ? 'مقروء' : 'غير مقروء';
            $remindTime = $notif->remind_at ? $notif->remind_at->format('Y-m-d H:i:s') : 'N/A';
            echo "- ID: {$notif->id} | {$notif->title} | {$notif->user->name} | {$remindTime} | {$status} | {$read}\n";
        }

        // Create a test notification for immediate testing
        echo "\nCreating a test notification for immediate testing...\n";
        $testNotification = Notification::create([
            'title' => 'اختبار فوري للتنبيه',
            'message' => 'هذا اختبار فوري للتنبيه - يجب أن يظهر الآن',
            'user_id' => $testUser->id,
            'customer_id' => null,
            'remind_at' => Carbon::now()->subMinute(), // 1 minute ago
            'is_read' => false,
            'is_triggered' => false,
        ]);

        echo "✅ Test notification created with ID: {$testNotification->id}\n";
        echo "   Remind time: {$testNotification->remind_at}\n";
        echo "   Should be triggered immediately!\n\n";

        // Trigger it immediately
        $testNotification->update(['is_triggered' => true]);
        echo "✅ Test notification marked as triggered!\n\n";

        echo "🎯 Next steps:\n";
        echo "1. Go to /notifications page\n";
        echo "2. You should see the test notification\n";
        echo "3. The alert should appear automatically\n";
        echo "4. Check browser console for any JavaScript errors\n\n";

        echo "🔧 To set up automatic checking:\n";
        echo "Add this to your crontab (runs every minute):\n";
        echo "* * * * * cd " . getcwd() . " && php artisan notifications:check >> /dev/null 2>&1\n\n";

        echo "🧪 Manual testing:\n";
        echo "Run this command to manually check for notifications:\n";
        echo "php artisan notifications:check\n";

    } else {
        echo "❌ No users found in the system!\n";
    }

    echo "\n🎉 Notification alert test completed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
