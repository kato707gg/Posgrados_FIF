<?php
  include('../Header/MenuA.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Header/styles.css">
    <title>Documentos</title>
</head>

<style>
    body {
        margin: 0;
        padding: 0;
    }

    :root {
        --primary-color: rgb(26, 115, 232);
        --secondary-color: #366d6f;
        --text-color: #3c4043;
        --background-color: #fafcff;
    }

    /* Centrar la sección completa */
    .container-subirdoc {
        display: flex;
        flex-direction: column; /* Alinear los elementos verticalmente */
        justify-content: center;
        align-items: center;
        margin-top: 5rem;
    }

    /* Espaciar los elementos */
    .container-tipodoc, .container-subir {
        margin-bottom: 1rem;
    }

    #file-label {
        display: inline-block;
        padding: 10px;
        border: 1px solid #ccc;
        background-color: #f4f4f4;
        margin-right: 10px;
        cursor: not-allowed; /* Deshabilitar clic al inicio */
    }

    #clear-file-btn {
        display: none;
        margin-left: 10px;
        color: red;
        cursor: pointer;
    }

    #upload-btn {
        display: inline-block;
        padding: 10px;
        background-color: grey;
        color: white;
        border: none;
        cursor: not-allowed;
        pointer-events: none;
    }

    #upload-btn.enabled {
        background-color: #4CAF50;
        cursor: pointer;
        pointer-events: auto;
    }

    table {
        table-layout: fixed;
        border-collapse: collapse;
        margin-bottom: 5rem;
        width: 100%;
        max-width: 40rem;
    }

    tr {
        border-top: 0.1rem solid var(--primary-color);
        border-bottom: 0.1rem solid var(--primary-color);
    }

    th,
    td {
        width: 33.33%;
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
        display: flex !important;
        justify-content: center;
        overflow-x: auto;
    }

    .container-tabla {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-top: 3rem;
        padding: 1rem;
    }

    @media (max-width: 48rem) {
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
</style>

<body>
    <div class="container-subirdoc">
        <!-- Sección de selección del tipo de documento -->
        <div class="container-tipodoc">
            <label for="document-type">Tipo:</label>
            <select id="document-type" onchange="enableFileSection()">
                <option value="">Selecciona un tipo</option>
                <option value="Recibo">Recibo</option>
                <option value="Protocolo">Protocolo</option>
                <option value="Registro de tesis">Registro de tesis</option>
            </select>
        </div>

        <!-- Sección de subir archivo -->
        <div class="container-subir">
            <label for="file">Subir:</label>
            <span id="file-label" onclick="triggerFileInput()">Examinar...</span>
            <input type="file" id="file" name="file" style="display:none;" accept=".pdf,.docx,.xlsx" onchange="handleFileSelect()" disabled>
            <button id="upload-btn">Subir archivo</button>
            <span id="clear-file-btn" onclick="clearFile()">Quitar archivo &times;</span>
        </div>
    </div>
    <div class="container-tabla">
        <h3>Subir documentos:</h3>
        <div id="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Vista</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                // Aquí deberías incluir la lógica para conectarte a la base de datos y obtener los datos de los alumnos
                // Por ejemplo:
                // $conexion = new mysqli("localhost", "usuario", "contraseña", "basededatos");
                // $resultado = $conexion->query("SELECT id, nombre, grupo FROM alumnos");

                // Simulamos algunos datos para el ejemplo
                $alumnos = [
                    ['fecha' => '2024-05-01', 'tipo' => 'Recibo', 'vista' => 1],
                ];

                foreach ($alumnos as $alumno) {
                    echo "<tr>";
                    echo "<td>" . $alumno['fecha'] . "</td>";
                    echo "<td>" . $alumno['tipo'] . "</td>";
                    echo "<td>" . $alumno['vista'] . "</td>";
                    echo "</tr>";
                }

                // Si estuvieras usando una conexión real a la base de datos, cerrarías la conexión aquí
                // $conexion->close();
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('file');
        const fileLabel = document.getElementById('file-label');
        const clearFileBtn = document.getElementById('clear-file-btn');
        const uploadBtn = document.getElementById('upload-btn');
        const documentType = document.getElementById('document-type');

        function enableFileSection() {
            // Habilitar la parte de subir archivo si se selecciona un tipo válido
            if (documentType.value !== "") {
                fileInput.disabled = false; // Habilitar input de archivo
                fileLabel.style.cursor = 'pointer'; // Cambiar cursor para indicar habilitación
            } else {
                fileInput.disabled = true; // Deshabilitar input de archivo
                fileLabel.style.cursor = 'not-allowed'; // Cambiar cursor para indicar deshabilitación
                clearFile(); // Limpiar campos
            }
        }

        function triggerFileInput() {
            // Solo permitir abrir el diálogo si el input no está deshabilitado
            if (!fileInput.disabled) {
                fileInput.click();
            }
        }

        function handleFileSelect() {
            const file = fileInput.files[0];
            
            if (file) {
                fileLabel.innerText = file.name;
                uploadBtn.classList.add('enabled'); // Habilitar botón de subida
                clearFileBtn.style.display = 'inline'; // Mostrar botón de quitar archivo
            }
        }

        function clearFile() {
            fileInput.value = ''; // Limpiar el archivo seleccionado
            fileLabel.innerText = 'Examinar...';
            uploadBtn.classList.remove('enabled'); // Deshabilitar el botón de subida
            clearFileBtn.style.display = 'none'; // Ocultar botón de quitar archivo
        }
    </script>

</body>

</html>