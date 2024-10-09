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
    }

    :root {
        --primary-color: rgb(26,115,232);
        --secondary-color: #aaa;
        --text-color: #3c4043;
        --background-color: #fafcff;
    }

    .container-proximas-evaluacionesS {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 85vh;
        padding: 1rem;
    }

    table {
        table-layout: fixed;
        border-collapse: collapse;
        margin-bottom: 5rem;
        width: 100%;
        max-width: 60%;
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

    @media screen and (max-width: 1600px) {
        .container-agendar-evaluacion {
            height: 75vh;
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

<div class="container-proximas-evaluacionesS">
<h3>Mis evaluaciones:</h3>
    <div id="table-container">
        <table>
            <thead>
                <tr>
                    <th>Número de evaluación</th>
                    <th>Fecha</th>
                    <th>Calificación Final</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar los datos obtenidos de la base de datos
                if ($Resultado) {
                    while ($fila = mysqli_fetch_assoc($Resultado)) {
                        echo "<tr>";
                        echo "<td>" . $fila['id'] . "</td>";
                        echo "<td>" . $fila['fecha_evaluacion'] . "</td>";
                        echo "<td>" . round($fila['promedio_final'], 2) . "</td>";  // Mostrando la calificación final
                        echo "<td><button onclick=\"mostrarDetalles(" . $fila['id'] . ")\">Más</button></td>";  // Botón "Más"
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
