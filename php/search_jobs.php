<?php
session_start(); // Inicia la sesión
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'postulante') { // Verifica si es un postulante
    header("Location: login.php"); // Redirige a login si no es postulante
    exit();
}

include('db.php');

// Procesar búsqueda
$busqueda = "";
if (isset($_GET['buscar'])) {
    $busqueda = $_GET['buscar'];
    $sql = "SELECT * FROM ofertas WHERE titulo LIKE '%$busqueda%' OR descripcion LIKE '%$busqueda%'";
} else {
    $sql = "SELECT * FROM ofertas";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Vacantes</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Buscar Vacantes</h1>
    <form method="get">
        <label for="buscar">Buscar:</label>
        <input type="text" name="buscar" value="<?php echo $busqueda; ?>" required>
        <button type="submit">Buscar</button>
    </form>

    <h2>Ofertas de Trabajo</h2>
    <ul>
        <?php while($row = $result->fetch_assoc()): ?>
            <li>
                <?php echo $row['titulo']; ?> - <?php echo $row['descripcion']; ?>
                <!-- Aquí puedes agregar la opción para postularse a la oferta -->
            </li>
        <?php endwhile; ?>
    </ul>

    <a href="dashboard.php">Volver al Dashboard</a>
</body>
</html>
