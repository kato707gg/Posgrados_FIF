
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>EDUCON FIF</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/checkout/">

    <!-- Bootstrap core CSS -->
<link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="form-validation.css" rel="stylesheet">
  </head>
  <body class="bg-light">
  <form class="needs-validation" method="POST" action="RegistraCursos.php">
      <div class="container">
        <div class="row">
            <div class="col-sm">
            <label for="Expediente">Expediente: </label>
            <label for="Expediente1"><?php print($Expediente); ?> </label>
            <label for="Nombre">Nombre: </label>
            <label for="Nombre1"><?php print($Nombre);?> </label>
            </div>
        </div>  
            <div class="col-sm">
                <label for="Observacion">Observacion  </label>
                <input type="text" class="form-control" id="Duracion"name="Duracion" value="0">
            </div>
            <div class="col-sm">
                <label for="Calificacion">Calificacion </label>
                <input type="number" class="form-control" id="Duracion"name="Duracion" value="0">
            </div>
        </div>
    
    <p>
    </form>
    <?php if(isset($_REQUEST['Nombre'])){    
        include("BD.php");
        $Con=Conectar();
        //$SQL="INSERT INTO CURSOS VALUES(NULL,'$Nombre','$FInicio','$FFin','$Tipo',0,'$Duracion','$Modalidad','$Dias','$Horario','$Tipo2','$Instructor','$Cupo','$ReciboI','$ReciboE','$Aula','$ReciboD1','$ReciboD2','$ReciboD3','$ReciboD4','$ReciboD5Descuento');";
       // $Result= Ejecutar($Con,$SQL);
       // $n=mysqli_num_rows($Result);
        Cerrar($Con);
    }  
?> 
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../SSD/assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
        <script src="form-validation.js"></script></body>
</html>
