<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

echo "🔔 Fixing Automatic Notification Triggering\n";
echo "==========================================\n\n";

try {
    // Get first user
    $user = User::first();
    if (!$user) {
        echo "❌ No users found!\n";
        exit(1);
    }

    echo "👤 User: {$user->name}\n";
    echo "⏰ Current time: " . Carbon::now()->format('Y-m-d H:i:s') . "\n\n";

    // 1. Check for overdue notifications that need to be triggered
    echo "🔍 Checking for overdue notifications...\n";
    $overdueNotifications = Notification::where('remind_at', '<=', now())
        ->where('is_triggered', false)
        ->where('is_read', false)
        ->get();

    echo "📊 Found " . $overdueNotifications->count() . " overdue notifications\n";

    // 2. Mark overdue notifications as triggered
    foreach ($overdueNotifications as $notification) {
        $notification->update(['is_triggered' => true]);
        echo "✅ Triggered: {$notification->title} (ID: {$notification->id})\n";
    }

    // 3. Create a test notification for 30 seconds from now
    echo "\n🎯 Creating test notification for 30 seconds from now...\n";

    // Delete any existing test notifications
    Notification::where('title', 'LIKE', '%اختبار%')->delete();

    $testNotification = Notification::create([
        'title' => 'اختبار تنبيه تلقائي',
        'message' => 'هذا اختبار للتنبيه التلقائي - سيظهر خلال 30 ثانية',
        'user_id' => $user->id,
        'customer_id' => null,
        'remind_at' => Carbon::now()->addSeconds(30),
        'is_read' => false,
        'is_triggered' => false,
    ]);

    echo "✅ Created test notification ID: {$testNotification->id}\n";
    echo "   Title: {$testNotification->title}\n";
    echo "   Remind time: {$testNotification->remind_at}\n";
    echo "   Will trigger automatically in 30 seconds\n\n";

    // 4. Check current triggered notifications
    $triggeredNotifications = Notification::where('user_id', $user->id)
        ->where('is_triggered', true)
        ->where('is_read', false)
        ->get();

    echo "📋 Current triggered notifications:\n";
    foreach ($triggeredNotifications as $notification) {
        echo "   - {$notification->title} (ID: {$notification->id})\n";
    }
    echo "\n";

    // 5. Instructions for testing
    echo "🎯 Testing Instructions:\n";
    echo "=======================\n";
    echo "1. Go to http://127.0.0.1:8000/notifications\n";
    echo "2. You should see the test notification in the table\n";
    echo "3. Wait 30 seconds for it to trigger automatically\n";
    echo "4. The side panel should appear with sound\n";
    echo "5. Check browser console (F12) for debugging info\n\n";

    // 6. Set up automatic checking
    echo "🔧 Setting Up Automatic Checking:\n";
    echo "================================\n";
    echo "For Windows (Task Scheduler):\n";
    echo "1. Press Win + R, type 'taskschd.msc'\n";
    echo "2. Click 'Create Basic Task'\n";
    echo "3. Name: 'CRM Notifications'\n";
    echo "4. Trigger: Daily\n";
    echo "5. Action: Start a program\n";
    echo "6. Program: php\n";
    echo "7. Arguments: artisan notifications:check\n";
    echo "8. Start in: " . getcwd() . "\n";
    echo "9. Advanced Settings: Set to repeat every 30 seconds\n\n";

    echo "For Linux/Mac (Cron):\n";
    echo "Add this to crontab (runs every 30 seconds):\n";
    echo "* * * * * cd " . getcwd() . " && php artisan notifications:check >> /dev/null 2>&1\n";
    echo "* * * * * sleep 30 && cd " . getcwd() . " && php artisan notifications:check >> /dev/null 2>&1\n\n";

    // 7. Manual testing
    echo "🧪 Manual Testing:\n";
    echo "=================\n";
    echo "To manually check for notifications:\n";
    echo "php artisan notifications:check\n\n";

    echo "To create a new test notification:\n";
    echo "php fix_notification_auto_trigger.php\n\n";

    echo "🎉 Setup completed! The notification will trigger in 30 seconds.\n";
    echo "Make sure you have the notifications page open to see the alert!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
