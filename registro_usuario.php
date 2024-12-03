<?php
$host = "140.84.187.91";
$usuario = "user001";
$contraseña = "tTVtVCPM";
$base_datos = "bdtienda";

$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Verificar que los datos del formulario estén presentes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : null;
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : null;
    $contraseña_input = isset($_POST['contraseña']) ? trim($_POST['contraseña']) : null;
    $tipo_usuario = isset($_POST['tipo_usuario']) ? trim($_POST['tipo_usuario']) : null;
    $fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? trim($_POST['fecha_nacimiento']) : null;
    $genero = isset($_POST['genero']) ? trim($_POST['genero']) : null;

    // Verificar que todos los campos sean enviados
    if (!$nombre || !$correo || !$contraseña_input || !$tipo_usuario || !$fecha_nacimiento || !$genero) {
        die("Todos los campos son obligatorios.");
    }

    // Validación de la contraseña
    if (empty($contraseña_input)) {
        die("La contraseña es obligatoria.");
    }

    // Validar el correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Correo electrónico inválido.");
    }

    // Verificar edad
    $fecha_actual = new DateTime();
    $fecha_nacimiento_obj = new DateTime($fecha_nacimiento);
    $edad = $fecha_actual->diff($fecha_nacimiento_obj)->y;

    if ($edad < 16) {
        die("Debe ser mayor de 16 años para registrarse.");
    }

    // Validar el valor de genero
    $valores_validos_genero = ['Masculino', 'Femenino', 'Otro'];
    if (!in_array($genero, $valores_validos_genero)) {
        die("El valor del género no es válido. Debe ser 'Masculino', 'Femenino' o 'Otro'.");
    }

    // Hashear la contraseña antes de insertarla
    $contraseña = password_hash($contraseña_input, PASSWORD_DEFAULT);

    // Insertar datos en la base de datos
    $sql = "INSERT INTO Usuarios (nombre, correo, contraseña, tipo_usuario, fecha_nacimiento, genero) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssss", $nombre, $correo, $contraseña, $tipo_usuario, $fecha_nacimiento, $genero);
        if ($stmt->execute()) {
            // Redirigir a la página home.php
            header("Location: home.php");
            exit();
        } else {
            echo "Error al registrar el usuario: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }
} else {
    echo "Acceso no permitido.";
}

$conexion->close();
?>
