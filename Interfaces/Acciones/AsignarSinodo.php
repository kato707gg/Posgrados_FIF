<?php
  include('../Header/MenuC.php');
?>

<?php
// Incluir el archivo de conexión
include '../../conexion.php';

// Conectar a la base de datos
$Con = Conectar();

// Consulta SQL para obtener los datos de los estudiantes
$SQL = "
    SELECT e.exp, e.nombre, e.a_paterno, e.a_materno 
    FROM estudiantes e 
    INNER JOIN coordinadores c ON e.programa = c.programa 
    LEFT JOIN asignaciones a ON e.exp = a.exp_alumno 
    WHERE a.exp_alumno IS NULL
";

$Resultado = Ejecutar($Con, $SQL);

$SQLSinodos = "SELECT clave, nombre, a_paterno, a_materno FROM docentes"; // Consulta para obtener los sinodos
$ResultadoSinodos = Ejecutar($Con, $SQLSinodos);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Header/styles.css">
    <title>Asignar Sinodo</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden; /* Evitar desplazamiento horizontal en todo el cuerpo */
        }

        :root {
            --primary-color: rgb(26,115,232);
            --secondary-color: #366d6f;
            --text-color: #3c4043;
            --background-color: #fafcff;
        }

        .container-asignar-sinodo {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 80vh;
            padding: 1rem;
        }

        #table-container {
            display: flex;
            justify-content: center;
            overflow-x: auto; /* Habilitar desplazamiento horizontal si es necesario */
            overflow-y: auto; /* Habilitar desplazamiento vertical dentro del contenedor */
            width: 100%;
        }

        table {
            table-layout: fixed;
            border-collapse: collapse;
            width: 100%;
            max-width: 80%; /* Asegurar que la tabla no sobrepase el contenedor */
        }

        tr {
            border-top: 0.1rem solid var(--primary-color);
            border-bottom: 0.1rem solid var(--primary-color);
        }

        th, td {
            border-bottom: 0.0625rem solid #e0e0e0;
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

        h3 {
            font-size: 2rem;
            font-family: "Google Sans", Roboto, Arial, sans-serif;
        }

        .asignar-button {
            font-size: 1rem;
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            padding: 0.5rem 0.6rem;
            background-color: #123773;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 0.4rem;
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

        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fff;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 2rem;
            width: 50%;
            height: 80%;
            border-radius: 0.4rem;
            overflow-y: auto;
        }

        .modal-table {
            max-width: 100%;
        }

        input[type="checkbox" i] {
            cursor: pointer;
            width: 1.2rem;
            height: 1.2rem;
        }

        .confirmar-button {
            display: flex;
            margin: auto;
            font-size: 1.3rem;
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            padding: 0.7rem 3rem;
            background-color: #123773;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 0.4rem;
            margin-top: 3rem;
            margin-bottom: 1.5rem;
        }

        .confirmar-button.disabled {
            background-color: grey;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .close {
            color: #aaa;
            right: 1rem;
            top: 0.5rem;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        @media (max-width: 48rem) {

            table {
                display: block;
                white-space: nowrap;
                width: 100%;
                max-width: 90%;
                max-height: 75%;
            }

            th, td {
                font-size: 1rem;
                padding: 0.75rem;
                white-space: nowrap;
            }

            h3 {
                font-size: 1.5rem;
            }

            .modal-content {
                width: 90%;
            }
        }

    </style>
</head>

<body>

<div class="container-asignar-sinodo">
    <h3>Asignar sinodo:</h3>
    <div id="table-container">
        <table>
            <thead>
                <tr>
                    <th>Expediente</th>
                    <th>Nombre</th>
                    <th>Sinodo 1 <br> (Director)</th>
                    <th>Sinodo 2</th>
                    <th>Sinodo 3</th>
                    <th>Sinodo 4 <br> (Externo)</th>
                    <th>Confirmar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($Resultado->num_rows > 0){
                    while ($Fila = $Resultado->fetch_assoc()){
                        $Nombre = $Fila["nombre"] . " " . $Fila["a_paterno"] . " " . $Fila["a_materno"];
                        echo "<tr>";
                        echo "<td>" . $Fila ["exp"] . "</td>";
                        echo "<td>" . $Nombre . "</td>";
                        // Botones de asignar para cada sinodo
                        for ($i = 1; $i <= 4; $i++) {
                            echo "<td><button class='asignar-button sinodo-button' onclick='openModal(this)'>Asignar</button><div class='sinodo-container'></div></td>";
                        }
                        // Botón de confirmar que inserta en la base de datos
                        echo "<td><button class='confirmar-icon' onclick='confirmarAsignacion(\"" . $Fila['exp'] . "\")'>&#x2714;</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No se encontraron estudiantes</td></tr>";
                }
                Cerrar($Con);
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="sinodoModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Seleccionar sinodo</h3>
        <table class="modal-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Seleccionar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($ResultadoSinodos->num_rows > 0) {
                    while ($Sinodo = $ResultadoSinodos->fetch_assoc()) {
                        $NombreSin = $Sinodo["nombre"] . " " . $Sinodo["a_paterno"] . " " . $Sinodo["a_materno"];
                        echo "<tr>";
                        echo "<td>" . $Sinodo['clave'] . "</td>";
                        echo "<td>" . $NombreSin . "</td>";
                        // Cambiar el value del checkbox a la clave del sinodo
                        echo "<td><input type='checkbox' class='sinodo-checkbox' value='" . $Sinodo['clave'] ."' onclick='handleCheckbox(this, \"" . $NombreSin . "\")'></td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
        <button class="confirmar-button" onclick="confirmSelection()">Confirmar</button>
    </div>
</div>

<script>
let sinodosSeleccionadosPorEstudiante = {}; // Objeto para almacenar sínodos seleccionados por cada estudiante
let selectedSinodo = null;
let currentButton;
const confirmarButton = document.querySelector('.confirmar-button');

// Iniciar con el botón deshabilitado
confirmarButton.classList.add('disabled');
confirmarButton.disabled = true;

// Función para permitir solo un checkbox seleccionado a la vez
function handleCheckbox(checkbox, nombreSinodo) {
    let checkboxes = document.querySelectorAll('.sinodo-checkbox');
    
    // Desmarcar otros checkboxes si se selecciona uno nuevo
    checkboxes.forEach(cb => {
        if (cb !== checkbox) {
            cb.checked = false;
        }
    });

    // Guardar o quitar el sínodo seleccionado
    if (checkbox.checked) {
        selectedSinodo = checkbox.value;
        confirmarButton.classList.remove('disabled');
        confirmarButton.disabled = false;
        currentButton.sinodoNombre = nombreSinodo;
    } else {
        selectedSinodo = null;
        confirmarButton.classList.add('disabled');
        confirmarButton.disabled = true;
        currentButton.sinodoNombre = null;
    }
}

// Función para abrir el modal
function openModal(button, exp) {
    currentButton = button; // Guardamos el botón actual para modificarlo después
    document.getElementById("sinodoModal").style.display = "block";

    // Restablecer el botón "Confirmar" cuando se abre un nuevo modal
    confirmarButton.classList.add('disabled');
    confirmarButton.disabled = true;

    // Obtener los sínodos seleccionados para el estudiante actual (si existen)
    let sinodosSeleccionados = sinodosSeleccionadosPorEstudiante[exp] || [];

    // Actualizar los checkboxes según las selecciones del estudiante actual
    let checkboxes = document.querySelectorAll('.sinodo-checkbox');
    checkboxes.forEach(checkbox => {
        if (sinodosSeleccionados.includes(checkbox.value)) {
            checkbox.disabled = true; // Deshabilitar si ya fue seleccionado
        } else {
            checkbox.disabled = false; // Habilitar si no ha sido seleccionado
        }
        checkbox.checked = false; // Restablecer los checkboxes
    });

    // También restablecemos el valor de selectedSinodo a null
    selectedSinodo = null;
}

// Función para confirmar la selección
function confirmSelection(exp) {
    if (selectedSinodo && currentButton) {
        let sinodoContainer = currentButton.nextElementSibling;
        if (sinodoContainer) {
            currentButton.style.display = 'none'; // Ocultar el botón de Asignar
            sinodoContainer.textContent = currentButton.sinodoNombre; // Mostrar el sínodo asignado (nombre)
            sinodoContainer.dataset.sinodoClave = selectedSinodo; // Almacenar la clave en un data attribute
            document.getElementById("sinodoModal").style.display = "none"; // Cerrar el modal

            // Guardar el sínodo asignado en el objeto del estudiante actual
            if (!sinodosSeleccionadosPorEstudiante[exp]) {
                sinodosSeleccionadosPorEstudiante[exp] = [];
            }
            sinodosSeleccionadosPorEstudiante[exp].push(selectedSinodo); // Añadir sínodo al estudiante
        }
    } else {
        alert("Por favor selecciona un sínodo");
    }
}

// Función para insertar en la base de datos cuando se confirma la asignación
function confirmarAsignacion(exp) {
    let sinodos = [];
    document.querySelectorAll('.sinodo-container').forEach(container => {
        if (container.dataset.sinodoClave) {
            sinodos.push(container.dataset.sinodoClave);
        }
    });

    if (sinodos.length === 4) {
        console.log(sinodos);
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "insertar_sinodos.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert("Asignación hecha correctamente!!"); // Mostrar respuesta del servidor
                location.reload();
            }
        };
        xhr.send("exp=" + exp + "&sinodo1=" + sinodos[0] + "&sinodo2=" + sinodos[1] + "&sinodo3=" + sinodos[2] + "&sinodo4=" + sinodos[3]);
    } else {
        alert("Debes asignar los 4 sinodos antes de confirmar");
    }
}

// Función para cerrar el modal
let closeModalButton = document.querySelector('.close');
closeModalButton.onclick = function() {
    document.getElementById("sinodoModal").style.display = "none";
}

// Cerrar el modal si se hace clic fuera del contenido
window.onclick = function(event) {
    let modal = document.getElementById("sinodoModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
}
</script>

</body>
</html>