<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'empresa') {
    header("Location: login.php");
    exit();
}

include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['crear_oferta'])) {
        $titulo = $_POST['titulo'];
        $descripcion = $_POST['descripcion'];
        $profesion_requerida = $_POST['profesion_requerida'];
        $empresa_id = $_SESSION['user_id'];

        $sql = "INSERT INTO ofertas (titulo, descripcion, profesion_requerida, empresa_id) VALUES ('$titulo', '$descripcion', '$profesion_requerida', '$empresa_id')";

        if ($conn->query($sql) === TRUE) {
            echo "Oferta creada exitosamente.";
        } else {
            echo "Error al crear la oferta: " . $conn->error;
        }
    }
}

// Obtener todas las ofertas
$empresa_id = $_SESSION['user_id'];
$sql = "SELECT * FROM ofertas WHERE empresa_id = '$empresa_id'";
$result = $conn->query($sql);

// Buscar postulantes por profesión
$postulantes_sql = "SELECT u.nombre, u.email, p.profesion FROM usuarios u INNER JOIN postulantes_info p ON u.id = p.usuario_id WHERE u.tipo = 'postulante' AND p.profesion = (SELECT profesion_requerida FROM ofertas WHERE empresa_id = '$empresa_id' LIMIT 1)";
$postulantes_result = $conn->query($postulantes_sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Ofertas</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>Gestionar Ofertas</h1>
    <form method="post">
        <label for="titulo">Título de la Oferta:</label>
        <input type="text" name="titulo" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" required></textarea>

        <label for="profesion_requerida">Profesión Requerida:</label>
        <input type="text" name="profesion_requerida" required>

        <button type="submit" name="crear_oferta">Crear Oferta</button>
    </form>

    <h2>Mis Ofertas</h2>
    <ul>
        <?php while($row = $result->fetch_assoc()): ?>
            <li>
                <?php echo $row['titulo']; ?> - <?php echo $row['descripcion']; ?>
                <br>Profesión Requerida: <?php echo $row['profesion_requerida']; ?>
            </li>
        <?php endwhile; ?>
    </ul>

    <h2>Postulantes para esta Oferta</h2>
    <ul>
        <?php while($row = $postulantes_result->fetch_assoc()): ?>
            <li>
                <?php echo $row['nombre']; ?> - <?php echo $row['email']; ?>
                <br>Profesión: <?php echo $row['profesion']; ?>
            </li>
        <?php endwhile; ?>
    </ul>

    <a href="dashboard.php">Volver al Dashboard</a>
</body>
</html>
