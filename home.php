<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Verificar si el usuario está logueado
$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'Visitante';
$id_usuario_actual = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

// Configuración de la base de datos
$host = "140.84.187.91";
$usuario = "user001";
$contrasena = "tTVtVCPM";
$base_datos = "bdtienda";

// Conexión a la base de datos
$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Obtener las obras destacadas para la página de inicio
$query = "SELECT Obras.id_obra, Obras.titulo, Obras.ruta_archivo, Obras.id_usuario, Usuarios.nombre AS artista 
          FROM Obras 
          JOIN Usuarios ON Obras.id_usuario = Usuarios.id_usuario
          JOIN ObrasDestacadas ON Obras.id_obra = ObrasDestacadas.id_obra
          WHERE ObrasDestacadas.mostrar_en_home = true";
$resultado = $conexion->query($query);

if (!$resultado) {
    die("Error al obtener las obras: " . $conexion->error);
}

$obras = $resultado->fetch_all(MYSQLI_ASSOC);

// Liberar resultado y cerrar conexión
$resultado->free();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header class="header">
    <nav class="menu">
      <button class="highlighted">Inicio</button>
      <a href="coleccion.php"><button>Colección</button></a>
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
    <div class="search-container">
      <img src="img/buscar.gif" alt="Buscar" class="search-icon">
      <input type="text" placeholder="Buscar" class="search-input">
    </div>
    
    <div class="obras-container">
      <?php if (!empty($obras)): ?>
        <?php foreach ($obras as $obra): ?>
          <div class="obra">
            <h3 class="obra-titulo"><?php echo htmlspecialchars($obra['artista']); ?></h3>
            <img src="<?php echo htmlspecialchars($obra['ruta_archivo']); ?>" alt="<?php echo htmlspecialchars($obra['titulo']); ?>" class="obra-img">
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay obras disponibles.</p>
      <?php endif; ?>
    </div>

    <?php if ($rol === 'Administrador'): ?>
      <div class="admin-options">
        <a href="admin_home.php"><button class="admin-btn">Configurar Obras en Inicio</button></a>
      </div>
    <?php endif; ?>
  </main>
</body>
</html>
