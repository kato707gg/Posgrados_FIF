<?php
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

    // Prepara la consulta SQL para actualizar los detalles de la evaluación
    $SQL = "
        UPDATE detalle_evaluaciones
        SET calificacion = ?, observacion = ?
        WHERE id_sinodo = ? AND id_evaluacion = (SELECT id FROM evaluaciones WHERE exp_alumno = ?)
    ";
    
    // Prepara la sentencia
    if ($stmt = $Con->prepare($SQL)) {
        // Reemplaza los marcadores de posición con los valores correspondientes
        $stmt->bind_param("dsis", $calificacion, $observacion, $_SESSION['id'], $expediente);

        // Ejecuta la sentencia
        if ($stmt->execute()) {
            echo "Evaluación actualizada exitosamente";
        } else {
            echo "Error al actualizar la evaluación: " . $stmt->error;
        }

        // Cierra la sentencia
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $Con->error;
    }

    // Cierra la conexión
    Cerrar($Con);
} else {
    echo "Datos incompletos para actualizar la evaluación.";
}
?>
