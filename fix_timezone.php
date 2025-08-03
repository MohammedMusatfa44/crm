<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Fixing Timezone Configuration...\n\n";

try {
    // Update the config/app.php file
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

    // Clear config cache
    echo "Clearing config cache...\n";
    $app['config']->set('app.timezone', 'Asia/Riyadh');

    // Test current time
    echo "\nCurrent timezone: " . date_default_timezone_get() . "\n";
    echo "Current time: " . now()->format('Y-m-d H:i:s') . "\n";
    echo "Current time (local): " . date('Y-m-d H:i:s') . "\n";

    // Test with Carbon
    echo "Carbon time: " . \Carbon\Carbon::now()->format('Y-m-d H:i:s') . "\n";

    echo "\n✅ Timezone fix completed!\n";
    echo "The system will now use your local time (Asia/Riyadh).\n";
    echo "Please restart your Laravel application for changes to take effect.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
