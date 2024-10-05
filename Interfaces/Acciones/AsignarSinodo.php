<?php
  include('../Header/MenuC.php');
?>

<?php
// Incluir el archivo de conexión
include '../../conexion.php';

// Conectar a la base de datos
$Con = Conectar();

// Consulta SQL para obtener los datos de los estudiantes
$SQL = "SELECT e.exp, e.nombre, e.a_paterno, e.a_materno FROM estudiantes e INNER JOIN coordinadores c ON e.programa = c.programa";
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
        }

        :root {
            --primary-color: rgb(26,115,232);
            --secondary-color: #366d6f;
            --text-color: #3c4043;
            --background-color: #fafcff;
        }

        table {
            table-layout: auto;
            border-collapse: collapse;
            margin-bottom: 4rem;
            width: 100%;
            max-width: 100rem;
        }

        tr {
            border-top: 0.1rem solid var(--primary-color);
            border-bottom: 0.1rem solid var(--primary-color);
        }

        th, td {
            width: 20%;
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

        .confirmar-button {
            font-size: 1.5rem;
            color: green;
            cursor: pointer;
            border: none;
            background-color: transparent;
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
            overflow: auto;
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
            border-radius: 0.4rem;
        }

        .confirmar-button {
            display: flex;
            margin: auto;
            font-size: 1.3rem;
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            padding: 0.7rem 0.9rem;
            background-color: #123773;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 0.4rem;
            margin-bottom: 1.5rem;
        }

        .confirmar-button.disabled {
            background-color: grey; /* Color deshabilitado */
            cursor: not-allowed;
            opacity: 0.6; /* Para indicar visualmente que está deshabilitado */
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
                            echo "<td><button class='asignar-button' onclick='openModal(this)'>Asignar</button><div class='sinodo-container'></div></td>";
                        }
                        // Botón de confirmar que inserta en la base de datos
                        echo "<td><button class='confirmar-button' onclick='confirmarAsignacion(\"" . $Fila['exp'] . "\")'>✔️</button></td>";
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
        <table>
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
                        echo "<td><input type='checkbox' class='sinodo-checkbox' value='" . $NombreSin ."' onclick='handleCheckbox(this)'></td>";
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
let selectedSinodo = null;
let currentButton;

// Función para permitir solo un checkbox seleccionado a la vez
function handleCheckbox(checkbox) {
    let checkboxes = document.querySelectorAll('.sinodo-checkbox');
    
    // Desmarcar otros checkboxes si se selecciona uno nuevo
    checkboxes.forEach(cb => {
        if (cb !== checkbox) {
            cb.checked = false;
        }
    });
    
    // Guardar el sínodo seleccionado
    selectedSinodo = checkbox.checked ? checkbox.value : null;
}

// Función para abrir el modal
function openModal(button) {
    currentButton = button; // Guardamos el botón actual para modificarlo después
    document.getElementById("sinodoModal").style.display = "block";
}

// Función para confirmar la selección
function confirmSelection() {
    if (selectedSinodo && currentButton) {
        let sinodoContainer = currentButton.nextElementSibling;
        if (sinodoContainer) {
            currentButton.style.display = 'none'; // Ocultar el botón de Asignar
            sinodoContainer.textContent = selectedSinodo; // Mostrar el sínodo asignado
            document.getElementById("sinodoModal").style.display = "none"; // Cerrar el modal
        }
    } else {
        alert("Por favor selecciona un sínodo");
    }
}

// Función para insertar en la base de datos cuando se confirma la asignación
function confirmarAsignacion(exp) {
    let sinodos = [];
    document.querySelectorAll('.sinodo-container').forEach(container => {
        if (container.textContent) {
            sinodos.push(container.textContent);
        }
    });

    if (sinodos.length === 4) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "insertar_sinodos.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(xhr.responseText); // Mostrar respuesta del servidor
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
