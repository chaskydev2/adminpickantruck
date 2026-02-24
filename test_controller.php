<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simular exactamente lo que hace el controlador UserDocumentController@show
$userDocument = App\Models\UserDocument::find(115);

if ($userDocument) {
    $userDocument->load(['user', 'requiredDocument']);
    
    $doc = $userDocument->toArray();
    $doc['document_url'] = $userDocument->document_url;
    $doc['created_at_formatted'] = $userDocument->created_at ? $userDocument->created_at->format('d/m/Y H:i') : null;
    $doc['user_name'] = $userDocument->user->name ?? null;
    $doc['user_email'] = $userDocument->user->email ?? null;
    $doc['required_document_name'] = $userDocument->requiredDocument->name ?? null;
    
    echo "==== RESPUESTA DEL CONTROLADOR ====\n";
    echo json_encode([
        'success' => true,
        'document' => $doc
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
} else {
    echo "Documento 115 no encontrado\n";
}