<?php
    session_start();
    include("BD.php");

    // Recibir y sanitizar las entradas del usuario
    $usuario = mysqli_real_escape_string($Con, $_POST['inputUsuario']);
    $contrasena = mysqli_real_escape_string($Con, $_POST['inputPassword']);

    // Conectar a la base de datos
    $Con = Conectar();

    // Consultar la cuenta según el usuario ingresado
    $query = "SELECT * FROM cuentas WHERE id = '$usuario'";
    $result = mysqli_query($Con, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Verificar la contraseña
        if ($row['contrasena'] === $contrasena) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['tipo'] = $row['tipo'];

            // Redirigir según el tipo de usuario
            switch ($row['tipo']) {
                case 'A':
                    header('Location: Alumno.php');
                    break;
                case 'C':
                    header('Location: Coordinador.php');
                    break;
                case 'D':
                    header('Location: Docente.php');
                    break;
                default:
                    session_destroy();
                    echo '<script type="text/javascript">
                            alert("Tipo de cuenta no válido.");
                            window.location.href="index.html";
                          </script>';
                    break;
            }
        } else {
            // Contraseña incorrecta
            echo '<script type="text/javascript">
                    alert("Usuario o Contraseña Incorrecta");
                    window.location.href="index.html";
                  </script>';
        }
    } else {
        // Usuario no encontrado
        echo '<script type="text/javascript">
                alert("Usuario o Contraseña Incorrecta");
                window.location.href="index.html";
              </script>';
    }

    // Cerrar la conexión
    mysqli_close($Con);
?>
