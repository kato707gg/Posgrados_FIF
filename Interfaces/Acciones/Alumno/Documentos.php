<?php
  include('../../Header/MenuA.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos</title>
</head>

<style>
    body {
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    :root {
        --primary-color: rgb(26,115,232);
        --secondary-color: #aaa;
        --text-color: #3c4043;
        --background-color: #fafcff;
    }

    .container-documentos {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 81vh;
        margin: 2vh 2vw;
        padding: 2vh 2vw;
        border-radius: clamp(.4rem, .4vw, .4rem);
        background-color: #e9e9e9;      
    }

    .container-subirdoc {
        font: caption;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-bottom: 1rem;
    }

    .container-tipodoc,
    .container-subir {
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    label {
        font-size: 1.1rem;
        margin-right: 0.4rem;
    }

    #document-type {
        font-size: 1rem;
        padding: 0.7rem 1rem;
        width: 13rem;
        border: 1px solid #ccc;
        border-radius: 0.4rem;
        line-height: 1.5rem;
    }

    #file-label {
        font-size: 1rem;
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
        background-color: white;
    }

    #clear-file-btn {
        font-size: 1rem;
        display: none;
        margin-left: 10px;
        color: red;
        cursor: pointer;
        line-height: 1.5rem;
    }

    #upload-btn {
        font-size: 0.9rem;
        display: inline-block;
        padding: 0.7rem 1rem;
        background-color: grey;
        color: white;
        border: none;
        cursor: not-allowed;
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

    #table-container {
        display: flex;
        justify-content: center;
        overflow-x: auto; /* Habilitar desplazamiento horizontal si es necesario */
        overflow-y: auto; /* Habilitar desplazamiento vertical dentro del contenedor */
        width: 100%;
    }

    #table-header table {
        border-collapse: collapse;
        margin-bottom: 0;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        max-width: 100%; /* Asegurar que la tabla no sobrepase el contenedor */
    }

    tr {
        border-top: 0.1rem solid var(--primary-color);
        border-bottom: 0.1rem solid var(--secondary-color);
    }
    
    th, td {
        border-bottom: 0.0625rem solid var(--secondary-color);
        padding: 1.25rem;
    }
    th:nth-child(2), td:nth-child(2) {
        width: 20vw;
    }
    th:not(:nth-child(2)), td:not(:nth-child(2)) {
        width: 10vw;
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

    .ver-doc {
        color: #123773;
        margin: auto;
        font-size: 1rem;
        padding: 0.25rem 0.7rem;
        background-color: #ffffff;
        border: none;
        cursor: pointer;
        border-radius: clamp(.4rem, .4vw, .4rem);
        border-bottom: 0.0625rem solid var(--secondary-color);
    }

    .ver-doc:hover {
        background-color: #cfcfcf;
    }

    @media screen and (max-width: 1600px) {

        .container-agendar-evaluacion {
            height: 75vh;
        }

    }

    @media (max-width: 770px) {
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
    <div class="container-documentos">
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
        <h3>Documentos subidos:</h3>
        <div id="table-container">
        
            <table>
            <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Vista</th>
                    </tr>
                </thead>
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
                fileLabel.style.cursor = 'pointer';
                uploadBtn.style.cursor = 'pointer'; // Cambiar cursor para indicar habilitación
                uploadBtn.classList.add("option-selected"); // Cambia el botón a azul
                uploadBtn.classList.remove("enabled"); // Asegúrate de que no esté en verde
            } else {
                fileInput.disabled = true; // Deshabilitar input de archivo
                uploadBtn.style.cursor = 'not-allowed';
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
                const formData = new FormData();
                formData.append('file', fileInput.files[0]);
                formData.append('documentType', documentType.value);

                // Mostrar algún indicador de carga si lo deseas
                uploadBtn.disabled = true;
                uploadBtn.textContent = 'Subiendo...';

                fetch('upload_document.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar la tabla con el nuevo documento
                        let documents = JSON.parse(localStorage.getItem('documents') || '[]');
                        documents.push({
                            date: data.data.date,
                            type: data.data.type,
                            fileName: data.data.fileName,
                            fileURL: data.data.path
                        });
                        localStorage.setItem('documents', JSON.stringify(documents));
                        updateDocumentsTable();

                        // Limpiar el formulario
                        clearFile();
                        documentType.value = "";
                        enableFileSection();

                        alert('Archivo subido correctamente');
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al subir el archivo');
                })
                .finally(() => {
                    // Restaurar el botón
                    uploadBtn.disabled = false;
                    uploadBtn.textContent = 'Subir archivo';
                });
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
                viewCell.innerHTML = `<span class="ver-doc" style="cursor: pointer; font-size: 2rem;" onclick="viewDocument('${doc.fileURL}')">👁</span>`;
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