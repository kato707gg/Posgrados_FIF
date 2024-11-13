<?php
  include('../Header/MenuA.php');
 if(session_status()===PHP_SESSION_NONE){
  session_start();
 }


include('../../conexion.php');
$Con = Conectar();
$id = $_SESSION['id'];

$SQL = "
    SELECT exp_alumno, fecha_evaluacion, aula FROM evaluaciones WHERE exp_alumno = '$id'
";

$Res = Ejecutar($Con, $SQL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Header/styles.css">
    <title>Evaluaciones Agendadas</title>
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

        .input-container input {
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-color);
            border: 1px solid #ccc;
            padding: 0.5rem;
            border-radius: 0.5rem;
        }

        .input-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .eliminar-icon {
            color: #123773;
            margin: auto;
            font-size: 1rem;
            padding: 0.7rem 0.9rem;
            background-color: #ffffff;
            border: none;
            cursor: pointer;
            border-radius: 0.4rem;
            border-bottom: 0.0625rem solid var(--secondary-color);
        }

        .eliminar-icon:hover {
            background-color: #cfcfcf;
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
    <h3>Evaluaciones Agendadas:</h3>
    <div id="table-container">
          <table>
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Aula</th>
            <th>Eliminar</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($Res->num_rows > 0){
            while($Fila = $Res->fetch_assoc()){
              $exp = $Fila["exp_alumno"];
              $fechaCompleta = $Fila['fecha_evaluacion'];
              
              // Dividimos la fecha y la hora
              $fecha = date("Y-m-d", strtotime($fechaCompleta));
              $hora = date("H:i:s", strtotime($fechaCompleta));

              echo "<tr id='fila-" . $exp . "'>";
              echo "<td>" . $fecha . "</td>";
              echo "<td>" . $hora . "</td>";
              echo "<td>" . $Fila['aula'] . "</td>";
              echo "<td><button class='eliminar-icon' onclick='eliminarEvaluacion(\"" . $exp . "\")'>❌</button></td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='4'>No se encontraron evaluaciones agendadas</td></tr>";
          }
          Cerrar($Con);
          ?>
        </tbody>
      </table>

    </div>
  </div>

</body>
<script>
    function eliminarEvaluacion(expediente) {
      // Confirmar eliminación
      if (confirm('¿Estás seguro de que quieres eliminar esta evaluación?')) {
        // Crear el objeto XMLHttpRequest
        var xhr = new XMLHttpRequest();
        
        // Configurar la solicitud
        xhr.open('POST', 'eliminar_evaluacion.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        
        // Definir lo que sucederá cuando la solicitud se complete
        xhr.onload = function() {
          if (xhr.status === 200) {
            // Eliminar la fila de la tabla si la solicitud fue exitosa
            var fila = document.getElementById('fila-' + expediente);
            if (fila) {
              fila.remove();
            }
            alert('Evaluación eliminada exitosamente.');
          } else {
            alert('Hubo un error al eliminar la evaluación.');
          }
        };
        
        // Enviar la solicitud con el expediente del alumno
        xhr.send('accion=eliminar&expediente=' + expediente);
      }
    }
</script>
</html>
