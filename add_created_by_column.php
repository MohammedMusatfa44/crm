<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "Checking and adding created_by column to customers table...\n";

try {
    // Check if created_by column exists
    if (!Schema::hasColumn('customers', 'created_by')) {
        echo "Adding created_by column to customers table...\n";
        Schema::table('customers', function ($table) {
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('id');
        });
        echo "Column added successfully!\n";
    } else {
        echo "created_by column already exists.\n";
    }

    echo "Column check completed!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
