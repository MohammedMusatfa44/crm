<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

echo "🔔 Testing Notification System\n";
echo "=============================\n\n";

try {
    // Get first user
    $user = User::first();
    if (!$user) {
        echo "❌ No users found!\n";
        exit(1);
    }

    echo "👤 User: {$user->name}\n";
    echo "⏰ Current time: " . Carbon::now()->format('Y-m-d H:i:s') . "\n\n";

    // Delete any existing test notifications
    Notification::where('title', 'LIKE', '%اختبار%')->delete();

    // Create immediate test notification (1 minute ago)
    $notification = Notification::create([
        'title' => 'اختبار فوري للتنبيه',
        'message' => 'هذا اختبار فوري للتنبيه - يجب أن يظهر الآن في اللوحة الجانبية مع صوت',
        'user_id' => $user->id,
        'customer_id' => null,
        'remind_at' => Carbon::now()->subMinute(),
        'is_read' => false,
        'is_triggered' => false,
    ]);

    echo "✅ Created notification ID: {$notification->id}\n";
    echo "   Title: {$notification->title}\n";
    echo "   Remind time: {$notification->remind_at}\n";
    echo "   Should be triggered immediately!\n\n";

    // Mark as triggered
    $notification->update(['is_triggered' => true]);
    echo "✅ Marked as triggered\n\n";

    // Create another notification for 30 seconds from now
    $futureNotification = Notification::create([
        'title' => 'اختبار تنبيه مستقبلي',
        'message' => 'هذا اختبار للتنبيه المستقبلي - سيظهر خلال 30 ثانية',
        'user_id' => $user->id,
        'customer_id' => null,
        'remind_at' => Carbon::now()->addSeconds(30),
        'is_read' => false,
        'is_triggered' => false,
    ]);

    echo "⏰ Future notification created for: {$futureNotification->remind_at}\n";
    echo "   This will trigger automatically in 30 seconds\n\n";

    // Check triggered notifications
    $triggered = Notification::where('user_id', $user->id)
        ->where('is_triggered', true)
        ->where('is_read', false)
        ->count();

    echo "📊 Triggered notifications count: {$triggered}\n\n";

    echo "🎯 Next Steps:\n";
    echo "1. Go to http://127.0.0.1:8000/notifications\n";
    echo "2. You should see the test notification in the table\n";
    echo "3. The side panel should appear automatically with sound\n";
    echo "4. Check browser console for any JavaScript errors\n";
    echo "5. Test the sound control button in the top-right corner\n";
    echo "6. Wait 30 seconds for the second notification to trigger\n\n";

    echo "🔧 To set up automatic checking:\n";
    echo "Run this command every minute:\n";
    echo "php artisan notifications:check\n\n";

    echo "🎉 Test completed! Check your notifications page now.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
