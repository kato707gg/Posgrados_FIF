<?php
include('../Header/MenuA.php');
?>

<?php
if (session_status() === PHP_SESSION_NONE){
    session_start();
}

include '../../Config/conexion.php';

$Con = Conectar();
$clave_alumno = $_SESSION['id'];

// Consulta SQL para obtener las calificaciones finales de todas las evaluaciones del alumno
$SQL = "SELECT e.id, e.fecha_evaluacion, 
               (SELECT AVG(de.calificacion) 
                FROM detalle_evaluaciones de 
                WHERE de.id_evaluacion = e.id 
                AND de.calificacion IS NOT NULL 
                AND de.calificacion > 0) AS promedio_final 
        FROM evaluaciones e
        WHERE e.exp_alumno = '$clave_alumno'";

$Resultado = mysqli_query($Con, $SQL);

if ($Resultado) {
    while ($fila = mysqli_fetch_assoc($Resultado)) {
        $idEvaluacion = $fila['id'];
        $promedioFinal = $fila['promedio_final'];
        
        // Obtener el número real de calificaciones (excluyendo nulos y sinodales ausentes)
        $SQL_COUNT = "SELECT COUNT(*) as total 
                     FROM detalle_evaluaciones de
                     INNER JOIN asignaciones a ON de.id_sinodo IN (a.sinodo2, a.sinodo3, a.externo)
                     WHERE de.id_evaluacion = '$idEvaluacion' 
                     AND de.calificacion IS NOT NULL
                     AND de.id_sinodo != 0";  // Excluir "Sin sinodo"
        $ResultCount = mysqli_query($Con, $SQL_COUNT);
        $rowCount = mysqli_fetch_assoc($ResultCount);
        $numCalificaciones = $rowCount['total'];
        
        // Obtener la suma de calificaciones
        $SQL_SUM = "SELECT SUM(de.calificacion) as suma 
                    FROM detalle_evaluaciones de
                    INNER JOIN asignaciones a ON de.id_sinodo IN (a.sinodo2, a.sinodo3, a.externo)
                    WHERE de.id_evaluacion = '$idEvaluacion' 
                    AND de.calificacion IS NOT NULL
                    AND de.id_sinodo != 0";  // Excluir "Sin sinodo"
        $ResultSum = mysqli_query($Con, $SQL_SUM);
        $rowSum = mysqli_fetch_assoc($ResultSum);
        $sumaCalificaciones = $rowSum['suma'];
        
        // Calcular el promedio real (dividiendo entre el número real de calificaciones)
        $promedioFinalReal = $numCalificaciones > 0 ? ($sumaCalificaciones / $numCalificaciones) : 0;
        $promedioFinalRedondeado = round($promedioFinalReal, 2);
        
        $SQL_UPDATE = "UPDATE evaluaciones 
                      SET cal_final = '$promedioFinalRedondeado' 
                      WHERE id = '$idEvaluacion' 
                      AND exp_alumno = '$clave_alumno'";
        mysqli_query($Con, $SQL_UPDATE);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/tablas.css">
    <title>Mis evaluaciones</title>
</head>

<style>

.generar-pdf {
        color: #ffffff;
        font-size: 1.7rem;
        padding: 0.2rem 0.5rem 0.4rem;
        background-color: #123773;
        border: none;
        cursor: pointer;
        border-radius: clamp(.4rem, .4vw, .4rem);
        border-bottom: 0.0625rem solid var(--secondary-color);     
        text-decoration: none;
    }

    .generar-pdf:hover {
        background-color: #1455bd;
    }

    .generar-pdf:disabled {
        cursor: not-allowed;
    }

    .generar-pdf:disabled:hover {
        background-color: #123773;
    }

    @media (max-width: 770px) {
        .generar-pdf {
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

                        // Verificar si todos los sinodales asignados han calificado
                        $SQL_VERIFICAR = "SELECT 
                            (SELECT COUNT(DISTINCT de.id_sinodo) 
                             FROM detalle_evaluaciones de
                             INNER JOIN asignaciones a ON de.id_sinodo IN (a.sinodo2, a.sinodo3, a.externo)
                             WHERE de.id_evaluacion = e.id 
                             AND de.calificacion IS NOT NULL
                             AND de.id_sinodo != 0
                             AND de.id_sinodo != a.director
                             AND de.id_sinodo IN (
                                 SELECT CASE WHEN sinodo2 != 0 THEN sinodo2 END
                                 FROM asignaciones WHERE exp_alumno = e.exp_alumno
                                 UNION
                                 SELECT CASE WHEN sinodo3 != 0 THEN sinodo3 END
                                 FROM asignaciones WHERE exp_alumno = e.exp_alumno
                                 UNION
                                 SELECT CASE WHEN externo != 0 THEN externo END
                                 FROM asignaciones WHERE exp_alumno = e.exp_alumno
                             )) as calificaciones_registradas,
                            (SELECT 
                                (CASE WHEN sinodo2 != 0 THEN 1 ELSE 0 END) +
                                (CASE WHEN sinodo3 != 0 THEN 1 ELSE 0 END) +
                                (CASE WHEN externo != 0 THEN 1 ELSE 0 END)
                             FROM asignaciones a 
                             WHERE a.exp_alumno = e.exp_alumno 
                             LIMIT 1) as total_sinodales,
                            (SELECT GROUP_CONCAT(DISTINCT de.id_sinodo) 
                             FROM detalle_evaluaciones de 
                             INNER JOIN asignaciones a ON de.id_sinodo IN (a.sinodo2, a.sinodo3, a.externo)
                             WHERE de.id_evaluacion = e.id
                             AND de.calificacion IS NOT NULL
                             AND de.id_sinodo != 0
                             AND de.id_sinodo != a.director
                             AND de.id_sinodo IN (a.sinodo2, a.sinodo3, a.externo)) as sinodos_calificaron,
                            (SELECT GROUP_CONCAT(DISTINCT 
                                CASE WHEN sinodo2 != 0 THEN sinodo2 END, ',',
                                CASE WHEN sinodo3 != 0 THEN sinodo3 END, ',',
                                CASE WHEN externo != 0 THEN externo END
                             ) 
                             FROM asignaciones a 
                             WHERE a.exp_alumno = e.exp_alumno) as sinodos_asignados
                        FROM evaluaciones e 
                        WHERE e.id = ?";

                        $stmt = mysqli_prepare($Con, $SQL_VERIFICAR);
                        mysqli_stmt_bind_param($stmt, 'i', $fila['id']);
                        mysqli_stmt_execute($stmt);
                        $result_verificacion = mysqli_stmt_get_result($stmt);
                        $verificacion = mysqli_fetch_assoc($result_verificacion);

                        // Debug detallado
                        error_log("ID Evaluación: " . $fila['id']);
                        error_log("Calificaciones registradas: " . $verificacion['calificaciones_registradas']);
                        error_log("Total sinodales: " . $verificacion['total_sinodales']);
                        error_log("Sinodos que calificaron: " . $verificacion['sinodos_calificaron']);
                        error_log("Sinodos asignados: " . $verificacion['sinodos_asignados']);

                        if ($verificacion['calificaciones_registradas'] == $verificacion['total_sinodales'] && $verificacion['calificaciones_registradas'] > 0) {
                            // Si todos calificaron, mostrar la calificación y habilitar el botón
                            echo "<td data-label='Calificación Final'>" . round($fila['promedio_final'], 2) . "</td>";
                            echo "<td data-label='Acta de seminario'><a href='../../libraries/ActaSeminarioTutoral.php?id=" . $fila['id'] . "' target='_blank' class='generar-pdf'>📄</a></td>";
                        } else {
                            // Si faltan calificaciones, mostrar "Pendiente" y deshabilitar el botón
                            echo "<td data-label='Calificación Final'>Pendiente</td>";
                            echo "<td data-label='Acta de seminario'><button class='generar-pdf' disabled title='Pendiente de calificaciones'>📄</button></td>";
                        }

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
