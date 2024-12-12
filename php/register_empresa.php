<?php
session_start();
include('db.php');

// Verificar si el usuario está autenticado y es una empresa
if (!isset($_SESSION['user_id']) || $_SESSION['tipo'] !== 'empresa') {
    header("Location: login.php");
    exit();
}

$empresa_id = $_SESSION['user_id'];

// Comprobar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_empresa = $_POST['nombre_empresa'];
    $direccion = $_POST['direccion'];
    $correo_empresa = $_POST['correo_empresa'];

    // Validación simple de campos
    if (empty($nombre_empresa) || empty($direccion) || empty($correo_empresa)) {
        echo "<p>Por favor, complete todos los campos.</p>";
    } else {
        // Insertar la información de la empresa en la base de datos
        $sql = "INSERT INTO direcciones_empresa (empresa_id, nombre_empresa, direccion, correo_empresa) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $empresa_id, $nombre_empresa, $direccion, $correo_empresa);

        if ($stmt->execute()) {
            echo "<p>Información de la empresa registrada correctamente.</p>";
        } else {
            echo "<p>Error al registrar la información de la empresa. Intenta nuevamente.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Empresa</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Registro de Información de la Empresa</h1>
    <form action="register_empresa.php" method="POST">
        <label for="nombre_empresa">Nombre de la Empresa:</label>
        <input type="text" name="nombre_empresa" id="nombre_empresa" required>

        <label for="direccion">Dirección de la Empresa:</label>
        <input type="text" name="direccion" id="direccion" required>

        <label for="correo_empresa">Correo de la Empresa:</label>
        <input type="email" name="correo_empresa" id="correo_empresa" required>

    </form>
</body>
</html>
