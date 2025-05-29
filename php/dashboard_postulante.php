<?php
session_start(); // Inicia la sesión
include('db.php');

// Verificar si el usuario está logueado y es un postulante
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'postulante') {
    header("Location: login.php"); // Redirigir al login si no está logueado o no es postulante
    exit();
}

$user_id = $_SESSION['user_id']; // Obtener el ID del usuario desde la sesión

// Obtener el nombre y la información del postulante
$sql_user = "SELECT nombre, habilidades, experiencia, profesion FROM usuarios u
             JOIN postulantes_info p ON u.id = p.usuario_id
             WHERE u.id = '$user_id'";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();
$user_name = $user['nombre'];
$user_habilidades = $user['habilidades'];
$user_experiencia = $user['experiencia'];
$user_profesion = $user['profesion'];

// Verificar el estado de la postulación (aceptado o rechazado)
$sql_aceptacion_rechazo = "SELECT o.titulo AS oferta_titulo, u.nombre AS empresa_nombre, p.estado
                           FROM postulaciones p
                           JOIN ofertas_laborales o ON p.oferta_id = o.id
                           JOIN usuarios u ON o.empresa_id = u.id
                           WHERE p.postulante_id = '$user_id'";

$result_aceptacion_rechazo = $conn->query($sql_aceptacion_rechazo);

// Inicializar variables
$estado_postulacion = '';
$empresa_aceptante = '';
$oferta_aceptada = '';
if ($result_aceptacion_rechazo->num_rows > 0) {
    $row = $result_aceptacion_rechazo->fetch_assoc();
    $estado_postulacion = $row['estado'];
    $empresa_aceptante = $row['empresa_nombre'];
    $oferta_aceptada = $row['oferta_titulo'];
} else {
    $estado_postulacion = 'sin_postulacion';
}


