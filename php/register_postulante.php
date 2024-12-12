<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('db.php');

    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $habilidades = $_POST['habilidades'];
    $experiencia = $_POST['experiencia'];
    $profesion = $_POST['profesion'];

    // Insertar datos del postulante en la base de datos
    $sql = "INSERT INTO usuarios (nombre, email, password, tipo) VALUES ('$nombre', '$email', '$password', 'postulante')";
    
    if ($conn->query($sql) === TRUE) {
        // Obtener el id del nuevo usuario
        $postulante_id = $conn->insert_id;

        // Guardar la información adicional del postulante
        $sql_info = "INSERT INTO postulantes_info (usuario_id, habilidades, experiencia, profesion) VALUES ('$postulante_id', '$habilidades', '$experiencia', '$profesion')";
        
        if ($conn->query($sql_info) === TRUE) {
            echo "Registro exitoso. Ahora puedes iniciar sesión.";
            header("Location: login.php");
            exit();
        } else {
            echo "Error al registrar la información adicional.";
        }
    } else {
        echo "Error al registrar el usuario: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Postulante</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Registro de Postulante</h1>
    <form method="post">
        <label for="nombre">Nombre Completo:</label>
        <input type="text" name="nombre" required>
        
        <label for="email">Correo Electrónico:</label>
        <input type="email" name="email" required>
        
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>

        <label for="habilidades">Habilidades:</label>
        <textarea name="habilidades" required></textarea>

        <label for="experiencia">Experiencia Laboral:</label>
        <textarea name="experiencia" required></textarea>

        <label for="profesion">Profesión:</label>
        <input type="text" name="profesion" required>

    </form>
</body>
</html>
