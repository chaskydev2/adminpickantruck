<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$model = new App\Models\UserDocument();
$model->user_id = 76;
$model->file_path = 'public/documents/76/698b7f12bb6f7_76_1770749714.png';

echo "URL generada: " . $model->document_url . "\n";
echo "MAIN_APP_URL: " . env('MAIN_APP_URL') . "\n";