<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Testing Notification Web Endpoints\n";
echo "====================================\n\n";

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

    // Test 3: Test getNotificationCount method
    echo "3. Testing getNotificationCount method...\n";
    $result = $controller->getNotificationCount();
    $responseData = json_decode($result->getContent(), true);

    echo "   Response status: " . $result->getStatusCode() . "\n";
    echo "   Response content: " . $result->getContent() . "\n";

    if ($responseData['success']) {
        echo "✅ getNotificationCount executed successfully\n";
        echo "   Count: {$responseData['count']}\n";
    } else {
        echo "❌ getNotificationCount failed: {$responseData['message']}\n";
    }
    echo "\n";

    // Test 4: Test getTriggeredNotifications method
    echo "4. Testing getTriggeredNotifications method...\n";
    $result = $controller->getTriggeredNotifications();
    $responseData = json_decode($result->getContent(), true);

    echo "   Response status: " . $result->getStatusCode() . "\n";
    echo "   Response content: " . $result->getContent() . "\n";

    if ($responseData['success']) {
        echo "✅ getTriggeredNotifications executed successfully\n";
        echo "   Count: {$responseData['count']}\n";
        if ($responseData['count'] > 0) {
            echo "   First notification: " . json_encode($responseData['notifications'][0]) . "\n";
        }
    } else {
        echo "❌ getTriggeredNotifications failed: {$responseData['message']}\n";
    }
    echo "\n";

    echo "🎯 Web endpoint test complete!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
