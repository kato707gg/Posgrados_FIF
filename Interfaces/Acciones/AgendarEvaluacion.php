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

        .edit-button {
            display: none;
            font-size: 0.8rem;
            color: blue;
            background: none;
            border: none;
            cursor: pointer;
            text-decoration: underline;
        }
        
        .confirmar-button {
            display: flex;
            margin: auto;
            font-size: 1.3rem;
            font-family: "Google Sans", Roboto, Arial, sans-serif;
            padding: 0.7rem 0.9rem;
            background-color: #123773;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 0.4rem;
            margin-bottom: 1.5rem;
        }

        .confirmar-button.disabled {
            background-color: grey; /* Color deshabilitado */
            cursor: not-allowed;
            opacity: 0.6; /* Para indicar visualmente que está deshabilitado */
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
              echo "<td><button class='asignar-button' onclick='openModalFecha(this)'>Asignar Fecha</button></td>";
              echo "<td><button class='asignar-button' onclick='openModalHora(this)'>Asignar Hora</button></td>";
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



  <div id="modal-fecha" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModalFecha()">&times;</span>
      <h3>Seleccionar Fecha:</h3>
      <input type="date" id="fecha-seleccionada" min="" style="font-size: 1.2rem; padding: 0.5rem;">
      <button class="confirmar-button" onclick="confirmarFecha()">Confirmar</button>
    </div>
  </div>

  <div id="modal-hora" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModalHora()">&times;</span>
    <h3>Seleccionar Hora:</h3>
    <input type="time" id="hora-seleccionada" style="font-size: 1.2rem; padding: 0.5rem;">
    <button class="confirmar-button" onclick="confirmarHora()">Confirmar</button>
  </div>
</div>






  <script> 

  //Funciones para los bótones
    let currentRow;

    //Abrir ventana de Fecha
    function openModalFecha(button){
      const modal = document.getElementById("modal-fecha");
      modal.style.display = "block";

      currentRow = button.closest('tr');
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('fecha-seleccionada').setAttribute('min', today);  
    }

    //Cerrar ventana de fecha
    function closeModalFecha(){
        const modal= document.getElementById("modal-fecha");
        modal.style.display = "none";
      }


      //Botón para confirmar fecha
    function confirmarFecha(){
        const fecha = document.getElementById('fecha-seleccionada').value;

        if(fecha){
          const fechaCell = currentRow.cells[2];
          fechaCell.innerHTML = `<span>${fecha}</span><br><button class='edit-button' onclick='openModalFecha(this)'>EditarFecha</button>`;
          closeModalFecha();
        }else{
          alert('Por favor seleccionar una fecha. ');
        }
    }
    </script>






    <script>
    function openModalHora(button) {
      const modal = document.getElementById("modal-hora");
      modal.style.display = "block";
      currentRow = button.closest('tr');
    }


    function closemodalHora(){
      const modal = document.getElementById("modal-hora");
      modal.style.display = "none";
    }

    function confirmarHora(){
      const hora = document.getElementById('hora-seleccionada').value;
      
      if(hora){
        const horaCell = currentRow.cells[3];
        horaCell.innerHTML = `<span>${hora}</span><br><button class='edit-button' onclick='openModalHora(this)'>Editar Hora</button>`;
        closemodalHora();
      }else{
        alert('Por favor selecciona una hora.');
      }
    }
    </script>
  
</body>
</html>