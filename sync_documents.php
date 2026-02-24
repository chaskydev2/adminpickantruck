#!/usr/bin/env php
<?php
/**
 * Script para sincronizar las rutas de documentos en la BD con los archivos físicos reales
 * Este script debe ejecutarse en el servidor de producción
 */

// Configuración de base de datos
$host = 'localhost';
$dbname = 'u556487000_apppickn';
$username = 'u556487000_marior';
$password = 'Diadelpadre2024';

// Ruta base donde están los documentos
$documentsBasePath = '/home/u556487000/domains/app.pickntruck.com/public_html/documents';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conectado a la base de datos\n\n";
    
    // Obtener todos los documentos
    $stmt = $pdo->query("SELECT id, user_id, file_path, status FROM user_documents ORDER BY user_id, id");
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total documentos en BD: " . count($documents) . "\n\n";
    
    $updated = 0;
    $notFound = 0;
    $alreadyCorrect = 0;
    
    foreach ($documents as $doc) {
        $userId = $doc['user_id'];
        $currentPath = $doc['file_path'];
        $docId = $doc['id'];
        
        // Extraer el nombre del archivo de la ruta actual
        $currentFileName = basename($currentPath);
        $userDocsDir = $documentsBasePath . '/' . $userId;
        
        // Verificar si el directorio del usuario existe
        if (!is_dir($userDocsDir)) {
            echo "❌ Usuario $userId - Directorio no existe\n";
            $notFound++;
            continue;
        }
        
        // Verificar si el archivo actual existe
        $fullCurrentPath = $documentsBasePath . '/' . $currentPath;
        if (file_exists($fullCurrentPath)) {
            echo "✅ Doc ID $docId - Usuario $userId - Archivo ya existe correctamente\n";
            $alreadyCorrect++;
            continue;
        }
        
        // Buscar archivos en el directorio del usuario
        $files = scandir($userDocsDir);
        $foundFile = null;
        
        // Intentar encontrar el archivo por patrón en el nombre
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            // Verificar si el archivo pertenece a este usuario (tiene _userId_ en el nombre)
            if (strpos($file, '_' . $userId . '_') !== false) {
                // Este es un archivo del usuario, usar el primero que encontremos
                // o podríamos implementar una lógica más sofisticada
                $foundFile = $file;
                break;
            }
        }
        
        if ($foundFile) {
            $newPath = 'documents/' . $userId . '/' . $foundFile;
            
            // Actualizar en la base de datos
            $updateStmt = $pdo->prepare("UPDATE user_documents SET file_path = ? WHERE id = ?");
            $updateStmt->execute([$newPath, $docId]);
            
            echo "🔄 Doc ID $docId - Usuario $userId\n";
            echo "   Anterior: $currentPath\n";
            echo "   Nueva:    $newPath\n\n";
            $updated++;
        } else {
            echo "⚠️  Doc ID $docId - Usuario $userId - No se encontró archivo físico\n";
            $notFound++;
        }
    }
    
    echo "\n==========================================\n";
    echo "RESUMEN:\n";
    echo "==========================================\n";
    echo "✅ Documentos correctos: $alreadyCorrect\n";
    echo "🔄 Documentos actualizados: $updated\n";
    echo "❌ Documentos sin archivo físico: $notFound\n";
    echo "==========================================\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
