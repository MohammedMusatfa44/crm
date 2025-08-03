<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

echo "🔔 Quick Notification Test\n";
echo "=========================\n\n";

try {
    // Get first user
    $user = User::first();
    if (!$user) {
        echo "❌ No users found!\n";
        exit(1);
    }

    echo "👤 User: {$user->name}\n";
    echo "⏰ Current time: " . Carbon::now()->format('Y-m-d H:i:s') . "\n\n";

    // Create immediate test notification
    $notification = Notification::create([
        'title' => 'اختبار سريع',
        'message' => 'هذا اختبار سريع للتنبيه',
        'user_id' => $user->id,
        'remind_at' => Carbon::now()->subMinute(),
        'is_read' => false,
        'is_triggered' => false,
    ]);

    echo "✅ Created notification ID: {$notification->id}\n";

    // Mark as triggered
    $notification->update(['is_triggered' => true]);
    echo "✅ Marked as triggered\n\n";

    // Check if it's in triggered list
    $triggered = Notification::where('user_id', $user->id)
        ->where('is_triggered', true)
        ->where('is_read', false)
        ->count();

    echo "📊 Triggered notifications count: {$triggered}\n";
    echo "🎯 Now go to /notifications page and check if alert appears!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
