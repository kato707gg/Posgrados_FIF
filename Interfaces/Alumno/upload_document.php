<?php
session_start();
include '../../Config/conexion.php';

// Obtener la conexión
$Con = Conectar();

// Agregar log de depuración
error_log("Iniciando upload_document.php");

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id'])) {
    error_log("Usuario no autenticado");
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

// Configurar la respuesta como JSON
header('Content-Type: application/json');

// Verificar si se recibió un archivo
if (!isset($_FILES['file']) || !isset($_POST['documentType'])) {
    error_log("No se recibió archivo o tipo de documento");
    echo json_encode(['success' => false, 'message' => 'No se recibió el archivo o el tipo de documento']);
    exit;
}

// Obtener el ID del usuario de la sesión
$userId = $_SESSION['id'];
error_log("ID de usuario: " . $userId);

// Sanitizar el nombre de la carpeta del tipo de documento
$documentType = $_POST['documentType'];
$typeFolderName = str_replace(' ', '_', $documentType);
$typeFolderName = preg_replace('/[^A-Za-z0-9_-]/', '', $typeFolderName);

// Definir la ruta base para los documentos
$baseDir = '../../Documentos/';
$userDir = $baseDir . $userId . '/';
$typeDir = $userDir . $typeFolderName . '/';

error_log("Intentando crear directorios:");
error_log("baseDir: " . $baseDir);
error_log("userDir: " . $userDir);
error_log("typeDir: " . $typeDir);

// Crear directorios si no existen
foreach ([$baseDir, $userDir, $typeDir] as $dir) {
    if (!file_exists($dir)) {
        if (!mkdir($dir, 0755, true)) {
            error_log("Error al crear directorio: " . $dir);
            echo json_encode(['success' => false, 'message' => 'Error al crear directorio: ' . $dir]);
            exit;
        }
    }
}

// Obtener información del archivo
$file = $_FILES['file'];
error_log("Información del archivo:");
error_log("Nombre: " . $file['name']);
error_log("Tipo: " . $file['type']);
error_log("Tamaño: " . $file['size']);
error_log("Error: " . $file['error']);

$fileName = $file['name'];
$targetPath = $typeDir . time() . '_' . $fileName;

// Verificar el tipo de archivo
$allowedTypes = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'application/octet-stream'
];

if (!in_array($file['type'], $allowedTypes)) {
    error_log("Tipo de archivo no permitido: " . $file['type']);
    echo json_encode(['success' => false, 'message' => 'Tipo de archivo no permitido: ' . $file['type']]);
    exit;
}

// Intentar mover el archivo
if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    error_log("Archivo movido exitosamente a: " . $targetPath);
    
    // Guardar en la base de datos
    $SQL = "INSERT INTO documentos_alumno (exp_alumno, tipo, nombre_archivo, ruta, fecha_subida) 
            VALUES (?, ?, ?, ?, CURDATE())";
    
    $stmt = $Con->prepare($SQL);
    if (!$stmt) {
        error_log("Error en prepare: " . $Con->error);
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta']);
        exit;
    }

    $stmt->bind_param("isss", $userId, $documentType, $fileName, $targetPath);
    
    if ($stmt->execute()) {
        error_log("Registro guardado en la base de datos");
        echo json_encode([
            'success' => true,
            'message' => 'Archivo subido correctamente',
            'data' => [
                'id' => $stmt->insert_id,
                'fileName' => $fileName,
                'type' => $documentType,
                'path' => $targetPath,
                'date' => date('Y-m-d')
            ]
        ]);
    } else {
        error_log("Error al ejecutar la consulta: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Error al guardar en la base de datos']);
    }
} else {
    error_log("Error al mover el archivo");
    echo json_encode(['success' => false, 'message' => 'Error al mover el archivo']);
}
?>