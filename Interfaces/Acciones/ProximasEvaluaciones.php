<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posgrado FIF</title>
    <link rel="stylesheet" href="loginDesign.css">
</head>

<style>
    table {
        table-layout: fixed;
        border-collapse: collapse;
        margin-bottom: 5rem;
        table: 100%;
        width: 40%;
    }

    tr {
        border-top: 0.1rem solid rgb(26,115,232);
        border-bottom: 0.1rem solid rgb(26,115,232);
    }

    th, td {
        width: 33.33%;
        border-bottom: 0.0625rem solid #e0e0e0;
        padding: 20px;
    }

    td {
        display: table-cell;
        text-align: center;
        font-family: "Google Sans",Roboto,Arial,sans-serif;
        font-size: 1.1rem;
        font-weight: 500;
        color: #3c4043;
    }

    th {
        letter-spacing: .01785714em;
        font-family: system-ui;
        font-weight: 600;
        font-size: 1.5rem;
        color: #3c4043;
        padding-bottom: 2rem;
        padding-top: 3.5rem;
    }
    button {
        height: 48px;
        width: 15rem;
        background-color: #366d6f;
        border-radius: 5px;
        border: 0.1rem;
        font-family: system-ui;
        font-weight: 600;
        font-size: 1rem;
        color: #fafcff;
    }
    h1 {
        font-family: "Google Sans",Roboto,Arial,sans-serif;
        text-align: center;
    }
    h3 {
        font-size: 2rem;
        font-family: "Google Sans",Roboto,Arial,sans-serif;
    }

    #title-container {
        display: flex;
        justify-content:space-between;
        width: 100vw;
        align-items:center;
    }
    #container-logo {
        display:flex;
        align-items: center;
    }
    #logo {
        width: 40px;
        margin-left: 20px;
    }
    #olmos {
        font-family: "Google Sans",Roboto,Arial,sans-serif;
        margin-left: 10px;
    }
    #back {
        all: unset;
    }
    #back:hover {
        all: unset;
        cursor: pointer;
    }
    body {
        width: 100vw;
        display: block;
        text-align: center;
    }
    #table-container {
        display: flex !important;
        justify-content: center;
    }
    .container-proximas-evaluacionesS {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
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