<?php
session_start(); // Iniciar la sesión

// Obtener datos del formulario
$Expediente = $_POST['inputUsuario'];
$Password = $_POST['inputPassword']; // Esto debería ser la contraseña en texto plano

include("../../Config/conexion.php");
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

        // Consulta para obtener el nombre completo según el tipo de usuario
        switch ($row['tipo']) {
            case 'A':
                $query_usuario = "SELECT nombre, a_paterno, a_materno FROM estudiantes WHERE exp = ?";
                break;
            case 'D':
                $query_usuario = "SELECT nombre, a_paterno, a_materno FROM docentes WHERE clave = ?";
                break;
            case 'C':
                $query_usuario = "SELECT nombre, a_paterno, a_materno FROM coordinadores WHERE clave = ?";
                break;
            default:
                $query_usuario = null;
        }

        if ($query_usuario) {
            $stmt_usuario = $Con->prepare($query_usuario);
            $stmt_usuario->bind_param("i", $Expediente);
            $stmt_usuario->execute();
            $result_usuario = $stmt_usuario->get_result();

            if ($result_usuario->num_rows == 1) {
                $row_usuario = $result_usuario->fetch_assoc();
                // Guardar el nombre completo del usuario en la sesión
                $_SESSION['Nombre'] = $row_usuario['nombre'] . ' ' . $row_usuario['a_paterno'] . ' ' . $row_usuario['a_materno'];
            }
            $stmt_usuario->close();
        }

        // Redirigir según el tipo de usuario
        switch ($row['tipo']) {
            case 'A':
                header('Location: ../Fondo estatico/Alumno.php');
                break;
            case 'C':
                header('Location: ../Fondo estatico/Coordinador.php');
                break;
            case 'D':
                header('Location: ../Fondo estatico/Docente.php');
                break;
            default:
                session_destroy();
                echo '<script type="text/javascript">
                        alert("Tipo de cuenta no válido.");
                        window.location.href="../../index.html";
                      </script>';
                break;
        }
    } else {
        echo '<script type="text/javascript">
                alert("Usuario o Contraseña Incorrecta");
                window.location.href="../../index.html";
              </script>';
    }
} else {
    echo '<script type="text/javascript">
            alert("Usuario no encontrado.");
            window.location.href="../../index.html";
          </script>';
}

// Cerrar la conexión
$stmt->close();
mysqli_close($Con);
?>
