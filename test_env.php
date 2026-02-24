<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "MAIN_APP_URL actual: " . env('MAIN_APP_URL') . "\n";
echo "APP_URL: " . env('APP_URL') . "\n";
