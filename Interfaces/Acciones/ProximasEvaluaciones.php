<?php
  include('../Header/MenuA.php');
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../conexion.php';


$Con = Conectar();
$clave_alumno = $_SESSION['id'];
$SQL = "SELECT id, fecha_evaluacion, aula FROM evaluaciones WHERE exp_alumno = $clave_alumno";
$Resultado = Ejecutar($Con, $SQL);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Header/styles.css">  
    <title>Próximas evaluaciones</title>
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

    #table-container {
        display: flex;
        justify-content: center;
        overflow: auto;
    }

    table {
        table-layout: fixed;
        border-collapse: collapse;
        margin-bottom: 5rem;
        width: 100%;
        max-width: 60%;
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

    h3 {
        font-size: 2rem;
        font-family: "Google Sans", Roboto, Arial, sans-serif;
    }

    h1 {
        font-family: "Google Sans", Roboto, Arial, sans-serif;
        text-align: center;
    }

    .container-proximas-evaluacionesS {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 85vh;
        padding: 1rem;
    }

    
    @media screen and (max-width: 1600px) {

        .container-agendar-evaluacion {
            height: 75vh;
        }

    }

    @media (max-width: 770px) {

        table {
            max-width: 90%;
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
                    <th>No. Evaluación</th>
                    <th>Fecha</th>
                    <th>Salon</th>
                </tr>
            </thead>
            <tbody>
                <?php

                if($Resultado->num_rows > 0){
                    while ($Fila = $Resultado->fetch_assoc()){
                        echo "<tr >";
                        echo "<td>" . $Fila ["id"] . "</td>";
                        echo "<td>" . $Fila ["fecha_evaluacion"] . "</td>";
                        echo "<td>" . $Fila ["aula"] . "</td>";
                    }
                }else {
                    echo "<tr><td colspan='7'>No se encontraron evaluaiones pendientes</td></tr>";
                }
                Cerrar($Con);

                ?>
            </tbody>
        </table>
    </div>
</div>

</body>

</html>