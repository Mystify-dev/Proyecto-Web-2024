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
  <title>Contacto</title>
  <link rel="stylesheet" href="css/contacto.css">
</head>
<body>
  <header>
    <nav class="menu">
      <a href="home.php"><button>Inicio</button></a>
      <a href="coleccion.php"><button>Colección</button></a>
      <button class="highlighted">Contacto</button>
      <a href="carrito.php"><button>Carrito</button></a>

      <?php if ($isLoggedIn): ?>
        <!-- Mostrar botón de cerrar sesión si el usuario está logueado -->
        <a href="logout.php"><button>Cerrar Sesión</button></a>
      <?php else: ?>
        <!-- Mostrar botón de iniciar sesión si el usuario no está logueado -->
        <a href="inicio de sesion.html"><button>Iniciar Sesión</button></a>
      <?php endif; ?>
    </nav>
  </header>

  <main>
    <div class="contact-container">
      <h2>Contacto</h2>
      <form action="procesar_contacto.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>
        
        <label for="proposito">Propósito:</label>
        <input type="text" id="proposito" name="proposito" required>
        
        <label for="correo">Correo Electrónico:</label>
        <input type="email" id="correo" name="correo" required>
        
        <label for="mensaje">Mensaje:</label>
        <textarea id="mensaje" name="mensaje" rows="4" required></textarea>
        
        <button type="submit">Enviar</button>
      </form>
    </div>

    <div class="social-container" style="background-image: url('img/Fondo3.jpg')">
      <img src="img/twitter.png" alt="Twitter">
      <img src="img/mail.png" alt="Mail">
      <img src="img/facebook.png" alt="Facebook">
      <img src="img/instagram.png" alt="Instagram">
    </div>
  </main>
</body>
</html>
