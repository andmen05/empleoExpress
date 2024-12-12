<?php
session_start();

// Verificar que el usuario esté autenticado y sea una empresa
if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo_usuario'] != 'empresa') {
    header('Location: login.php');
    exit();
}

include('db.php');

// Obtener las ofertas de la empresa actual
$sql_ofertas = "SELECT * FROM ofertas WHERE empresa_id = ?";
$stmt = $conn->prepare($sql_ofertas);
$stmt->bind_param('i', $_SESSION['usuario_id']);
$stmt->execute();
$result_ofertas = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Postulaciones</title>
</head>
<body>
    <h1>Mis Postulaciones</h1>

    <?php while ($oferta = $result_ofertas->fetch_assoc()): ?>
        <h2>Postulaciones para: <?php echo $oferta['titulo']; ?></h2>

        <?php
        // Obtener los postulantes que se han postulado a esta oferta
        $sql_postulantes = "SELECT u.nombre, u.email, p.fecha_postulacion 
                            FROM postulaciones p
                            JOIN usuarios u ON p.postulante_id = u.id
                            WHERE p.oferta_id = ?";
        $stmt_postulantes = $conn->prepare($sql_postulantes);
        $stmt_postulantes->bind_param('i', $oferta['id']);
        $stmt_postulantes->execute();
        $result_postulantes = $stmt_postulantes->get_result();
        ?>

        <?php if ($result_postulantes->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Fecha de Postulación</th>
                </tr>
                <?php while ($postulante = $result_postulantes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $postulante['nombre']; ?></td>
                        <td><?php echo $postulante['email']; ?></td>
                        <td><?php echo $postulante['fecha_postulacion']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No hay postulantes para esta oferta.</p>
        <?php endif; ?>
    <?php endwhile; ?>
</body>
</html>
