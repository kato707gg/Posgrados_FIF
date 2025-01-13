<?php
  include('../Header/MenuC.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/tablas.css">
    <title>Posgrado FIF</title>
    <style> 
        .alta-docentes {
          width: 50%;
          top: 54%;
          left: 50%;
          position: absolute;
          transform: translate(-50%, -50%);
          box-sizing: border-box;
        }
        .alta-docentes h1 {
          font-family: "Google Sans", Roboto, Arial, sans-serif;
          color: #000000;
          margin: 0;
          margin: 0 0 2.5rem;
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
          width: 96%;
          margin-top: 0.5rem;
          margin-bottom: 1.5rem;
          border-bottom: 1px solid #636363;
          outline: none;
          font-size: 1rem;
          font-weight: 500;
          color: var(--text-color);
          border: 1px solid #ccc;
          padding: 1rem 0.5rem;
          border-radius: clamp(.4rem, .4vw, .4rem);
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
          border-radius: clamp(.4rem, .4vw, .4rem);
          width: 30%;
          display: block;
          margin: 3rem auto 0 auto;
          cursor: pointer;
        }

        .grid-container {
          margin-top: 2.5rem;
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
            top: 56%;
            width: 60%;
        }

        .boton_guardar {
                margin: 2rem auto 0 auto;
            }

        }

        @media screen and (max-width: 1050px) {

          .alta-docentes {
              top: 56%;
              width: 90%;
          }

          .alta-docentes input[type="text"],
          .alta-docentes input[type="number"],
          .alta-docentes select {
            margin-bottom: 0;
          }

          .grid-container {
            grid-template-columns: repeat(2, 1fr);
            row-gap: 1rem;
          }

        }

        @media screen and (max-width: 450px) {
          .alta-docentes input[type="text"],
          .alta-docentes input[type="number"],
          .alta-docentes select {
            width: 90%;
          }

          .alta-docentes h1 {
            font-size: 1.5rem;
            margin: 0 0 3rem;
          }

          .alta-docentes label {
            font-size: 1.25rem;
          }

          .grid-container {
            gap: 1rem;
          }

          .boton_guardar {
            margin-top: 4rem;
            width: 50%;
          }

          .popup-content {
            padding: 2rem 1rem 1rem 1rem;
          }
        }
    </style>
</head>
  <body>
  <div class="container-principal">
  <div class="alta-docentes" id="registrationOptions">
        <h3>Alta de Docentes</h3>
        <hr class="x-component x-component-default" style="border-top: 0;border-bottom: 0.05rem solid #196ad3;margin:auto;width: 100%;" id="box-1034">
        <form action="../Coordinador/RegistroDocentes.php" method="POST">
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
        document.querySelector('form[action="../Coordinador/RegistroDocentes.php"]').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío del formulario de la manera tradicional

            const formData = new FormData(this);

            fetch('../Coordinador/RegistroDocentes.php', {
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
            if (window.copyText) {
                navigator.clipboard.writeText(window.copyText)
                    .then(() => {
                        alert("Credenciales copiadas al portapapeles");
                    })
                    .catch(err => {
                        console.error('Error al copiar:', err);
                        alert("Error al copiar el texto");
                    });
            }
        }

        function closePopup() {
            document.getElementById("popup").style.display = "none";
            // Limpiar todos los campos del formulario
            document.querySelector('form[action="../Coordinador/RegistroDocentes.php"]').reset();
        }
    </script>
  </body>
</html>