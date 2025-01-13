<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario y limpiar las entradas
    $Expediente = trim($_POST['expediente']);
    $Nombre = trim($_POST['nombre']);
    $ApellidoP = trim($_POST['apellidoPaterno']);
    $ApellidoM = trim($_POST['apellidoMaterno']);
    $Telefono = trim($_POST['telefono']);
    $Correo = trim($_POST['correo']);
    $Programa = trim($_POST['programa']);

    // Quitar  espacios  al inicio y al final 
    $Nombre=trim( $Nombre);
    $ApellidoM=trim($ApellidoM);
    $ApellidoP=trim($ApellidoP);
    $Telefono=trim($Telefono);

    // Convertir a mayúsculas y quitar acentos
    include("LimpiaCadenas.php");
    $Nombre = strtoupper(eliminar_acentos($Nombre));
    $ApellidoP = strtoupper(eliminar_acentos($ApellidoP));
    $ApellidoM = strtoupper(eliminar_acentos($ApellidoM));

    // Crear contraseña
    $Password = $ApellidoP . substr($Telefono, -2);

    //Variable de nombre completo 
    $NombreC = "$ApellidoP $ApellidoM $Nombre";

    // Conectar a la base de datos
    include("../../Config/conexion.php");
    $Con = Conectar();
    if (!$Con) {
        die(json_encode(["status" => "error", "message" => "Error de conexión: " . mysqli_connect_error()]));
    }

    header('Content-Type: application/json');
    $response = [];

    // Verificar si el expediente ya existe
    $SQL1 = "SELECT * FROM estudiantes WHERE exp = ?";
    $stmt1 = mysqli_prepare($Con, $SQL1);
    mysqli_stmt_bind_param($stmt1, 's', $Expediente);
    mysqli_stmt_execute($stmt1);
    $Result = mysqli_stmt_get_result($stmt1);

    if (mysqli_num_rows($Result) == 0) {
        // Insertar datos en la base de datos
        $SQL2 = "INSERT INTO estudiantes (exp, nombre, a_paterno, a_materno, telefono, correo, programa) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)";
        $SQL3 = "INSERT INTO cuentas (id, contrasena, tipo) 
                 VALUES (?, ?, 'A')";

        $stmt2 = mysqli_prepare($Con, $SQL2);
        mysqli_stmt_bind_param($stmt2, 'isssiss', $Expediente, $Nombre, $ApellidoP, $ApellidoM, $Telefono, $Correo, $Programa);

        $stmt3 = mysqli_prepare($Con, $SQL3);
        mysqli_stmt_bind_param($stmt3, 'is', $Expediente, $Password);

        if (mysqli_stmt_execute($stmt2) && mysqli_stmt_execute($stmt3)) {
            $response['status'] = 'success';
            $response['message'] = "Cuenta registrada correctamente.\n\n" .
                                   "Bienvenido $NombreC.\n" .
                                   "Tus credenciales de acceso son:\n" .
                                   "Usuario: $Expediente\n" .
                                   "Password: $Password\n\n" .
                                   "Recuerda guardar tus credenciales para acceder al sistema.";
            
            $response['copyText'] = "Tus credenciales de acceso son:\n" .
                                    "Usuario: $Expediente\n" .
                                    "Password: $Password";
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error al registrar la cuenta: " . mysqli_error($Con);
        }
    } else {
        $response['status'] = 'exists';
        $response['message'] = "La cuenta ya ha sido registrada anteriormente.\n\n" .
                               "Para recuperar tus credenciales de acceso, envía un correo a: francisco.javier.paulin@uaq.mx";
    }

    echo json_encode($response);

    // Cerrar la conexión
    mysqli_close($Con);
}
?>
