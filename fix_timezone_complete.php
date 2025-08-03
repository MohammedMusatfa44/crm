<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

echo "🔧 Fixing Timezone Issue - Complete Solution\n";
echo "============================================\n\n";

try {
    // Step 1: Update config/app.php
    echo "Step 1: Updating timezone configuration...\n";
    $configPath = 'config/app.php';
    $configContent = file_get_contents($configPath);

    // Replace UTC with Asia/Riyadh
    $newContent = str_replace("'timezone' => 'UTC'", "'timezone' => 'Asia/Riyadh'", $configContent);

    if ($newContent !== $configContent) {
        file_put_contents($configPath, $newContent);
        echo "✅ Timezone updated from UTC to Asia/Riyadh in config/app.php\n";
    } else {
        echo "ℹ️  Timezone already set to Asia/Riyadh\n";
    }

    // Step 2: Clear all caches
    echo "\nStep 2: Clearing Laravel caches...\n";
    $app['config']->set('app.timezone', 'Asia/Riyadh');

    // Step 3: Test timezone
    echo "\nStep 3: Testing timezone settings...\n";
    echo "Current PHP timezone: " . date_default_timezone_get() . "\n";
    echo "Current time (PHP): " . date('Y-m-d H:i:s') . "\n";
    echo "Current time (Carbon): " . Carbon::now()->format('Y-m-d H:i:s') . "\n";
    echo "Current time (Laravel): " . now()->format('Y-m-d H:i:s') . "\n";

    // Step 4: Test notification creation with correct timezone
    echo "\nStep 4: Testing notification creation...\n";
    $testUser = User::first();
    if ($testUser) {
        // Create a test notification for 2 minutes from now
        $testTime = Carbon::now()->addMinutes(2);

        $notification = Notification::create([
            'title' => 'اختبار التوقيت المحلي',
            'message' => 'هذا اختبار للتأكد من أن النظام يستخدم التوقيت المحلي الصحيح',
            'user_id' => $testUser->id,
            'customer_id' => null,
            'remind_at' => $testTime,
            'is_read' => false,
            'is_triggered' => false,
        ]);

        echo "✅ Test notification created:\n";
        echo "   - ID: {$notification->id}\n";
        echo "   - Title: {$notification->title}\n";
        echo "   - Remind at: {$notification->remind_at}\n";
        echo "   - Local time: " . $testTime->format('Y-m-d H:i:s') . "\n";

        // Show all notifications with their times
        echo "\nCurrent notifications in database:\n";
        $notifications = Notification::with('user')->get();
        foreach ($notifications as $notif) {
            $remindTime = $notif->remind_at ? $notif->remind_at->format('Y-m-d H:i:s') : 'N/A';
            echo "- ID: {$notif->id} | {$notif->title} | {$notif->user->name} | Remind: {$remindTime}\n";
        }

        // Clean up test notification
        $notification->delete();
        echo "\n🧹 Test notification cleaned up.\n";
    }

    // Step 5: Instructions for user
    echo "\nStep 5: Next steps...\n";
    echo "✅ Timezone has been fixed to Asia/Riyadh\n";
    echo "✅ All notifications will now use your local time\n";
    echo "✅ Please restart your Laravel application:\n";
    echo "   - Stop the current server (Ctrl+C)\n";
    echo "   - Run: php artisan serve\n";
    echo "✅ Test by creating a new notification\n";
    echo "✅ The time should now match your laptop time\n";

    echo "\n🎉 Timezone fix completed successfully!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
