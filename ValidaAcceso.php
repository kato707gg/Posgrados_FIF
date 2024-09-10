<?php
session_start(); // Iniciar la sesión

// Obtener datos del formulario
$Expediente = $_POST['inputUsuario'];
$Password = $_POST['inputPassword']; // Esto debería ser la contraseña en texto plano

include("conexion.php");
$Con = Conectar();

// Verificar la conexión
if (!$Con) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Consulta SQL para verificar el usuario
$query = "SELECT * FROM cuentas WHERE id = ?";
$stmt = $Con->prepare($query);
$stmt->bind_param("i", $Expediente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();

    // Comparar directamente si ambos son hashes
    if ($Password === $row['contrasena']) {
        $_SESSION['id'] = $row['id'];
        $_SESSION['tipo'] = $row['tipo'];

        // Redirigir según el tipo de usuario
        switch ($row['tipo']) {
            case 'A':
                header('Location: Interfaces/Fondo estatico/Alumno.php');
                break;
            case 'C':
                header('Location: Interfaces/Fondo estatico/Coordinador.php');
                break;
            case 'D':
                header('Location: Interfaces/Fondo estatico/Docente.php');
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
        echo '<script type="text/javascript">
                alert("Usuario o Contraseña Incorrecta");
                window.location.href="index.html";
              </script>';
    }
} else {
    echo '<script type="text/javascript">
            alert("Usuario no encontrado.");
            window.location.href="index.html";
          </script>';
}

// Cerrar la conexión
$stmt->close();
mysqli_close($Con);

?>
