<?php
session_start();
include('db.php');

// Verificar si el usuario está autenticado y es una empresa
if (!isset($_SESSION['user_id']) || $_SESSION['tipo'] !== 'empresa') {
    header("Location: login.php");
    exit();
}

$empresa_id = $_SESSION['user_id'];

// Obtener las ofertas de la empresa
$sql_ofertas = "SELECT * FROM ofertas WHERE empresa_id = $empresa_id";
$result_ofertas = $conn->query($sql_ofertas);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postulantes a tus ofertas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Postulantes a tus Ofertas</h1>

    <?php
    if ($result_ofertas->num_rows > 0) {
        while ($oferta = $result_ofertas->fetch_assoc()) {
            echo "<h2>" . $oferta['titulo'] . "</h2>";

            // Obtener los postulantes de esta oferta
            $oferta_id = $oferta['id'];
            $sql_postulantes = "SELECT usuarios.nombre, usuarios.email, postulantes_info.habilidades, postulantes_info.experiencia, postulantes_info.profesion
                                FROM postulaciones
                                JOIN usuarios ON postulaciones.postulante_id = usuarios.id
                                JOIN postulantes_info ON usuarios.id = postulantes_info.usuario_id
                                WHERE postulaciones.oferta_id = $oferta_id";
            $result_postulantes = $conn->query($sql_postulantes);

            if ($result_postulantes->num_rows > 0) {
                echo "<ul>";
                while ($postulante = $result_postulantes->fetch_assoc()) {
                    echo "<li>";
                    echo "<strong>Nombre:</strong> " . $postulante['nombre'] . "<br>";
                    echo "<strong>Email:</strong> " . $postulante['email'] . "<br>";
                    echo "<strong>Habilidades:</strong> " . $postulante['habilidades'] . "<br>";
                    echo "<strong>Experiencia:</strong> " . $postulante['experiencia'] . "<br>";
                    echo "<strong>Profesión:</strong> " . $postulante['profesion'] . "<br>";
                    echo "</li>";
                }
                echo "</ul>";
            } else {
                echo "No hay postulantes para esta oferta.";
            }
        }
    } else {
        echo "No has publicado ninguna oferta aún.";
    }
    ?>
</body>
</html>
