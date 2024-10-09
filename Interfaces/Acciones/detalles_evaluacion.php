<?php
include '../../conexion.php';

$Con = Conectar();
$id_evaluacion = $_GET['id_evaluacion'];

// Verificar que se recibió el ID de la evaluación
if (!$id_evaluacion) {
    echo "No se recibió un ID de evaluación";
    exit();
}

// Consulta SQL para obtener los detalles de los sinodales
$SQL = "SELECT de.calificacion, de.observacion
        FROM detalle_evaluaciones de
        WHERE de.id_evaluacion = '$id_evaluacion'";
$Resultado = mysqli_query($Con, $SQL);

// Crear la tabla de detalles
if ($Resultado && mysqli_num_rows($Resultado) > 0) {
    $sinodales = ["Sinodo 1", "Sinodo 2", "Sinodo 3", "Sinodo 4"];
    $i = 0;

    // Iterar sobre los resultados de la consulta
    while ($fila = mysqli_fetch_assoc($Resultado)) {
        echo "<tr>";
        echo "<td>" . $sinodales[$i] . "</td>";  // Nombre del sinodal
        echo "<td>" . $fila['calificacion'] . "</td>";  // Calificación del sinodal
        echo "<td>" . $fila['observacion'] . "</td>";
        echo "</tr>";
        $i++;
    }
} else {
    echo "<tr><td colspan='3'>No se encontraron detalles para esta evaluación</td></tr>";
}
?>