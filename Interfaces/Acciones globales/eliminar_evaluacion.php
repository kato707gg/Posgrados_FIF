<?php
// Incluir archivo de conexión si es necesario
include '../../Config/conexion.php';

// Conectar a la base de datos
$Con = Conectar();

// Verificar si la solicitud es de tipo POST y si contiene el expediente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'eliminar' && isset($_POST['expediente'])) {
    // Limpiar el expediente recibido
    $expediente = intval($_POST['expediente']);
    
    // Obtener el ID de la evaluación
    $SQL_eval = "SELECT id FROM evaluaciones WHERE exp_alumno = ?";
    $stmt_eval = $Con->prepare($SQL_eval);
    $stmt_eval->bind_param("i", $expediente);
    $stmt_eval->execute();
    $result_eval = $stmt_eval->get_result();
    $eval = $result_eval->fetch_assoc();
    
    if ($eval) {
        // Eliminar solo el archivo correspondiente
        $carpeta_entregables = "../../Entregables/" . $expediente;
        if (is_dir($carpeta_entregables)) {
            $archivos = scandir($carpeta_entregables);
            foreach ($archivos as $archivo) {
                if ($archivo != "." && $archivo != "..") {
                    unlink($carpeta_entregables . "/" . $archivo);
                    break; // Solo elimina el primer archivo encontrado
                }
            }
        }
        
        // Eliminar el registro de la base de datos
        $SQL = "DELETE FROM evaluaciones WHERE exp_alumno = ?";
        $stmt = $Con->prepare($SQL);
        $stmt->bind_param("i", $expediente);
        
        if ($stmt->execute()) {
            echo "Evaluación eliminada correctamente.";
        } else {
            echo "Error al eliminar la evaluación.";
        }
    }
    
    Cerrar($Con);
} else {
    echo "Solicitud inválida.";
}
?>
