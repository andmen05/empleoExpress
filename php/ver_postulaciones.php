<?php
session_start();

// Verificar si el usuario es una empresa y está autenticado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'empresa') {
    header("Location: login.php");
    exit();
}

include('db.php'); // Incluir la conexión a la base de datos

// Obtener la oferta específica a la que se están postulando
if (isset($_GET['oferta_id'])) {
    $oferta_id = $_GET['oferta_id'];

    // Verificar si la oferta pertenece a la empresa
    $empresa_id = $_SESSION['user_id'];
    $sql_oferta = "SELECT * FROM ofertas_laborales WHERE id = '$oferta_id' AND empresa_id = '$empresa_id'";
    $result_oferta = $conn->query($sql_oferta);

    if ($result_oferta->num_rows == 0) {
        echo "No tienes acceso a esta oferta.";
        exit();
    }
} else {
    echo "Oferta no especificada.";
    exit();
}

// Obtener los postulantes a la oferta
$sql_postulantes = "SELECT p.*, u.nombre AS postulante_nombre, u.email AS postulante_email
                    FROM postulaciones p
                    JOIN usuarios u ON p.postulante_id = u.id
                    WHERE p.oferta_id = '$oferta_id'";
$result_postulantes = $conn->query($sql_postulantes);

// Aceptar o rechazar postulante
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postulante_id = $_POST['postulante_id'];

    if (isset($_POST['aceptar_postulante'])) {
        // Actualizar la base de datos para marcar al postulante como aceptado
        $sql_update = "UPDATE postulaciones SET estado = 'aceptado' WHERE postulante_id = '$postulante_id' AND oferta_id = '$oferta_id'";
    } elseif (isset($_POST['rechazar_postulante'])) {
        // Actualizar la base de datos para marcar al postulante como rechazado
        $sql_update = "UPDATE postulaciones SET estado = 'rechazado' WHERE postulante_id = '$postulante_id' AND oferta_id = '$oferta_id'";
    }

    if (isset($sql_update) && $conn->query($sql_update) === TRUE) {
        header("Location: ver_postulaciones.php?oferta_id=$oferta_id"); // Redirigir para recargar la página
        exit();
    } else {
        echo "<p>Error al actualizar el estado del postulante: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postulantes a la Oferta</title>
    <link rel="stylesheet" href="../css/stylesp.css">
</head>
<Style>
    /* Reset general */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: #f7f7f7;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    height: 100vh;
    padding: 20px;
}

/* Contenedor principal */
.container {
    background-color: #fff;
    width: 100%;
    max-width: 900px;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Título */
header h1 {
    font-size: 2rem;
    color: #6c5ce7;
    margin-bottom: 20px;
}

/* Sección de postulantes */
.postulantes {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
    text-align: left;
}

table th, table td {
    padding: 15px;
    border: 1px solid #ddd;
}

table th {
    background-color: #6c5ce7;
    color: white;
    font-size: 1.1rem;
}

table td {
    font-size: 1rem;
}

/* Estilos de los botones de aceptación y rechazo */
button {
    padding: 8px 16px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

.btn-aceptar {
    background-color: #27ae60;
    color: white;
    border: none;
}

.btn-aceptar:hover {
    background-color: #2ecc71;
}

.btn-rechazar {
    background-color: #e74c3c;
    color: white;
    border: none;
}

.btn-rechazar:hover {
    background-color: #c0392b;
}

/* Estado de los postulantes */
.estado-aceptado {
    color: #27ae60;
    font-weight: bold;
}

.estado-rechazado {
    color: #e74c3c;
    font-weight: bold;
}

/* Botón para volver al dashboard */
footer {
    margin-top: 20px;
}

.btn-volver {
    background-color: #6c5ce7;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

.btn-volver:hover {
    background-color: #8e44ad;
}

/* Responsividad */
@media (max-width: 600px) {
    .container {
        width: 100%;
        padding: 20px;
    }

    header h1 {
        font-size: 1.5rem;
    }

    table th, table td {
        font-size: 0.9rem;
        padding: 10px;
    }

    button {
        font-size: 0.9rem;
    }
}

</Style>
<body>
    <div class="container">
        <header>
            <h1>Postulantes a la Oferta</h1>
        </header>

        <section class="postulantes">
            <table>
                <thead>
                    <tr>
                        <th>Postulante</th>
                        <th>Correo Electrónico</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($postulante = $result_postulantes->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $postulante['postulante_nombre']; ?></td>
                            <td><?php echo $postulante['postulante_email']; ?></td>
                            <td><?php echo ucfirst($postulante['estado']); ?></td>
                            <td>
                                <?php if ($postulante['estado'] == 'aceptado'): ?>
                                    <span class="estado-aceptado">Aceptado</span>
                                <?php elseif ($postulante['estado'] == 'rechazado'): ?>
                                    <span class="estado-rechazado">Rechazado</span>
                                <?php else: ?>
                                    <form method="POST" action="ver_postulaciones.php?oferta_id=<?php echo $oferta_id; ?>">
                                        <input type="hidden" name="postulante_id" value="<?php echo $postulante['postulante_id']; ?>">
                                        <button type="submit" name="aceptar_postulante" class="btn-aceptar">Aceptar</button>
                                        <button type="submit" name="rechazar_postulante" class="btn-rechazar">Rechazar</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <footer>
            <a href="dashboard_empresa.php" class="btn-volver">Volver al Dashboard</a>
        </footer>
    </div>
</body>
</html>
