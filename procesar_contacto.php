<?php
// Iniciar la sesión si es necesario
session_start();

// Verificar si el formulario se ha enviado con el método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario (de manera simulada, aquí solo mostramos un mensaje)
    $nombre = isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : 'Usuario';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : 'correo@ejemplo.com';
    $mensaje = isset($_POST['mensaje']) ? htmlspecialchars($_POST['mensaje']) : 'No se ha enviado un mensaje';

    // Aquí podrías agregar código para enviar un correo electrónico o guardar la información en una base de datos.

    // Mostrar un mensaje de confirmación
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>Solicitud Enviada</title>
        <link rel='stylesheet' href='styles.css'>
    </head>
    <body>
        <div class='mensaje-confirmacion'>
            <h2>¡Gracias, $nombre!</h2>
            <p>Tu solicitud ha sido enviada. Nos pondremos en contacto contigo pronto.</p>
            <a href='contacto.php'><button>Volver a Contacto</button></a>
        </div>
    </body>
    </html>";
} else {
    // Si se accede a este archivo directamente, redirigir a la página de contacto.
    header("Location: contacto.php");
    exit();
}
?>
