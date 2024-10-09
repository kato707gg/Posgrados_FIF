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

$clave_coordinador = $_SESSION['id']; // Aquí debes reemplazarlo con el valor correspondiente, si lo tienes en alguna parte de tu sistema

// Consulta SQL para obtener los datos de los estudiantes
$SQL = "
    SELECT
    a.exp_alumno,
    e.nombre,
    e.a_paterno,
    e.a_materno,
    ev.aula,
    ev.fecha_evaluacion
FROM 
    asignaciones a
LEFT JOIN 
    estudiantes e ON a.exp_alumno = e.exp
LEFT JOIN 
    evaluaciones ev ON a.exp_alumno = ev.exp_alumno
LEFT JOIN 
    detalle_evaluaciones de ON (
        de.id_sinodo = $clave_coordinador  AND 
        (
            de.id_sinodo = a.sinodo1 OR
            de.id_sinodo = a.sinodo2 OR
            de.id_sinodo = a.sinodo3 OR
            de.id_sinodo = a.externo
        )
    )
WHERE 
    a.sinodo1 = $clave_coordinador OR a.sinodo2 = $clave_coordinador OR a.sinodo3 = $clave_coordinador OR a.externo = $clave_coordinador;
";
$Resultado = Ejecutar($Con, $SQL);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Header/styles.css">  
    <title>Evaluaciones Pendientes</title>
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
        max-width: 80%;
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

    .container-evaluaciones-pendientes {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 85vh;
        padding: 1rem;
    }

    .confirmar-icon {
        color: #123773;
        margin: auto;
        font-size: 1.5rem;
        padding: 0.5rem 0.9rem;
        background-color: #e0e0e0;
        border: none;
        cursor: pointer;
        border-radius: 0.4rem;
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

<div class="container-evaluaciones-pendientes">
<h3>Evaluaciones pendientes:</h3>
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
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($Resultado->num_rows > 0){
                    while ($Fila = $Resultado->fetch_assoc()){
                        $Nombre = $Fila["nombre"] . " " . $Fila["a_paterno"] . " " . $Fila["a_materno"];
                        echo "<tr>";
                        echo "<td>" . $Fila ["exp_alumno"] . "</td>";
                        echo "<td>" . $Nombre . "</td>";
                        echo "<td>" . $Fila["fecha_evaluacion"] . "</td>";
                        echo "<td>" . $Fila["aula"] . "</td>";
                        // Input para la calificación (tipo número con decimales)
                        echo "<td>";
                        echo "<input type='number' name='calificacion_" . $Fila['exp_alumno'] . "' step='0.01' min='0' max='10' placeholder='Calificación' required>";
                        echo "</td>";

                        // Textarea para observaciones
                        echo "<td>";
                        echo "<textarea style='resize: none;' name='observacion_" . $Fila['exp_alumno'] . "' placeholder='Escribe observaciones aquí' rows='2'></textarea>";
                        echo "</td>";
                        echo "<td><button class='confirmar-icon' onclick='confirmarEvaluacion(\"" . $Fila['exp_alumno'] . "\")'>&#x2714;</button></td>";
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

<script>
    function confirmarEvaluacion(expediente) {
      const fechaSeleccionada = document.getElementById('fecha-' + expediente).value;
      const horaSeleccionada = document.getElementById('hora-' + expediente).value;
      const aula = document.getElementById('aula-' + expediente).value;

      if (!fechaSeleccionada || !horaSeleccionada || !aula) {
        alert('Por favor, selecciona tanto la fecha como la hora antes de confirmar o ingresa el aula.');
        return;
      }

      // Crear una nueva instancia de XMLHttpRequest
      const xhr = new XMLHttpRequest();

      // Configurar la solicitud
      xhr.open('POST', 'insertar_evaluacion.php', true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          alert('Evaluación confirmada exitosamente');
          // alert(xhr.responseText);
          location.reload();
        }
      };
      xhr.send("exp=" + expediente + "&fecha_evaluacion=" + fechaSeleccionada + " " + horaSeleccionada + "&aula=" + aula);

      // Configurar manejo de errores
      xhr.onerror = function() {
        console.error('Error de red');
        alert('Ocurrió un error al procesar la solicitud');
      };
    }
</script>

</body>

</html>