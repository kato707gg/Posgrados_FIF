<?php
// Incluir el archivo de conexión
include '../../conexion.php';

// Conectar a la base de datos
$Con = Conectar();

// Asegúrate de que la solicitud sea POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exp_alumno = $_POST['exp'];
    $fecha_evaluacion = $_POST['fecha_evaluacion'];
    $aula = $_POST['aula'];

    // Preparar la consulta SQL para insertar la evaluación
    $SQL = "INSERT INTO evaluaciones (exp_alumno, fecha_evaluacion, aula) VALUES ('$exp_alumno', '$fecha_evaluacion', '$aula')";
    
    // Ejecutar la inserción en la tabla 'evaluaciones'
    if (Ejecutar($Con, $SQL)) {
        // Obtener el último ID insertado de la tabla 'evaluaciones'
        $nuevo_id = mysqli_insert_id($Con); // Recuperar el id de la última inserción
        
        // Preparar la consulta para insertar en 'detalle_evaluaciones' usando el nuevo ID
        $SQL2 = "INSERT INTO detalle_evaluaciones (id_evaluacion) VALUES ('$nuevo_id')";
        echo $SQL2;
        // Ejecutar la inserción en la tabla 'detalle_evaluaciones'
        if (Ejecutar($Con, $SQL2)) {
            // Si ambas inserciones son exitosas
            echo "Evaluación agendada correctamente para el expediente: $exp_alumno.";
        } else {
            echo "Error al insertar en la tabla detalle_evaluaciones.";
        }
    } else {
        // Si hay un error al ejecutar la primera consulta
        echo "Error al agendar evaluación para el expediente: $exp_alumno.";
    }
    
    // Cerrar la conexión a la base de datos
    Cerrar($Con);
} else {
    // Si la solicitud no fue enviada correctamente
    echo "Método de solicitud no válido.";
}
?>
