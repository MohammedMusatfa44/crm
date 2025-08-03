<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

echo "🔔 Quick Test\n";
echo "============\n\n";

$user = User::first();
if ($user) {
    echo "User: {$user->name}\n";
    echo "Time: " . Carbon::now()->format('Y-m-d H:i:s') . "\n\n";

    // Create test notification
    $notification = Notification::create([
        'title' => 'اختبار فوري',
        'message' => 'هذا اختبار للتنبيه',
        'user_id' => $user->id,
        'remind_at' => Carbon::now()->subMinute(),
        'is_read' => false,
        'is_triggered' => false,
    ]);

    $notification->update(['is_triggered' => true]);

    echo "✅ Test notification created and triggered!\n";
    echo "Now go to /notifications page\n";
} else {
    echo "❌ No users found\n";
}
