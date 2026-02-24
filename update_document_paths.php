#!/usr/bin/env php
<?php
/**
 * Script para actualizar las rutas de documentos en BD agregando el prefijo 'public/' si es necesario
 */

$host = 'localhost';
$dbname = 'u556487000_apppickn';
$username = 'u556487000_marior';
$password = 'Diadelpadre2024';

$basePath = '/home/u556487000/domains/app.pickntruck.com/public_html';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Actualizando rutas de documentos...\n\n";
    
    $stmt = $pdo->query("SELECT id, user_id, file_path FROM user_documents");
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $updated = 0;
    $errors = 0;
    
    foreach ($documents as $doc) {
        $userId = $doc['user_id'];
        $currentPath = $doc['file_path'];
        $fileName = basename($currentPath);
        $docId = $doc['id'];
        
        // Posibles ubicaciones físicas
        $locations = [
            "{$basePath}/public/documents/{$userId}/{$fileName}",
            "{$basePath}/documents/{$userId}/{$fileName}",
            "{$basePath}/storage/app/public/documents/{$userId}/{$fileName}",
        ];
        
        $newPath = null;
        
        // Buscar dónde existe físicamente el archivo
        foreach ($locations as $idx => $location) {
            if (file_exists($location)) {
                switch ($idx) {
                    case 0:
                        $newPath = "public/documents/{$userId}/{$fileName}";
                        break;
                    case 1:
                        $newPath = "documents/{$userId}/{$fileName}";
                        break;
                    case 2:
                        $newPath = "storage/documents/{$userId}/{$fileName}";
                        break;
                }
                break;
            }
        }
        
        if ($newPath && $newPath !== $currentPath) {
            $updateStmt = $pdo->prepare("UPDATE user_documents SET file_path = ? WHERE id = ?");
            $updateStmt->execute([$newPath, $docId]);
            echo "✅ Doc ID $docId - Usuario $userId: $newPath\n";
            $updated++;
        } elseif (!$newPath) {
            echo "⚠️  Doc ID $docId - Usuario $userId - Archivo no encontrado\n";
            $errors++;
        }
    }
    
    echo "\n==========================================\n";
    echo "RESUMEN:\n";
    echo "==========================================\n";
    echo "✅ Documentos actualizados: $updated\n";
    echo "⚠️  Archivos no encontrados: $errors\n";
    echo "==========================================\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
