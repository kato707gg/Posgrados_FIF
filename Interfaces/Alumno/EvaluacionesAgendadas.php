<?php
  include('../Header/MenuA.php');
 if(session_status()===PHP_SESSION_NONE){
  session_start();
 }


include('../../Config/conexion.php');
$Con = Conectar();
$id = $_SESSION['id'];

$SQL = "
    SELECT e.exp_alumno, e.fecha_evaluacion, e.aula, e.id,
           (SELECT COUNT(*) 
            FROM detalle_evaluaciones de 
            WHERE de.id_evaluacion = e.id 
            AND de.calificacion IS NOT NULL) as tiene_calificaciones
    FROM evaluaciones e 
    WHERE e.exp_alumno = '$id'
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
    <title>Evaluaciones Agendadas</title>
</head>

<body>
  <div class="container-principal">
    <h3>Evaluaciones Agendadas:</h3>
    <div id="table-container">
          <table>
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Aula</th>
            <th>Entregables</th>
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

              // Obtener lista de archivos en la carpeta de entregables
              $entregables = [];
              $dir = "../../Entregables/$exp/";
              if (is_dir($dir)) {
                  $files = scandir($dir);
                  foreach ($files as $file) {
                      if ($file !== '.' && $file !== '..') {
                          $entregables[] = "<a href='$dir$file' target='_blank'>$file</a>";
                      }
                  }
              }
              $entregablesContent = empty($entregables) ? "No disponible" : implode(", ", $entregables);

              echo "<tr id='fila-" . $exp . "'>";
              echo "<td data-label='Fecha'>" . $fecha . "</td>";
              echo "<td data-label='Hora'>" . $hora . "</td>";
              echo "<td data-label='Aula'>" . $Fila['aula'] . "</td>";
              echo "<td data-label='Entregables'>" . $entregablesContent . "</td>";
              if ($Fila['tiene_calificaciones'] > 0) {
                  echo "<td data-label='Eliminar'>No disponible</td>";
              } else {
                  echo "<td data-label='Eliminar'><button class='btn btn-eliminar' onclick='eliminarEvaluacion(\"" . $exp . "\")'>❌</button></td>";
              }
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='5'>No se encontraron evaluaciones agendadas</td></tr>";
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
        xhr.open('POST', '../Acciones globales/eliminar_evaluacion.php', true);
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
            location.reload();
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
