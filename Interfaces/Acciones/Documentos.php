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
        flex-direction: column;
        /* Alinear los elementos verticalmente */
        justify-content: center;
        align-items: center;
        margin-top: 5rem;
    }

    .container-tipodoc,
    .container-subir {
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        /* Alinea verticalmente los elementos hijos */
    }

    label {
        font-size: 1.1rem;
        margin-right: 0.4rem;
    }

    #document-type {
        font: caption;
        padding: 0.7rem 1rem;
        width: 13rem;
        border: 1px solid #ccc;
        border-radius: 0.4rem;
        line-height: 1.5rem;
        /* Asegura una alineación uniforme */
    }

    #file-label {
        font: caption;
        display: inline-block;
        width: 13rem;
        padding: 0.7rem 1rem;
        border: 1px solid #ccc;
        border-radius: 0.4rem 0 0 0.4rem;
        cursor: not-allowed;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.5rem;
        vertical-align: middle;
    }

    #clear-file-btn {
        font: caption;
        display: none;
        margin-left: 10px;
        color: red;
        cursor: pointer;
        line-height: 1.5rem;
        /* Ajuste de altura */
    }

    #upload-btn {
        font: caption;
        display: inline-block;
        padding: 0.7rem 1rem;
        background-color: grey;
        color: white;
        border: none;
        cursor: not-allowed;
        pointer-events: none;
        border-radius: 0 0.4rem 0.4rem 0;
        vertical-align: middle;
        line-height: 1.62rem;
        transition: background-color 0.3s ease;
    }

    #upload-btn.option-selected {
        background-color: #123773;
        /* Azul cuando se ha seleccionado una opción */
        cursor: pointer;
        pointer-events: auto;
    }

    #upload-btn.option-selected:hover {
        background-color: #1A4DA1;
    }


    #upload-btn.enabled {
        background-color: #4CAF50;
        /* Verde cuando se sube un archivo */
        cursor: pointer;
        pointer-events: auto;
    }

    .container-tabla {
        max-height: 50vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-top: 1.5rem;
        padding: 1rem;
    }

    #table-header table {
        border-collapse: collapse;
        margin-bottom: 0;
    }

    table {
        table-layout: fixed;
        width: 100%;
        max-width: 60rem;
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

    @media (max-width: 48rem) {
        #file-label {
            width: 6rem;
        }
        #upload-btn {
            height: 3.01rem;
            padding: 0.5rem;
        }
        #document-type {
            width: 12rem;
        }
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
            <input type="file" id="file" name="file" style="display:none;" accept=".pdf,.docx,.xlsx"
                onchange="handleFileSelect()" disabled>
            <button id="upload-btn" onclick="uploadDocument()">Subir archivo</button>
            <span id="clear-file-btn" onclick="clearFile()">Quitar archivo &times;</span>
        </div>
    </div>
    <div class="container-tabla">
        <h3>Documentos subidos:</h3>
        <div id="table-header">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Vista</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div id="table-container">
            <table>
                <tbody id="documents-table-body">
                    <!-- Los documentos se cargarán aquí dinámicamente -->
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
                uploadBtn.classList.add("option-selected"); // Cambia el botón a azul
                uploadBtn.classList.remove("enabled"); // Asegúrate de que no esté en verde
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
            var fileInput = document.getElementById("file");
            var fileLabel = document.getElementById("file-label");
            var uploadBtn = document.getElementById("upload-btn");
            var clearFileBtn = document.getElementById("clear-file-btn");

            if (fileInput.files.length > 0) {
                // Actualizar el label con el nombre del archivo seleccionado
                fileLabel.innerText = fileInput.files[0].name;
                uploadBtn.classList.add("enabled"); // Cambiar el color del botón a verde
                uploadBtn.classList.remove("option-selected"); // Remueve el color azul
                clearFileBtn.style.display = "inline-block"; // Mostrar el botón de quitar archivo
            }
        }

        function clearFile() {
            var fileInput = document.getElementById("file");
            var fileLabel = document.getElementById("file-label");
            var uploadBtn = document.getElementById("upload-btn");
            var clearFileBtn = document.getElementById("clear-file-btn");

            // Limpiar el input de archivo
            fileInput.value = "";
            fileLabel.innerText = "Examinar..."; // Restablecer el label
            uploadBtn.classList.remove("enabled"); // Volver a azul (opción seleccionada)
            uploadBtn.classList.add("option-selected");
            clearFileBtn.style.display = "none"; // Ocultar el botón de quitar archivo
        }

        function uploadDocument() {
            const fileInput = document.getElementById('file');
            const documentType = document.getElementById('document-type');
            
            if (fileInput.files.length > 0 && documentType.value !== "") {
                const file = fileInput.files[0];
                const date = new Date().toISOString().split('T')[0];
                const type = documentType.value;
                
                // Crear un objeto URL para el archivo
                const fileURL = URL.createObjectURL(file);
                
                // Simular guardado en localStorage
                let documents = JSON.parse(localStorage.getItem('documents') || '[]');
                documents.push({ date, type, fileName: file.name, fileURL });
                localStorage.setItem('documents', JSON.stringify(documents));
                
                // Actualizar la tabla
                updateDocumentsTable();
                
                // Limpiar el formulario
                clearFile();
                documentType.value = "";
                enableFileSection();
            }
        }

        function updateDocumentsTable() {
            const tableBody = document.getElementById('documents-table-body');
            const documents = JSON.parse(localStorage.getItem('documents') || '[]');
            
            tableBody.innerHTML = '';
            documents.forEach(doc => {
                const row = tableBody.insertRow();
                row.insertCell(0).textContent = doc.date;
                row.insertCell(1).textContent = doc.type;
                const viewCell = row.insertCell(2);
                viewCell.innerHTML = `<span style="cursor: pointer; font-size: 2rem;" onclick="viewDocument('${doc.fileURL}')">&#x1f4c4</span>`;
            });
        }

        function viewDocument(fileURL) {
            // Abrir el documento en una nueva pestaña
            window.open(fileURL, '_blank');
        }

        // Cargar documentos al iniciar la página
        updateDocumentsTable();
    </script>

</body>

</html>