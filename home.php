<?php
session_start();

// Simulación de inicio de sesión para probar (remover en producción).
// $_SESSION['logged_in'] = true; // Comentar o eliminar después de implementar el sistema de autenticación real.

$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
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
    
    <div class="background-image" style="background-image: url('img/Fondo.jpg');"></div>
  </main>
</body>
</html>
