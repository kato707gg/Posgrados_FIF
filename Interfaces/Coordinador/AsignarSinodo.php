<?php
  include('../Header/MenuC.php');
?>

<?php
// Incluir el archivo de conexión
include '../../Config/conexion.php';

// Conectar a la base de datos
$Con = Conectar();

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$id_coordinador = $_SESSION['id'];

// Obtener el programa del coordinador
$SQL_programa = "SELECT programa FROM coordinadores WHERE clave = ?";
$stmt = mysqli_prepare($Con, $SQL_programa);
mysqli_stmt_bind_param($stmt, 'i', $id_coordinador);
mysqli_stmt_execute($stmt);
$resultado_programa = mysqli_stmt_get_result($stmt);
$programa_coordinador = mysqli_fetch_assoc($resultado_programa)['programa'];

// Consulta SQL para obtener los datos de los estudiantes
$SQL = "
    SELECT e.exp, e.nombre, e.a_paterno, e.a_materno 
    FROM estudiantes e 
    LEFT JOIN asignaciones a ON e.exp = a.exp_alumno 
    WHERE a.exp_alumno IS NULL 
    AND e.programa = ?
";

$stmt = mysqli_prepare($Con, $SQL);
mysqli_stmt_bind_param($stmt, 's', $programa_coordinador);
mysqli_stmt_execute($stmt);
$Resultado = mysqli_stmt_get_result($stmt);

$SQLSinodos = "SELECT clave, nombre, a_paterno, a_materno FROM docentes"; // Consulta para obtener los sinodos
$ResultadoSinodos = Ejecutar($Con, $SQLSinodos);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/components/tablas.css">
    <link rel="stylesheet" href="../../CSS/components/modales.css">
    <link rel="stylesheet" href="../../CSS/components/buttons.css">
    <link rel="stylesheet" href="../../CSS/transitions.css">
    <title>Asignar Sinodo</title>
    <style>

        .container-guardar-button {
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
            z-index: 100; /* Para asegurarse de que esté por encima de otros elementos como la tabla */
        }

        @media (max-width: 770px) {
        .btn-secondary{
            font-size: var(--font-size-base);
        }
    }

    .sinodo-container:empty {
        display: none;
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
                        echo "<td data-label='Sinodo 1 (Director)'><button class='btn btn-secondary' onclick='openModal(this)'>Asignar</button><div class='sinodo-container'></div></td>";
                        echo "<td data-label='Sinodo 2'><button class='btn btn-secondary' onclick='openModal(this)'>Asignar</button><div class='sinodo-container'></div></td>";
                        echo "<td data-label='Sinodo 3'><button class='btn btn-secondary' onclick='openModal(this)'>Asignar</button><div class='sinodo-container'></div></td>";
                        echo "<td data-label='Sinodo 4 (Externo)'><button class='btn btn-secondary' onclick='openModal(this)'>Asignar</button><div class='sinodo-container'></div></td>";
                        
                        // Botón de confirmar
                        echo "<td data-label='Confirmar'><button class='btn btn-primary' onclick='confirmarAsignacion(\"" . $Fila['exp'] . "\")'>&#x2714;</button></td>";
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
    <div class="modal-content large">
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
                        echo "<td data-label='Seleccionar'><input type='checkbox' class='checkbox' value='" . $Sinodo['clave'] ."' onclick='handleCheckbox(this, \"" . $NombreSin . "\")'></td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
        <div class="container-guardar-button">
            <button class="guardar-button" onclick="confirmSelection()">Confirmar</button>
        </div>
    </div>
</div>

<script>
let sinodosSeleccionadosPorEstudiante = {}; // Objeto para almacenar sínodos seleccionados por cada estudiante
let selectedSinodo = null;
let currentButton;
const confirmarButton = document.querySelector('.guardar-button');

// Iniciar con el botón deshabilitado
confirmarButton.classList.add('disabled');
confirmarButton.disabled = true;

// Función para permitir solo un checkbox seleccionado a la vez
function handleCheckbox(checkbox, nombreSinodo) {
    let checkboxes = document.querySelectorAll('.checkbox');
    
    // Si el sínodo seleccionado es el comodín (clave 0)
    if (checkbox.value === "0") {
        // Si está checkeado, permitir su selección sin desmarcar otros
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
    } else {
        // Para otros sínodos, mantener el comportamiento original de selección única
        checkboxes.forEach(cb => {
            if (cb !== checkbox && cb.value !== "0") {
                cb.checked = false;
            }
        });

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
}

// Función para abrir el modal
function openModal(button) {
    currentButton = button;
    const modal = document.getElementById("sinodoModal");
    const exp = button.closest('tr').querySelector('td:first-child').textContent;// Obtener el expediente de la fila actual
    
    modal.style.display = "block";
    // Forzar un reflow para que la transición funcione
    modal.offsetHeight;
    modal.classList.add('show');

    confirmarButton.classList.add('disabled');
    confirmarButton.disabled = true;

    let sinodosSeleccionados = sinodosSeleccionadosPorEstudiante[exp] || [];

    let checkboxes = document.querySelectorAll('.checkbox');
    checkboxes.forEach(checkbox => {
        // Si el sínodo es el comodín (clave 0), siempre debe estar habilitado
        if (checkbox.value === "0") {
            checkbox.disabled = false;
        } else {
            // Para otros sínodos, bloquear solo si ya fue seleccionado para este estudiante
            if (sinodosSeleccionados.includes(checkbox.value)) {
                checkbox.disabled = true;
            } else {
                checkbox.disabled = false;
            }
        }
        checkbox.checked = false;
    });

    selectedSinodo = null;
}

// Función para confirmar la selección
function confirmSelection() {
    if (selectedSinodo && currentButton) {
        const exp = currentButton.closest('tr').querySelector('td:first-child').textContent; // Obtener el expediente de la fila actual
        let sinodoContainer = currentButton.nextElementSibling;
        
        if (sinodoContainer) {
            currentButton.style.display = 'none'; // Ocultar el botón de Asignar
            sinodoContainer.textContent = currentButton.sinodoNombre; // Mostrar el sínodo asignado (nombre)
            sinodoContainer.dataset.sinodoClave = selectedSinodo; // Almacenar la clave en un data attribute
            
            const modal = document.getElementById("sinodoModal");
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = "none";
            }, 300);

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
        xhr.open("POST", "../Coordinador/insertar_sinodos.php", true);
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
    const modal = document.getElementById("sinodoModal");
    modal.classList.remove('show');
    setTimeout(() => {
        modal.style.display = "none";
    }, 300);
}

// Cerrar el modal si se hace clic fuera del contenido
window.onclick = function(event) {
    let modal = document.getElementById("sinodoModal");
    if (event.target === modal) {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = "none";
        }, 300);
    }
}
</script>

</body>
</html>