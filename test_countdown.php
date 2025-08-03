<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

echo "🧪 Testing Countdown Display with Minutes\n";
echo "========================================\n\n";

try {
    $testUser = User::first();
    if ($testUser) {
        echo "Creating test notifications with different time intervals...\n\n";

        // Create notifications with different time intervals
        $testTimes = [
            'now' => Carbon::now(),
            '5_minutes' => Carbon::now()->addMinutes(5),
            '30_minutes' => Carbon::now()->addMinutes(30),
            '2_hours' => Carbon::now()->addHours(2),
            '1_day' => Carbon::now()->addDay(),
            'past_10_minutes' => Carbon::now()->subMinutes(10),
        ];

        foreach ($testTimes as $name => $time) {
            $notification = Notification::create([
                'title' => "اختبار العد التنازلي - {$name}",
                'message' => "هذا اختبار للعد التنازلي: {$name}",
                'user_id' => $testUser->id,
                'customer_id' => null,
                'remind_at' => $time,
                'is_read' => false,
                'is_triggered' => false,
            ]);

            // Calculate and display the countdown
            $now = Carbon::now();
            $diff = $now->diff($time);
            $isPast = $now->gt($time);

            $countdownText = '';
            if ($isPast) {
                $countdownText = "منذ {$diff->days} يوم {$diff->h} ساعة {$diff->i} دقيقة";
            } else {
                $countdownText = "بعد {$diff->days} يوم {$diff->h} ساعة {$diff->i} دقيقة";
            }

            echo "✅ {$name}:\n";
            echo "   - ID: {$notification->id}\n";
            echo "   - Remind at: {$time->format('Y-m-d H:i:s')}\n";
            echo "   - Countdown: {$countdownText}\n";
            echo "   - Is Past: " . ($isPast ? 'Yes' : 'No') . "\n\n";
        }

        echo "🎯 Expected Results:\n";
        echo "- now: يجب أن يظهر 'بعد 0 يوم 0 ساعة 0 دقيقة' أو 'منذ 0 يوم 0 ساعة 0 دقيقة'\n";
        echo "- 5_minutes: يجب أن يظهر 'بعد 0 يوم 0 ساعة 5 دقيقة'\n";
        echo "- 30_minutes: يجب أن يظهر 'بعد 0 يوم 0 ساعة 30 دقيقة'\n";
        echo "- 2_hours: يجب أن يظهر 'بعد 0 يوم 2 ساعة 0 دقيقة'\n";
        echo "- 1_day: يجب أن يظهر 'بعد 1 يوم 0 ساعة 0 دقيقة'\n";
        echo "- past_10_minutes: يجب أن يظهر 'منذ 0 يوم 0 ساعة 10 دقيقة'\n\n";

        echo "📋 To test in browser:\n";
        echo "1. Go to /notifications page\n";
        echo "2. Check the countdown column\n";
        echo "3. You should see minutes included in all countdowns\n";
        echo "4. The format should be: 'بعد/منذ X يوم X ساعة X دقيقة'\n\n";

        echo "🧹 Clean up test notifications? (y/n): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        fclose($handle);

        if (trim($line) === 'y') {
            // Clean up test notifications
            Notification::where('user_id', $testUser->id)
                ->where('title', 'like', 'اختبار العد التنازلي%')
                ->delete();
            echo "✅ Test notifications cleaned up.\n";
        } else {
            echo "ℹ️  Test notifications kept for manual inspection.\n";
        }

    } else {
        echo "❌ No users found in the system!\n";
    }

    echo "\n🎉 Countdown test completed!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
