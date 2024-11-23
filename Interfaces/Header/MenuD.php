<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Posgrados_FIF/Interfaces/Header/styles.css">  
    <title>Docente</title>
</head>
<body>
    <header class="header">
        <div class="container-titulo-header">
            <a class="titulo-header" href="/Posgrados_FIF/Interfaces/Fondo estatico/Docente.php">SSAP FIF UAQ</a>
        </div>
        <div class="container-botones">
            <ul class="container-lista-botones">  
                <li class="boton-link">
                    <a class="evaluaciones-pendientes" href="/Posgrados_FIF/Interfaces/Acciones/Docente/EvaluacionesPendientes.php"><button type="button">Evaluaciones Pendientes</button></a>
                </li>
                <li class="boton-link">
                    <a class="historial-evaluaciones" href="/Posgrados_FIF/Interfaces/Acciones/Docente/HistorialEvaluaciones.php"><button type="button">Historial de Evaluaciones</button></a>
                </li>
            </ul>
        </div>
        <div class="container-usuario">
            <a class="usuario">Usuario: <?php echo(isset($_SESSION['Nombre']) ? $_SESSION['Nombre'] : 'Invitado');?></a>
        </div>
        <div class="container-cerrar-btn">
            <a class="cerrar-btn" href="/Posgrados_FIF/Interfaces/Acciones/Cerrar.php"><button type="button">Cerrar</button></a>
        </div>
    </header>

    <div class="header-telefono">
        <div class="container-titulo-sidebarbtn">
            <a class="titulo-header" href="/Posgrados_FIF/Interfaces/Fondo estatico/Alumno.php">SSAP FIF UAQ</a>
            <button class="open-sidebar" onclick="openNav()">☰</button>
        </div>

        <div id="mySidebar" class="sidebar">
            <span href="javascript:void(0)" class="close-sidebar" onclick="closeNav()">×</span>
            <span class="espacio-sidebar"></span>
            <a>Usuario: <?php echo(isset($_SESSION['Nombre']) ? $_SESSION['Nombre'] : 'Invitado');?></a>
            <a href="/Posgrados_FIF/Interfaces/Acciones/Docente/EvaluacionesPendientes.php">Evaluaciones Pendientes</a>
            <a href="/Posgrados_FIF/Interfaces/Acciones/Docente/HistorialEvaluaciones.php">Historial de Evaluaciones</a>
            <a class="cerrar-btn-telefono" href="/Posgrados_FIF/Interfaces/Acciones/Cerrar.php">Cerrar</a>
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
