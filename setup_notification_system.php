<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "🔔 Setting Up Complete Notification System\n";
echo "==========================================\n\n";

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

    // 3. Clear any existing test notifications
    echo "🧹 Clearing existing test notifications...\n";
    Notification::where('title', 'LIKE', '%اختبار%')->delete();
    echo "✅ Cleared test notifications\n\n";

    // 4. Create immediate test notification
    echo "🎯 Creating immediate test notification...\n";
    $immediateNotification = Notification::create([
        'title' => 'اختبار فوري للتنبيه',
        'message' => 'هذا اختبار فوري للتنبيه - يجب أن يظهر الآن في اللوحة الجانبية مع صوت',
        'user_id' => $testUser->id,
        'customer_id' => null,
        'remind_at' => Carbon::now()->subMinute(),
        'is_read' => false,
        'is_triggered' => false,
    ]);

    echo "✅ Created immediate notification ID: {$immediateNotification->id}\n";
    echo "   Title: {$immediateNotification->title}\n";
    echo "   Remind time: {$immediateNotification->remind_at}\n\n";

    // 5. Mark immediate notification as triggered
    $immediateNotification->update(['is_triggered' => true]);
    echo "✅ Marked immediate notification as triggered\n\n";

    // 6. Create notification for 30 seconds from now
    echo "⏰ Creating future test notification (30 seconds)...\n";
    $futureNotification = Notification::create([
        'title' => 'اختبار تنبيه مستقبلي',
        'message' => 'هذا اختبار للتنبيه المستقبلي - سيظهر خلال 30 ثانية',
        'user_id' => $testUser->id,
        'customer_id' => null,
        'remind_at' => Carbon::now()->addSeconds(30),
        'is_read' => false,
        'is_triggered' => false,
    ]);

    echo "✅ Created future notification ID: {$futureNotification->id}\n";
    echo "   Title: {$futureNotification->title}\n";
    echo "   Remind time: {$futureNotification->remind_at}\n\n";

    // 7. Create notification for 1 minute from now
    echo "⏰ Creating 1-minute test notification...\n";
    $oneMinuteNotification = Notification::create([
        'title' => 'اختبار تنبيه دقيقة واحدة',
        'message' => 'هذا اختبار للتنبيه بعد دقيقة واحدة',
        'user_id' => $testUser->id,
        'customer_id' => null,
        'remind_at' => Carbon::now()->addMinute(),
        'is_read' => false,
        'is_triggered' => false,
    ]);

    echo "✅ Created 1-minute notification ID: {$oneMinuteNotification->id}\n";
    echo "   Title: {$oneMinuteNotification->title}\n";
    echo "   Remind time: {$oneMinuteNotification->remind_at}\n\n";

    // 8. Check current triggered notifications
    $triggeredNotifications = Notification::where('user_id', $testUser->id)
        ->where('is_triggered', true)
        ->where('is_read', false)
        ->get();

    echo "📊 Current triggered notifications for {$testUser->name}:\n";
    foreach ($triggeredNotifications as $notification) {
        echo "   - {$notification->title} (ID: {$notification->id})\n";
    }
    echo "\n";

    // 9. Test the triggered notifications API
    echo "🔍 Testing triggered notifications API...\n";

    // Simulate the API call
    $apiNotifications = Notification::where('user_id', $testUser->id)
        ->where('is_triggered', true)
        ->where('is_read', false)
        ->with(['customer'])
        ->latest()
        ->take(5)
        ->get();

    echo "   API returned " . $apiNotifications->count() . " notifications\n";
    foreach ($apiNotifications as $notification) {
        echo "   - {$notification->title} (ID: {$notification->id})\n";
    }
    echo "\n";

    // 10. Instructions for testing
    echo "🎯 Testing Instructions:\n";
    echo "=======================\n";
    echo "1. Go to http://127.0.0.1:8000/notifications\n";
    echo "2. You should see 3 test notifications in the table\n";
    echo "3. The side panel should appear automatically with the immediate notification\n";
    echo "4. Check browser console (F12) for any JavaScript errors\n";
    echo "5. Test the sound control button in the top-right corner\n";
    echo "6. Wait 30 seconds for the second notification to trigger\n";
    echo "7. Wait 1 minute for the third notification to trigger\n\n";

    // 11. Set up automatic checking
    echo "🔧 Setting Up Automatic Notification Checking:\n";
    echo "=============================================\n";
    echo "For Windows (Task Scheduler):\n";
    echo "1. Press Win + R, type 'taskschd.msc'\n";
    echo "2. Click 'Create Basic Task'\n";
    echo "3. Name: 'CRM Notifications'\n";
    echo "4. Trigger: Daily\n";
    echo "5. Action: Start a program\n";
    echo "6. Program: php\n";
    echo "7. Arguments: artisan notifications:check\n";
    echo "8. Start in: " . getcwd() . "\n";
    echo "9. Advanced Settings: Set to repeat every 1 minute\n\n";

    echo "For Linux/Mac (Cron):\n";
    echo "Add this to crontab (runs every minute):\n";
    echo "* * * * * cd " . getcwd() . " && php artisan notifications:check >> /dev/null 2>&1\n\n";

    // 12. Manual testing commands
    echo "🧪 Manual Testing Commands:\n";
    echo "==========================\n";
    echo "To manually check for notifications:\n";
    echo "php artisan notifications:check\n\n";

    echo "To create a new test notification:\n";
    echo "php test_notification_immediate.php\n\n";

    // 13. Expected behavior
    echo "🎯 Expected Behavior:\n";
    echo "===================\n";
    echo "✅ Countdown timers should update every second with seconds included\n";
    echo "✅ Side panel should slide in from the right when notifications trigger\n";
    echo "✅ Sound should play (if enabled) when notifications appear\n";
    echo "✅ Desktop notifications should appear (if browser permission granted)\n";
    echo "✅ Notifications should be marked as read when dismissed\n";
    echo "✅ Sound control button should toggle sound on/off\n";
    echo "✅ Polling should check for new notifications every 5 seconds\n\n";

    echo "🎉 Notification system setup completed!\n";
    echo "Go to http://127.0.0.1:8000/notifications to test now!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
