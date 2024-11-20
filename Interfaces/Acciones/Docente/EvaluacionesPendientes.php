<?php
include('../../Header/MenuD.php');

// Verificar si ya hay una sesión activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de conexión
include '../../../conexion.php';

// Conectar a la base de datos
$Con = Conectar();

$id_sinodo = $_SESSION['id'];

// Consulta principal para obtener evaluaciones pendientes
$SQL = "
    SELECT DISTINCT
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
            ev.id = de.id_evaluacion AND
            de.id_sinodo = $id_sinodo
        )
    WHERE 
        (a.director = $id_sinodo OR a.sinodo2 = $id_sinodo OR 
        a.sinodo3 = $id_sinodo OR a.externo = $id_sinodo)
        AND (de.id_evaluacion IS NULL OR ((de.calificacion IS NULL OR de.calificacion = 0) AND de.observacion IS NULL))
";

// Verificar si el usuario es director
$SQL2 = "SELECT director FROM asignaciones WHERE director = '$id_sinodo'";
$esDirector = false;
$resultadoDirector = Ejecutar($Con, $SQL2);

// Si la consulta encontró que el usuario es director, actualiza $esDirector a true
if ($resultadoDirector->num_rows > 0) {
    $esDirector = true;
}

$Resultado = Ejecutar($Con, $SQL);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Header/styles.css">  
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

    .container-evaluaciones-pendientes {
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
    th:nth-child(2), td:nth-child(2) {
        width: 20vw;
    }
    th:not(:nth-child(2)), td:not(:nth-child(2)) {
        width: 10vw;
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

    .inputs {
        font-family: "Google Sans", Roboto, Arial, sans-serif;
        height: 2vh;
        border-bottom: 1px solid #636363;
        outline: none;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-color);
        border: 1px solid #ccc;
        padding: 1rem 0.5rem;
        border-radius: clamp(.4rem, .4vw, .4rem);
    }

    .observaciones {
        font-size: 1rem;
        font-family: "Google Sans", Roboto, Arial, sans-serif;
        padding: 0.8rem 0.9rem;
        background-color: #ffffff;
        border: none;
        cursor: pointer;
        color: var(--text-color);
        border-radius: clamp(.4rem, .4vw, .4rem);
        border-bottom: 0.0625rem solid var(--secondary-color);
    }

    .observaciones:hover {
        background-color: #cfcfcf;
    }

    .confirmar-icon {
        color: #123773;
        margin: auto;
        font-size: 1.5rem;
        padding: 0.5rem 0.9rem;
        background-color: #ffffff;
        border: none;
        cursor: pointer;
        border-radius: clamp(.4rem, .4vw, .4rem);
        border-bottom: 0.0625rem solid var(--secondary-color);
    }

    .confirmar-icon:hover {
        background-color: #cfcfcf;
    }

    @media screen and (max-width: 1600px) {

        .container-evaluaciones-pendientes {
            height: 79vh;
        }

    }

    @media screen and (max-width: 820px) {
        .container-evaluaciones-pendientes {
            height: 83.5vh;
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
                        $Fecha = $Fila ["fecha_evaluacion"];

                        if ((int)substr($Fecha, 1, 2) < 6){
                            $Periodo = substr($Fecha, 0, 4) . "-" .  + "2"; 
                        }else {
                            $Periodo = substr($Fecha, 0, 4) . "-" . "1"; 
                        }

                        echo "<input type='hidden' id='periodo_" . $Fila['exp_alumno'] . "' value='" . $Periodo . "'>";
                        echo "<tr data-expediente='" . $Fila['exp_alumno'] . "'>";
                        echo "<td>" . $Fila ["exp_alumno"] . "</td>";
                        echo "<td>" . $Nombre . "</td>";
                        echo "<td>" . (!empty($Fecha) ? $Fecha : "Pendiente") . "</td>";
                        echo "<td>" . (!empty($Fila["aula"]) ? $Fila["aula"] : "Pendiente") . "</td>";
                        
                        echo "<td>";
                        echo "<input type='number' class='inputs' name='calificacion_" . $Fila['exp_alumno'] . "' id='calificacion_" . $Fila['exp_alumno'] . "' step='0.01' min='0' max='10' placeholder='Calificación...' required onchange='checkFields(\"" . $Fila['exp_alumno'] . "\")' oninput='limitDigits(this, 4)'>";
                        echo "</td>";
                    
                        //Botón del Director, Falta abrir el modal para poder poner los comentarios
                        if ($esDirector) {
                            echo "<td><button class='observaciones' onclick='opcionDirector(\"" . $Fila['exp_alumno'] . "\")'>Opciones</button></td>";
                        }else{
                            echo "<td>";
                            echo "<textarea class='inputs' style='resize: none;' name='observacion_" . $Fila['exp_alumno'] . "' id='observacion_" . $Fila['exp_alumno'] . "' placeholder='Escribe aquí...' rows='3' onchange='checkFields(\"" . $Fila['exp_alumno'] . "\")'></textarea>";
                            echo "</td>";
                        }
                        // 
                        echo "<td><button class='confirmar-icon' id='btn_" . $Fila['exp_alumno'] . "' onclick='actualizarEvaluacion(\"" . $Fila['exp_alumno'] . "\")' disabled>&#x2714;</button></td>";
                       
                        echo "</tr>";
                    }
                    
                } else {
                    echo "<tr><td colspan='8'>No se encontraron evaluaciones pendientes</td></tr>";
                }
                Cerrar($Con);
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function checkFields(expediente) {
    const calificacion = document.getElementById('calificacion_' + expediente).value;
    const observacion = document.getElementById('observacion_' + expediente).value;
    const btn = document.getElementById('btn_' + expediente);

    const fila = document.querySelector(`tr[data-expediente="${expediente}"]`);
    const fecha = fila.querySelector('td:nth-child(3)').innerText;
    const aula = fila.querySelector('td:nth-child(4)').innerText;

    if (calificacion && observacion && fecha !== "Pendiente" && aula !== "Pendiente") {
        btn.disabled = false;
    } else {
        btn.disabled = true;
    }
}

function opcionDirector(expediente) {
    alert("Opciones adicionales para el director para el expediente: " + expediente);
}

function actualizarEvaluacion(expediente) {
    const calificacion = document.getElementById('calificacion_' + expediente).value;
    const observacion = document.getElementById('observacion_' + expediente).value;
    const periodo = document.getElementById('periodo_' + expediente).value;

    // Crear una nueva instancia de XMLHttpRequest
    const xhr = new XMLHttpRequest();

    // Configurar la solicitud
    xhr.open('POST', 'actualizar_detalle_evaluaciones.php', true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Función a ejecutar cuando la solicitud cambie de estado
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var fila = document.querySelector(`tr[data-expediente="${expediente}"]`);
            console.log(fila);  // Para verificar si la fila fue seleccionada correctamente
            if (fila) {
                fila.remove();
                alert('Evaluación actualizada exitosamente');
            } else {
                console.error('No se encontró la fila para el expediente: ' + expediente);
            }
        }
    };


    // Enviar los datos a actualizar
    xhr.send("expediente=" + expediente + "&calificacion=" + calificacion + "&observacion=" + encodeURIComponent(observacion) +"&periodo=" + encodeURIComponent(periodo));

    // Configurar manejo de errores
    xhr.onerror = function() {
        console.error('Error de red');
        alert('Ocurrió un error al actualizar la evaluación');
    };
}

function limitDigits(input, maxDigits) {
    if (input.value.length > maxDigits) {
        input.value = input.value.slice(0, maxDigits);
    }
}
</script>

</body>
</html>
