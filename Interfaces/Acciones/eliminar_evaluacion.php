<?php
// Incluir archivo de conexión si es necesario
include '../../conexion.php';

// Conectar a la base de datos
$Con = Conectar();

// Verificar si la solicitud es de tipo POST y si contiene el expediente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar' && isset($_POST['expediente'])) {
    // Limpiar el expediente recibido
    $expediente = intval($_POST['expediente']);
    
    // Preparar la consulta de eliminación
    $SQL = "DELETE FROM evaluaciones WHERE exp_alumno = '$expediente'";
    
    // Ejecutar la consulta
    if (Ejecutar($Con, $SQL)) {
        echo "Evaluación eliminada correctamente.";
    } else {
        echo "Error al eliminar la evaluación.";
    }
    
    // Cerrar la conexión
    Cerrar($Con);
} else {
    echo "Solicitud inválida.";
}
?>
