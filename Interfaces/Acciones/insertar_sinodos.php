<?php
// Incluir el archivo de conexión
include '../../conexion.php';

// Conectar a la base de datos
$Con = Conectar();

// Verificar si los datos han sido enviados por POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos enviados desde la función de JavaScript
    $exp_alumno = $_POST['exp']; // Cambié 'exp' a 'exp_alumno' para coincidir con tu campo
    $sinodo1 = $_POST['sinodo1'];
    $sinodo2 = $_POST['sinodo2'];
    $sinodo3 = $_POST['sinodo3'];
    $externo = $_POST['sinodo4']; // En el código JS 'sinodo4' se refiere al sínodo externo
    $clave_coordinador = '4411071968'; // Aquí debes reemplazarlo con el valor correspondiente, si lo tienes en alguna parte de tu sistema

    // Consulta SQL para insertar o actualizar los sinodos asignados en la tabla 'asignaciones'
    $SQL = "INSERT INTO asignaciones (exp_alumno, sinodo1, sinodo2, sinodo3, externo, clave_coordinador) 
            VALUES ('$exp_alumno', '$sinodo1', '$sinodo2', '$sinodo3', '$externo', '$clave_coordinador') 
            ON DUPLICATE KEY UPDATE 
            sinodo1 = '$sinodo1', sinodo2 = '$sinodo2', sinodo3 = '$sinodo3', externo = '$externo', clave_coordinador = '$clave_coordinador'";
    echo $SQL;
    // Ejecutar la consulta
    if (Ejecutar($Con, $SQL)) {
        // Si la inserción es exitosa
        echo "Asignación de sínodos guardada con éxito para el expediente: $exp_alumno.";
    } else {
        // Si hay un error al ejecutar la consulta
        echo "Error al asignar los sínodos para el expediente: $exp_alumno.";
    }
    
    // Cerrar la conexión a la base de datos
    Cerrar($Con);
} else {
    // Si la solicitud no fue enviada correctamente
    echo "Método de solicitud no válido.";
}
?>
