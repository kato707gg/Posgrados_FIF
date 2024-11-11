<?php
  include('../Header/MenuD.php');
// Verificar si ya hay una sesión activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de conexión
include '../../conexion.php';

// Conectar a la base de datos
$Con = Conectar();

$clave_coordinador = $_SESSION['id'];
$SQL = "
SELECT DISTINCT
    a.exp_alumno,
    e.nombre,
    e.a_paterno,
    e.a_materno,
    ev.aula,
    ev.fecha_evaluacion,
    de.calificacion,
    de.observacion
FROM 
    asignaciones a
LEFT JOIN 
    estudiantes e ON a.exp_alumno = e.exp
LEFT JOIN 
    evaluaciones ev ON a.exp_alumno = ev.exp_alumno
INNER JOIN 
    detalle_evaluaciones de ON (
        ev.id = de.id_evaluacion AND
        de.id_sinodo = $clave_coordinador
    )
WHERE 
    (a.sinodo1 = $clave_coordinador OR a.sinodo2 = $clave_coordinador OR 
    a.sinodo3 = $clave_coordinador OR a.externo = $clave_coordinador)
    AND de.calificacion != 0
    AND de.calificacion IS NOT NULL
    AND de.observacion IS NOT NULL
  ";
$Resultado = Ejecutar($Con, $SQL);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Header/styles.css">  
    <title>Historial de Evaluaciones</title>
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

    table {
        table-layout: fixed;
        border-collapse: collapse;
        margin-bottom: 5rem;
        width: 100%;
        max-width: 60%;;
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

    .container-proximas-evaluacionesS {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 85vh;
        padding: 1rem;
    }

    
    @media screen and (max-width: 1600px) {

        .container-agendar-evaluacion {
            height: 75vh;
        }

    }

    @media (max-width: 48rem) {
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
<h3>Historial de evaluaciones:</h3>
    <div id="table-container">
        <table>
            <thead>
                <tr>
                <th>Expediente</th>
                    <th>Nombre</th>
                    <th>Fecha</th>
                    <th>Aula</th>
                    <th>Calificación</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($Resultado->num_rows > 0){
                    while ($Fila = $Resultado->fetch_assoc()){
                        $Nombre = $Fila["nombre"] . " " . $Fila["a_paterno"] . " " . $Fila["a_materno"];
                        echo "<tr data-expediente='" . $Fila['exp_alumno'] . "'>";
                        echo "<td>" . $Fila ["exp_alumno"] . "</td>";
                        echo "<td>" . $Nombre . "</td>";
                        echo "<td>" . (!empty($Fila["fecha_evaluacion"]) ? $Fila["fecha_evaluacion"] : "Pendiente") . "</td>";
                        echo "<td>" . (!empty($Fila["aula"]) ? $Fila["aula"] : "Pendiente") . "</td>";
                        
                        echo "<td>" . $Fila["calificacion"] . "</td>";
                        echo "<td>" . $Fila["observacion"] . "</td>";
                        echo "</tr>";
                    }
                    
                } else {
                    echo "<tr><td colspan='7'>No se encontraron evaluaiones pendientes</td></tr>";
                }
                Cerrar($Con);
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>

</html>