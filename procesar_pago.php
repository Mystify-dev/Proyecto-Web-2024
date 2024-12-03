<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Configuración de la base de datos
$host = "140.84.187.91";
$usuario = "user001";
$contraseña = "tTVtVCPM";
$base_datos = "bdtienda";

// Crear la conexión
$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Verificar si el usuario está logueado
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];

if (!$isLoggedIn) {
    echo "<h1>Acceso no autorizado</h1>";
    echo "<p>Por favor, <a href='inicio de sesion.html'>inicia sesión</a> para acceder al formulario de pago.</p>";
    exit;
}

// Obtener los datos del carrito de la sesión
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$id_comprador = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

if (empty($carrito)) {
    echo "<h1>El carrito está vacío</h1>";
    echo "<p><a href='coleccion.php'>Regresar a la colección</a></p>";
    exit;
}

// Procesar la compra al enviar el formulario
$stmt = $conexion->prepare("INSERT INTO Compras (id_comprador, id_obra, fecha_compra, monto) VALUES (?, ?, NOW(), ?)");

$total = 0;
foreach ($carrito as $producto) {
    $id_obra = $producto['id_obra'];
    $cantidad = $producto['cantidad'];
    $monto = $producto['precio'] * $cantidad;
    $total += $monto;

    // Insertar cada producto en la tabla Compras
    $stmt->bind_param("iid", $id_comprador, $id_obra, $monto);
    $stmt->execute();
}

// Cerrar la consulta
$stmt->close();

// Limpiar el carrito después de completar la compra
unset($_SESSION['carrito']);

// Mostrar mensaje de éxito
$mensaje_exito = "<h1>Compra realizada con éxito</h1>";
$mensaje_exito .= "<p>Total pagado: $" . number_format($total, 2) . "</p>";
$mensaje_exito .= "<p><a href='home.php'>Volver al inicio</a></p>";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Realizada</title>
    <link rel="stylesheet" href="css/pago.css">
</head>
<body>
    <div class="container">
        <?php echo $mensaje_exito; ?>
    </div>
</body>
</html>
