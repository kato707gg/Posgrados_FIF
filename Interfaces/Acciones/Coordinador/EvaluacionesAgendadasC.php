<?php
  include('../../Header/MenuC.php');
?>

<?php
include('../../../conexion.php');
$Con = Conectar();

$SQL = "
    SELECT ev.exp_alumno, e.nombre, e.a_paterno, e.a_materno, ev.fecha_evaluacion, ev.aula
    FROM evaluaciones ev
    JOIN estudiantes e ON ev.exp_alumno = e.exp;
";

$Res = Ejecutar($Con, $SQL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../tablas.css">
    <title>Evaluaciones Agendadas</title>
    <style>

        .eliminar-icon {
            color: #123773;
            font-size: 1rem;
            padding: 0.7rem 0.9rem 0.75rem;
            background-color: #ffffff;
            border: none;
            cursor: pointer;
            border-radius: clamp(.4rem, .4vw, .4rem);
            border-bottom: 0.0625rem solid var(--secondary-color);
        }

        .eliminar-icon:hover {
            background-color: #cfcfcf;
        }

        @media (max-width: 770px) {
          .eliminar-icon {
            background-color: red;
            color: white;
            padding: 0.7rem 0.9rem;
            border: none;
            cursor: pointer;
          }

          .eliminar-icon::before {
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            content: 'Confirmar';
          }

          .eliminar-icon {
            font-size: 0;  
          }
        }
        
    </style>
</head>

<body>
  <div class="container-principal">
    <h3>Evaluaciones Agendadas:</h3>
    <div id="table-container">
      <table>
        <thead>
          <tr>
            <th>Expediente</th>
            <th>Nombre</th>
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
              $NombreCom = $Fila["nombre"] . " " . $Fila["a_paterno"] . " " . $Fila["a_materno"];
              $exp = $Fila["exp_alumno"];
              $fecha = date('Y-m-d', strtotime($Fila['fecha_evaluacion']));
              $hora = date('H:i', strtotime($Fila['fecha_evaluacion']));
              echo "<tr id='fila-" . $exp . "'>";
              echo "<td data-label='Expediente'>" . $exp . "</td>";
              echo "<td data-label='Nombre'>" . $NombreCom . "</td>";
              echo "<td data-label='Fecha'>" . $fecha . "</td>";
              echo "<td data-label='Hora'>" . $hora . "</td>";
              echo "<td data-label='Aula'>" . $Fila['aula'] . "</td>";
              echo "<td data-label='Eliminar'><button class='eliminar-icon' onclick='eliminarEvaluacion(\"" . $exp . "\")'>❌</button></td>";
              echo "</tr>";
            }
          }else{
            echo "<tr><td colspan = '6'>No se encontraron evaluaciones agendadas </td></tr>";
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
        xhr.open('POST', '../eliminar_evaluacion.php', true);
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
