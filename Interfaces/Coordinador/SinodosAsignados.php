<?php
include('../Header/MenuC.php');
if(session_status()===PHP_SESSION_NONE){
    session_start();
}

include('../../Config/conexion.php');
$Con = Conectar();

// Consulta para obtener los sinodos asignados
$SQL = "
SELECT 
    a.exp_alumno,
    e.nombre as nombre_alumno,
    e.a_paterno as ap_alumno,
    e.a_materno as am_alumno,
    d1.nombre as nombre_director,
    d1.a_paterno as ap_director,
    d2.nombre as nombre_sinodo2,
    d2.a_paterno as ap_sinodo2,
    d3.nombre as nombre_sinodo3,
    d3.a_paterno as ap_sinodo3,
    d4.nombre as nombre_externo,
    d4.a_paterno as ap_externo
FROM asignaciones a
INNER JOIN estudiantes e ON a.exp_alumno = e.exp
LEFT JOIN docentes d1 ON a.director = d1.clave
LEFT JOIN docentes d2 ON a.sinodo2 = d2.clave
LEFT JOIN docentes d3 ON a.sinodo3 = d3.clave
LEFT JOIN docentes d4 ON a.externo = d4.clave
ORDER BY e.a_paterno, e.a_materno, e.nombre
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
    <title>Sinodos Asignados</title>
</head>

<body>
    <div class="container-principal">
        <h3>Sinodos Asignados:</h3>
        <div id="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Sinodo Director</th>
                        <th>Sinodo 2</th>
                        <th>Sinodo 3</th>
                        <th>Sinodo Externo</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($Res->num_rows > 0){
                        while($Fila = $Res->fetch_assoc()){
                            $exp = $Fila["exp_alumno"];
                            echo "<tr id='fila-" . $exp . "'>";
                            echo "<td data-label='Alumno'>" . $Fila['ap_alumno'] . " " . $Fila['am_alumno'] . " " . $Fila['nombre_alumno'] . "</td>";
                            echo "<td data-label='Sinodo Director'>" . $Fila['ap_director'] . " " . $Fila['nombre_director'] . "</td>";
                            echo "<td data-label='Sinodo 2'>" . $Fila['ap_sinodo2'] . " " . $Fila['nombre_sinodo2'] . "</td>";
                            echo "<td data-label='Sinodo 3'>" . $Fila['ap_sinodo3'] . " " . $Fila['nombre_sinodo3'] . "</td>";
                            echo "<td data-label='Sinodo Externo'>" . $Fila['ap_externo'] . " " . $Fila['nombre_externo'] . "</td>";
                            echo "<td data-label='Eliminar'><button class='btn btn-eliminar' onclick='eliminarAsignacion(\"" . $exp . "\")'>❌</button></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No hay sinodos asignados</td></tr>";
                    }
                    Cerrar($Con);
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function eliminarAsignacion(expediente) {
            if (confirm('¿Estás seguro de que quieres eliminar esta asignación de sinodos?')) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'eliminar_asignacion.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                
                xhr.onload = function() {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            var fila = document.getElementById('fila-' + expediente);
                            if (fila) {
                                fila.remove();
                            }
                            alert('Asignación eliminada exitosamente.');
                            location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', xhr.responseText);
                        alert('Error al procesar la respuesta del servidor');
                    }
                };
                
                xhr.onerror = function() {
                    alert('Error de conexión al servidor');
                };
                
                xhr.send('expediente=' + expediente);
            }
        }
    </script>
</body>
</html>
