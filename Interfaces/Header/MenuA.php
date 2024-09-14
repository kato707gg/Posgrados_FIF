<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Diseño_prueba.css">
    <title>Alumno</title>
</head>
<body>
    <header class="header">
        <div class="container-titulo-header">
            <a class="titulo-header" href="../Fondo estatico/Alumno.php">SSAP FIF UAQ</a>
        </div>
        <div class="container-botones">
            <ul class="container-lista-botones">  
                <li class="boton-link">
                    <a class="proximas-evaluaciones" href="../Acciones/ProximasEvaluaciones.php"><button type="button">Próximas Evaluaciones</button></a>
                </li>
                <li class="boton-link">
                    <a class="mis-evaluaciones" href=""><button type="button">Mis Evaluaciones</button></a>
                </li>
                
                <li class="boton-link">
                    <a class="documentos" href=""><button type="button">Documentos</button></a>
                </li>
                
                <li class="boton-link">
                    <a class="avisos" href="Avisos.php" ><button type="button">Avisos</button></a>
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
            <a class="titulo-header" href="../Fondo estatico/Alumno.php">SSAP FIF UAQ</a>
            <button class="open-sidebar" onclick="openNav()">☰</button>
        </div>

        <div id="mySidebar" class="sidebar">
            <span href="javascript:void(0)" class="close-sidebar" onclick="closeNav()">×</span>
            <span class="espacio-sidebar"></span>
            <a>Usuario: <?php echo(isset($_SESSION['Nombre']) ? $_SESSION['Nombre'] : 'Invitado');?></a>
            <a href="../Acciones/ProximasEvaluaciones.php">Próximas evaluaciones</a>
            <a href="#">Mis evaluaciones</a>
            <a href="#">Documentos</a>
            <a href="Avisos.php">Avisos</a>
            <a class="cerrar-btn-telefono" href="../Acciones/Cerrar.php">Cerrar</a>
        </div>
    </div>
    <script src="../../funcionalidades.js"></script>
</body>
</html>
