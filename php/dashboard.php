<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['user_id'])) { // Verifica si el usuario no está autenticado
    header("Location: login.php"); // Si no está autenticado, redirige a login
    exit();
}

$nombre = $_SESSION['user_name'];
$tipo = $_SESSION['user_type'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Bienvenido, <?php echo $nombre; ?>!</h1>
    <p>Tipo de usuario: <?php echo $tipo; ?></p>

    <?php if ($tipo === 'empresa'): ?>
        <a href="offers.php">Gestionar Ofertas</a>
    <?php else: ?>
        <a href="search_jobs.php">Buscar Vacantes</a>
    <?php endif; ?>

    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
