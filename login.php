<?php
$host = "140.84.187.91";
$usuario = "user001"; // Asegúrate de que este usuario tenga los permisos necesarios
$contraseña = "tTVtVCPM";
$base_datos = "bdtienda";

$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

$correo = $_POST['correo'];
$contraseña = $_POST['contraseña'];

// Consulta para obtener la contraseña, id_usuario y tipo_usuario
$sql = "SELECT id_usuario, contraseña, tipo_usuario FROM Usuarios WHERE correo = ?";
$stmt = $conexion->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_usuario, $hash, $tipo_usuario);
        $stmt->fetch();

        if (password_verify($contraseña, $hash)) {
            // Inicio de sesión exitoso
            session_start();
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['tipo_usuario'] = $tipo_usuario;
            $_SESSION['logged_in'] = true;

            echo "Inicio de sesión exitoso. Redirigiendo...";
            // Redirigir según el rol del usuario
            if ($tipo_usuario === 'Administrador') {
                header("Location: home.php");
            } elseif ($tipo_usuario === 'Comprador') {
                header("Location: home.php");
            } elseif ($tipo_usuario === 'Vendedor') {
                header("Location: home.php");
            }
            exit();
        } else {
            echo "Credenciales incorrectas.";
        }
    } else {
        echo "Correo no encontrado.";
    }
    $stmt->close();
} else {
    echo "Error en la consulta: " . $conexion->error;
}

$conexion->close();
?>
