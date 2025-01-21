<?php
  session_start();
  session_unset();
  session_destroy();
  
  // Limpiar el caché del navegador
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script>
        // Limpiar el historial y prevenir navegación hacia atrás
        window.onload = function() {
            if (window.history && window.history.pushState) {
                window.history.pushState('', null, './');
                window.addEventListener('popstate', function() {
                    window.history.pushState('', null, './');
                    window.location.replace('../../index.html');
                });
            }
            // Redirigir al login
            window.location.replace('../../index.html');
        }
    </script>
</head>
<body>
</body>
</html>