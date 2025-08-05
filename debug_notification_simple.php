<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Debugging Notification System\n";
echo "================================\n\n";

try {
    // Test 1: Check if we can access the controller
    echo "1. Testing controller access...\n";
    $controller = new \App\Http\Controllers\NotificationController();
    echo "✅ Controller instantiated successfully\n\n";

    // Test 2: Check if we can authenticate a user
    echo "2. Testing user authentication...\n";
    $user = \App\Models\User::where('email', 'admin@crm.com')->first();
    if ($user) {
        auth()->login($user);
        echo "✅ User authenticated: {$user->name} ({$user->email})\n";
        echo "   User ID: {$user->id}\n";
        echo "   Roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n\n";
    } else {
        echo "❌ User not found\n\n";
        exit(1);
    }

    // Test 3: Check if there are any notifications
    echo "3. Testing notifications...\n";
    $notifications = \App\Models\Notification::where('user_id', $user->id)->get();
    echo "   Total notifications for user: {$notifications->count()}\n";

    $triggeredNotifications = \App\Models\Notification::where('user_id', $user->id)
        ->where('is_triggered', true)
        ->where('is_read', false)
        ->get();
    echo "   Triggered unread notifications: {$triggeredNotifications->count()}\n\n";

    // Test 4: Test the getTriggeredNotifications method directly
    echo "4. Testing getTriggeredNotifications method...\n";
    $result = $controller->getTriggeredNotifications();
    $responseData = json_decode($result->getContent(), true);

    if ($responseData['success']) {
        echo "✅ Method executed successfully\n";
        echo "   Notifications count: {$responseData['count']}\n";
        if ($responseData['count'] > 0) {
            echo "   First notification: {$responseData['notifications'][0]['title']}\n";
        }
    } else {
        echo "❌ Method failed: {$responseData['message']}\n";
    }
    echo "\n";

    // Test 5: Check database directly
    echo "5. Database check...\n";
    $dbNotifications = \DB::table('notifications')
        ->where('user_id', $user->id)
        ->get();
    echo "   Total notifications in DB: {$dbNotifications->count()}\n";

    $triggeredInDB = \DB::table('notifications')
        ->where('user_id', $user->id)
        ->where('is_triggered', true)
        ->where('is_read', false)
        ->get();
    echo "   Triggered unread in DB: {$triggeredInDB->count()}\n\n";

    echo "🎯 Debug complete!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
