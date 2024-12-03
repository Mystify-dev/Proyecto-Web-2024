<?php
// Configuración de la conexión a la base de datos
$host = "140.84.187.91";
$username = "user001"; // Cambia esto si usas otro usuario
$password = "tTVtVCPM"; // Cambia esto si tienes una contraseña configurada
$database = "wellnest"; // Cambia esto por el nombre de tu base de datos

// Conexión a la base de datos
$conn = new mysqli($host, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error en la conexión: " . $conn->connect_error);
}

// Consulta a la tabla 'notas'
$sql = "SELECT idNote, idUser, date, content, state FROM notes";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        .estado-feliz {
            color: green;
            font-weight: bold;
        }
        .estado-triste {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Notas</h1>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID Nota</th>
                    <th>ID Usuario</th>
                    <th>Fecha</th>
                    <th>Contenido</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar cada fila de resultados
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['idNote'] . "</td>";
                    echo "<td>" . $row['idUser'] . "</td>";
                    echo "<td>" . $row['date'] . "</td>";
                    echo "<td>" . $row['content'] . "</td>";
                    echo "<td> ". $row['state'] ."</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se encontraron notas.</p>
    <?php endif; ?>

    <?php
    // Cerrar la conexión
    $conn->close();
    ?>
</body>
</html>