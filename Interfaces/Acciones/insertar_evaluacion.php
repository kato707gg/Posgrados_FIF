<?php
// Incluir el archivo de conexión
include '../../conexion.php';

// Conectar a la base de datos
$Con = Conectar();

// Asegúrate de que la solicitud sea POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exp_alumno = $_POST['exp'];
    $fecha_evaluacion = $_POST['fecha_evaluacion'];

    // Preparar la consulta SQL (asumiendo que tienes una tabla llamada 'evaluaciones')
    $SQL = "INSERT INTO evaluaciones (exp_alumno, fecha_evaluacion) VALUES ('$exp_alumno', '$fecha_evaluacion')";
    echo $SQL;
    // Ejecutar la consulta
    if (Ejecutar($Con, $SQL)) {
        // Si la inserción es exitosa
        echo "Evaluacion agendada correctamente para el expediente: $exp_alumno.";
    } else {
        // Si hay un error al ejecutar la consulta
        echo "Error al agendar evaluacion para el expediente: $exp_alumno.";
    }
    
    // Cerrar la conexión a la base de datos
    Cerrar($Con);
} else {
    // Si la solicitud no fue enviada correctamente
    echo "Método de solicitud no válido.";
}
?>