<?php
  include('../Header/MenuA.php');
?>

<!doctype html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POSGRADO FIF</title>
    <link rel="stylesheet" href="../Header/styles.css">
    <style>
      body {
        margin: 0;
        padding: 0;
      }
      .container-escudo {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 0 15px;
        margin: 0 auto;
      }
      .texto {
        padding-top: 3rem !important;
        padding-bottom: 3rem !important;
        text-align: center !important;
      }
      img {
        max-width: 100%;
        height: auto;
      }
      h1, h2 {
        font-size: 3rem;
        font-weight: 600;
        margin: 0.5rem 0;
        color: #454545;
      }
      h2 {
        font-weight: 300;
      }
      /* Ajustes para pantallas pequeñas */
    @media (max-width: 768px) {
        .container-escudo {
        width: 90%;
      }
      img {
        max-width: 80%;
        height: auto;
      }
      h1, h2 {
        font-size: 2rem;
      }
    }
    </style>
  </head>
  <body>
    <div class="container-escudo">
        <div class="texto">
            <img src="../../EscudoFIF.PNG" alt="Escudo FIF">
            <h1> B I E N V E N I D O </h1>
            <h2> (Alumno)  </h2>
        </div>
    </div>
  </body>
</html>
