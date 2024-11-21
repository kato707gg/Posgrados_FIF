<?php
  include('../../Header/MenuA.php');
  if(session_status()===PHP_SESSION_NONE){
    session_start();
   }
include('../../../conexion.php');
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
    <title>Agendar Evaluación</title>
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

        .container-agendar-evaluacion {
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

        #table-container {
            display: flex;
            justify-content: center;
            overflow-x: auto; /* Habilitar desplazamiento horizontal si es necesario */
            overflow-y: auto; /* Habilitar desplazamiento vertical dentro del contenedor */
            width: 100%;
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

        .input-container input {
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-color);
            border: 1px solid #ccc;
            padding: 0.5rem;
            border-radius: 0.5rem;
            width: 14vh;
        }

        .input-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .subir-entregable {
            color: #123773;
            font-size: 1.5rem;
            padding: 0.5rem 0.9rem;
            background-color: #ffffff;
            border: none;
            cursor: pointer;
            border-radius: 0.4rem;
            border-bottom: 0.0625rem solid var(--secondary-color);
        }

        .subir-entregable:hover {
            background-color: #cfcfcf;
        }

        .confirmar-icon {
            color: #123773;
            margin: auto;
            font-size: 1.5rem;
            padding: 0.5rem 0.9rem;
            background-color: #ffffff;
            border: none;
            cursor: pointer;
            border-radius: 0.4rem;
            border-bottom: 0.0625rem solid var(--secondary-color);
        }

        .confirmar-icon:hover {
            background-color: #cfcfcf;
        }

        .subir-entregable.archivo-subido {
            background-color: #cfcfcf; /* Verde claro para indicar archivo subido */
        }

        .quitar-archivo {
            color: red;
            margin-left: 10px;
            cursor: pointer;
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            font-size: 0.9rem;
            display: none; /* Inicialmente oculto */
        }

        @media screen and (max-width: 1600px) {

          .container-agendar-evaluacion {
                height: 79vh;
            }

        }

        @media screen and (max-width: 820px) {
            .container-agendar-evaluacion {
                height: 83.5vh;
            }
        }

        @media (max-width: 770px) {

            #table-container {
                display: unset;
            }
            table {
                font-size: 0.9rem;
            }

            th, td {
                width: 20%;
                font-size: 1rem;
                padding: 0.75rem;
            }

            h3 {
                font-size: 1.5rem;
            }

            button {
                height: 2.5rem;
                font-size: 0.9rem;
            }
            .input-container input {
                font-size: 0.8rem;
            }
            .confirmar-icon {
                padding-top: 0.25rem;
            }
        }
    </style>
</head>

<body>
  <div class="container-agendar-evaluacion">
    <h3>Agendar Evaluación:</h3>
    <div id="table-container">
      <table>
        <thead>
          <tr>
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
              echo "<td><div class='input-container'><input type='date' id='fecha-" . $exp . "'><span class='check-icon'></span></div></td>";
              echo "<td><div class='input-container'><input type='time' id='hora-" . $exp . "' ><span class='check-icon'></span></div></td>";
              echo "<td><div class='input-container'><input type='text' id='aula-" . $exp . "'><span class='check-icon'></span></div></td>";
              echo "<td>
                      <input type='file' id='file-" . $exp . "' style='display: none;' accept='.pdf,.docx,.xlsx'>
                      <div style='display: flex; align-items: center; justify-content: center;'>
                          <button id='upload-btn-" . $exp . "' class='subir-entregable' onclick='uploadDocument(\"" . $exp . "\")'>📁</button>
                          <span id='quitar-" . $exp . "' class='quitar-archivo' onclick='quitarArchivo(\"" . $exp . "\")'>Quitar archivo &times</span>
                      </div>
                    </td>";
              echo "<td><button id='confirm-btn-" . $exp . "' class='confirmar-icon' onclick='confirmarEvaluacion(\"" . $exp . "\")' disabled>✔</button></td>";
              echo "</tr>";
            }
          }else{
            echo "<tr><td colspan = '6'>No se encontraron evaluaciones</td></tr>";
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

          // Verificar que los campos no estén vacíos
          if (!fechaSeleccionada || !horaSeleccionada || !aula) {
              alert('Por favor, selecciona tanto la fecha como la hora antes de confirmar o ingresa el aula.');
              return;
          }

          // Combina la fecha y hora en el formato DATETIME (YYYY-MM-DD HH:MM:SS)
          const fechaHoraCombinada = fechaSeleccionada + ' ' + horaSeleccionada;

          // Crear una nueva instancia de XMLHttpRequest
          const xhr = new XMLHttpRequest();

          // Configurar la solicitud
          xhr.open('POST', '../insertar_evaluacion.php', true);
          xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

          // Manejo de la respuesta del servidor
          xhr.onreadystatechange = function() {
              if (xhr.readyState === 4 && xhr.status === 200) {
                  alert(xhr.responseText); // Mostrar respuesta del servidor
                  location.reload(); // Recargar la página
              }
          };

          // Enviar la fecha y hora combinadas, y otros datos
          xhr.send("exp=" + expediente + "&fecha_evaluacion=" + encodeURIComponent(fechaHoraCombinada) + "&aula=" + encodeURIComponent(aula));

          // Manejo de errores
          xhr.onerror = function() {
              console.error('Error de red');
              alert('Ocurrió un error al procesar la solicitud');
          };
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
                uploadBtn.classList.add('archivo-subido');
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
        uploadBtn.classList.remove('archivo-subido');
        quitarBtn.style.display = 'none';

            // Deshabilitar el botón "Confirmar"
            confirmBtn.disabled = true;
    }
  </script>

</body>


</html>