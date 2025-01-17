<?php
include('../Header/MenuD.php');
// Verificar si ya hay una sesión activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de conexión
include '../../Config/conexion.php';

// Conectar a la base de datos
$Con = Conectar();

$clave_coordinador = $_SESSION['id'];
$SQL = "
SELECT DISTINCT
    a.exp_alumno,
    e.nombre,
    e.a_paterno,
    e.a_materno,
    ev.aula,
    ev.fecha_evaluacion,
    de.calificacion,
    de.observacion,
    de.periodo
FROM 
    asignaciones a
LEFT JOIN 
    estudiantes e ON a.exp_alumno = e.exp
LEFT JOIN 
    evaluaciones ev ON a.exp_alumno = ev.exp_alumno
INNER JOIN 
    detalle_evaluaciones de ON (
        ev.id = de.id_evaluacion AND
        de.id_sinodo = $clave_coordinador
    )
WHERE 
    (a.director = $clave_coordinador OR a.sinodo2 = $clave_coordinador OR 
    a.sinodo3 = $clave_coordinador OR a.externo = $clave_coordinador)
    AND de.calificacion != 0
    AND de.calificacion IS NOT NULL
    AND de.observacion IS NOT NULL
";

$SQL2 = "SELECT DISTINCT periodo FROM detalle_evaluaciones WHERE periodo <> '' AND id_sinodo = '$clave_coordinador'" ;
$Resultado = Ejecutar($Con, $SQL);
$Periodos = Ejecutar($Con, $SQL2);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/components/tablas.css">
    <link rel="stylesheet" href="../../CSS/components/modales.css">
    <link rel="stylesheet" href="../../CSS/components/buttons.css">
    <link rel="stylesheet" href="../../CSS/transitions.css">
    <title>Historial de Evaluaciones</title>
</head>
<style>

    #observacionContent {
        font-size: 1rem;
        color: var(--text-color);
        line-height: 1.5;
    }

    @media (max-width: 770px) {
            .btn-primary {
                background-color: var(--primary-button-color);
            }
            .btn-primary::before {
                content: 'Ver';
            }
        }

</style>

<body>
<div class="container-principal">
    <h3>Historial de evaluaciones:</h3>
    <div id="table-container">
        <table>
            <thead>
                <tr>
                    <th>Expediente</th>
                    <th>
                        Nombre
                        <br>
                        <input class="buscar nombre" type="text" id="search-name" placeholder="Buscar...">
                    </th>
                    <th>Fecha</th>
                    <th>
                        Periodo
                        <br>
                        <select class="buscar periodo" id="search-periodo">
                            <option value="" disabled selected hidden>Seleccionar...</option>
                            <?php
                            if ($Periodos->num_rows > 0) {
                                while ($row = $Periodos->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($row['periodo']) . "'>" . htmlspecialchars($row['periodo']) . "</option>";
                                }
                            } else {
                                echo "<option value=''>No se encontraron periodos</option>";
                            }
                            ?>
                        </select>
                    </th>
                    <th>Calificación</th>
                    <th>Observaciones</th>
                    
                </tr>
            </thead>
            <tbody id="table-body">
                <?php
                if ($Resultado->num_rows > 0){
                    while ($Fila = $Resultado->fetch_assoc()){
                        $Nombre = $Fila["nombre"] . " " . $Fila["a_paterno"] . " " . $Fila["a_materno"];
                        echo "<tr data-expediente='" . $Fila['exp_alumno'] . "' data-nombre='" . $Nombre . "' data-periodo='" . $Fila['periodo'] . "'>";
                        echo "<td data-label='Expediente'>" . $Fila ["exp_alumno"] . "</td>";
                        echo "<td data-label='Nombre'>" . $Nombre . "</td>";
                        $Fecha = $Fila["fecha_evaluacion"];
                        $FechaSola = !empty($Fecha) ? date('Y-m-d', strtotime($Fecha)) : "Pendiente";
                        echo "<td data-label='Fecha'>" . $FechaSola . "</td>";
                        echo "<td data-label='Periodo'>" . $Fila["periodo"] . "</td>";
                        echo "<td data-label='Calificación'>" . $Fila["calificacion"] . "</td>";
                        echo "<td data-label='Observaciones'><button class='btn btn-primary' data-observacion='" . htmlspecialchars($Fila["observacion"]) . "'>👁</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No se encontraron evaluaciones</td></tr>";
                }
                Cerrar($Con);
                ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modalObservaciones" class="modal">
    <div class="modal-content small">
        <span class="close">&times;</span>
        <h3>Observaciones y Recomendaciones</h3>
        <hr class="x-component x-component-default" style="border-top: 0;border-bottom: 0.05rem solid #196ad3;margin:auto;width: 100%;margin-bottom: 2rem;">
        <div id="observacionContent"></div>
    </div>
</div>

<script>
    document.getElementById('search-name').addEventListener('input', filterTable);
    document.getElementById('search-periodo').addEventListener('change', filterTable);

    function filterTable() {
        const nameInput = document.getElementById('search-name').value.toLowerCase();
        const periodoInput = document.getElementById('search-periodo').value;
        const rows = document.querySelectorAll('#table-body tr');

        rows.forEach(row => {
            const nombre = row.getAttribute('data-nombre').toLowerCase();
            const periodo = row.getAttribute('data-periodo');
            const matchesName = nombre.includes(nameInput);
            const matchesPeriodo = periodoInput === '' || periodo === periodoInput;

            if (matchesName && matchesPeriodo) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    /* Estilo placeholder en select */
    document.getElementById('search-periodo').addEventListener('change', function() {
        if (this.value) {
            this.style.color = 'var(--text-color)'; // Cambia al color fuerte
        } else {
            this.style.color = '#757575'; // Mantiene el color tenue si no hay selección
        }
    });

    // Obtener todos los botones de ver observación
    const botonesObservacion = document.querySelectorAll('.btn-primary');
    const modal = document.getElementById('modalObservaciones');
    const observacionContent = document.getElementById('observacionContent');

    // Agregar evento click a cada botón
    botonesObservacion.forEach(boton => {
        boton.addEventListener('click', () => {
            const observacion = boton.getAttribute('data-observacion');
            observacionContent.textContent = observacion;
            modal.style.display = "block";
            // Forzar un reflow para que la transición funcione
            modal.offsetHeight;
            modal.classList.add('show');
        });
    });

    // Cerrar modal con el botón X
    document.querySelector('.close').onclick = () => {
        cerrarModal();
    };

    // Cerrar modal al hacer clic fuera
    window.onclick = (event) => {
        if (event.target === modal) {
            cerrarModal();
        }
    };

    function cerrarModal() {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = "none";
        }, 300); // Este tiempo debe coincidir con la duración de la transición en CSS
    }
</script>

</body>

</html>
