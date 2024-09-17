<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Header/styles.css">  
    <title>Docente</title>
</head>
<body>
    <header class="header">
        <div class="container-titulo-header">
            <a class="titulo-header" href="../Fondo estatico/Docente.php">SSAP FIF UAQ</a>
        </div>
        <div class="container-botones">
            <ul class="container-lista-botones">  
                <li class="boton-link">
                    <a class="evaluaciones-pendientes" href=""><button type="button">Evaluaciones Pendientes</button></a>
                </li>
                <li class="boton-link">
                    <a class="historial-evaluaciones" href=""><button type="button">Historial de Evaluaciones</button></a>
                </li>
            </ul>
        </div>
        <div class="container-usuario">
            <a class="usuario">Usuario: <?php echo(isset($_SESSION['Nombre']) ? $_SESSION['Nombre'] : 'Invitado');?></a>
        </div>
        <div class="container-cerrar-btn">
            <a class="cerrar-btn" href="../Acciones/Cerrar.php"><button type="button">Cerrar</button></a>
        </div>
    </header>

    <div class="header-telefono">
        <div class="container-titulo-sidebarbtn">
            <a class="titulo-header" href="../Fondo estatico/Docente.php">SSAP FIF UAQ</a>
            <button class="open-sidebar" onclick="openNav()">☰</button>
        </div>

        <div id="mySidebar" class="sidebar">
            <span href="javascript:void(0)" class="close-sidebar" onclick="closeNav()">×</span>
            <span class="espacio-sidebar"></span>
            <a>Usuario: <?php echo(isset($_SESSION['Nombre']) ? $_SESSION['Nombre'] : 'Invitado');?></a>
            <a href="">Evaluaciones Pendientes</a>
            <a href="">Historial de Evaluaciones</a>
            <a class="cerrar-btn-telefono" href="../Acciones/Cerrar.php">Cerrar</a>
        </div>
    </div>
    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "100vw";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
        }
    </script>
</body>
</html>
