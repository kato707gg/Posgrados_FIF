<?php
  include('../Header/MenuC.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posgrado FIF</title>
    <link rel="stylesheet" href="../Header/styles.css">
    <style> 
        .alta-docentes {
          width: 50%;
          top: 54%;
          left: 50%;
          position: absolute;
          transform: translate(-50%, -50%);
          box-sizing: border-box;
          padding: 2.5rem;
          border: 1px solid rgb(26,115,232);
          border-radius: 0.4rem;
        }
        .alta-docentes h1 {
          font-family: "Google Sans", Roboto, Arial, sans-serif;
          color: #000000;
          margin: 0;
          margin: 0 0 5rem;
          text-align: center;
          font-size: 2rem;
        }
        .alta-docentes label {
          font-family: system-ui;
          color: #3c4043;
          font-weight: 600;
          font-size: 1.5rem;
          margin: 0;
          padding: 0 5px;
        }
        .alta-docentes input[type="text"],
        .alta-docentes input[type="number"],
        .alta-docentes select {
          font-family: "Google Sans", Roboto, Arial, sans-serif;
          width: 100%;
          margin-top: 0.5rem;
          margin-bottom: 1.5rem;
          padding: 0 5px;
          border: none;
          border-bottom: 1px solid #636363;
          outline: none;
          height: 2rem;
          color: #000000;
          font-size: 1rem;
        }

        input::placeholder {
          color: rgb(200, 200, 200);
        }

        .boton_guardar {
          border: none;
          outline: none;
          height: 3rem;
          background: #123773;
          color: #fff;
          font-size: 1.5rem;
          border-radius: 0.4rem;
          width: 30%;
          display: block;
          margin: 3rem auto 0 auto;
          cursor: pointer;
        }

        .grid-container {
          display: grid;
          grid-template-columns: repeat(3, 1fr);
          gap: 3rem;
        }

        td {
          padding: 0 1rem;
        }

        .espacio {
          color: #fff;
          font-size: large;
        }

        /* Estilos para el pop-up */
        .popup {
          display: none;
          position: fixed;
          z-index: 1000;
          left: 0;
          top: 0;
          width: 100%;
          height: 100%;
          background-color: rgb(0 0 0 / 70%);
        }

        .popup-content {
          top: 50%;
          left: 50%;
          position: absolute;
          transform: translate(-50%, -50%);
          background-color: #fff;
          padding: 3rem;
          align-content: center;
          text-align: center;
          border-radius: 1rem;
        }

        #popup-text {
          font: 1.1rem sans-serif;
        }

        .close-btn {
          position: absolute;
          top: 0.5rem;
          right: 1rem;
          font-size: 1.7rem;
          cursor: pointer;
        }

        .copy-btn {
          margin: 2rem auto 0;
          padding: .5rem 1rem;
          font-size: 1rem;
          cursor: pointer;
        }

        @media screen and (max-width: 1600px) {

        .alta-docentes {
            width: 70%;
        }

        }

        @media screen and (max-width: 1050px) {

          .alta-docentes {
              width: 90%;
              padding: 1.5rem;
          }

          .grid-container {
            grid-template-columns: repeat(2, 1fr);
          }

        }

        @media screen and (max-width: 450px) {

          .alta-docentes h1 {
              font-size: 1.5rem;
              margin: 0 0 3rem;
          }

          .alta-docentes input[type="text"] {
            padding: 0;
          }

          .alta-docentes label {
            font-size: 1.25rem;
          }

          .grid-container {
              gap: 2rem;
          }

          .boton_guardar {
              width: 50%;
          }

          .popup-content {
            padding: 2rem 1rem 1rem 1rem;
          }
        }
    </style>
</head>
  <body>
    <div class="alta-docentes" id="registrationOptions">
        <h1>Alta de Docentes</h1>
        <form action="../Acciones/RegistroDocentes.php" method="POST">
            <div class="grid-container">
                <div>
                    <label for="expediente">Clave</label>
                    <input type="number" name="clave" id="clave" required>
                </div>
                <div>
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" required>
                </div>
                <div>
                    <label for="apellidoPaterno">Apellido paterno:</label>
                    <input type="text" name="apellidoPaterno" id="apellidoPaterno" required>
                </div>
                <div>
                    <label for="apellidoMaterno">Apellido materno:</label>
                    <input type="text" name="apellidoMaterno" id="apellidoMaterno" required>
                </div>
                <div>
                    <label for="programa">Estatus: </label>
                    <select name="status" id="status" required>
                        <option value="" disabled selected>Selecciona...</option>
                        <option value="A">Activ@</option>
                        <option value="I">Inactiv@</option>
                    </select>
                </div>
            </div>
            <input type="submit" value="Guardar" class="boton_guardar">
        </form>
    </div>

    <!-- Pop-up oculto inicialmente -->
    <div id="popup" class="popup" style="display: none;">
        <div class="popup-content">
            <span class="close-btn" onclick="closePopup()">&times;</span>
            <p id="popup-text"></p>
            <button id= "copy-btn" class="copy-btn" onclick="copyToClipboard()">&#x1F4CB; Copiar</button>
        </div>
    </div>
    <script>
        let operationSuccess = false; // Variable global para el estado de la operación
        document.querySelector('form[action="../Acciones/RegistroDocentes.php"]').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío del formulario de la manera tradicional

            const formData = new FormData(this);

            fetch('../Acciones/RegistroDocentes.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const popup = document.getElementById('popup');
                const popupText = document.getElementById('popup-text');
                const copyButton = document.getElementById('copy-btn');

                // Oculta el botón de copiar por defecto
                copyButton.style.display = 'none';

                if (data.status === 'success') {
                    operationSuccess = true; // Marca la operación como exitosa
                    // Mostrar el mensaje y el botón de copiar
                    popupText.innerText = data.message;
                    copyButton.style.display = 'block'; // Muestra el botón de copiar

                    // Manejar la funcionalidad de copiado
                    copyButton.addEventListener('click', function() {
                        navigator.clipboard.writeText(data.copyText)
                            .then(() => {
                                alert('Credenciales copiadas al portapapeles');
                            })
                            .catch(err => {
                                console.error('Error al copiar:', err);
                            });
                    });

                } else if (data.status === 'exists') {
                    // Mostrar el mensaje de que la cuenta ya existe sin el botón de copiar
                    popupText.innerText = data.message;

                } else if (data.status === 'error') {
                    // Mostrar el mensaje de error
                    popupText.innerText = data.message;
                }

                // Mostrar el popup
                popup.style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un problema al registrar la cuenta.');
            });
        });

        function copyToClipboard() {
            const text = document.getElementById("popup-text").innerText;
            navigator.clipboard.writeText(data.copyText).then(function() {
                alert("Texto copiado al portapapeles");
            }, function(err) {
                alert("Error al copiar el texto: ", err);
            });
        }

        function closePopup() {
            document.getElementById("popup").style.display = "none";
            // Limpiar todos los campos del formulario
            document.querySelector('form[action="../Acciones/RegistroDocentes.php"]').reset(); // {{ edit_1 }}
        }
    </script>
  </body>
</html>