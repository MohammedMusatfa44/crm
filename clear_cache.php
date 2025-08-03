<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Clearing Caches ===\n";

// Clear all caches
app()['cache']->flush();
echo "Application cache cleared.\n";

// Clear config cache
if (file_exists('bootstrap/cache/config.php')) {
    unlink('bootstrap/cache/config.php');
    echo "Config cache cleared.\n";
}

// Clear route cache
if (file_exists('bootstrap/cache/routes.php')) {
    unlink('bootstrap/cache/routes.php');
    echo "Route cache cleared.\n";
}

// Clear view cache
if (file_exists('bootstrap/cache/views.php')) {
    unlink('bootstrap/cache/views.php');
    echo "View cache cleared.\n";
}

// Clear permission cache
app()['cache']->forget('spatie.permission.cache');
echo "Permission cache cleared.\n";

echo "\n=== Cache Clear Complete ===\n";
echo "Please try accessing the permissions page again.\n";
