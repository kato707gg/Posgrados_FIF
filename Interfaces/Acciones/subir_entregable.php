<?php
session_start();

$session_id = $_SESSION['id']; // ID de la sesión actual

// Ruta base para guardar los archivos
$basePath = "../../docs/$session_id/entregables";

// Verifica si las carpetas existen, si no, créalas
if (!file_exists($basePath)) {
    mkdir($basePath, 0777, true); // Crea las carpetas con permisos adecuados
}

// Verifica si se recibió un archivo
if (!empty($_FILES['entregable']['name'])) {
    $fileName = basename($_FILES['entregable']['name']);
    $targetPath = $basePath . '/' . $fileName;

    // Mueve el archivo subido a la carpeta correspondiente
    if (move_uploaded_file($_FILES['entregable']['tmp_name'], $targetPath)) {
        $archivoGuardado = $targetPath;
        echo "Archivo subido correctamente";
    } else {
        echo "Error al guardar el archivo.";
        exit;
    }
} else {
    echo "No se seleccionó ningún archivo.";
    exit;
}
?>
