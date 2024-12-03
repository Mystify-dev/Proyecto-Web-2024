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
if (empty($carrito)) {
    echo "<h1>El carrito está vacío</h1>";
    echo "<p><a href='coleccion.php'>Regresar a la colección</a></p>";
    exit;
}

// Calcular el total para mostrarlo en el formulario
$total = 0;
foreach ($carrito as $producto) {
    $total += $producto['precio'] * $producto['cantidad'];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Pago</title>
    <link rel="stylesheet" href="css/pago.css">
    <script>
        // Función para formatear el número de tarjeta
        function formatCardNumber(input) {
            let value = input.value.replace(/\D/g, ''); // Elimina cualquier carácter que no sea un dígito
            let formattedValue = '';

            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formattedValue += ' ';
                }
                formattedValue += value[i];
            }

            input.value = formattedValue;
        }

        // Función para formatear la fecha de expiración
        function formatExpiryDate(input) {
            let value = input.value.replace(/\D/g, ''); // Elimina cualquier carácter que no sea un dígito
            let formattedValue = '';

            if (value.length > 2) {
                formattedValue = value.slice(0, 2) + '/' + value.slice(2, 4);
            } else {
                formattedValue = value;
            }

            input.value = formattedValue;
        }

        // Agregar eventos para formatear la entrada en tiempo real
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('card-number').addEventListener('input', function() {
                formatCardNumber(this);
            });

            document.getElementById('expiry-date').addEventListener('input', function() {
                formatExpiryDate(this);
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <form id="payment-form" method="POST" action="procesar_pago.php">
            <h2>Formulario de Pago</h2>
            <div class="form-group">
                <label for="card-number">Número de Tarjeta</label>
                <input type="text" id="card-number" placeholder="1234 5678 9012 3456" required>
            </div>
            <div class="form-group">
                <label for="card-name">Nombre en la Tarjeta</label>
                <input type="text" id="card-name" placeholder="Juan Pérez" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="expiry-date">Fecha de Expiración</label>
                    <input type="text" id="expiry-date" placeholder="MM/AA" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" placeholder="123" required>
                </div>
            </div>
            <div class="form-group">
                <label for="amount">Monto a Pagar</label>
                <input type="number" id="amount" name="amount" placeholder="0.00" step="0.01" required readonly value="<?php echo number_format($total, 2, '.', ''); ?>">
            </div>
            <button type="submit">Pagar Ahora</button>
        </form>
    </div>
</body>
</html>
