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
        .container1{
            background: #fff;
            color: #636363;
            top: 50%;
            left: 50%;
            position: absolute;
            transform: translate(-50%, -50%);
            box-sizing: border-box;
            padding: 2.5rem;
            border: 1px solid #9fb3c6;
            border-radius: 15px;
            font: caption;
            font-size: medium;
            z-index: 1;
            max-width: 80vw; /* Evitar que sea más ancho que el 90% del ancho de la ventana */
            max-height: 80vh; /* Evitar que sea más alto que el 90% de la altura de la ventana */
            overflow: auto;
        }
        .container1 h1{
            margin: 0;
            padding: 0 0 35px;
            text-align: center;
            font-size: 1.7rem;
        }

        .container1 h2 {
            margin: 0;
            padding: 0 35px;
        }

            .container1 input[type="text"],
            .container1 input[type="password"],
            .container1 input[type="text"],
            .container1 input[type="password"],
            .container1 input[type="number"],
            .container1 select,
            .container1 input[type="email"] {
            width: 100%;
            margin-bottom: 1.5rem;
            border: none;
            border-bottom: 1px solid #636363;
            background: transparent;
            outline: none;
            height: 2rem;
            color: #000000;
            font-size: 0.9rem;
            }

            input::placeholder {
            color: rgb(200, 200, 200);
            }

            .boton_enviar {
            border: none;
            outline: none;
            height: 40px;
            background: #134bb3;
            color: #fff;
            font-size: 18px;
            border-radius: 5px;
            width: 50%;
            display: block;
            margin: 1rem auto 2rem auto;
            cursor: pointer;
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
  width: 30%;
  height: 40%;
  align-content: center;
  text-align: center;
  border-radius: 1rem;
}

#popup-text {
  font: 1em sans-serif;
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
            @media screen and (max-width: 1050px) {

.container1 {
  width: 90%;
}

.grid-container {
  grid-template-columns: repeat(2, 1fr);
}

.mt-5,
.my-5 {
  display: none;
}

.popup-content {
  width: 60%;
  height: 40%;
}
}

@media screen and (max-width: 450px) {

.container1 h1 {
  font-size: 1.5rem;
}

.container1 {
  font-size: 0.9rem;
}

.container1 input[type="text"],
.container1 input[type="password"],
.container1 input[type="text"],
.container1 input[type="password"],
.container1 input[type="number"],
.container1 select,
.container1 input[type="email"] {
  margin-bottom: 1rem;
  font-size: 0.8rem;
}

.popup-content {
  padding: 1rem;
  width: 80%;
  height: 50%;
}

}

    </style>
</head>
<body>
<div class="container1" id="initialContent">
        <h1>Alta de Docentes</h1>
        <form action="../../RegistroDocentes.php" method="POST">
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
            <input type="submit" value="Guardar" class="boton_enviar">
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

    <script src="funcionalidades.js"></script>
</body>

</html>