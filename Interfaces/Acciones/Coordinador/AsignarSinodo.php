<?php
  include('../../Header/MenuC.php');
?>

<?php
// Incluir el archivo de conexión
include '../../../conexion.php';

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
    <link rel="stylesheet" href="../tablas.css">
    <title>Asignar Sinodo</title>
    <style>
        
        .asignar-button {
            font-size: 1.1rem;
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            padding: 0.7rem 0.9rem;
            background-color: #123773;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: clamp(.4rem, .4vw, .4rem);
        }

        .asignar-button:hover {
            background-color: #1455bd;
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

        /* Estilos para el modal */
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
            width: 50%;
            height: 80%;
            border-radius: 0.4rem;
            overflow-y: auto;
            padding-bottom: 0;
        }

        .modal-table {
            max-width: 100%;
        }

        .modal-table {
            max-width: 100%;
        }

        input[type="checkbox" i] {
            cursor: pointer;
            width: 1.2rem;
            height: 1.2rem;
        }

        .container-confirmar-button {
            mask-image: 
            linear-gradient(to bottom, transparent, black 10%, black 100%);
            mask-composite: intersect;
            -webkit-mask-image: 
            linear-gradient(to bottom, transparent, black 10%, black 100%);
            -webkit-mask-composite: source-in;
            padding: 7vh 0;
            display: flex;
            justify-content: center;
            position: sticky;
            background-color: white;
            bottom: 0; /* Mantendrá el botón en la parte inferior de su contenedor */
            z-index: 10; /* Para asegurarse de que esté por encima de otros elementos como la tabla */
        }

        .confirmar-button {
            font-size: 1.3rem;
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            padding: 0.7rem 3rem;
            background-color: #123773;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: clamp(.4rem, .4vw, .4rem);
        }

        .confirmar-button.disabled {
            background-color: grey;
            cursor: not-allowed;
            opacity: 0.6;
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

        @media (max-width: 770px) {
            .modal-content {
                width: 80%;
            }
            input[type="checkbox" i] {
                cursor: pointer;
                width: 1.5rem;
                height: 1.5rem;
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
            .asignar-button {
                margin-left: auto; /* Empuja el botón hacia la derecha */
            }
        }

    </style>
</head>

<body>

<div class="container-principal">
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
                        echo "<td data-label='Expediente'>" . $Fila ["exp"] . "</td>";
                        echo "<td data-label='Nombre'>" . $Nombre . "</td>";
                        
                        // Botones de asignar para cada sinodo
                        echo "<td data-label='Sinodo 1 (Director)'><button class='asignar-button' onclick='openModal(this)'>Asignar</button><div class='sinodo-container'></div></td>";
                        echo "<td data-label='Sinodo 2'><button class='asignar-button' onclick='openModal(this)'>Asignar</button><div class='sinodo-container'></div></td>";
                        echo "<td data-label='Sinodo 3'><button class='asignar-button' onclick='openModal(this)'>Asignar</button><div class='sinodo-container'></div></td>";
                        echo "<td data-label='Sinodo 4 (Externo)'><button class='asignar-button' onclick='openModal(this)'>Asignar</button><div class='sinodo-container'></div></td>";
                        
                        // Botón de confirmar
                        echo "<td data-label='Confirmar'><button class='confirmar-icon' onclick='confirmarAsignacion(\"" . $Fila['exp'] . "\")'>&#x2714;</button></td>";
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
                        echo "<td data-label='ID'>" . $Sinodo['clave'] . "</td>";
                        echo "<td data-label='Nombre'>" . $NombreSin . "</td>";
                        // Cambiar el value del checkbox a la clave del sinodo
                        echo "<td data-label='Seleccionar'><input type='checkbox' class='sinodo-checkbox' value='" . $Sinodo['clave'] ."' onclick='handleCheckbox(this, \"" . $NombreSin . "\")'></td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
        <div class="container-confirmar-button">
            <button class="confirmar-button" onclick="confirmSelection()">Confirmar</button>
        </div>
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
        xhr.open("POST", "../insertar_sinodos.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert("Asignación hecha correctamente!!"); // Mostrar respuesta del servidor
                // console.log(xhr.responseText);
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