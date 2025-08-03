<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Route;

echo "=== Testing Routes ===\n";

// Get all routes
$routes = Route::getRoutes();

foreach ($routes as $route) {
    if (str_contains($route->uri(), 'permissions')) {
        echo "Route: " . $route->methods()[0] . " " . $route->uri() . " -> " . $route->getName() . "\n";
    }
}

echo "\n=== Route Test Complete ===\n";
