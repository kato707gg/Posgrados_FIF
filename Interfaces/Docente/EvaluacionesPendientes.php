<?php
include('../Header/MenuD.php');

// Verificar si ya hay una sesión activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de conexión
include('../../Config/conexion.php');

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
        ev.fecha_evaluacion,
        CASE 
            WHEN a.director = $id_sinodo THEN 1
            ELSE 0
        END as es_director
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

$Resultado = Ejecutar($Con, $SQL);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/tablas.css">
    <title>Evaluaciones Pendientes</title>
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
            padding: 0.8rem 0.9rem;
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
            color: #ffffff;
            font-size: 1.5rem;
            padding: 0.5rem 0.9rem;
            background-color: #123773;
            border: none;
            cursor: pointer;
            border-radius: clamp(.4rem, .4vw, .4rem);
            border-bottom: 0.0625rem solid var(--secondary-color);
        }

        .confirmar-icon:disabled {
            cursor: not-allowed;
        }

        .confirmar-icon:hover {
            background-color: #1455bd;
        }

        .confirmar-icon:disabled:hover {
            background-color: #123773;
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
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.show {
            opacity: 1;
        }

        .modal-content {
            background-color: #fff;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.7);
            padding: 2rem;
            border-radius: 0.4rem;
            overflow-y: auto;
            width: 100%;
            max-width: 35%;
            max-height: 70%;
            overflow-x: hidden;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .modal.show .modal-content {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
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
            color: #555;
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            font-size: 1rem;
            font-weight: 500;
            width: 98%;
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
</head>

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
                            $esDirector = $Fila['es_director'] == 1;

                            // Obtener lista de archivos en la carpeta de entregables
                            $entregables = [];
                            $dir = "../Posgrados_FIF/Interfaces/Alumno/docs/$Expediente/entregables/";
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

                            // Generar nombre completo
                            $Nombre = $Fila["nombre"] . " " . $Fila["a_paterno"] . " " . $Fila["a_materno"];

                            // Separar fecha y hora
                            $Fecha = $Fila["fecha_evaluacion"];
                            $FechaSola = !empty($Fecha) ? date('Y-m-d', strtotime($Fecha)) : "Pendiente";

                            if ((int)substr($FechaSola, 5, 2) < 6) {
                                $Periodo = substr($FechaSola, 0, 4) . "-" . "2";
                            } else {
                                $Periodo = substr($FechaSola, 0, 4) . "-" . "1";
                            }

                            echo "<tr data-expediente='" . $Expediente . "'>";
                            echo "<td data-label='Expediente'>" . $Expediente . "</td>";
                            echo "<td data-label='Nombre'>" . $Nombre . "</td>";
                            echo "<td data-label='Fecha'>" . $FechaSola . "</td>";
                            echo "<td data-label='Aula'>" . (!empty($Fila["aula"]) ? $Fila["aula"] : "Pendiente") . "</td>";
                            echo "<td data-label='Entregable'>" . $entregablesContent . "</td>";

                            // Modificar la visualización de calificación según el rol
                            echo "<td data-label='Calificación'>";
                            if ($esDirector) {
                                echo "No asignable";
                            } else {
                                echo "<input type='number' class='inputs' name='calificacion_" . $Expediente . "' 
                                      id='calificacion_" . $Expediente . "' step='0.01' min='0' max='10' 
                                      placeholder='Calificación...' required 
                                      onchange='checkFields(\"" . $Expediente . "\")' 
                                      oninput='limitDigits(this, 4)'>";
                            }
                            echo "</td>";

                            echo "<td data-label='Observaciones'>
                                  <button class='observaciones' onclick='abrirModal(\"" . $Expediente . "\", " . ($esDirector ? 'true' : 'false') . ")'>
                                    Agregar
                                  </button>
                                  <input type='hidden' id='observacion_" . $Expediente . "' value=''>
                                  </td>";
                            echo "<td data-label='Acción'>
                                  <button class='confirmar-icon' id='btn_" . $Expediente . "' 
                                    onclick='actualizarEvaluacion(\"" . $Expediente . "\")' disabled>&#x2714;</button>
                                  </td>";
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
            <hr class="x-component x-component-default" style="border-top: 0;border-bottom: 0.05rem solid #196ad3;margin:auto;width: 100%;margin-bottom: 2rem;">
            <div id="observacionesContent">
                <!-- El contenido se llenará dinámicamente -->
            </div>
            <button onclick="guardarObservaciones()" class="confirmar-button">Guardar</button>
        </div>
    </div>

    <script>
        // Iniciar con el botón guardar del modal deshabilitado
        /* const confirmarButton = document.querySelector('.confirmar-button');
        confirmarButton.classList.add('disabled');
        confirmarButton.disabled = true; */
        
        function actualizarEvaluacion(expediente) {
            const fila = document.querySelector(`tr[data-expediente="${expediente}"]`);
            const fecha = fila.querySelector('td:nth-child(3)').innerText;
            const calificacionInput = document.getElementById('calificacion_' + expediente);
            const observacionInput = document.getElementById('observacion_' + expediente);
            const esDirector = !calificacionInput;

            // Calcular el periodo basado en la fecha
            let periodo = '';
            if (fecha !== "Pendiente") {
                const mes = parseInt(fecha.split('-')[1]);
                const año = fecha.split('-')[0];
                periodo = año + "-" + (mes < 6 ? "2" : "1");
            }

            // Preparar los datos para enviar
            const formData = new FormData();
            formData.append('expediente', expediente);
            formData.append('periodo', periodo);
            formData.append('esDirector', esDirector);

            if (esDirector) {
                // Para director, separar las observaciones
                const observaciones = observacionInput.value.split('|');
                formData.append('d_observacion1', observaciones[0] || '');
                formData.append('d_observacion2', observaciones[1] || '');
                formData.append('d_observacion3', observaciones[2] || '');
                formData.append('observacion', observaciones[3] || '');
            } else {
                // Para sínodo
                formData.append('calificacion', calificacionInput.value);
                formData.append('observacion', observacionInput.value);
            }

            // Enviar la solicitud al servidor
            fetch('actualizar_detalle_evaluaciones.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(result => {
                    if (result === 'success') {
                        alert('Evaluación actualizada correctamente');
                        // Recargar la página para actualizar la lista
                        location.reload();
                    } else {
                        alert('Error al actualizar la evaluación: ' + result);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud');
                });
        }
        let expedienteActual = '';
        let esDirectorModal = false;

        function checkFields(expediente) {
            const btn = document.getElementById('btn_' + expediente);
            const fila = document.querySelector(`tr[data-expediente="${expediente}"]`);
            const fecha = fila.querySelector('td:nth-child(3)').innerText;
            const aula = fila.querySelector('td:nth-child(4)').innerText;
            const calificacionInput = document.getElementById('calificacion_' + expediente);
            const observacionInput = document.getElementById('observacion_' + expediente);

            // Verificar si es director para este expediente específico
            const esDirector = !calificacionInput; // Si no hay input de calificación, es director

            let isValid = false;

            if (esDirector) {
                // Validación para director: todas las observaciones deben estar llenas
                const observaciones = observacionInput.value.split('|');
                isValid = observaciones.length === 4 &&
                    observaciones.every(obs => obs.trim() !== '') &&
                    fecha !== "Pendiente" &&
                    aula !== "Pendiente";
            } else {
                // Validación para sínodo: calificación y observación requeridas
                isValid = calificacionInput?.value &&
                    observacionInput?.value &&
                    fecha !== "Pendiente" &&
                    aula !== "Pendiente";
            }

            btn.disabled = !isValid;
        }

        function limitDigits(input, maxDigits) {
            if (input.value.length > maxDigits) {
                input.value = input.value.slice(0, maxDigits);
            }
        }

        function abrirModal(expediente, esDirector) {
            expedienteActual = expediente;
            esDirectorModal = esDirector;
            const modal = document.getElementById('modalObservaciones');
            const contenido = document.getElementById('observacionesContent');

            // Recuperar observaciones existentes
            const existingObservations = document.getElementById('observacion_' + expediente)?.value || '';

            contenido.innerHTML = '';

            if (esDirector) {
                // Formato para director
                const obsSections = existingObservations.split('|');
                contenido.innerHTML = `
                    <div>
                        <label class='label-observaciones'>Sobre Avance gradual:</label>
                        <textarea class="observacion-input" id="obs1_${expediente}" rows="5" 
                          onchange="checkFields('${expediente}')">${obsSections[0] || ''}</textarea>
                    </div>
                    <div>
                        <label class='label-observaciones'>Sobre entregable y resultados esperados:</label>
                        <textarea class="observacion-input" id="obs2_${expediente}" rows="5" 
                          onchange="checkFields('${expediente}')">${obsSections[1] || ''}</textarea>
                    </div>
                    <div>
                        <label class='label-observaciones'>Sobre el Avance del Proyecto:</label>
                        <textarea class="observacion-input" id="obs3_${expediente}" rows="5" 
                          onchange="checkFields('${expediente}')">${obsSections[2] || ''}</textarea>
                    </div>
                    <div>
                        <label class='label-observaciones'>Comentario Individual:</label>
                        <textarea class="observacion-input" id="obs4_${expediente}" rows="5" 
                          onchange="checkFields('${expediente}')">${obsSections[3] || ''}</textarea>
                    </div>`;
            } else {
                // Formato para sínodo
                contenido.innerHTML = `
                    <div>
                        <textarea class="observacion-input" id="obs_${expediente}" rows="5" 
                          onchange="checkFields('${expediente}')" required>${existingObservations}</textarea>
                    </div>`;
            }

            modal.style.display = "block";
            // Forzar un reflow para que la transición funcione
            modal.offsetHeight;
            modal.classList.add('show');
        }

        function guardarObservaciones() {
            const observacionInput = document.getElementById('observacion_' + expedienteActual);

            if (esDirectorModal) {
                // Guardar observaciones de director
                const obs1 = document.getElementById(`obs1_${expedienteActual}`)?.value || '';
                const obs2 = document.getElementById(`obs2_${expedienteActual}`)?.value || '';
                const obs3 = document.getElementById(`obs3_${expedienteActual}`)?.value || '';
                const obs4 = document.getElementById(`obs4_${expedienteActual}`)?.value || '';

                observacionInput.value = [obs1, obs2, obs3, obs4].join('|');
            } else {
                // Guardar observación de sínodo
                const obsInput = document.getElementById(`obs_${expedienteActual}`);
                if (obsInput) {
                    observacionInput.value = obsInput.value;
                }
            }

            checkFields(expedienteActual);
            cerrarModal();
        }

        function cerrarModal() {
            const modal = document.getElementById('modalObservaciones');
            modal.classList.remove('show');
            // Esperar a que termine la animación antes de ocultar el modal
            setTimeout(() => {
                modal.style.display = "none";
            }, 300); // Este tiempo debe coincidir con la duración de la transición en CSS
        }

        // Event listeners
        document.querySelector('.close').onclick = cerrarModal;

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('modalObservaciones');
            if (event.target === modal) {
                cerrarModal();
            }
        };
    </script>
</body>

</html>

Adipisicing consequat pariatur incididunt reprehenderit non aliqua adipisicing aute nulla. Occaecat laborum nulla in aliqua ipsum amet. Pariatur incididunt in laboris pariatur anim et pariatur exercitation est. Elit esse fugiat exercitation pariatur ad anim. Irure sint in irure aliquip officia anim veniam tempor consectetur fugiat commodo.

Non in voluptate do laborum consectetur aliquip nostrud commodo aliqua elit proident nostrud ullamco excepteur. Qui ad adipisicing aute cupidatat duis tempor in. Voluptate reprehenderit dolore officia duis adipisicing velit dolor sint qui culpa occaecat tempor qui non. Elit anim esse ipsum consequat voluptate nisi labore duis pariatur proident exercitation occaecat aliquip. Nulla cillum sint est minim. Mollit esse voluptate dolore duis sunt ex non magna ipsum laboris. Reprehenderit cillum dolor duis dolore.

Incididunt laboris culpa do nostrud consequat ea et Lorem proident ea pariatur. Nisi ipsum id aliquip amet nisi ex Lorem cillum ullamco nulla. Magna aute sit velit labore in sint voluptate eiusmod nisi et anim mollit ullamco aliquip. Officia elit sunt sint occaecat eiusmod exercitation aliqua est nulla. Dolore incididunt reprehenderit reprehenderit Lorem mollit exercitation cupidatat.

Ea ut mollit dolor fugiat consequat duis occaecat enim ea. Cupidatat elit eiusmod esse ea dolore culpa velit mollit ea labore. Dolor velit consectetur veniam dolore duis irure ipsum aliqua minim reprehenderit eu cupidatat eiusmod adipisicing. Aute sit proident aliquip sunt nostrud mollit ut aute. Labore duis in mollit cupidatat exercitation consequat enim ex sunt sit laborum sunt ex.

Officia cupidatat commodo ad aliquip sit aliquip in. Non culpa occaecat nisi laboris aliquip sunt quis ullamco fugiat. Ad eiusmod enim nisi Lorem quis qui aliqua in. Fugiat ea amet eu minim est ullamco. Proident quis id consequat sunt cillum exercitation incididunt ipsum laborum fugiat anim nostrud. Nulla amet laborum mollit deserunt. Reprehenderit pariatur eiusmod proident eu deserunt commodo id laboris.