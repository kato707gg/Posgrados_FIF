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
    <link rel="stylesheet" href="../tablas.css">
    <title>Evaluaciones Pendientes</title>
</head>

<style>
    .inputs {
        font-family: "Google Sans", Roboto, Arial, sans-serif;
        height: 2vh;
        border-bottom: 1px solid #636363;
        outline: none;
        font-size: 1rem;
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

    @media (max-width: 770px) {
        .inputs {
            width: 16vw;
        }

        .modal-content {
            max-width: 80%;
        }

        .observacion-input {
            height: 6rem;
        }

        .observaciones {
            color: white;
            width: 20vw;
            background-color: #123773;
        }

        .confirmar-icon {
            background-color: #118f1d;
            color: white;
            padding: 0.7rem 0.9rem;
            border: none;
            cursor: pointer;
        }

        .confirmar-icon::before {
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            content: 'Confirmar';
        }

        .confirmar-icon {
            font-size: 0;
        }
    }
</style>

<body>
    <div class="container-principal">
        <h3>Evaluaciones pendientes:</h3>
        <div id="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Expediente</th>
                        <th>Nombre</th>
                        <th>Fecha</th>
                        <th>Aula</th>
                        <th>Entregable</th>
                        <th>Calificación</th>
                        <th>Observaciones</th>
                        <th>Confirmar</th>

                    </tr>
                </thead>
                <tbody>
                    <?php

                    if ($Resultado->num_rows > 0) {
                        while ($Fila = $Resultado->fetch_assoc()) {
                            $Expediente = $Fila['exp_alumno'];

                            // Obtener lista de archivos en la carpeta de entregables
                            $entregables = [];
                            $dir = "../Posgrados_FIF/Interfaces/Acciones/Alumno/docs/$Expediente/entregables/";
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

                            // Generar nombre completo"
                            $Nombre = $Fila["nombre"] . " " . $Fila["a_paterno"] . " " . $Fila["a_materno"];

                            // Separar fecha y hora
                            $Fecha = $Fila["fecha_evaluacion"];
                            $FechaSola = !empty($Fecha) ? date('Y-m-d', strtotime($Fecha)) : "Pendiente";

                            if ((int)substr($FechaSola, 5, 2) < 6) {
                                $Periodo = substr($FechaSola, 0, 4) . "-" . "2";
                            } else {
                                $Periodo = substr($FechaSola, 0, 4) . "-" . "1";
                            }

                            echo "<input type='hidden' id='periodo_" . $Fila['exp_alumno'] . "' value='" . $Periodo . "'>";
                            echo "<tr data-expediente='" . $Fila['exp_alumno'] . "'>";
                            echo "<td data-label='Expediente'>" . $Fila["exp_alumno"] . "</td>";
                            echo "<td data-label='Nombre'>" . $Nombre . "</td>";
                            echo "<td data-label='Fecha'>" . $FechaSola . "</td>";
                            echo "<td data-label='Aula'>" . (!empty($Fila["aula"]) ? $Fila["aula"] : "Pendiente") . "</td>";
                            echo "<td data-label='Entregable'>" . $entregablesContent . "</td>";
                            if ($esDirector) {
                                echo "<td data-label='Calificación'>N/A</td>";
                            } else {
                                echo "<td data-label='Calificación'>";
                                echo "<input type='number' class='inputs' name='calificacion_" . $Fila['exp_alumno'] . "' id='calificacion_" . $Fila['exp_alumno'] . "' step='0.01' min='0' max='10' placeholder='Calificación...' required onchange='checkFields(\"" . $Fila['exp_alumno'] . "\")' oninput='limitDigits(this, 4)'>";
                                echo "</td>";
                            }

                            if ($esDirector) {
                                echo "<td data-label='Observaciones'><button class='observaciones' onclick='abrirModal(\"" . $Expediente . "\", true)'>Agregar</button></td>";
                            } else {
                                echo "<td data-label='Observaciones'><button class='observaciones' onclick='abrirModal(\"" . $Expediente . "\", false)'>Agregar</button></td>";
                            }

                            echo "<td data-label='Acción'><button class='confirmar-icon' id='btn_" . $Expediente . "' onclick='actualizarEvaluacion(\"" . $Expediente . "\")' disabled>&#x2714;</button></td>";
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
            const btn = document.getElementById('btn_' + expediente);
            const fila = document.querySelector(`tr[data-expediente="${expediente}"]`);
            const fecha = fila.querySelector('td:nth-child(3)').innerText;
            const aula = fila.querySelector('td:nth-child(4)').innerText;

            if (esDirectorModal) {
                // Para director: solo verificar observaciones
                const obs1 = document.getElementById('obs1_' + expediente)?.value;
                const obs2 = document.getElementById('obs2_' + expediente)?.value;
                const obs3 = document.getElementById('obs3_' + expediente)?.value;
                const obs4 = document.getElementById('obs4_' + expediente)?.value;

                if (obs1 && obs2 && obs3 && obs4 && fecha !== "Pendiente" && aula !== "Pendiente") {
                    btn.disabled = false;
                } else {
                    btn.disabled = true;
                }
            } else {
                // Para no director: verificar calificación y observación
                const calificacion = document.getElementById('calificacion_' + expediente)?.value;
                const observacion = document.getElementById('observacion_' + expediente)?.value;

                if (calificacion && observacion && fecha !== "Pendiente" && aula !== "Pendiente") {
                    btn.disabled = false;
                } else {
                    btn.disabled = true;
                }
            }
        }



        function actualizarEvaluacion(expediente) {
            const periodo = document.getElementById('periodo_' + expediente).value;
            let formData = new FormData();

            formData.append('expediente', expediente);
            formData.append('periodo', periodo);
            formData.append('esDirector', esDirectorModal);

            if (!esDirectorModal) {
                const calificacion = document.getElementById('calificacion_' + expediente).value;
                formData.append('calificacion', calificacion);
            }

            if (esDirectorModal) {
                // Para director: obtener las cuatro observaciones
                const obs1 = document.getElementById('obs1_' + expediente)?.value || '';
                const obs2 = document.getElementById('obs2_' + expediente)?.value || '';
                const obs3 = document.getElementById('obs3_' + expediente)?.value || '';
                const obs4 = document.getElementById('obs4_' + expediente)?.value || '';

                formData.append('observacion', obs4);
                formData.append('d_observacion1', obs1);
                formData.append('d_observacion2', obs2);
                formData.append('d_observacion3', obs3);
            } else {
                // Para no director: obtener observación única
                const observacion = document.getElementById('observacion_' + expediente)?.value || '';
                formData.append('observacion', observacion);
            }

            fetch('../actualizar_detalle_evaluaciones.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data.includes('success')) {
                        const fila = document.querySelector(`tr[data-expediente="${expediente}"]`);
                        if (fila) {
                            fila.remove();
                            alert('Evaluación actualizada exitosamente');
                        }
                    } else {
                        console.error('Error en la respuesta:', data);
                        alert('Ocurrió un error al actualizar la evaluación');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ocurrió un error al actualizar la evaluación');
                });
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

            // Recuperar las observaciones existentes desde los inputs ocultos
            const existingObservations = document.getElementById('observacion_' + expediente)?.value || '';

            contenido.innerHTML = '';

            if (esDirector) {
                // Dividir las observaciones existentes (separadas por "|")
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
                // Una sola área de texto para no directores
                contenido.innerHTML = `
        <div>
            <textarea class="observacion-input" id="obs_${expediente}" rows="3">${existingObservations}</textarea>
        </div>`;
            }

            modal.style.display = "block";
        }

        function guardarObservaciones() {
            let observacionInput = document.getElementById('observacion_' + expedienteActual);
            if (!observacionInput) {
                // Crear el input oculto si no existe
                observacionInput = document.createElement('input');
                observacionInput.type = 'hidden';
                observacionInput.id = 'observacion_' + expedienteActual;
                document.body.appendChild(observacionInput);
            }

            if (esDirectorModal) {
                // Recuperar y concatenar las observaciones en una cadena separada por "|"
                const obs1 = document.getElementById(`obs1_${expedienteActual}`)?.value || '';
                const obs2 = document.getElementById(`obs2_${expedienteActual}`)?.value || '';
                const obs3 = document.getElementById(`obs3_${expedienteActual}`)?.value || '';
                const obs4 = document.getElementById(`obs4_${expedienteActual}`)?.value || '';

                observacionInput.value = [obs1, obs2, obs3, obs4].join('|'); // Guardar todas las observaciones juntas
            } else {
                // Para no directores, guardar la observación única
                const obsInput = document.getElementById(`obs_${expedienteActual}`);
                if (obsInput) {
                    observacionInput.value = obsInput.value;
                } else {
                    console.error("No se encontró el campo de observación para expediente: " + expedienteActual);
                }
            }

            // Llamar a checkFields solo si los elementos necesarios existen
            if (observacionInput && observacionInput.value) {
                checkFields(expedienteActual); // Validación o actualización en la base de datos
            } else {
                console.warn("No se guardaron observaciones porque no existen datos.");
            }

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