// Actualizar la información del postulante
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_datos'])) {
    $nuevas_habilidades = $_POST['habilidades'];
    $nueva_experiencia = $_POST['experiencia'];
    $nueva_profesion = $_POST['profesion'];

    // Actualizar en la base de datos
    $sql_actualizar = "UPDATE postulantes_info 
                       SET habilidades = '$nuevas_habilidades', experiencia = '$nueva_experiencia', profesion = '$nueva_profesion'
                       WHERE usuario_id = '$user_id'";

    if ($conn->query($sql_actualizar) === TRUE) {
        $_SESSION['datos_actualizados'] = 'Datos actualizados correctamente'; // Establece la variable de sesión
        
        // Recargar los valores actualizados directamente desde la base de datos
        $sql_user = "SELECT nombre, habilidades, experiencia, profesion FROM usuarios u
                     JOIN postulantes_info p ON u.id = p.usuario_id
                     WHERE u.id = '$user_id'";
        $result_user = $conn->query($sql_user);
        $user = $result_user->fetch_assoc();

        $user_habilidades = $user['habilidades'];
        $user_experiencia = $user['experiencia'];
        $user_profesion = $user['profesion'];
    } else {
        echo "<p>Error al actualizar los datos: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Postulante</title>
    <link rel="stylesheet" href="../css/styles.css">
<style>
/* styles2.css */

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
    color:rgb(0, 0, 0);
    background: linear-gradient(135deg,#4c8bca,#ffffff);
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
    background: linear-gradient(135deg, #4c8bca,rgb(255, 255, 255));
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

/* Botón de Cerrar Sesión */
.logout-btn {
    position: absolute;
    top: 20px;
    right: 20px;
    background: #e74c3c;
    color: #fff;
    border: none;
    padding: 10px 15px;
    border-radius: 10px;
    font-family: 'Poppins', sans-serif;
    font-weight: bold;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    text-decoration: none; /* Para evitar subrayado si es un enlace */
}

.logout-btn:hover {
    background: #c0392b;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(231, 76, 60, 0.2);
}

/* Sections */
.section {
    margin-bottom: 30px;
}

.update-section h2,
.acceptance-section h2,
.offers-section h2 {
    font-size: 1.6rem;
    margin-bottom: 15px;
    color: #4c8bca;
    border-bottom: 2px solid #4c8bca;
    display: inline-block;
    padding-bottom: 5px;
}

/* Form Styles */
.form {
    margin-top: 15px;
}

textarea, input {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
    background: #f8f8f8;
    transition: all 0.3s ease;
    font-size: 1rem;
}

textarea:focus, input:focus {
    border-color: #4c8bca;
    box-shadow: 0 0 10px #4c8bca;
    outline: none;
}

button {
    background: #4c8bca;
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.3s ease;
}

button:hover {
    background: #4c8bca;
    transform: translateY(-3px);
    box-shadow: 0 4px 10px #4c8bca;
}

/* Offer Section */
.offer {
    padding: 20px;
    background: #fafafa;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.offer:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
}

.offer h3 {
    margin-bottom: 10px;
    color: #4c8bca;
    font-size: 1.4rem;
}

.offer p {
    margin: 5px 0;
    color: #777;
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
    <script>
        // Mostrar el mensaje de éxito si los datos fueron actualizados
        <?php if (isset($_SESSION['datos_actualizados'])): ?>
            window.onload = function() {
                alert("<?php echo $_SESSION['datos_actualizados']; ?>");
                <?php unset($_SESSION['datos_actualizados']); ?> // Eliminar la variable de sesión después de mostrar el mensaje
            };
        <?php endif; ?>
    </script>
</head>
<body>
    <div class="container">
    <header>
    <h1>Bienvenido, <?php echo htmlspecialchars($user_name); ?></h1>
    <p>Este es tu dashboard. Aquí puedes postularte a las ofertas laborales, ver el estado de tus postulaciones y actualizar tus datos.</p>
    <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
</header>


        <!-- Sección de actualización de datos -->
        <section class="update-section">
            <h2>Actualizar Información</h2>
            <form method="POST" class="form">
                <label for="habilidades">Habilidades:</label>
                <textarea name="habilidades"><?php echo htmlspecialchars($user_habilidades); ?></textarea>

                <label for="experiencia">Experiencia:</label>
                <textarea name="experiencia"><?php echo htmlspecialchars($user_experiencia); ?></textarea>

                <label for="profesion">Profesión:</label>
                <input type="text" name="profesion" value="<?php echo htmlspecialchars($user_profesion); ?>">

                <button type="submit" name="actualizar_datos" class="btn">Actualizar Datos</button>
            </form>
        </section>

        <!-- Sección de aceptación/rechazo de la oferta -->
        <section class="acceptance-section">
            <?php if ($estado_postulacion == 'aceptado'): ?>
                <h2>¡Felicidades! Has sido aceptado</h2>
                <p>La empresa <strong><?php echo htmlspecialchars($empresa_aceptante); ?></strong> te ha aceptado para la oferta <strong><?php echo htmlspecialchars($oferta_aceptada); ?></strong>.</p>
            <?php elseif ($estado_postulacion == 'rechazado'): ?>
                <h2>Lo siento, has sido rechazado</h2>
                <p>La empresa <strong><?php echo htmlspecialchars($empresa_aceptante); ?></strong> ha rechazado tu postulación para la oferta <strong><?php echo htmlspecialchars($oferta_aceptada); ?></strong>.</p>
            <?php else: ?>
                <h2>Aún no has sido aceptado o rechazado en ninguna oferta.</h2>
            <?php endif; ?>
        </section>

        <!-- Sección de ofertas disponibles -->
        <section class="offers-section">
            <h2>Ofertas Disponibles</h2>
            <?php
            // Obtener las ofertas laborales
            $sql_ofertas = "SELECT o.id, o.titulo, o.descripcion, o.profesion_solicitada, u.nombre AS empresa_nombre 
                            FROM ofertas_laborales o 
                            JOIN usuarios u ON o.empresa_id = u.id";
            $result_ofertas = $conn->query($sql_ofertas);

            if ($result_ofertas->num_rows > 0) {
                while ($oferta = $result_ofertas->fetch_assoc()) {
                    // Verificar si el usuario ya está postulado a esta oferta
                    $oferta_id = $oferta['id'];
                    $sql_postulacion = "SELECT * FROM postulaciones WHERE oferta_id = '$oferta_id' AND postulante_id = '$user_id'";
                    $result_postulacion = $conn->query($sql_postulacion);

                    // Verificar si el postulante tiene la profesión requerida
                    $profesion_solicitada = $oferta['profesion_solicitada'];

                    echo "<div class='offer'>";
                    echo "<h3>" . htmlspecialchars($oferta['titulo']) . "</h3>";
                    echo "<p><strong>Empresa:</strong> " . htmlspecialchars($oferta['empresa_nombre']) . "</p>";
                    echo "<p>" . htmlspecialchars($oferta['descripcion']) . "</p>";

                    if ($user_profesion != $profesion_solicitada) {
                        echo "<p class='no-apply'>No puedes postularte, la profesión solicitada es diferente a tu profesión.</p>";
                    } else {
                        if ($result_postulacion->num_rows > 0) {
                            echo "<p class='already-applied'>Ya estás postulado a esta oferta.</p>";
                        } else {
                            echo "<form action='apply_offer.php' method='post'>
                                    <input type='hidden' name='oferta_id' value='" . $oferta['id'] . "'>
                                    <button type='submit' class='btn-apply'>Postularme</button>
                                  </form>";
                        }
                    }

                    echo "</div><hr>";
                }
            } else {
                echo "<p>No hay ofertas disponibles en este momento.</p>";
            }
            ?>
        </section>
    </div>
</body>
</html>