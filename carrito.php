<?php
session_start();

// Verificar si el usuario está logueado
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];

if (!$isLoggedIn) {
    echo "<h1>Acceso no autorizado</h1>";
    echo "<p>Por favor, <a href='inicio de sesion.html'>inicia sesión</a> para acceder al carrito.</p>";
    exit;
}

// Calcular el total de la compra y mostrar los productos en el carrito
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito</title>
    <link rel="stylesheet" href="carrito.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <nav class="menu">
            <a href="home.php"><button>Inicio</button></a>
            <a href="coleccion.php"><button>Colección</button></a>
            <a href="contacto.php"><button>Contacto</button></a>
            <a href="logout.php"><button>Cerrar Sesión</button></a>
        </nav>
    </header>
    <main class="main-content">
        <h1>Carrito de Compras</h1>
        <?php if (!empty($carrito)): ?>
            <form action="pago.php" method="post">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Subtotal</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carrito as $index => $producto): 
                            // Verificar si la clave 'titulo' existe
                            $titulo = isset($producto['titulo']) ? htmlspecialchars($producto['titulo']) : 'Título no disponible';
                            $precio = isset($producto['precio']) ? number_format($producto['precio'], 2) : 'Precio no disponible';
                            $cantidad = isset($producto['cantidad']) ? $producto['cantidad'] : 'Cantidad no disponible';
                            $subtotal = isset($producto['precio']) && isset($producto['cantidad']) ? $producto['precio'] * $producto['cantidad'] : 0;
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td><?php echo $titulo; ?></td>
                                <td>$<?php echo $precio; ?></td>
                                <td><?php echo $cantidad; ?></td>
                                <td>$<?php echo number_format($subtotal, 2); ?></td>
                                <td><a href="eliminar_producto.php?id=<?php echo $index; ?>">🗑️</a></td>
                            </tr>
                            <!-- Pasar cada producto como JSON -->
                            <input type="hidden" name="productos[]" value='<?php echo json_encode($producto); ?>'>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="actions">
                    <p>Total: $<?php echo number_format($total, 2); ?></p>
                    <input type="hidden" name="total_pago" value="<?php echo $total; ?>">
                    <button type="submit">Pagar</button>
                </div>
            </form>
        <?php else: ?>
            <p>Tu carrito está vacío.</p>
        <?php endif; ?>
    </main>
</body>
</html>
