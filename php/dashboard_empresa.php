<?php
session_start();

// Verificar si el usuario es una empresa y está autenticado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'empresa') {
    header("Location: login.php");
    exit();
}

include('db.php'); // Incluir la conexión a la base de datos

$empresa_id = $_SESSION['user_id'];

// Obtener las ofertas laborales de la empresa
$sql_ofertas = "SELECT * FROM ofertas_laborales WHERE empresa_id = '$empresa_id'";
$result_ofertas = $conn->query($sql_ofertas);

// Crear una nueva oferta laboral
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_oferta'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $profesion_solicitada = $_POST['profesion_solicitada'];

    $sql_crear_oferta = "INSERT INTO ofertas_laborales (empresa_id, titulo, descripcion, profesion_solicitada) 
                         VALUES ('$empresa_id', '$titulo', '$descripcion', '$profesion_solicitada')";

    if ($conn->query($sql_crear_oferta) === TRUE) {
        header("Location: dashboard_empresa.php");
        exit();
    } else {
        echo "<p>Error al crear la oferta: " . $conn->error . "</p>";
    }
}

// Editar oferta laboral
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_oferta'])) {
    $oferta_id = $_POST['oferta_id'];
    $nuevo_titulo = $_POST['titulo'];
    $nueva_descripcion = $_POST['descripcion'];
    $nueva_profesion = $_POST['profesion_solicitada'];

    $sql_editar_oferta = "UPDATE ofertas_laborales 
                          SET titulo = '$nuevo_titulo', descripcion = '$nueva_descripcion', profesion_solicitada = '$nueva_profesion' 
                          WHERE id = '$oferta_id' AND empresa_id = '$empresa_id'";

    if ($conn->query($sql_editar_oferta) === TRUE) {
        header("Location: dashboard_empresa.php");
        exit();
    } else {
        echo "<p>Error al actualizar la oferta: " . $conn->error . "</p>";
    }
}

// Eliminar oferta laboral
if (isset($_GET['eliminar_oferta_id'])) {
    $oferta_id = $_GET['eliminar_oferta_id'];

    $sql_eliminar_oferta = "DELETE FROM ofertas_laborales WHERE id = '$oferta_id' AND empresa_id = '$empresa_id'";

    if ($conn->query($sql_eliminar_oferta) === TRUE) {
        header("Location: dashboard_empresa.php");
        exit();
    } else {
        echo "<p>Error al eliminar la oferta: " . $conn->error . "</p>";
    }
}

// Obtener las postulaciones para las ofertas de esta empresa
$sql_postulaciones = "SELECT p.*, u.nombre AS postulante_nombre, o.titulo AS oferta_titulo, p.estado, p.fecha_postulacion
                      FROM postulaciones p
                      JOIN usuarios u ON p.postulante_id = u.id
                      JOIN ofertas_laborales o ON p.oferta_id = o.id
                      WHERE o.empresa_id = '$empresa_id'";
$result_postulaciones = $conn->query($sql_postulaciones);

