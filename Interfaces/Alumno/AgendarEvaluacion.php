<?php
  include('../Header/MenuA.php');
  if(session_status()===PHP_SESSION_NONE){
    session_start();
   }
include('../../Config/conexion.php');
$Con = Conectar();
$id = $_SESSION['id'];

$SQL = "
SELECT e.exp, e.nombre, e.a_paterno, e.a_materno
FROM estudiantes e
LEFT JOIN evaluaciones ev ON e.exp = ev.exp_alumno
LEFT JOIN asignaciones a ON e.exp = a.exp_alumno
WHERE ev.fecha_evaluacion IS NULL 
AND a.exp_alumno = '$id';
";
$Res = Ejecutar($Con, $SQL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/components/tablas.css">
    <link rel="stylesheet" href="../../CSS/components/buttons.css">
    <link rel="stylesheet" href="../../CSS/transitions.css">
    <title>Agendar Evaluación</title>
    <style>
        .quitar-archivo {
            color: red;
            margin-left: 10px;
            cursor: pointer;
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            font-size: 0.9rem;
            display: none; /* Inicialmente oculto */
        }

        @media (max-width: 770px) {
            .btn-secondary::before {
                content: 'Adjuntar';
            }
            .quitar-archivo::before {
                font-size: 1.5rem;
                content: '❌';
            }
            .quitar-archivo {
                font-size: 0;  /* Oculta el texto original */
            }
        }
    </style>
</head>

<body>
  <div class="container-principal">
    <h3>Agendar Evaluación:</h3>
    <div id="table-container">
      <table>
        <thead>
          <tr>
            <th>Semestre</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Aula</th>
            <th>Entregable</th>
            <th>Confirmar</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($Res->num_rows > 0){
            while($Fila = $Res->fetch_assoc()){
              $exp = $Fila["exp"];
              echo "<tr>";
              echo "<td data-label='Semestre'><div'><select class='inputs' id='semestre-" . $exp . "' required>
                <option value='' disabled selected>Selecciona...</option>
                <option value='1'>1</option>
                <option value='2'>2</option>
                <option value='3'>3</option>
                <option value='4'>4</option>
                <option value='5'>5</option>
                <option value='6'>6</option>
                <option value='7'>7</option>
                <option value='8'>8</option>
              </select><span class='check-icon'></span></div></td>";
              echo "<td data-label='Fecha'><div'><input type='date' class='inputs' id='fecha-" . $exp . "'><span class='check-icon'></span></div></td>";
              echo "<td data-label='Hora'><div'><input type='time' class='inputs' id='hora-" . $exp . "' ><span class='check-icon'></span></div></td>";
              echo "<td data-label='Aula'><div'><input type='text' class='inputs' id='aula-" . $exp . "'><span class='check-icon'></span></div></td>";
              echo "<td data-label='Entregable'>
                      <input type='file' id='file-" . $exp . "' style='display: none;' accept='.pdf,.docx,.xlsx'>
                      <div style='display: flex; align-items: center; justify-content: center;'>
                          <button id='upload-btn-" . $exp . "' class='btn btn-secondary' onclick='uploadDocument(\"" . $exp . "\")'>📁</button>
                          <span id='quitar-" . $exp . "' class='quitar-archivo' onclick='quitarArchivo(\"" . $exp . "\")'>Quitar archivo &times</span>
                      </div>
                    </td>";
              echo "<td data-label='Confirmar'><button id='confirm-btn-" . $exp . "' class='btn btn-primary' onclick='confirmarEvaluacion(\"" . $exp . "\")' disabled>✔</button></td>";
              echo "</tr>";
            }
          }else{
            echo "<tr><td colspan='6'>No se encontraron evaluaciones</td></tr>";
          }
          Cerrar($Con);
          ?>
        </tbody>
      </table>
    </div>
  </div>

    <script>
        function confirmarEvaluacion(expediente) {
            const fechaSeleccionada = document.getElementById('fecha-' + expediente).value;
            const horaSeleccionada = document.getElementById('hora-' + expediente).value;
            const aula = document.getElementById('aula-' + expediente).value;
            const semestre = document.getElementById('semestre-' + expediente).value;
            const fileInput = document.getElementById('file-' + expediente);

            // Verificar que los campos no estén vacíos
            if (!fechaSeleccionada || !horaSeleccionada || !aula || !fileInput.files.length || !semestre) {
                alert('Por favor, completa todos los campos y selecciona un archivo.');
                return;
            }

            // Combina la fecha y hora en el formato DATETIME
            const fechaHoraCombinada = fechaSeleccionada + ' ' + horaSeleccionada;

            // Crear un objeto FormData
            const formData = new FormData();
            formData.append('exp', expediente);
            formData.append('fecha_evaluacion', fechaHoraCombinada);
            formData.append('aula', aula);
            formData.append('semestre', semestre);
            formData.append('entregable', fileInput.files[0]);
        
            // Crear una solicitud XMLHttpRequest
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '../../../Posgrados_FIF/insertar_evaluacion.php', true);
        
            // Manejo de la respuesta del servidor
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText); // Mostrar respuesta del servidor
                    location.reload(); // Recargar la página
                }
            };
        
            // Manejo de errores de la solicitud
            xhr.onerror = function () {
                console.error('Error de red');
                alert('Ocurrió un error al procesar la solicitud');
            };
        
            // Enviar la solicitud con los datos
            xhr.send(formData); // Enviar el FormData, que contiene todos los datos
        }

        function uploadDocument(expediente) {

            const fileInput = document.getElementById('file-' + expediente);
            const uploadBtn = document.getElementById('upload-btn-' + expediente);
            const quitarBtn = document.getElementById('quitar-' + expediente);
            const confirmBtn = document.getElementById('confirm-btn-' + expediente);

            fileInput.click();

            fileInput.addEventListener('change', function() {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                console.log('Archivo seleccionado:', file.name);

                // Cambiar apariencia del botón
                uploadBtn.classList.add('accion-completada');
                quitarBtn.style.display = 'inline';

                // Habilitar el botón "Confirmar"
                confirmBtn.disabled = false;
                }
            });
        }


        function quitarArchivo(expediente) {
            const fileInput = document.getElementById('file-' + expediente);
            const uploadBtn = document.getElementById('upload-btn-' + expediente);
            const quitarBtn = document.getElementById('quitar-' + expediente);
            const confirmBtn = document.getElementById('confirm-btn-' + expediente);

            // Limpiar el input file
            fileInput.value = '';

            // Restaurar apariencia original
            uploadBtn.classList.remove('accion-completada');
            quitarBtn.style.display = 'none';

            // Deshabilitar el botón "Confirmar"
            confirmBtn.disabled = true;
        }
    
    </script>

</body>

</html>