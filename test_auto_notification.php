<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

echo "🔔 Testing Automatic Notification System\n";
echo "=======================================\n\n";

try {
    // Get first user
    $user = User::first();
    if (!$user) {
        echo "❌ No users found!\n";
        exit(1);
    }

    echo "👤 User: {$user->name}\n";
    echo "⏰ Current time: " . Carbon::now()->format('Y-m-d H:i:s') . "\n\n";

    // Clear existing test notifications
    Notification::where('title', 'LIKE', '%اختبار%')->delete();

    // Create a test notification for 30 seconds from now
    $testNotification = Notification::create([
        'title' => 'اختبار تنبيه تلقائي',
        'message' => 'هذا اختبار للتنبيه التلقائي - سيظهر تلقائياً خلال 30 ثانية',
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

    echo "🎯 Testing Instructions:\n";
    echo "=======================\n";
    echo "1. Go to http://127.0.0.1:8000/notifications\n";
    echo "2. You should see the test notification in the table\n";
    echo "3. The countdown timer should show 30 seconds and count down\n";
    echo "4. When it reaches 0, the notification should trigger automatically\n";
    echo "5. The side panel should appear with sound\n";
    echo "6. No manual commands needed - everything is automatic!\n\n";

    echo "🔧 How it works:\n";
    echo "===============\n";
    echo "✅ JavaScript checks countdown every second\n";
    echo "✅ When countdown reaches 0, automatically triggers notification\n";
    echo "✅ Side panel appears with sound\n";
    echo "✅ No server commands or terminal needed\n";
    echo "✅ Works completely in real-time\n\n";

    echo "🎉 Test completed! The notification will trigger automatically in 30 seconds.\n";
    echo "Just keep the notifications page open and watch the magic happen! 🪄\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
