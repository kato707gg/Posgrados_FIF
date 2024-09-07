<?php
  include('../Header/MenuA_prueba.php');
  //include('../Header/MenuA.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Header/Diseño_prueba.css">
    <title>Próximas evaluaciones</title>
</head>

<style>
    body {
        margin: 0;
        padding: 0;
    }

    :root {
        --primary-color: rgb(26,115,232);
        --secondary-color: #366d6f;
        --text-color: #3c4043;
        --background-color: #fafcff;
    }

    table {
        table-layout: fixed;
        border-collapse: collapse;
        margin-bottom: 5rem;
        width: 100%;
        max-width: 40rem;
    }

    tr {
        border-top: 0.1rem solid var(--primary-color);
        border-bottom: 0.1rem solid var(--primary-color);
    }

    th, td {
        width: 33.33%;
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

    .container-proximas-evaluacionesS {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 85vh;
        padding: 1rem;
    }

    @media (max-width: 48rem) {
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

<div class="container-proximas-evaluacionesS">
<h3>Próximas evaluaciones:</h3>
    <div id="table-container">
        <table>
            <thead>
                <tr>
                    <th>Expediente</th>
                    <th>Fecha</th>
                    <th>Calificación</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Aquí deberías incluir la lógica para conectarte a la base de datos y obtener los datos de los alumnos
                // Por ejemplo:
                // $conexion = new mysqli("localhost", "usuario", "contraseña", "basededatos");
                // $resultado = $conexion->query("SELECT id, nombre, grupo FROM alumnos");

                // Simulamos algunos datos para el ejemplo
                $alumnos = [
                    ['exp_alumno' => 1, 'fecha_evaluacion' => '2024-05-01', 'cal_final' => 10],
                    ['exp_alumno' => 2, 'fecha_evaluacion' => '2024-05-02', 'cal_final' => 9],
                    ['exp_alumno' => 3, 'fecha_evaluacion' => '2024-05-03', 'cal_final' => 8]
                ];

                foreach ($alumnos as $alumno) {
                    echo "<tr>";
                    echo "<td>" . $alumno['exp_alumno'] . "</td>";
                    echo "<td>" . $alumno['fecha_evaluacion'] . "</td>";
                    echo "<td>" . $alumno['cal_final'] . "</td>";
                    echo "</tr>";
                }

                // Si estuvieras usando una conexión real a la base de datos, cerrarías la conexión aquí
                // $conexion->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>

</html>