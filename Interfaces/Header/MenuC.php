<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Header/styles.css">  
    <title>Coordinador</title>
</head>
<body>
    <header class="header">
        <div class="container-titulo-header">
            <a class="titulo-header" href="../Fondo estatico/Coordinador.php">SSAP FIF UAQ</a>
        </div>
        <div class="container-botones">
            <ul class="container-lista-botones">  
                <li class="boton-link">
                    <a class="asignar-sinodo" href="../Acciones/AsignarSinodo.php"><button type="button">Asignar Sínodo</button></a>
                </li>
                <li class="boton-link">
                    <a class="agendar-evaluacion" href="../Acciones/AgendarEvaluacion.php"><button type="button">Agendar Evaluación</button></a>
                </li>
                <li class="boton-link">
                    <a class="evaluaciones-agendadas" href="../Acciones/EvaluacionesAgendadas.php"><button type="button">Evaluaciones Agendadas</button></a>
                </li>
                <li class="boton-link">
                    <a class="agendar-evaluacion" href="../Acciones/AltaDocentes.php"><button type="button">Alta de docentes</button></a>
                </li>
                <li class="boton-link">
                    <a class="seguimiento" href=""><button type="button">Seguimiento</button></a>
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
            <a class="titulo-header" href="../Fondo estatico/Coordinador.php">SSAP FIF UAQ</a>
            <button class="open-sidebar" onclick="openNav()">☰</button>
        </div>

        <div id="mySidebar" class="sidebar">
            <span href="javascript:void(0)" class="close-sidebar" onclick="closeNav()">×</span>
            <span class="espacio-sidebar"></span>
            <a>Usuario: <?php echo(isset($_SESSION['Nombre']) ? $_SESSION['Nombre'] : 'Invitado');?></a>
            <a href="../Acciones/AsignarSinodo.php">Asignar Sínodo</a>
            <a href="../Acciones/AgendarEvaluacion.php">Agendar Evaluación</a>
            <a href="../Acciones/EvaluacionesAgendadas.php">Evaluaciones Agendadas</a>
            <a href="../Acciones/AltaDocentes.php">Alta de docentes</a>
            <a href="">Seguimiento</a>
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
