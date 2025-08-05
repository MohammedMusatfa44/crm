<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

echo "🧪 Manual Notification Test\n";
echo "==========================\n\n";

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

    // Create a test notification for 30 seconds from now
    $testNotification = Notification::create([
        'title' => 'اختبار تنبيه يدوي',
        'message' => 'هذا اختبار للتنبيه اليدوي - سيظهر تلقائياً خلال 30 ثانية',
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

    // Check current notifications
    echo "📊 Current notifications for user:\n";
    $notifications = Notification::where('user_id', $user->id)->get();
    foreach ($notifications as $notif) {
        $status = $notif->is_triggered ? 'تم التنبيه' : 'في الانتظار';
        echo "   - ID: {$notif->id}, Title: {$notif->title}, Status: {$status}\n";
    }
    echo "\n";

    echo "🎯 Testing Instructions:\n";
    echo "=======================\n";
    echo "1. Go to http://127.0.0.1:8000/notifications\n";
    echo "2. Open browser console (F12)\n";
    echo "3. Wait 30 seconds for the notification to trigger\n";
    echo "4. Check console for these messages:\n";
    echo "   - 'Notification is overdue, triggering automatically: {$testNotification->id}'\n";
    echo "   - 'Triggering notification automatically: {$testNotification->id}'\n";
    echo "   - 'Notification triggered successfully: {$testNotification->id}'\n";
    echo "   - 'Found X triggered notifications'\n";
    echo "   - 'Side panel should now be visible'\n\n";

    echo "🔧 Manual Testing Steps:\n";
    echo "=======================\n";
    echo "1. Keep the notifications page open\n";
    echo "2. Watch the countdown timer in the table\n";
    echo "3. When it reaches 0, check if side panel appears\n";
    echo "4. Check browser console for any errors\n";
    echo "5. Check if sound plays (if enabled)\n\n";

    echo "🔍 Debugging Steps:\n";
    echo "==================\n";
    echo "If notifications don't trigger:\n";
    echo "1. Check browser console for JavaScript errors\n";
    echo "2. Verify the notification ID matches in console\n";
    echo "3. Check if the countdown timer is updating\n";
    echo "4. Verify the side panel HTML exists in the page\n";
    echo "5. Check network tab for failed AJAX requests\n\n";

    echo "🎉 Test completed! The notification will trigger in 30 seconds.\n";
    echo "Make sure to keep the notifications page open!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
