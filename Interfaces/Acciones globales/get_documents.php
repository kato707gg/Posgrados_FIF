<?php
session_start();
include '../../Config/conexion.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

$Con = Conectar();
$userId = $_SESSION['id'];

$SQL = "SELECT * FROM documentos_alumno WHERE exp_alumno = ? ORDER BY fecha_subida DESC";
$stmt = $Con->prepare($SQL);
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

$documents = [];
while ($row = $result->fetch_assoc()) {
    $documents[] = [
        'id' => $row['id'],
        'date' => $row['fecha_subida'],
        'type' => $row['tipo'],
        'fileName' => $row['nombre_archivo'],
        'fileURL' => $row['ruta']
    ];
}

echo json_encode(['success' => true, 'data' => $documents]);
?>