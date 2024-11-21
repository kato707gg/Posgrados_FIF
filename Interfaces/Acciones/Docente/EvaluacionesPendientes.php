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
    <title>Evaluaciones Pendientes</title>
</head>

<style>
    body {
        margin: 0;
        padding: 0;
    }

    :root {
        --primary-color: rgb(26, 115, 232);
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
        overflow-x: auto;
        /* Habilitar desplazamiento horizontal si es necesario */
        overflow-y: auto;
        /* Habilitar desplazamiento vertical dentro del contenedor */
        width: 100%;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        max-width: 100%;
        /* Asegurar que la tabla no sobrepase el contenedor */
    }

    tr {
        border-top: 0.1rem solid var(--primary-color);
        border-bottom: 0.1rem solid var(--secondary-color);
    }

    th,
    td {
        border-bottom: 0.0625rem solid var(--secondary-color);
        padding: 1.25rem;
    }

    th:nth-child(2),
    td:nth-child(2) {
        width: 20vw;
    }

    th:not(:nth-child(2)),
    td:not(:nth-child(2)) {
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

        th,
        td {
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

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
    }

    .modal-content {
        background-color: #fff;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 2rem;
        border-radius: 0.4rem;
        overflow-y: auto;
        width: 100%;
        max-width: 35%;
        max-height: 70%;
        overflow-x: hidden;
    }

    .close {
        position: absolute;
        top: 0;
        right: .6rem;
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .label-observaciones {
        letter-spacing: .01785714em;
        font-family: system-ui;
        font-weight: 600;
        font-size: 1.5rem;
        color: var(--text-color);
    }

    .observacion-input {
        font-family: "Google Sans", Roboto, Arial, sans-serif;
        font-size: 0.9rem;
        font-weight: 500;
        width: 98%;
        height: 4rem;
        margin: .5rem 0 1rem;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: clamp(.4rem, .4vw, .4rem);
        resize: none;
    }

    .confirmar-button {
        display: flex;
        font-size: 1.3rem;
        font-family: "Google Sans", Roboto, Arial, sans-serif;
        padding: 0.7rem 3rem;
        background-color: #123773;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: clamp(.4rem, .4vw, .4rem);
        margin: auto;
    }


    .confirmar-button.disabled {
        background-color: grey;
        cursor: not-allowed;
        opacity: 0.6;
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
                        <th>Entergable</th>
                        <th>Calificación</th>
                        <th>Observaciones</th>
                        <th>Acción</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($Resultado->num_rows > 0) {
                        while ($Fila = $Resultado->fetch_assoc()) {
                            $Nombre = $Fila["nombre"] . " " . $Fila["a_paterno"] . " " . $Fila["a_materno"];
                            $Fecha = $Fila["fecha_evaluacion"];
                            $Expediente = $Fila['exp_alumno'];

                            // Separar fecha y hora
                            $FechaSola = !empty($Fecha) ? date('Y-m-d', strtotime($Fecha)) : "Pendiente";

                            if ((int)substr($FechaSola, 5, 2) < 6) {
                                $Periodo = substr($FechaSola, 0, 4) . "-" . "2";
                            } else {
                                $Periodo = substr($FechaSola, 0, 4) . "-" . "1";
                            }

                            // Obtener lista de archivos en la carpeta de entregables
                            $entregables = [];
                            $dir = "../../../docs/$Expediente/entregables/";
                            if (is_dir($dir)) {
                                $files = scandir($dir);
                                foreach ($files as $file) {
                                    if ($file !== '.' && $file !== '..') {
                                        $entregables[] = "<a href='$dir$file' target='_blank'>$file</a>";
                                    }
                                }
                            }

                            // Generar contenido para la columna "Entregable"
                            $entregablesContent = empty($entregables) ? "No disponible" : implode(", ", $entregables);

                            echo "<input type='hidden' id='periodo_" . $Expediente . "' value='" . $Periodo . "'>";
                            echo "<tr data-expediente='" . $Expediente . "'>";
                            echo "<td>" . $Expediente . "</td>";
                            echo "<td>" . $Nombre . "</td>";
                            echo "<td>" . $FechaSola . "</td>";
                            echo "<td>" . (!empty($Fila["aula"]) ? $Fila["aula"] : "Pendiente") . "</td>";
                            echo "<td>" . $entregablesContent . "</td>";
                            echo "<td>";
                            echo "<input type='number' class='inputs' name='calificacion_" . $Expediente . "' id='calificacion_" . $Expediente . "' step='0.01' min='0' max='10' placeholder='Calificación...' required onchange='checkFields(\"" . $Expediente . "\")' oninput='limitDigits(this, 4)'>";
                            echo "</td>";

                            if ($esDirector) {
                                echo "<td><button class='observaciones' onclick='abrirModal(\"" . $Expediente . "\", true)'>Agregar</button></td>";
                            } else {
                                echo "<td><button class='observaciones' onclick='abrirModal(\"" . $Expediente . "\", false)'>Agregar</button></td>";
                            }

                            echo "<td><button class='confirmar-icon' id='btn_" . $Expediente . "' onclick='actualizarEvaluacion(\"" . $Expediente . "\")' disabled>&#x2714;</button></td>";
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

    <div id="modalObservaciones" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Observaciones y recomendaciones</h3>
            <hr class="x-component x-component-default" style="border-top: 0;border-bottom: 0.05rem solid #196ad3;margin:auto;width: 100%;margin-bottom: 2rem;" id="box-1034">
            <div id="observacionesContent">
                <!-- El contenido se llenará dinámicamente -->
            </div>
            <button onclick="guardarObservaciones()" class="confirmar-button">Guardar</button>
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

       

        function actualizarEvaluacion(expediente) {


            const calificacion = document.getElementById('calificacion_' + expediente).value;
            const observacion = document.getElementById('observacion_' + expediente);
            const periodo = document.getElementById('periodo_' + expediente).value;
            const split = observacion.split('|');
                const d_observacion1 = split[0];
                const d_observacion2 = split[1];
                const d_observacion3 = split[2];
                const observacion4 = split[3];

            if (esDirectorModal) {

                const xhr = new XMLHttpRequest();
                console.log(d_observacion1);


                xhr.open('POST', '../actualizar_detalle_evaluaciones.php', true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");


                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var fila = document.querySelector(`tr[data-expediente="${expediente}"]`);
                        console.log(fila); // Para verificar si la fila fue seleccionada correctamente
                        if (fila) {
                            fila.remove();
                            alert('Evaluación actualizada exitosamente');
                            console.log(d_observacion1);
                        } else {
                            console.error('No se encontró la fila para el expediente: ' + expediente);
                        }
                    }
                };

                xhr.send("expediente=" + expediente + "&calificacion=" + 0 + "&observacion=" +
                    encodeURIComponent(d_observacion4) + "&periodo=" + encodeURIComponent(periodo) +
                    "&d_observacion1=" + encodeURIComponent(d_observacion2) + "&d_observacion2" + encodeURIComponent(d_observacion3));

                // Configurar manejo de errores
                xhr.onerror = function() {
                    console.error('Error de red');
                    alert('Ocurrió un error al actualizar la evaluación');
                };

            } else {
                // Crear una nueva instancia de XMLHttpRequest
                const xhr = new XMLHttpRequest();

                // Configurar la solicitud
                xhr.open('POST', '../actualizar_detalle_evaluaciones.php', true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                // Función a ejecutar cuando la solicitud cambie de estado
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var fila = document.querySelector(`tr[data-expediente="${expediente}"]`);
                        console.log(fila); // Para verificar si la fila fue seleccionada correctamente
                        if (fila) {
                            fila.remove();
                            alert('Evaluación actualizada exitosamente');
                            console.log(d_observacion1);
                        } else {
                            console.error('No se encontró la fila para el expediente: ' + expediente);
                        }
                    }
                };


                // Enviar los datos a actualizar
                xhr.send("expediente=" + expediente + "&calificacion=" + calificacion + "&observacion=" + 
                encodeURIComponent(observacion) + "&periodo=" + encodeURIComponent(periodo));

                // Configurar manejo de errores
                xhr.onerror = function() {
                    console.error('Error de red');
                    alert('Ocurrió un error al actualizar la evaluación');
                };

            }



        }



        function limitDigits(input, maxDigits) {
            if (input.value.length > maxDigits) {
                input.value = input.value.slice(0, maxDigits);
            }
        }

        let expedienteActual = '';
        let esDirectorModal = false;

        function abrirModal(expediente, esDirector) {
            expedienteActual = expediente;
            esDirectorModal = esDirector;
            const modal = document.getElementById('modalObservaciones');
            const contenido = document.getElementById('observacionesContent');

            // Get the existing observations
            const existingObservations = document.getElementById('observacion_' + expediente)?.value || '';

            contenido.innerHTML = '';

            if (esDirector) {
                // Split the observations if they exist
                const obsSections = existingObservations.split('|');

                contenido.innerHTML += `
            <div>
                <label class='label-observaciones'>Sobre Avance gradual:</label>
                <textarea class="observacion-input" id="obs1_${expediente}" rows="3">${obsSections[0] || ''}</textarea>
            </div>
            <div>
                <label class='label-observaciones'>Sobre entregable y resultados esperados de acuerdo con el semestre a evaluar:</label>
                <textarea class="observacion-input" id="obs2_${expediente}" rows="3">${obsSections[1] || ''}</textarea>
            </div>
            <div>
                <label class='label-observaciones'>Sobre el Avance del Proyecto:</label>
                <textarea class="observacion-input" id="obs3_${expediente}" rows="3">${obsSections[2] || ''}</textarea>
            </div>
            <div>
                <label class='label-observaciones'>Comentario Individual:</label>
                <textarea class="observacion-input" id="obs4_${expediente}" rows="3">${obsSections[3] || ''}</textarea>
            </div>`;
            } else {
                // Single textarea for non-director
                contenido.innerHTML = `
            <div>
                <textarea class="observacion-input" id="obs_${expediente}" rows="3">${existingObservations}</textarea>
            </div>`;
            }

            modal.style.display = "block";
        }

        function guardarObservaciones() {
            let observaciones = '';
            // Create the hidden observation input if it doesn't exist
            let observacionInput = document.getElementById('observacion_' + expedienteActual);
            if (!observacionInput) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.id = 'observacion_' + expedienteActual;
                document.body.appendChild(hiddenInput);
                observacionInput = hiddenInput;
            }

            if (esDirectorModal) {
                // Safely get values or use empty string if element doesn't exist
                const getObservationValue = (id) => {
                    const el = document.getElementById(id);
                    return el ? el.value : '';

                };

                // Concatenar las 4 observaciones para director
                observaciones = [
                    getObservationValue(`obs1_${expedienteActual}`),
                    getObservationValue(`obs2_${expedienteActual}`),
                    getObservationValue(`obs3_${expedienteActual}`),
                    getObservationValue(`obs4_${expedienteActual}`)
                ].join('|');

                





            } else {
                // One observation for non-director
                let obsInput = document.getElementById(`obs_${expedienteActual}`);
                observaciones = obsInput.value;
                observaciones = obsInput ? obsInput.value : '';
            }

            console.log(esDirectorModal);

            // Set the value of the hidden input
            observacionInput.value = observaciones;

            // Check if all required fields are filled
            checkFields(expedienteActual);

            // Close the modal
            cerrarModal();
        }

        function cerrarModal() {
            const modal = document.getElementById('modalObservaciones');
            modal.style.display = "none";
        }

        // Cerrar modal al hacer clic en la X
        document.querySelector('.close').onclick = cerrarModal;
    </script>

</body>

</html>