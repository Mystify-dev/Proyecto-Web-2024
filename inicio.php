<?php
// Configuración de conexión a la base de datos
$host = "140.84.187.91";
$usuario = "user001"; // Cambia si usas un usuario distinto
$contraseña = "tTVtVCPM"; // Cambia si tienes una contraseña
$base_de_datos = "bdtienda"; // Cambia al nombre de tu base de datos

$conn = new mysqli($host, $usuario, $contraseña, $base_de_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejo de solicitudes de POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];

        if ($accion === 'iniciar_sesion') {
            // Lógica de inicio de sesión
            $correo = $_POST['correo'];
            $contraseña = $_POST['contraseña'];

            $sql = "SELECT * FROM Usuarios WHERE correo = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $correo);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $usuario = $resultado->fetch_assoc();
                // Verificar la contraseña
                if (password_verify($contraseña, $usuario['contraseña'])) {
                    echo "Inicio de sesión exitoso. Bienvenido, " . $usuario['nombre'] . "!";
                } else {
                    echo "Contraseña incorrecta.";
                }
            } else {
                echo "El usuario no existe.";
            }
            $stmt->close();
        } elseif ($accion === 'registrar') {
            // Lógica de registro
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT); // Cifrado de contraseña

            $sql = "INSERT INTO Usuarios (nombre, correo, contraseña, tipo_usuario) VALUES (?, ?, ?, 2)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $nombre, $correo, $contraseña);

            if ($stmt->execute()) {
                echo "Registro exitoso. Ahora puedes iniciar sesión.";
            } else {
                echo "Error al registrar: " . $conn->error;
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>
