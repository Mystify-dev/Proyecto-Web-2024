<?php
$host = "140.84.187.91";
$usuario = "user001";
$contraseña = "tTVtVCPM";
$base_datos = "bdtienda";

$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);

if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Datos del administrador
$nombre = 'BrocaAdmi'; // Nombre del administrador
$correo = 'Broca20@iCloud.com'; // Correo del nuevo administrador
$contraseña_input = '1234567890'; // Contraseña en texto plano
$tipo_usuario = 'Administrador'; // Tipo de usuario (Administrador)
$fecha_nacimiento = '2003-08-06'; // Fecha de nacimiento
$genero = 'Otro'; // Género

// Hashear la contraseña antes de insertarla
$contraseña = password_hash($contraseña_input, PASSWORD_DEFAULT);

// Insertar datos en la base de datos
$sql = "INSERT INTO Usuarios (nombre, correo, contraseña, tipo_usuario, fecha_nacimiento, genero) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssssss", $nombre, $correo, $contraseña, $tipo_usuario, $fecha_nacimiento, $genero);
    if ($stmt->execute()) {
        echo "Administrador creado exitosamente.";
    } else {
        echo "Error al crear el administrador: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error en la preparación de la consulta: " . $conexion->error;
}

$conexion->close();
?>

<!-- 
Para agregar otro administrador en el futuro, solo cambia los valores de las siguientes variables:
$nombre: el nombre del nuevo administrador.
$correo: el correo electrónico del nuevo administrador (debe ser único).
$contraseña_input: la nueva contraseña en texto plano que se hasheará antes de almacenarla.
$fecha_nacimiento: la fecha de nacimiento del nuevo administrador.
$genero: el género del nuevo administrador (puede ser 'Masculino', 'Femenino' u 'Otro').

Ejemplo:
$nombre = 'NuevoAdministrador';
$correo = 'nuevoadmin@miapp.com';
$contraseña_input = 'nuevacontraseña';
$fecha_nacimiento = '1990-05-15';
$genero = 'Masculino';
-->
