<?php
session_start();
$host = "140.84.187.91";
$usuario = "user001";
$contraseña = "tTVtVCPM";
$base_datos = "bdtienda";

$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_obra'])) {
    $id_obra = $_POST['id_obra'];

    // Consultar la obra para obtener los detalles
    $query = "SELECT * FROM Obras WHERE id_obra = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id_obra);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        // Verificar si la sesión del carrito ya existe
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        // Verificar si el producto ya está en el carrito
        $existe = false;
        foreach ($_SESSION['carrito'] as &$producto) {
            if ($producto['id_obra'] == $fila['id_obra']) {
                $producto['cantidad'] += 1; // Incrementar la cantidad si ya existe
                $existe = true;
                break;
            }
        }

        if (!$existe) {
            $_SESSION['carrito'][] = [
                'id_obra' => $fila['id_obra'],
                'titulo' => $fila['titulo'], // Usar 'titulo' como clave
                'precio' => $fila['precio'],
                'cantidad' => 1
            ];
        }

        header("Location: carrito.php");
        exit();
    } else {
        echo "Producto no encontrado.";
    }
}

?>
