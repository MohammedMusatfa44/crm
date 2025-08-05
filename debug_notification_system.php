<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

echo "🔍 Debugging Notification System\n";
echo "===============================\n\n";

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

    // Create a test notification for 10 seconds from now
    $testNotification = Notification::create([
        'title' => 'اختبار تنبيه تلقائي',
        'message' => 'هذا اختبار للتنبيه التلقائي - سيظهر تلقائياً خلال 10 ثانية',
        'user_id' => $user->id,
        'customer_id' => null,
        'remind_at' => Carbon::now()->addSeconds(10),
        'is_read' => false,
        'is_triggered' => false,
    ]);

    echo "✅ Created test notification ID: {$testNotification->id}\n";
    echo "   Title: {$testNotification->title}\n";
    echo "   Remind time: {$testNotification->remind_at}\n";
    echo "   Will trigger automatically in 10 seconds\n\n";

    // Test the trigger method directly
    echo "🧪 Testing trigger method directly...\n";

    try {
        $controller = new \App\Http\Controllers\NotificationController();
        $response = $controller->trigger($testNotification->id);
        $data = $response->getData();
        echo "✅ Trigger method works: " . json_encode($data) . "\n\n";
    } catch (Exception $e) {
        echo "❌ Trigger method error: " . $e->getMessage() . "\n\n";
    }

    // Test the getTriggeredNotifications method
    echo "🧪 Testing getTriggeredNotifications method...\n";

    try {
        $controller = new \App\Http\Controllers\NotificationController();
        $response = $controller->getTriggeredNotifications();
        $data = $response->getData();
        echo "✅ getTriggeredNotifications works: " . json_encode($data) . "\n\n";
    } catch (Exception $e) {
        echo "❌ getTriggeredNotifications error: " . $e->getMessage() . "\n\n";
    }

    // Test API endpoints
    echo "🌐 Testing API endpoints...\n";
    echo "1. Trigger endpoint: POST /notifications/{$testNotification->id}/trigger\n";
    echo "2. Triggered endpoint: GET /notifications/triggered\n\n";

    echo "🎯 Manual Testing Steps:\n";
    echo "=======================\n";
    echo "1. Go to http://127.0.0.1:8000/notifications\n";
    echo "2. Open browser console (F12)\n";
    echo "3. Wait 10 seconds for notification to trigger\n";
    echo "4. Check console for these messages:\n";
    echo "   - 'Notification is overdue, triggering automatically: {$testNotification->id}'\n";
    echo "   - 'Triggering notification automatically: {$testNotification->id}'\n";
    echo "   - 'Notification triggered successfully: {$testNotification->id}'\n";
    echo "   - 'Found X triggered notifications'\n";
    echo "   - 'Showing notification alert: {...}'\n";
    echo "   - 'Side panel should now be visible'\n\n";

    echo "🔧 If it's not working, check:\n";
    echo "1. Browser console for JavaScript errors\n";
    echo "2. Network tab for failed AJAX requests\n";
    echo "3. That the notification ID matches in the console\n";
    echo "4. That the side panel HTML exists in the page\n\n";

    echo "🎉 Debug test completed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
