<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

// Configurar la respuesta como JSON
header('Content-Type: application/json');

// Verificar si se recibió un archivo
if (!isset($_FILES['file']) || !isset($_POST['documentType'])) {
    echo json_encode(['success' => false, 'message' => 'No se recibió el archivo o el tipo de documento']);
    exit;
}

// Obtener el ID del usuario de la sesión
$userId = $_SESSION['id'];

// Definir la ruta base para los documentos
$baseDir = '../../docs/';

// Crear el directorio base si no existe
if (!file_exists($baseDir)) {
    mkdir($baseDir, 0755, true);
}

// Crear el directorio del usuario si no existe
$userDir = $baseDir . $userId . '/';
if (!file_exists($userDir)) {
    mkdir($userDir, 0755, true);
}

// Obtener información del archivo
$file = $_FILES['file'];
$fileName = $file['name'];
$fileType = $_POST['documentType'];
$targetPath = $userDir . time() . '_' . $fileName;

// Verificar el tipo de archivo (puedes ajustar los tipos permitidos)
$allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Tipo de archivo no permitido']);
    exit;
}

// Intentar mover el archivo
if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    // Aquí podrías guardar la información del archivo en la base de datos si lo necesitas
    echo json_encode([
        'success' => true,
        'message' => 'Archivo subido correctamente',
        'data' => [
            'fileName' => $fileName,
            'type' => $fileType,
            'path' => $targetPath,
            'date' => date('Y-m-d')
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al subir el archivo']);
}
?>