// Aceptar postulante
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['postulante_id']) && isset($_POST['oferta_id'])) {
    $postulante_id = $_POST['postulante_id'];
    $oferta_id = $_POST['oferta_id'];

    $sql_aceptar = "UPDATE postulaciones SET estado = 'aceptado' WHERE postulante_id = '$postulante_id' AND oferta_id = '$oferta_id'";

    if ($conn->query($sql_aceptar) === TRUE) {
        header("Location: dashboard_empresa.php");
        exit();
    } else {
        echo "<p>Error al aceptar al postulante: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Empresa</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Bienvenido, <?php echo $_SESSION['user_name']; ?> (Empresa)</h1>
            <nav>
                <a href="logout.php" class="btn">Cerrar sesión</a>
            </nav>
        </header>
<style>/* styles1.css */

/* General Reset */
body, h1, h2, h3, p, form, input, textarea, button {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* General Styles */
body {
    font-family: 'Poppins', sans-serif;
    line-height: 1.6;
    color: #4a4a4a;
    background: linear-gradient(135deg, #8e44ad, #6c5ce7);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 30px 20px; /* Espaciado general */
}

.container {
    background: #ffffff;
    max-width: 900px;
    width: 100%;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    animation: fadeIn 0.8s ease-in-out;
    padding: 30px; /* Espaciado interno */
    position: relative; /* Para posicionar elementos dentro */
}

/* Header */
header {
    background: linear-gradient(135deg, #6c5ce7, #8e44ad);
    color: #fff;
    padding: 30px 20px;
    border-radius: 10px;
    margin-bottom: 30px;
    position: relative;
}

header h1 {
    font-size: 2rem;
    font-weight: bold;
}

header p {
    font-size: 1.1rem;
    margin-top: 10px;
    color: #e4e4e4;
}

nav a {
    position: absolute;
    top: 20px;
    right: 20px;
    background: #e74c3c;
    color: #fff;
    padding: 10px 15px;
    border-radius: 10px;
    font-family: 'Poppins', sans-serif;
    font-weight: bold;
    cursor: pointer;
    font-size: 0.9rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

nav a:hover {
    background: #c0392b;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(231, 76, 60, 0.2);
}

/* Form Section */
.form-section h2 {
    font-size: 1.8rem;
    color: #6c5ce7;
    margin-bottom: 20px;
}

.form {
    display: flex;
    flex-direction: column;
}

.form label {
    font-size: 1rem;
    margin-bottom: 5px;
}

input, textarea {
    padding: 12px 15px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
    background: #f8f8f8;
    transition: all 0.3s ease;
}

input:focus, textarea:focus {
    border-color: #6c5ce7;
    box-shadow: 0 0 10px rgba(108, 92, 231, 0.3);
    outline: none;
}

button {
    background: #6c5ce7;
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.3s ease;
}

button:hover {
    background: #8e44ad;
    transform: translateY(-3px);
    box-shadow: 0 4px 10px rgba(142, 68, 173, 0.2);
}

/* Offers Table */
.offers-section h2 {
    font-size: 1.8rem;
    color: #6c5ce7;
    margin-bottom: 20px;
}

.offers-table {
    width: 100%;
    border-collapse: collapse;
}

.offers-table th, .offers-table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: left;
}

.offers-table th {
    background-color: #6c5ce7;
    color: #fff;
}

.offers-table td {
    background-color: #f9f9f9;
}

.offers-table td a {
    display: inline-block;
    padding: 8px 15px;
    margin: 5px 0;
    border-radius: 10px;
    background-color: #6c5ce7;
    color: #fff;
    text-decoration: none;
    transition: all 0.3s ease;
}

.offers-table td a:hover {
    background-color: #8e44ad;
    transform: translateY(-2px);
}

/* Applications Section */
.applications-section h2 {
    font-size: 1.8rem;
    color: #6c5ce7;
    margin-bottom: 20px;
}

.applications-table {
    width: 100%;
    border-collapse: collapse;
}

.applications-table th, .applications-table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: left;
}

.applications-table th {
    background-color: #6c5ce7;
    color: #fff;
}

.applications-table td {
    background-color: #f9f9f9;
}

.applications-table td form {
    display: inline-block;
}

.estado-aceptado {
    color: #2ecc71;
    font-weight: bold;
}

.estado-pendiente {
    color: #f39c12;
    font-weight: bold;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
        <section class="form-section">
            <h2>Crear Nueva Oferta Laboral</h2>
            <form method="POST" class="form">
                <label for="titulo">Título de la Oferta:</label>
                <input type="text" name="titulo" required>

                <label for="descripcion">Descripción de la Oferta:</label>
                <textarea name="descripcion" required></textarea>

                <label for="profesion_solicitada">Profesión Solicitada:</label>
                <input type="text" name="profesion_solicitada" required>

                <button type="submit" name="crear_oferta" class="btn">Crear Oferta</button>
            </form>
        </section>

        <section class="offers-section">
            <h2>Mis Ofertas Laborales</h2>
            <table class="offers-table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Profesión Solicitada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($oferta = $result_ofertas->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $oferta['titulo']; ?></td>
                            <td><?php echo $oferta['descripcion']; ?></td>
                            <td><?php echo $oferta['profesion_solicitada']; ?></td>
                            <td>
                                <a href="editar_oferta.php?oferta_id=<?php echo $oferta['id']; ?>" class="btn">Editar</a>
                                <a href="?eliminar_oferta_id=<?php echo $oferta['id']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta oferta?')">Eliminar</a>
                                <a href="ver_postulaciones.php?oferta_id=<?php echo $oferta['id']; ?>" class="btn">Ver Postulantes</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <section class="applications-section">
            <h2>Postulantes a Mis Ofertas</h2>
            <table class="applications-table">
                <thead>
                    <tr>
                        <th>Postulante</th>
                        <th>Oferta</th>
                        <th>Fecha de Postulación</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($postulacion = $result_postulaciones->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $postulacion['postulante_nombre']; ?></td>
                            <td><?php echo $postulacion['oferta_titulo']; ?></td>
                            <td><?php echo $postulacion['fecha_postulacion']; ?></td>
                            <td>
                                <?php if ($postulacion['estado'] == 'aceptado'): ?>
                                    <span class="estado-aceptado">Aceptado</span>
                                <?php else: ?>
                                    <span class="estado-pendiente">Pendiente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($postulacion['estado'] != 'aceptado'): ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="postulante_id" value="<?php echo $postulacion['postulante_id']; ?>">
                                        <input type="hidden" name="oferta_id" value="<?php echo $postulacion['oferta_id']; ?>">
                                        <button type="submit" class="btn">Aceptar</button>
                                    </form>
                                <?php else: ?>
                                    <span>-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>
