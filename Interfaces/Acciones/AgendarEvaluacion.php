<?php
  include('../Header/MenuC.php');
?>


<?php
include('../../conexion.php');
$Con = Conectar();

$SQL = "SELECT exp, nombre, a_paterno, a_materno FROM estudiantes" ;
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
            --secondary-color: #366d6f;
            --text-color: #3c4043;
            --background-color: #fafcff;
        }

        .container-agendar-evaluacion {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 80vh;
            padding: 1rem;
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
            max-width: 80%; /* Asegurar que la tabla no sobrepase el contenedor */
        }

        tr {
            border-top: 0.1rem solid var(--primary-color);
            border-bottom: 0.1rem solid var(--primary-color);
        }

        th, td {
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

        @media (max-width: 48rem) {

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
            <th>Confirmar</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($Res->num_rows > 0){
            while($Fila = $Res->fetch_assoc()){
              $NombreCom = $Fila["nombre"] . " " . $Fila["a_paterno"] . " " . $Fila["a_materno"];
              echo "<tr>";
              echo "<td>" . $Fila["exp"] . "</td>";
              echo "<td>" . $NombreCom . "</td>";
              echo "<td><div class='input-container'><input type='date' id='fecha-seleccionada'><span class='check-icon'></span></div></td>";
              echo "<td><div class='input-container'><input type='time' id='hora-seleccionada' ><span class='check-icon'></span></div></td>";
              echo "<td><button class='confirmar-icon' onclick='confirmarAsignacion(&quot;301574&quot;)'>✔</button></td>";
              
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
      // Aquí puedes implementar la lógica para enviar los datos al servidor y actualizar la base de datos
      alert('Confirmación de evaluación para el expediente: ' + expediente);
      // Ejemplo de llamada AJAX para actualizar la base de datos
      /*
      fetch('ruta/a/tu/script.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ exp: expediente, fecha: 'fecha-seleccionada', hora: 'hora-seleccionada' })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Evaluación confirmada exitosamente');
        } else {
          alert('Error al confirmar la evaluación');
        }
      });
      */
    }
  </script>

</body>


</html>