<?php
include('../../Header/MenuD.php');
// Verificar si ya hay una sesión activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de conexión
include '../../../conexion.php';

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
    <link rel="stylesheet" href="../../Header/styles.css">  
    <title>Historial de Evaluaciones</title>
</head>
<style>
    body {
        margin: 0;
        padding: 0;
    }

    :root {
        --primary-color: rgb(26,115,232);
        --secondary-color: #aaa;
        --text-color: #3c4043;
        --background-color: #fafcff;
    }

    .container-historial-evaluaciones {
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

    .buscar {
        font-family: "Google Sans", Roboto, Arial, sans-serif;
        border-bottom: 1px solid #636363;
        outline: none;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-color);
        border: 1px solid #ccc;
        padding: 0.5rem;
        border-radius: clamp(.4rem, .4vw, .4rem);   
    }

    /* Estilo placeholder en select */
    .buscar.periodo {
        color: #757575; /* Color del texto del placeholder */
    }

    .buscar.periodo option {
        color: var(--text-color); /* Color del texto de las opciones */
    }

    .buscar.periodo:focus {
        outline: none;
    }

    .buscar.periodo option:first-child {
        display: none; /* Ocultar el primer option si es el placeholder */
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

    #title-container {
        display: flex;
        justify-content: space-between;
        width: 100%;
        align-items: center;
    }

    #table-container {
        display: flex !important;
        justify-content: center;
        overflow-x: auto;
    }

    
    @media screen and (max-width: 1600px) {

        .container-historial-evaluaciones {
            height: 79vh;
        }

    }

    @media screen and (max-width: 820px) {
        .container-historial-evaluaciones {
            height: 83.5vh;
        }
    }

    @media (max-width: 770px) {
        table {
            font-size: 0.9rem;
        }

        th, td {
            font-size: 1.1rem;
            padding: 0.75rem;
        }

        h3 {
            font-size: 1.5rem;
        }

        button {
            height: 2.5rem;
            font-size: 0.9rem;
        }
    }
</style>

<body>
<div class="container-historial-evaluaciones">
    <h3>Historial de evaluaciones:</h3>
    <div id="table-container">
        <table>
            <thead>
                <tr>
                    <th>Expediente</th>
                    <th>
                        Nombre
                        <br>
                        <input class="buscar nombre" type="text" id="search-name" placeholder="Buscar por nombre...">
                    </th>
                    <th>Fecha</th>
                    <th>Aula</th>
                    <th>Calificación</th>
                    <th>Observaciones</th>
                    <th>
                        Periodo
                        <br>
                        <select class="buscar periodo" id="search-periodo">
                            <option value="" disabled selected hidden>Seleccione un periodo</option>
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
                </tr>
            </thead>
            <tbody id="table-body">
                <?php
                if ($Resultado->num_rows > 0){
                    while ($Fila = $Resultado->fetch_assoc()){
                        $Nombre = $Fila["nombre"] . " " . $Fila["a_paterno"] . " " . $Fila["a_materno"];
                        echo "<tr data-expediente='" . $Fila['exp_alumno'] . "' data-nombre='" . $Nombre . "' data-periodo='" . $Fila['periodo'] . "'>";
                        echo "<td>" . $Fila ["exp_alumno"] . "</td>";
                        echo "<td>" . $Nombre . "</td>";
                        echo "<td>" . (!empty($Fila["fecha_evaluacion"]) ? $Fila["fecha_evaluacion"] : "Pendiente") . "</td>";
                        echo "<td>" . (!empty($Fila["aula"]) ? $Fila["aula"] : "Pendiente") . "</td>";
                        echo "<td>" . $Fila["calificacion"] . "</td>";
                        echo "<td>" . $Fila["observacion"] . "</td>";
                        echo "<td>" . $Fila["periodo"] . "</td>";
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
</script>

</body>

</html>
