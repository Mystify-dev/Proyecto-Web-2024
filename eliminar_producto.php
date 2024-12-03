<?php
session_start();

// Verificar si el carrito existe en la sesión
if (isset($_SESSION['carrito'])) {
    $carrito = $_SESSION['carrito'];

    // Verificar si el índice del producto está en el carrito
    if (isset($_GET['id']) && isset($carrito[$_GET['id']])) {
        // Eliminar el producto del carrito
        unset($carrito[$_GET['id']]);

        // Volver a guardar el carrito en la sesión
        $_SESSION['carrito'] = array_values($carrito); // Re-indexa el array para evitar índices discontinuos

        // Redirigir de vuelta al carrito
        header("Location: carrito.php");
        exit();
    }
}

// Si el producto no se encuentra, redirigir con un mensaje de error (opcional)
header("Location: carrito.php?error=producto_no_encontrado");
exit();
?>
