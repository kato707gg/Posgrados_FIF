<?php
  include('../Header/MenuC.php');
?>


<?php
include('../../conexion.php');
$Con = Conectar();

$SQL = "
SELECT e.exp, e.nombre, e.a_paterno, e.a_materno
FROM estudiantes e
LEFT JOIN evaluaciones ev ON e.exp = ev.exp_alumno
WHERE ev.exp_alumno IS NULL;
" ;
$Res = Ejecutar($Con, $SQL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Header/styles.css">
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
            border-radius: 0.4vw;
            background-color: #e9e9f3;
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

        .confirmar-icon {
            color: #123773;
            margin: auto;
            font-size: 1.5rem;
            padding: 0.5rem 0.9rem;
            background-color: #e0e0e0;
            border: none;
            cursor: pointer;
            border-radius: 0.4rem;
        }

        @media screen and (max-width: 1600px) {

          .container-agendar-evaluacion {
                height: 79vh;
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
            <th> Expediente</th>
            <th>Nombre</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Aula</th>
            <th>Confirmar</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($Res->num_rows > 0){
            while($Fila = $Res->fetch_assoc()){
              $NombreCom = $Fila["nombre"] . " " . $Fila["a_paterno"] . " " . $Fila["a_materno"];
              $exp = $Fila["exp"];
              echo "<tr>";
              echo "<td>" . $exp . "</td>";
              echo "<td>" . $NombreCom . "</td>";
              echo "<td><div class='input-container'><input type='date' id='fecha-" . $exp . "'><span class='check-icon'></span></div></td>";
              echo "<td><div class='input-container'><input type='time' id='hora-" . $exp . "' ><span class='check-icon'></span></div></td>";
              echo "<td><div class='input-container'><input type='text' id='aula-" . $exp . "'><span class='check-icon'></span></div></td>";
              echo "<td><button class='confirmar-icon' onclick='confirmarEvaluacion(\"" . $exp . "\")'>✔</button></td>";
              echo "</tr>";
            }
          }else{
            echo "<tr><td colspan = '6'>No se encontraron estudiantes </td></tr>";
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

      if (!fechaSeleccionada || !horaSeleccionada || !aula) {
        alert('Por favor, selecciona tanto la fecha como la hora antes de confirmar o ingresa el aula.');
        return;
      }

      // Crear una nueva instancia de XMLHttpRequest
      const xhr = new XMLHttpRequest();

      // Configurar la solicitud
      xhr.open('POST', 'insertar_evaluacion.php', true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          alert('Evaluación confirmada exitosamente');
          // alert(xhr.responseText);
          location.reload();
        }
      };
      xhr.send("exp=" + expediente + "&fecha=" + fechaSeleccionada + "&hora" + horaSeleccionada + "&aula=" + aula);

      // Configurar manejo de errores
      xhr.onerror = function() {
        console.error('Error de red');
        alert('Ocurrió un error al procesar la solicitud');
      };
    }
  </script>

</body>


</html>