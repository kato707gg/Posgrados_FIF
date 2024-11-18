<?php
include('../Header/MenuA.php');
?>

<?php
if (session_status() === PHP_SESSION_NONE){
    session_start();
}

include '../../conexion.php';

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
    <link rel="stylesheet" href="../Header/styles.css">  
    <title>Mis evaluaciones</title>
</head>

<style>
    body {
        margin: 0;
        padding: 0;
        overflow: hidden;
    }
    :root {
        --primary-color: rgb(26,115,232);
        --secondary-color: #aaa;
        --text-color: #3c4043;
        --background-color: #fafcff;
    }

    .container-proximas-evaluaciones {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 81vh;
        margin: 2vh 2vw;
        padding: 2vh 2vw;
        border-radius: clamp(.4rem, .4vw, .4rem);
        background-color: #e9e9e9;      
    }

    #table-container {
        display: flex;
        justify-content: center;
        overflow-x: auto; /* Habilitar desplazamiento horizontal si es necesario */
        overflow-y: auto; /* Habilitar desplazamiento vertical dentro del contenedor */
        width: 100%;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        max-width: 100%; /* Asegurar que la tabla no sobrepase el contenedor */
    }

    tr {
        border-top: 0.1rem solid var(--primary-color);
        border-bottom: 0.1rem solid var(--secondary-color);
    }

    th, td {
        border-bottom: 0.0625rem solid var(--secondary-color);
        padding: 1.25rem;
    }

    td {
        display: table-cell;
        text-align: center;
        font-family: "Google Sans", Roboto, Arial, sans-serif;
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--text-color);
    }

    th {
        letter-spacing: .01785714em;
        font-family: system-ui;
        font-weight: 600;
        font-size: 1.5rem;
        color: var(--text-color);
        padding-bottom: 2rem;
        padding-top: 3.5rem;
    }

    h1 {
        font-family: "Google Sans", Roboto, Arial, sans-serif;
        text-align: center;
    }

    h3 {
        font-size: 2rem;
        font-family: "Google Sans", Roboto, Arial, sans-serif;
    }

    #title-container {
        display: flex;
        justify-content: space-between;
        width: 100%;
        align-items: center;
    }

    #table-container {
        display: flex !important;
        justify-content: center;
        overflow-x: auto;
    }

    .generar-pdf {
        color: #123773;
        margin: auto;
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

    @media screen and (max-width: 1600px) {
        .container-agendar-evaluacion {
            height: 79vh;
        }
    }

    @media (max-width: 770px) {
        table {
            font-size: 0.9rem;
        }

        th, td {
            font-size: 1.1rem;
            padding: 0.75rem;
        }

        h3 {
            font-size: 1.5rem;
        }

        button {
            height: 2.5rem;
            font-size: 0.9rem;
        }
    }
</style>

<body>

<div class="container-proximas-evaluaciones">
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
                        echo "<td>" . $fila['id'] . "</td>";
                        echo "<td>" . $fila['fecha_evaluacion'] . "</td>";
                        echo "<td>" . round($fila['promedio_final'], 2) . "</td>";  // Mostrando la calificación final
                        echo "<td><button class='generar-pdf' onclick=\"mostrarDetalles(" . $fila['id'] . ")\">📄</button></td>";  // Botón "Más"
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
    xhr.open("GET", "../Acciones/detalles_evaluacion.php?id_evaluacion=" + idEvaluacion, true);
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
