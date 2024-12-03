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

$id_Vendedor = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

if (isset($_GET['id'])) {
    $id_obra = $_GET['id'];

    $sql = "SELECT * FROM Obras WHERE id_obra = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_obra);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $obra = $resultado->fetch_assoc();
    $stmt->close();

    if (!$obra) {
        die("La obra no se encontró.");
    }

    if ($obra['id_usuario'] != $id_Vendedor && $_SESSION['tipo_usuario'] != 'Administrador') {
        die("No tienes permisos para editar esta obra.");
    }
} else {
    die("ID de obra no válido.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Formulario de Edición de Obra</title>
  <link rel="stylesheet" href="css/crear_obra.css">
</head>
<body>
  <div class="form-container">
    <h2>Formulario de Edición de Obra</h2>
    <form action="guardar_obra.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id_obra" value="<?php echo $obra['id_obra']; ?>">
      <div class="form-group">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" value="<?php echo htmlspecialchars($obra['titulo']); ?>" required>
      </div>
      <div class="form-group">
        <label for="descripcion">Descripción:</label>
        <textarea id="descripcion" name="descripcion" required><?php echo htmlspecialchars($obra['descripcion']); ?></textarea>
      </div>
      <div class="form-group">
        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" value="<?php echo $obra['precio']; ?>" required>
      </div>
      <div class="form-group">
        <label for="imagen">Imagen (dejar en blanco si no quieres cambiarla):</label>
        <input type="file" id="imagen" name="imagen" accept="image/*">
      </div>
      <div class="form-group">
        <button type="submit">Guardar</button>
      </div>
    </form>
    <div class="form-group">
      <a href="coleccion.php"><button type="button">Regresar a Colección</button></a>
    </div>
  </div>
</body>
</html>
