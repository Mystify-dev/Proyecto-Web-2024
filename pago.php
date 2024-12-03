<?php
session_start();

// Verificar si el usuario está logueado
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];

if (!$isLoggedIn) {
    echo "<h1>Acceso no autorizado</h1>";
    echo "<p>Por favor, <a href='inicio de sesion.html'>inicia sesión</a> para acceder al formulario de pago.</p>";
    exit;
}

// Obtener los datos del carrito de la sesión
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$total = 0;

// Calcular el total del carrito
foreach ($carrito as $producto) {
    $subtotal = $producto['precio'] * $producto['cantidad'];
    $total += $subtotal;
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
    <form id="payment-form">
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
    // Función para formatear el número de la tarjeta con espacios cada 4 dígitos
    function formatCardNumber(input) {
        let value = input.value.replace(/\D/g, ''); // Elimina caracteres no numéricos
        value = value.match(/.{1,4}/g); // Divide en grupos de 4 dígitos
        if (value) {
            input.value = value.join(' '); // Une con un espacio
        }
    }

    // Función para formatear la fecha de expiración como MM/AA
    function formatExpiryDate(input) {
        let value = input.value.replace(/\D/g, ''); // Elimina caracteres no numéricos
        if (value.length > 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4); // Inserta el '/'
        }
        input.value = value; // Asigna el valor formateado al campo
    }
</script>
    </div>
</body>
</html>
