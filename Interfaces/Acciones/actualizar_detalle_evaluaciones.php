<?php
// Segundo archivo (actualizar_detalle_evaluaciones.php)
session_start();
include '../../conexion.php';
$Con = Conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['expediente'])) {
    $expediente = $_POST['expediente'];
    $periodo = isset($_POST['periodo']) ? $_POST['periodo'] : '';
    $esDirector = isset($_POST['esDirector']) && $_POST['esDirector'] === 'true';
    
    if (isset($_SESSION['id'])) {
        $id_sinodo = $_SESSION['id'];
        
        // Verificar si ya existe un registro
        $checkSQL = "SELECT id_evaluacion FROM detalle_evaluaciones 
                    WHERE id_sinodo = ? AND 
                    id_evaluacion = (SELECT id FROM evaluaciones WHERE exp_alumno = ?)";
        
        $stmt = $Con->prepare($checkSQL);
        $stmt->bind_param("ss", $id_sinodo, $expediente);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($esDirector) {
            // Manejo para director (sin calificación)
            if ($result->num_rows > 0) {
                $SQL = "UPDATE detalle_evaluaciones 
                       SET d_observacion1 = ?,
                           d_observacion2 = ?,
                           d_observacion3 = ?,
                           observacion = ?,
                           periodo = ? 
                       WHERE id_sinodo = ? AND 
                       id_evaluacion = (SELECT id FROM evaluaciones WHERE exp_alumno = ?)";

                $stmt = $Con->prepare($SQL);
                $d_obs1 = $_POST['d_observacion1'] ?? '';
                $d_obs2 = $_POST['d_observacion2'] ?? '';
                $d_obs3 = $_POST['d_observacion3'] ?? '';
                $observacion = $_POST['observacion'] ?? '';
                $stmt->bind_param(
                    "sssssss",
                    $d_obs1,
                    $d_obs2,
                    $d_obs3,
                    $observacion,
                    $periodo,
                    $id_sinodo,
                    $expediente
                );
            } else {
                $SQL = "INSERT INTO detalle_evaluaciones 
                       (id_sinodo, id_evaluacion, d_observacion1, d_observacion2, d_observacion3, observacion, periodo) 
                       SELECT ?, id, ?, ?, ?, ?, ? 
                       FROM evaluaciones 
                       WHERE exp_alumno = ?";

                $stmt = $Con->prepare($SQL);
                $d_obs1 = $_POST['d_observacion1'] ?? '';
                $d_obs2 = $_POST['d_observacion2'] ?? '';
                $d_obs3 = $_POST['d_observacion3'] ?? '';
                $observacion = $_POST['observacion'] ?? '';
                $stmt->bind_param(
                    "sssssss",
                    $id_sinodo,
                    $d_obs1,
                    $d_obs2,
                    $d_obs3,
                    $observacion,
                    $periodo,
                    $expediente
                );
            }
        } else {
            // Manejo para no director (con calificación)
            $calificacion = isset($_POST['calificacion']) ? $_POST['calificacion'] : 0;
            
            if ($result->num_rows > 0) {
                $SQL = "UPDATE detalle_evaluaciones 
                       SET calificacion = ?, 
                           observacion = ?,
                           periodo = ? 
                       WHERE id_sinodo = ? AND 
                       id_evaluacion = (SELECT id FROM evaluaciones WHERE exp_alumno = ?)";

                $stmt = $Con->prepare($SQL);
                $observacion = $_POST['observacion'] ?? '';
                $stmt->bind_param(
                    "dssss",
                    $calificacion,
                    $observacion,
                    $periodo,
                    $id_sinodo,
                    $expediente
                );
            } else {
                $SQL = "INSERT INTO detalle_evaluaciones 
                       (id_sinodo, id_evaluacion, calificacion, observacion, periodo) 
                       SELECT ?, id, ?, ?, ? 
                       FROM evaluaciones 
                       WHERE exp_alumno = ?";

                $stmt = $Con->prepare($SQL);
                $observacion = $_POST['observacion'] ?? '';
                $stmt->bind_param(
                    "sdsss",
                    $id_sinodo,
                    $calificacion,
                    $observacion,
                    $periodo,
                    $expediente
                );
            }
        }

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: ID de sínodo no encontrado en la sesión";
    }

    Cerrar($Con);
} else {
    echo "Error: Datos incompletos";
}
?>