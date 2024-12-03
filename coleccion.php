<?php
session_start();

// Variables de sesión
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
$userId = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
$userRol = isset($_SESSION['rol']) ? $_SESSION['rol'] : null;

// Conexión a la base de datos
$host = "140.84.187.91";
$usuario = "user001";
$contraseña = "tTVtVCPM";
$base_datos = "bdtienda";

$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Consulta para obtener las obras
$query = "SELECT o.id_obra, o.titulo, o.precio, o.ruta_archivo, 
                 COALESCE(o.descripcion, 'Sin descripción') AS descripcion, 
                 COALESCE(u.nombre, 'Sin Asignar') AS nombre_vendedor, 
                 o.id_usuario AS id_vendedor
          FROM Obras o
          LEFT JOIN Usuarios u ON o.id_usuario = u.id_usuario";

$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colección</title>
    <link rel="stylesheet" href="css/coleccion.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <nav class="menu">
            <a href="home.php"><button>Inicio</button></a>
            <button class="highlighted">Colección</button>
            <a href="contacto.php"><button>Contacto</button></a>
            <a href="carrito.php"><button>Carrito</button></a>

            <?php if ($isLoggedIn): ?>
                <a href="logout.php"><button>Cerrar Sesión</button></a>
            <?php else: ?>
                <a href="inicio de sesion.html"><button>Iniciar Sesión</button></a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="main-content">
        <div class="collection-container">
            <?php if ($isLoggedIn && ($userRol == 'Administrador' || $userRol == 'Vendedor')): ?>
                <a href="crear_obra.html"><button>Cargar Nueva Obra</button></a>
            <?php endif; ?>

            <?php
            if (isset($_SESSION['obra_subida'])) {
                echo "<p>" . $_SESSION['obra_subida'] . "</p>";
                unset($_SESSION['obra_subida']); 
            }

            if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="art-card">
                        <img src="<?php echo $row['ruta_archivo']; ?>" alt="Imagen de <?php echo $row['titulo']; ?>" class="art-image">
                        <p class="art-title">Título: <?php echo htmlspecialchars($row['titulo']); ?></p>
                        <p class="art-description">Descripción: <?php echo htmlspecialchars($row['descripcion']); ?></p>
                        <p class="art-artist">Artista: <?php echo htmlspecialchars($row['nombre_vendedor']); ?></p>
                        <p class="art-price">Precio: $<?php echo number_format($row['precio'], 2); ?></p>

                        <?php if ($userRol == 'Vendedor' && $row['id_vendedor'] == $userId): ?>
                            <form action="editar_obra.php" method="GET">
                                <input type="hidden" name="id" value="<?php echo $row['id_obra']; ?>">
                                <button type="submit">Editar</button>
                            </form>
                        <?php elseif ($userRol == 'Administrador'): ?>
                            <form action="editar_obra.php" method="GET">
                                <input type="hidden" name="id" value="<?php echo $row['id_obra']; ?>">
                                <button type="submit">Editar</button>
                            </form>
                        <?php endif; ?>

                        <!-- Botón de añadir al carrito -->
                        <?php if ($userRol == 'Comprador'): ?>
                            <form action="agregar_producto.php" method="POST">
                                <input type="hidden" name="id_obra" value="<?php echo $row['id_obra']; ?>">
                                <input type="hidden" name="titulo" value="<?php echo htmlspecialchars($row['titulo']); ?>">
                                <input type="hidden" name="precio" value="<?php echo $row['precio']; ?>">
                                <input type="hidden" name="ruta_archivo" value="<?php echo $row['ruta_archivo']; ?>">
                                <button type="submit">Añadir al Carrito</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No se encontraron resultados.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
