<?php
session_start();

// Configuración de la base de datos
$host = "140.84.187.91";
$usuario = "user001";
$contrasena = "tTVtVCPM";
$base_datos = "bdtienda";

// Verificar si el usuario es administrador
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'Visitante';
if ($rol !== 'Administrador') {
    die("Acceso denegado.");
}

// Conexión a la base de datos
$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Verificar si se ha enviado un ID de obra
if (isset($_POST['id_obra'])) {
    $id_obra = (int)$_POST['id_obra'];
    $query = "SELECT mostrar_en_home FROM ObrasDestacadas WHERE id_obra = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param('i', $id_obra);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Si la obra ya está en la tabla, alternar el estado
        $stmt->bind_result($mostrar_en_home);
        $stmt->fetch();
        $nuevo_estado = !$mostrar_en_home;

        $update_query = "UPDATE ObrasDestacadas SET mostrar_en_home = ? WHERE id_obra = ?";
        $update_stmt = $conexion->prepare($update_query);
        $update_stmt->bind_param('ii', $nuevo_estado, $id_obra);
        $update_stmt->execute();
    } else {
        // Si la obra no está en la tabla, agregarla con estado "mostrar en home"
        $insert_query = "INSERT INTO ObrasDestacadas (id_obra, mostrar_en_home) VALUES (?, true)";
        $insert_stmt = $conexion->prepare($insert_query);
        $insert_stmt->bind_param('i', $id_obra);
        $insert_stmt->execute();
    }
}

$conexion->close();

// Redirigir de vuelta a la página de administración
header("Location: admin_home.php");
exit;
?>
