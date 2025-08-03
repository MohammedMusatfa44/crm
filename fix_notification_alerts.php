<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "🔔 Fixing Notification Alert System\n";
echo "===================================\n\n";

try {
    // 1. Check current time and timezone
    echo "📅 Current System Time: " . Carbon::now()->format('Y-m-d H:i:s') . "\n";
    echo "🌍 Timezone: " . config('app.timezone') . "\n\n";

    // 2. Find a test user
    $testUser = User::first();
    if (!$testUser) {
        echo "❌ No users found in the system!\n";
        exit(1);
    }
    echo "👤 Test User: {$testUser->name} (ID: {$testUser->id})\n\n";

    // 3. Check for overdue notifications
    echo "🔍 Checking for overdue notifications...\n";
    $overdueNotifications = Notification::where('remind_at', '<=', now())
        ->where('is_triggered', false)
        ->where('is_read', false)
        ->with(['user', 'customer'])
        ->get();

    echo "📊 Found " . $overdueNotifications->count() . " overdue notifications\n";

    // 4. Mark overdue notifications as triggered
    $triggeredCount = 0;
    foreach ($overdueNotifications as $notification) {
        $notification->update(['is_triggered' => true]);
        echo "✅ Triggered: {$notification->title} for {$notification->user->name}\n";
        $triggeredCount++;
    }

    // 5. Create a test notification for immediate testing
    echo "\n🎯 Creating test notification for immediate testing...\n";

    // Delete any existing test notifications
    Notification::where('title', 'LIKE', '%اختبار%')->delete();

    $testNotification = Notification::create([
        'title' => 'اختبار فوري للتنبيه',
        'message' => 'هذا اختبار فوري للتنبيه - يجب أن يظهر الآن في اللوحة الجانبية',
        'user_id' => $testUser->id,
        'customer_id' => null,
        'remind_at' => Carbon::now()->subMinute(), // 1 minute ago
        'is_read' => false,
        'is_triggered' => false,
    ]);

    echo "✅ Test notification created with ID: {$testNotification->id}\n";
    echo "   Title: {$testNotification->title}\n";
    echo "   Remind time: {$testNotification->remind_at}\n";
    echo "   Should be triggered immediately!\n\n";

    // 6. Mark test notification as triggered
    $testNotification->update(['is_triggered' => true]);
    echo "✅ Test notification marked as triggered!\n\n";

    // 7. Create another test notification for 2 minutes from now
    $futureNotification = Notification::create([
        'title' => 'اختبار تنبيه مستقبلي',
        'message' => 'هذا اختبار للتنبيه المستقبلي - سيظهر خلال دقيقتين',
        'user_id' => $testUser->id,
        'customer_id' => null,
        'remind_at' => Carbon::now()->addMinutes(2),
        'is_read' => false,
        'is_triggered' => false,
    ]);

    echo "⏰ Future test notification created for: {$futureNotification->remind_at}\n\n";

    // 8. Check all triggered notifications for the user
    $triggeredNotifications = Notification::where('user_id', $testUser->id)
        ->where('is_triggered', true)
        ->where('is_read', false)
        ->get();

    echo "📋 Current triggered notifications for {$testUser->name}:\n";
    foreach ($triggeredNotifications as $notification) {
        echo "   - {$notification->title} (ID: {$notification->id})\n";
    }
    echo "\n";

    // 9. Instructions for testing
    echo "🎯 Next Steps to Test:\n";
    echo "1. Go to http://127.0.0.1:8000/notifications\n";
    echo "2. You should see the test notification in the table\n";
    echo "3. The side panel should appear automatically\n";
    echo "4. Check browser console for any JavaScript errors\n";
    echo "5. Test the sound control button in the top-right corner\n\n";

    echo "🔧 To set up automatic checking:\n";
    echo "For Windows (Task Scheduler):\n";
    echo "1. Open Task Scheduler\n";
    echo "2. Create Basic Task\n";
    echo "3. Name: 'CRM Notifications'\n";
    echo "4. Trigger: Daily, every 1 minute\n";
    echo "5. Action: Start a program\n";
    echo "6. Program: php\n";
    echo "7. Arguments: " . getcwd() . "\\artisan notifications:check\n";
    echo "8. Start in: " . getcwd() . "\n\n";

    echo "For Linux/Mac (Cron):\n";
    echo "Add this to crontab (runs every minute):\n";
    echo "* * * * * cd " . getcwd() . " && php artisan notifications:check >> /dev/null 2>&1\n\n";

    echo "🧪 Manual testing:\n";
    echo "Run this command to manually check for notifications:\n";
    echo "php artisan notifications:check\n\n";

    echo "🎉 Notification alert system setup completed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
