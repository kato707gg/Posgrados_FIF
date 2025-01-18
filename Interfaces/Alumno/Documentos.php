<?php
  include('../Header/MenuA.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/components/tablas.css">
        <link rel="stylesheet" href="../../CSS/components/buttons.css">
    <link rel="stylesheet" href="../../CSS/transitions.css">
    <title>Documentos</title>
</head>

<style>
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
        height: clamp(3rem, 3vh, 5rem);
    }

    label {
        font-size: var(--font-size-base);
        margin-right: 0.4rem;
    }

    #document-type {
        font-size: calc(var(--font-size-base) - 0.2rem);
        padding: 0.7rem 1rem;
        width: 13rem;
        border: 1px solid #ccc;
        border-radius: var(--border-radius);
        height: 100%;
    }

    #file-label {
        font-size: calc(var(--font-size-base) - 0.2rem);
        display: flex;
        width: 13rem;
        padding: 0 1rem;
        border: 1px solid #ccc;
        border-radius: var(--border-radius) 0 0 var(--border-radius);
        cursor: not-allowed;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 100%;
        background-color: white;
        align-items: center;
    }

    #clear-file-btn {
        font-size: var(--font-size-sm);
        display: none;
        margin-left: 10px;
        color: red;
        cursor: pointer;
        height: 100%;
        align-items: center;
    }

    #upload-btn {
        font-size: calc(var(--font-size-base) - 0.2rem);
        display: inline-block;
        padding: 0.7rem 1rem;
        background-color: grey;
        color: white;
        border: none;
        cursor: not-allowed;
        border-radius: 0 var(--border-radius) var(--border-radius) 0;
        vertical-align: middle;
        height: 100%;
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
        .btn-primary {
            background-color: var(--primary-button-color);
        }
        .btn-primary::before {
            content: 'Ver archivo';
        }
    }
</style>

<body>
    <div class="container-principal">
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
                clearFileBtn.style.display = "flex"; // Mostrar el botón de quitar archivo
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

                uploadBtn.disabled = true;
                uploadBtn.textContent = 'Subiendo...';

                fetch('upload_document.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar la tabla directamente
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
                    uploadBtn.disabled = false;
                    uploadBtn.textContent = 'Subir archivo';
                });
            }
        }

        function updateDocumentsTable() {
            fetch('../Acciones globales/get_documents.php')
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const tableBody = document.getElementById('documents-table-body');
                        tableBody.innerHTML = '';
                        
                        if (result.data.length === 0) {
                            // Si no hay documentos, mostrar mensaje
                            const row = tableBody.insertRow();
                            const cell = row.insertCell(0);
                            cell.colSpan = 3;
                            cell.textContent = 'No se encontraron documentos';
                        } else {
                            // Si hay documentos, mostrar la tabla normal
                            result.data.forEach(doc => {
                                const row = tableBody.insertRow();
                                
                                const dateCell = row.insertCell(0);
                                dateCell.setAttribute('data-label', 'Fecha');
                                dateCell.textContent = doc.date;
                                
                                const typeCell = row.insertCell(1);
                                typeCell.setAttribute('data-label', 'Tipo');
                                typeCell.textContent = doc.type;
                                
                                const viewCell = row.insertCell(2);
                                viewCell.setAttribute('data-label', 'Vista');
                                viewCell.innerHTML = `
                                    <span class="btn btn-primary" onclick="viewDocument('${doc.fileURL}')">👁</span>
                                `;
                            });
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
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