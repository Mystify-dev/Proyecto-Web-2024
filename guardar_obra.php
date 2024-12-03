<?php
session_start();

$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
$id_Vendedor = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
$userRol = isset($_SESSION['tipo_usuario']) ? $_SESSION['tipo_usuario'] : null;

if (!$isLoggedIn || !$id_Vendedor) {
    die("No estás autorizado para realizar esta acción.");
}

$host = "140.84.187.91";
$usuario = "user001";
$contraseña = "tTVtVCPM";
$base_datos = "bdtienda";

$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_obra = $_POST['id_obra'] ?? null;
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $imagen = $_FILES['imagen'] ?? null;

    if ($id_obra) {
        // Verificar si la obra existe en la base de datos
        $sql = "SELECT * FROM Obras WHERE id_obra = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_obra);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $obra = $resultado->fetch_assoc();
        $stmt->close();

        if (!$obra) {
            die("La obra no se encontró.");
        }

        if ($obra['id_usuario'] != $id_Vendedor && $_SESSION['tipo_usuario'] != 'Administrador') {
            die("No tienes permisos para editar esta obra.");
        }
    }

    // Manejar la imagen si se carga una nueva
    if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
        $ruta_imagen = "img/" . basename($imagen['name']);
        if (move_uploaded_file($imagen['tmp_name'], $ruta_imagen)) {
            // Imagen cargada correctamente
        } else {
            die("Error al cargar la imagen.");
        }
    } else {
        $ruta_imagen = $obra['ruta_archivo']; // Usar la ruta anterior si no se carga una nueva imagen
    }

    // Preparar y ejecutar la consulta de actualización
    $sql = "UPDATE Obras SET titulo = ?, descripcion = ?, precio = ?, ruta_archivo = ? WHERE id_obra = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssdsi", $titulo, $descripcion, $precio, $ruta_imagen, $id_obra);
    if ($stmt->execute()) {
        $_SESSION['obra_subida'] = "La obra se ha actualizado correctamente.";
        header("Location: coleccion.php");
        exit();
    } else {
        die("Error al actualizar la obra: " . $stmt->error);
    }
}
?>
