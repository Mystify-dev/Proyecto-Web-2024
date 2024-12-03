<?php
session_start();

// Configuración de la base de datos
$host = "140.84.187.91";
$usuario = "user001";
$contrasena = "tTVtVCPM";
$base_datos = "bdtienda";

// Verificar si el usuario es administrador
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'Visitante';
if ($rol !== 'Administrador') {
    die("Acceso denegado.");
}

// Conexión a la base de datos
$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);
if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Obtener todas las obras
$query = "SELECT Obras.id_obra, Obras.titulo, Obras.ruta_archivo, Usuarios.nombre AS artista, 
          IF(ObrasDestacadas.mostrar_en_home, true, false) AS destacada 
          FROM Obras 
          JOIN Usuarios ON Obras.id_usuario = Usuarios.id_usuario
          LEFT JOIN ObrasDestacadas ON Obras.id_obra = ObrasDestacadas.id_obra";
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
  <title>Administrar Inicio</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header class="header">
    <nav class="menu">
      <button class="highlighted">Administrar Inicio</button>
      <a href="home.php"><button>Volver a Inicio</button></a>
    </nav>
  </header>
  <main class="main-content">
    <h2>Obras en la colección</h2>
    <div class="obras-container">
      <?php if (!empty($obras)): ?>
        <?php foreach ($obras as $obra): ?>
          <div class="obra">
            <h3 class="obra-titulo"><?php echo htmlspecialchars($obra['artista']); ?></h3>
            <img src="<?php echo htmlspecialchars($obra['ruta_archivo']); ?>" alt="<?php echo htmlspecialchars($obra['titulo']); ?>" class="obra-img">
            <form method="post" action="admin_home_action.php">
              <input type="hidden" name="id_obra" value="<?php echo $obra['id_obra']; ?>">
              <button type="submit" name="toggleDestacada">
                <?php echo $obra['destacada'] ? 'Quitar de Inicio' : 'Agregar a Inicio'; ?>
              </button>
            </form>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No hay obras disponibles.</p>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>
