<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

echo "🔔 Testing Side Panel System\n";
echo "===========================\n\n";

try {
    // Get first user
    $user = User::first();
    if (!$user) {
        echo "❌ No users found!\n";
        exit(1);
    }

    echo "👤 User: {$user->name}\n";
    echo "⏰ Current time: " . Carbon::now()->format('Y-m-d H:i:s') . "\n\n";

    // Create a test notification that should trigger immediately
    $notification = Notification::create([
        'title' => 'اختبار اللوحة الجانبية',
        'message' => 'هذا اختبار للوحة الجانبية - يجب أن تظهر فوراً',
        'user_id' => $user->id,
        'customer_id' => null,
        'remind_at' => Carbon::now()->subMinute(),
        'is_read' => false,
        'is_triggered' => false,
    ]);

    echo "✅ Created notification ID: {$notification->id}\n";
    echo "   Title: {$notification->title}\n";
    echo "   Remind time: {$notification->remind_at}\n\n";

    // Mark as triggered
    $notification->update(['is_triggered' => true]);
    echo "✅ Marked as triggered\n\n";

    // Test the API response
    echo "🔍 Testing API Response:\n";
    $apiNotifications = Notification::where('user_id', $user->id)
        ->where('is_triggered', true)
        ->where('is_read', false)
        ->with(['customer'])
        ->latest()
        ->take(5)
        ->get();

    echo "   Found " . $apiNotifications->count() . " triggered notifications\n";
    foreach ($apiNotifications as $notif) {
        echo "   - {$notif->title} (ID: {$notif->id})\n";
    }

    // Simulate the JSON response
    $response = [
        'success' => true,
        'notifications' => $apiNotifications,
        'count' => $apiNotifications->count(),
        'debug' => [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'check_time' => now()->format('Y-m-d H:i:s')
        ]
    ];

    echo "\n📋 API Response JSON:\n";
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo "\n\n";

    echo "🎯 Next Steps:\n";
    echo "1. Go to http://127.0.0.1:8000/notifications\n";
    echo "2. Open browser console (F12 → Console)\n";
    echo "3. Look for console messages about notifications\n";
    echo "4. The side panel should appear automatically\n";
    echo "5. If not, check for any JavaScript errors\n\n";

    echo "🔧 Manual API Test:\n";
    echo "Visit: http://127.0.0.1:8000/notifications/triggered\n";
    echo "This should return the JSON response above\n\n";

    echo "🎉 Test completed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
