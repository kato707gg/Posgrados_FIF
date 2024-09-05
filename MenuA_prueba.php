<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Diseño_prueba.css">
    <title>Header de Prueba</title>
</head>
<body>
    <header class="header">
        <div class="container-titulo-header">
            <a class="titulo-header" href="Alumno.php">SSAP FIF UAQ</a>
        </div>
        <div class="container-botones">
            <ul class="container-lista-botones">  
                <li class="boton-link">
                    <a class="proximas-evaluaciones" href=""><button type="button">Próximas Evaluaciones</button></a>
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
            <a class="usuario" href="">Usuario: <?php echo(isset($_SESSION['Nombre']) ? $_SESSION['Nombre'] : 'Invitado');?> <span class="sr-only">(current)</span></a>
        </div>
        <div class="container-cerrar-btn">
            <a class="cerrar-btn" href="Cerrar.php"><button type="button">Cerrar</button></a>
        </div>
    </header>

    <script src="funcionalidades.js"></script>
</body>
</html>
