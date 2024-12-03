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
$conexion = new mysqli($host, $user, $password, $database);

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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    echo "<h1>Compra realizada con éxito</h1>";
    echo "<p>Total pagado: $" . number_format($total, 2) . "</p>";
    echo "<p><a href='home.php'>Volver al inicio</a></p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Pago</title>
    <link rel="stylesheet" href="css/pago.css">
</head>
<body>
    <div class="container">
        <form method="POST" id="payment-form">
            <h2>Formulario de Pago</h2>
            <div class="form-group">
                <label for="card-number">Número de Tarjeta</label>
                <input type="text" id="card-number" placeholder="1234 5678 9012 3456" required oninput="formatCardNumber(this)">
            </div>
            <div class="form-group">
                <label for="card-name">Nombre en la Tarjeta</label>
                <input type="text" id="card-name" placeholder="Juan Pérez" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="expiry-date">Fecha de Expiración</label>
                    <input type="text" id="expiry-date" placeholder="MM/AA" required oninput="formatExpiryDate(this)">
                </div>
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" placeholder="123" required>
                </div>
            </div>
            <div class="form-group">
                <label for="amount">Monto a Pagar</label>
                <!-- Mostrar el total calculado como valor predeterminado en el campo -->
                <input type="number" id="amount" name="amount" value="<?php echo number_format($total, 2, '.', ''); ?>" step="0.01" readonly>
            </div>
            <button type="submit">Pagar Ahora</button>
        </form>

        <script>
            function formatCardNumber(input) {
                let value = input.value.replace(/\D/g, '');
                value = value.match(/.{1,4}/g);
                if (value) {
                    input.value = value.join(' ');
                }
            }

            function formatExpiryDate(input) {
                let value = input.value.replace(/\D/g, '');
                if (value.length > 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }
                input.value = value;
            }
        </script>
    </div>
</body>
</html>
