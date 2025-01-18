<?php
function verificarSesion($tipoRequerido) {
    // Verificar si ya hay una sesión activa antes de intentar iniciarla
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Headers de caché
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    
    // Verificar sesión activa
    if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
        header("Location: ../Index/index.html");
        exit();
    }
    
    // Verificar tipo de usuario
    if ($_SESSION['tipo'] !== $tipoRequerido) {
        header("Location: ../Index/index.html");
        exit();
    }
}
?>