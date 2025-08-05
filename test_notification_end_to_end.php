<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

echo "🧪 End-to-End Notification System Test\n";
echo "=====================================\n\n";

try {
    // Get first user and authenticate
    $user = User::first();
    if (!$user) {
        echo "❌ No users found!\n";
        exit(1);
    }

    // Authenticate the user
    Auth::login($user);

    echo "👤 User: {$user->name} (ID: {$user->id})\n";
    echo "🔐 Authenticated: " . (Auth::check() ? 'Yes' : 'No') . "\n";
    echo "⏰ Current time: " . Carbon::now()->format('Y-m-d H:i:s') . "\n\n";

    // Clear existing test notifications
    Notification::where('title', 'LIKE', '%اختبار%')->delete();

    // Create a test notification for 15 seconds from now
    $testNotification = Notification::create([
        'title' => 'اختبار تنبيه شامل',
        'message' => 'هذا اختبار شامل للتنبيه التلقائي - سيظهر تلقائياً خلال 15 ثانية',
        'user_id' => $user->id,
        'customer_id' => null,
        'remind_at' => Carbon::now()->addSeconds(15),
        'is_read' => false,
        'is_triggered' => false,
    ]);

    echo "✅ Created test notification ID: {$testNotification->id}\n";
    echo "   Title: {$testNotification->title}\n";
    echo "   Remind time: {$testNotification->remind_at}\n";
    echo "   Will trigger automatically in 15 seconds\n\n";

    // Test the complete flow
    echo "🧪 Testing Complete Flow:\n";
    echo "========================\n";

    // 1. Test initial state
    echo "1️⃣ Initial state:\n";
    $initialNotifications = Notification::where('user_id', $user->id)
        ->where('is_triggered', true)
        ->where('is_read', false)
        ->count();
    echo "   Triggered notifications: {$initialNotifications}\n\n";

    // 2. Test trigger method
    echo "2️⃣ Testing trigger method:\n";
    try {
        $controller = new \App\Http\Controllers\NotificationController();
        $response = $controller->trigger($testNotification->id);
        $data = $response->getData();
        echo "   ✅ Trigger method: " . ($data->success ? 'SUCCESS' : 'FAILED') . "\n";
        echo "   Message: {$data->message}\n\n";
    } catch (Exception $e) {
        echo "   ❌ Trigger method error: " . $e->getMessage() . "\n\n";
    }

    // 3. Test getTriggeredNotifications method
    echo "3️⃣ Testing getTriggeredNotifications method:\n";
    try {
        $controller = new \App\Http\Controllers\NotificationController();
        $response = $controller->getTriggeredNotifications();
        $data = $response->getData();
        echo "   ✅ getTriggeredNotifications: " . ($data->success ? 'SUCCESS' : 'FAILED') . "\n";
        echo "   Count: {$data->count}\n";
        if ($data->count > 0) {
            echo "   Notifications: " . json_encode($data->notifications) . "\n";
        }
        echo "\n";
    } catch (Exception $e) {
        echo "   ❌ getTriggeredNotifications error: " . $e->getMessage() . "\n\n";
    }

    // 4. Test final state
    echo "4️⃣ Final state:\n";
    $finalNotifications = Notification::where('user_id', $user->id)
        ->where('is_triggered', true)
        ->where('is_read', false)
        ->count();
    echo "   Triggered notifications: {$finalNotifications}\n\n";

    echo "🎯 Manual Testing Instructions:\n";
    echo "=============================\n";
    echo "1. Go to http://127.0.0.1:8000/notifications\n";
    echo "2. Open browser console (F12)\n";
    echo "3. Wait 15 seconds for the notification to trigger automatically\n";
    echo "4. Watch for these console messages:\n";
    echo "   - 'Notification is overdue, triggering automatically: {$testNotification->id}'\n";
    echo "   - 'Notification triggered successfully: {$testNotification->id}'\n";
    echo "   - 'Found X triggered notifications'\n";
    echo "   - 'Side panel should now be visible'\n";
    echo "5. The side panel should appear with the notification\n";
    echo "6. Sound should play (if enabled)\n\n";

    echo "🔧 Expected Behavior:\n";
    echo "===================\n";
    echo "✅ Countdown timer shows seconds and updates every second\n";
    echo "✅ When countdown reaches 0, notification triggers automatically\n";
    echo "✅ Side panel slides in from the right\n";
    echo "✅ Sound plays (if enabled)\n";
    echo "✅ Desktop notification appears (if browser permission granted)\n";
    echo "✅ Status changes to 'تم التنبيه' automatically\n\n";

    echo "🎉 End-to-end test completed!\n";
    echo "The notification will trigger automatically in 15 seconds.\n";
    echo "Keep the notifications page open to see it in action! 🪄\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
