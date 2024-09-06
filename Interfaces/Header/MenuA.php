

<!doctype html>
<html lang="en">
  <head>
    <title>ALUMNO</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/starter-template/">

    <!-- Bootstrap core CSS -->
<link href="../../Assets/dist/css/bootstrap.min.css" rel="stylesheet">

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
    <link href="starter-template.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
  <a class="navbar-brand" href="Alumno.php">SSAP FIF UAQ </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">  
      <li class="nav-item active">
        <a class="nav-link" href=""><button type="button" class="btn btn-info" >Próximas Evaluaciones</button> <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="" ><button type="button" class="btn btn-info"  >Mis Evaluaciones</button> <span class="sr-only">(current)</span></a>
      </li>
      
      <li class="nav-item active">
        <a class="nav-link" href=""><button type="button" class="btn btn-info"  >Documentos</button> <span class="sr-only">(current)</span></a>
      </li>
      
      <li class="nav-item active">
        <a class="nav-link" href="Avisos.php" ><button type="button" class="btn btn-info" >Avisos</button> <span class="sr-only">(current)</span></a>
      </li>
    
    </ul>

    <form class="collapse navbar-collapse">
      <a class="nav-link" href="">Usuario: <?php echo(isset($_SESSION['Nombre']) ? $_SESSION['Nombre'] : 'Invitado');?> <span class="sr-only">(current)</span></a>
    </form>

    <form class="collapse navbar-collapse">
      <a class="nav-link" href="Cerrar.php"><button type="button" class="btn btn-danger">Cerrar</button><span class="sr-only">(current)</span></a>
    </form>
  </div>
</nav>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="../../Assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="../../Assets/dist/js/bootstrap.bundle.min.js"></script>
</html>
