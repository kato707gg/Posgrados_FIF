<?php
include('../../Header/MenuA.php');
?>

<?php
if (session_status() === PHP_SESSION_NONE){
    session_start();
}

include '../../../conexion.php';

$Con = Conectar();
$clave_alumno = $_SESSION['id'];

// Consulta SQL para obtener las calificaciones finales de todas las evaluaciones del alumno
$SQL = "SELECT e.id, e.fecha_evaluacion, 
               (SELECT AVG(de.calificacion) FROM detalle_evaluaciones de WHERE de.id_evaluacion = e.id) AS promedio_final 
        FROM evaluaciones e
        WHERE e.exp_alumno = '$clave_alumno'";

$Resultado = mysqli_query($Con, $SQL);

if ($Resultado) {
    while ($fila = mysqli_fetch_assoc($Resultado)) {
        $idEvaluacion = $fila['id'];
        $promedioFinal = $fila['promedio_final'];

        // Redondeamos el promedio a 2 decimales, por ejemplo:
        $promedioFinalRedondeado = round($promedioFinal, 2);

        // Consulta para actualizar el campo cal_final con el promedio calculado
        $SQL_UPDATE = "UPDATE evaluaciones SET cal_final = '$promedioFinalRedondeado' WHERE id = '$idEvaluacion' AND exp_alumno = '$clave_alumno'";

        // Ejecutar la consulta de actualización
        mysqli_query($Con, $SQL_UPDATE);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../tablas.css">
    <title>Mis evaluaciones</title>
</head>

<style>

    .generar-pdf {
        color: #123773;
        font-size: 1.7rem;
        padding: 0.2rem 0.5rem 0.4rem;
        background-color: #ffffff;
        border: none;
        cursor: pointer;
        border-radius: clamp(.4rem, .4vw, .4rem);
        border-bottom: 0.0625rem solid var(--secondary-color);     
    }

    .generar-pdf:hover {
        background-color: #cfcfcf;
    }

    @media (max-width: 770px) {
        .generar-pdf {
        background-color: #123773;
        color: white;
        padding: 0.7rem 0.9rem;
        border: none;
        cursor: pointer;
        }

        .generar-pdf::before {
        font-family: "Google Sans", Roboto, Arial, sans-serif;
        font-size: 1.1rem;
        font-weight: 600;
        content: 'Generar';
        }

        .generar-pdf {
        font-size: 0;  
        }
    }

</style>

<body>

<div class="container-principal">
<h3>Mis evaluaciones:</h3>
    <div id="table-container">
        <table>
            <thead>
                <tr>
                    <th>Número de evaluación</th>
                    <th>Fecha</th>
                    <th>Calificación Final</th>
                    <th>Acta de seminario</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar los datos obtenidos de la base de datos
                if ($Resultado) {
                    mysqli_data_seek($Resultado, 0);
                    while ($fila = mysqli_fetch_assoc($Resultado)) {
                        echo "<tr>";
                        echo "<td data-label='Número de evaluación'>" . $fila['id'] . "</td>";
                        echo "<td data-label='Fecha'>" . date("Y-m-d", strtotime($fila['fecha_evaluacion'])) . "</td>";
                        echo "<td data-label='Calificación Final'>" . round($fila['promedio_final'], 2) . "</td>";  // Mostrando la calificación final
                        echo "<td data-label='Acta de seminario'><button class='generar-pdf' onclick=\"mostrarDetalles(" . $fila['id'] . ")\">📄</button></td>";
                        echo "</tr>";
                    }
                    
                } else {
                    echo "<tr><td colspan='4'>No se encontraron evaluaciones</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Aquí es donde se mostrará la tabla de detalles -->
<div id="detalles-container" style="display:none; padding:20px;">
    <h3>Detalles de la evaluación:</h3>
    <table>
        <thead>
            <tr>
                <th>Sinodo</th>
                <th>Calificación</th>
                <th>Observación</th>
            </tr>
        </thead>
        <tbody id="detalles-tbody">
            <!-- Aquí se llenarán los datos dinámicamente -->
        </tbody>
    </table>
</div>

<script>
function mostrarDetalles(idEvaluacion) {
    // Realizar una petición AJAX para obtener los detalles de la evaluación
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "../detalles_evaluacion.php?id_evaluacion=" + idEvaluacion, true);
    xhr.onload = function() {
        if (this.status == 200) {
            document.getElementById('detalles-container').style.display = 'block';
            document.getElementById('detalles-tbody').innerHTML = this.responseText;
        }
    };
    xhr.send();
}
</script>

</body>

</html>
