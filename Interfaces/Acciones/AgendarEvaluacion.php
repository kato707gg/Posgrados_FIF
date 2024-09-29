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
        }

        :root {
            --primary-color: rgb(26,115,232);
            --secondary-color: #366d6f;
            --text-color: #3c4043;
            --background-color: #fafcff;
        }

        table {
            table-layout: auto;
            border-collapse: collapse;
            margin-bottom: 4rem;
            width: 100%;
            max-width: 100rem;
        }

        tr {
            border-top: 0.1rem solid var(--primary-color);
            border-bottom: 0.1rem solid var(--primary-color);
        }

        th, td {
            width: 20%;
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
            display: flex;
            justify-content: center;
            width: max-content;
            overflow-x: auto;
        }

        .container-agendar-evaluacion {
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

        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fff;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 2rem;
            width: 50%;
            border-radius: 0.4rem;
        }

        .close {
            color: #aaa;
            right: 1rem;
            top: 0.5rem;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .asignar-button {
            font-size: 1rem;
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            padding: 0.5rem 0.6rem;
            background-color: #123773;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 0.4rem;
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

        .check-icon {
            margin-left: 0.5rem;
            cursor: pointer;
            font-size: 1.2rem;
        }

        .check-icon:hover {
            color: green;
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
              echo "<td><div class='input-container'><input type='date' id='fecha-seleccionada' oninput='showIcon(this)'><span class='check-icon' onclick='confirmDate(this)' style='display:none;'>&#x2714;</span></div></td>";
              echo "<td><div class='input-container'><input type='time' id='hora-seleccionada' oninput='showIcon(this)'><span class='check-icon' onclick='confirmTime(this)' style='display:none;'>&#x2714;</span></div></td>";
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
    // Mostrar el ícono de la paloma al ingresar un valor en el input
    function showIcon(inputElement) {
      let icon = inputElement.nextElementSibling;
      if (inputElement.value) {
        icon.style.display = 'inline'; // Mostrar icono si hay valor en el input
      }
    }

    function confirmDate(element) {
      let input = element.previousElementSibling;
      if (input.disabled === false) {
        input.disabled = true; // Deshabilitar input
        input.style.backgroundColor = '#a6b3c7'; // Cambiar color de fondo
        input.style.color = 'white'; // Cambiar color de texto

        // Cambiar icono a equis
        element.innerHTML = '&#x2716';
        element.setAttribute('onclick', 'editDate(this)');
      }
    }

    function confirmTime(element) {
      let input = element.previousElementSibling;
      if (input.disabled === false) {
        input.disabled = true; // Deshabilitar input
        input.style.backgroundColor = '#a6b3c7'; // Cambiar color de fondo
        input.style.color = 'white'; // Cambiar color de texto

        // Cambiar icono a equis
        element.innerHTML = '&#x2716';
        element.setAttribute('onclick', 'editTime(this)');
      }
    }

    function editDate(element) {
      let input = element.previousElementSibling;
      input.disabled = false; // Habilitar input
      input.style.color = '#3c4043'; // Restaurar color de texto
      input.style.backgroundColor = 'white'; // Cambiar color de fondo

      // Cambiar icono a paloma
      element.innerHTML = '&#x2714;';
      element.setAttribute('onclick', 'confirmDate(this)');
    }

    function editTime(element) {
      let input = element.previousElementSibling;
      input.disabled = false; // Habilitar input
      input.style.color = '#3c4043'; // Restaurar color de texto
      input.style.backgroundColor = 'white'; // Cambiar color de fondo

      // Cambiar icono a paloma
      element.innerHTML = '&#x2714;';
      element.setAttribute('onclick', 'confirmTime(this)');
    }
  </script>

</body>


</html>