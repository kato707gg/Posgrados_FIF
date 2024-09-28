<?php
  include('../Header/MenuC.php');
?>

<?php
// Incluir el archivo de conexión
include '../../conexion.php';

// Conectar a la base de datos
$Con = Conectar();

// Consulta SQL para obtener los datos de los estudiantes
$SQL = "SELECT exp, nombre, a_paterno, a_materno FROM estudiantes";
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
            display: flex;
            justify-content: center;
            width: max-content;
            overflow-x: auto;
        }

        .container-asignar-sinodo {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 85vh;
            padding: 1rem;
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

        .edit-button {
            display: none;
            font-size: 0.8rem;
            color: blue;
            background: none;
            border: none;
            cursor: pointer;
            text-decoration: underline;
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
                            echo "<td><button class='asignar-button' onclick='openModal(this)'>Asignar</button><div class='sinodo-nombre'></div><button class='edit-button' onclick='openModal(this)'>Editar</button></td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No se encontraron estudiantes</td></tr>";
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
const confirmarButton = document.querySelector('.confirmar-button');

// Iniciar con el botón deshabilitado
confirmarButton.classList.add('disabled');
confirmarButton.disabled = true;

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
    if (checkbox.checked) {
        selectedSinodo = checkbox.value;
        confirmarButton.classList.remove('disabled');
        confirmarButton.disabled = false;
    } else {
        selectedSinodo = null;
        confirmarButton.classList.add('disabled');
        confirmarButton.disabled = true;
    }
}

// Función para abrir el modal y reiniciar la selección
function openModal(button) {
    currentButton = button; // Guardamos el botón actual para modificarlo después
    document.getElementById("sinodoModal").style.display = "block";
    
    // Reiniciar la selección previa cuando se abre el modal
    let checkboxes = document.querySelectorAll('.sinodo-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = false;
    });

    selectedSinodo = null;
    confirmarButton.classList.add('disabled');
    confirmarButton.disabled = true;
}

// Función para confirmar la selección
function confirmSelection() {
    if (selectedSinodo) {
        let sinodoContainer = currentButton.nextElementSibling; // Contenedor donde se mostrará el nombre del sínodo
        let editButton = currentButton.nextElementSibling.nextElementSibling; // Botón de editar

        // Ocultar el botón de Asignar
        currentButton.style.display = 'none';
        
        // Mostrar el nombre del sínodo seleccionado
        sinodoContainer.textContent = selectedSinodo;
        
        // Mostrar el botón de editar
        editButton.style.display = 'inline';

        // Cerrar el modal
        document.getElementById("sinodoModal").style.display = "none";
    }
}

// Función para abrir el modal al hacer clic en Editar y permitir seleccionar otro sínodo
function editSinodo(button) {
    currentButton = button.previousElementSibling.previousElementSibling; // Recuperar el botón de Asignar

    // Mostrar el botón de Asignar para permitir seleccionar otro sínodo
    currentButton.style.display = 'inline';

    // Limpiar el contenedor de la selección previa
    let sinodoContainer = button.previousElementSibling;
    sinodoContainer.textContent = '';

    // Abrir el modal
    openModal(currentButton);
}

// Cerrar el modal al hacer clic en el botón de cerrar
document.querySelector(".close").onclick = function() {
    document.getElementById("sinodoModal").style.display = "none";
};

// Cerrar el modal si el usuario hace clic fuera del contenido del modal
window.onclick = function(event) {
    if (event.target == document.getElementById("sinodoModal")) {
        document.getElementById("sinodoModal").style.display = "none";
    }
};

// Cuando se edita un sínodo, actualizar el estado y permitir que el botón de confirmar funcione
document.addEventListener('click', (event) => {
    if (event.target.classList.contains('sinodo-checkbox')) {
        handleCheckbox(event.target); // Asegurarse de manejar el checkbox al hacer clic
    }
});

</script>

</body>

</html>