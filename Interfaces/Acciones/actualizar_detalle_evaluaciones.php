<?php
// Iniciar la sesión
session_start();

// Incluir el archivo de conexión
include '../../conexion.php';

// Conectar a la base de datos
$Con = Conectar();

// Verificar si se ha enviado una solicitud POST con los parámetros necesarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['expediente']) && isset($_POST['calificacion']) && isset($_POST['observacion'])) {
    $expediente = $_POST['expediente'];
    $calificacion = $_POST['calificacion'];
    $observacion = $_POST['observacion'];

    // Verificar si la variable de sesión 'id' está definida
    if (isset($_SESSION['id'])) {
        $id_sinodo = $_SESSION['id'];
        
        // Prepara la consulta SQL para actualizar los detalles de la evaluación
        $SQL = "
            UPDATE detalle_evaluaciones
            SET calificacion = '$calificacion', observacion = '$observacion'
            WHERE id_sinodo = '$id_sinodo' AND id_evaluacion = (SELECT id FROM evaluaciones WHERE exp_alumno = '$expediente')
        ";

        echo $SQL; // Para depuración

        // Ejecutar la consulta
        if (Ejecutar($Con, $SQL)) {
            // Si la inserción es exitosa
            echo "Evaluación actualizada exitosamente";
        } else {
            // Si hay un error al ejecutar la consulta
            echo "Error al actualizar la evaluación";
        } 
    } else {
        echo "ID de sínodo no encontrado en la sesión.";
    }
    Cerrar($Con);
} else {
    echo "Datos incompletos para actualizar la evaluación.";
}
?>
