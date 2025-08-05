<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Testing Notification Endpoint\n";
echo "===============================\n\n";

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
        echo "   User ID: {$user->id}\n\n";
    } else {
        echo "❌ User not found\n\n";
        exit(1);
    }

    // Test 3: Check database directly
    echo "3. Database check...\n";
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

    // Test 4: Test the getTriggeredNotifications method directly
    echo "4. Testing getTriggeredNotifications method...\n";
    $result = $controller->getTriggeredNotifications();
    $responseData = json_decode($result->getContent(), true);

    echo "   Response status: " . $result->getStatusCode() . "\n";
    echo "   Response content: " . $result->getContent() . "\n";

    if ($responseData['success']) {
        echo "✅ Method executed successfully\n";
        echo "   Notifications count: {$responseData['count']}\n";
        if ($responseData['count'] > 0) {
            echo "   First notification: " . json_encode($responseData['notifications'][0]) . "\n";
        }
    } else {
        echo "❌ Method failed: {$responseData['message']}\n";
    }
    echo "\n";

    // Test 5: Test with Eloquent directly
    echo "5. Testing Eloquent query directly...\n";
    try {
        $notifications = \App\Models\Notification::where('user_id', $user->id)
            ->where('is_triggered', true)
            ->where('is_read', false)
            ->with(['customer'])
            ->latest()
            ->take(5)
            ->get();

        echo "✅ Eloquent query successful\n";
        echo "   Count: {$notifications->count()}\n";

        if ($notifications->count() > 0) {
            echo "   First notification: " . json_encode($notifications->first()->toArray()) . "\n";
        }
    } catch (Exception $e) {
        echo "❌ Eloquent query failed: " . $e->getMessage() . "\n";
    }
    echo "\n";

    echo "🎯 Debug complete!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
