<?php
// Incluir el archivo de conexión
include 'Config/conexion.php';

// Conectar a la base de datos
$Con = Conectar();

// Asegúrate de que la solicitud sea POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exp_alumno = mysqli_real_escape_string($Con, $_POST['exp']);
    $fecha_evaluacion = mysqli_real_escape_string($Con, $_POST['fecha_evaluacion']);
    $aula = mysqli_real_escape_string($Con, $_POST['aula']);

    $archivoGuardado = "";

    // Manejo de archivo (si existe)
    if (isset($_FILES['entregable']) && $_FILES['entregable']['error'] == 0) {
        $basePath = "Entregables/$exp_alumno";

        // Crear la carpeta si no existe
        if (!file_exists($basePath)) {
            mkdir($basePath, 0777, true); // Crear carpetas con permisos adecuados
        }

        // Guardar el archivo
        $fileName = basename($_FILES['entregable']['name']);
        $targetPath = $basePath . '/' . $fileName;

        if (move_uploaded_file($_FILES['entregable']['tmp_name'], $targetPath)) {
            $archivoGuardado = $targetPath; // Ruta del archivo guardado
        } else {
            echo "Error al guardar el archivo.";
            exit;
        }
    } else {
        echo "No se seleccionó ningún archivo o hubo un error en la carga.";
        exit;
    }

    // Iniciar transacción
    mysqli_begin_transaction($Con);

    try {
        // Insertar en evaluaciones
        $SQL = "INSERT INTO evaluaciones (exp_alumno, fecha_evaluacion, aula) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($Con, $SQL);
        mysqli_stmt_bind_param($stmt, "sss", $exp_alumno, $fecha_evaluacion, $aula);
        mysqli_stmt_execute($stmt);
        $id_evaluacion = mysqli_insert_id($Con);

        // Obtener sinodales de asignaciones
        $SQL_sinodales = "SELECT director, sinodo2, sinodo3, externo FROM asignaciones WHERE exp_alumno = ?";
        $stmt_sinodales = mysqli_prepare($Con, $SQL_sinodales);
        mysqli_stmt_bind_param($stmt_sinodales, "s", $exp_alumno);
        mysqli_stmt_execute($stmt_sinodales);
        $result = mysqli_stmt_get_result($stmt_sinodales);
        $asignacion = mysqli_fetch_assoc($result);

        // Insertar en detalle_evaluaciones para cada sinodal
        $sinodales = array_filter([$asignacion['director'], $asignacion['sinodo2'], $asignacion['sinodo3'], $asignacion['externo']]);
        $SQL_detalle = "INSERT INTO detalle_evaluaciones (id_evaluacion, id_sinodo) VALUES (?, ?)";
        $stmt_detalle = mysqli_prepare($Con, $SQL_detalle);

        foreach ($sinodales as $sinodal) {
            mysqli_stmt_bind_param($stmt_detalle, "is", $id_evaluacion, $sinodal);
            mysqli_stmt_execute($stmt_detalle);
        }

        // Confirmar la transacción
        mysqli_commit($Con);
        echo "Evaluación agendada correctamente para el expediente: $exp_alumno.";
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        mysqli_rollback($Con);
        echo "Error al agendar evaluación: " . $e->getMessage();
    }

    // Cerrar la conexión a la base de datos
    Cerrar($Con);
} else {
    // Si la solicitud no fue enviada correctamente
    echo "Método de solicitud no válido.";
}
?>