<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();

// Verificar si el usuario está logueado y tiene un ID de vendedor válido
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
$id_Vendedor = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

if (!$isLoggedIn || !$id_Vendedor) {
    die("No estás autorizado para realizar esta acción.");
}

// Configuración de conexión a la base de datos
$host = "140.84.187.91";
$usuario = "user001";
$contraseña = "tTVtVCPM";
$base_datos = "bdtienda";

$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);

// Validar la conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $imagen = $_FILES['imagen'] ?? null;

    // Validar datos
    if (empty($titulo) || empty($descripcion) || empty($precio)) {
        die("Todos los campos son obligatorios.");
    }

    if (!is_numeric($precio) || $precio <= 0) {
        die("El precio debe ser un número positivo.");
    }

    // Validar que se haya cargado una imagen
    if ($imagen && $imagen['error'] === UPLOAD_ERR_OK) {
        $ruta_imagen = "img/" . basename($imagen['name']);
        if (!move_uploaded_file($imagen['tmp_name'], $ruta_imagen)) {
            die("Error al cargar la imagen.");
        }
    } else {
        die("Por favor, sube una imagen válida.");
    }

    // Insertar nueva obra en la base de datos
    $sql = "INSERT INTO Obras (titulo, descripcion, precio, ruta_archivo, id_usuario) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmt->bind_param("ssdsi", $titulo, $descripcion, $precio, $ruta_imagen, $id_Vendedor);

    if ($stmt->execute()) {
        $_SESSION['obra_subida'] = "La obra se ha creado correctamente.";
        header("Location: coleccion.php");
        exit();
    } else {
        die("Error al crear la obra: " . $stmt->error);
    }
}
?>
