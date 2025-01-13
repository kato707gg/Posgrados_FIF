<?php
header('Content-Type: application/json');
include('../../Config/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['expediente'])) {
    $Con = Conectar();
    $expediente = $_POST['expediente'];
    
    try {
        $SQL_DELETE_ASIGNACION = "DELETE FROM asignaciones WHERE exp_alumno = ?";
        $stmt = mysqli_prepare($Con, $SQL_DELETE_ASIGNACION);
        mysqli_stmt_bind_param($stmt, 's', $expediente);
        mysqli_stmt_execute($stmt);

        if (mysqli_affected_rows($Con) > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Asignación eliminada exitosamente'
            ]);
        } else {
            throw new Exception('No se encontró la asignación');
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error al eliminar la asignación: ' . $e->getMessage()
        ]);
    }

    mysqli_close($Con);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Solicitud inválida'
    ]);
}
?>
