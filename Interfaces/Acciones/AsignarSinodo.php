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
            margin-bottom: 5rem;
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

        .container-proximas-evaluacionesS {
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
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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
    </style>
</head>

<body>

<div class="container-proximas-evaluacionesS">
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
                            echo "<td><button onclick='openModal(this)'>Asignar</button><div class='sinodo-nombre'></div><button class='edit-button' onclick='openModal(this)'>Editar</button></td>";
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
                        $NombreCom = $Sinodo["nombre"] . " " . $Sinodo["a_paterno"] . " " . $Sinodo["a_materno"];
                        echo "<tr>";
                        echo "<td>" . $Sinodo['clave'] . "</td>";
                        echo "<td>" . $NombreCom . "</td>";
                        echo "<td><button onclick='selectSinodo(this, \"" . $Sinodo['nombre'] . "\")'>Seleccionar</button></td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    let currentButton;

    // Función para abrir el modal
    function openModal(button) {
        currentButton = button; // Guardamos el botón actual para modificarlo después
        document.getElementById("sinodoModal").style.display = "block";
    }

    // Función para seleccionar un sínodo
    function selectSinodo(button, sinodoNombre) {
        let sinodoContainer = currentButton.nextElementSibling; // El contenedor donde mostraremos el nombre del sínodo
        let editButton = currentButton.nextElementSibling.nextElementSibling; // El botón de editar

        // Ocultar el botón de Asignar
        currentButton.style.display = 'none';
        
        // Mostrar el nombre del sinodo
        sinodoContainer.textContent = sinodoNombre;
        
        // Mostrar el botón de editar
        editButton.style.display = 'inline';

        // Cerrar el modal
        document.getElementById("sinodoModal").style.display = "none";
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
</script>

</body>

</html>
