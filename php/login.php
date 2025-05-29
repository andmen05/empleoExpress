<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar si el usuario existe
    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar si la contraseña es correcta
        if (password_verify($password, $user['password'])) {
            // Establecer las variables de sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre']; // Guardamos el nombre del usuario
            $_SESSION['user_type'] = $user['tipo'];  // Guardamos el tipo de usuario (empresa/postulante)

            // Redirigir al dashboard
            if ($_SESSION['user_type'] == 'empresa') {
                header("Location: dashboard_empresa.php");
            } else {
                header("Location: dashboard_postulante.php");
            }
            exit();
        } else {
            echo "<p class='error'>Contraseña incorrecta.</p>";
        }
    } else {
        echo "<p class='error'>Usuario no encontrado.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/stylesgin.css">
    <Style>
        /* Reset general */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to bottom right, #4c8bca, #ffffff);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    padding: 20px;
}


/* Contenedor principal */
.login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.3); /* Fondo con ligera opacidad */
}

.login-form {
    background-color: #fff;
    width: 100%;
    max-width: 400px;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-align: center;
}

h1 {
    font-size: 1.8rem;
    color: #4c8bca;
    margin-bottom: 20px;
}

/* Estilo del formulario */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    text-align: left;
}

/* Etiquetas */
label {
    font-size: 1rem;
    font-weight: bold;
    color: #4c8bca;
    margin-bottom: 8px;
}

/* Campos de entrada */
input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    background: #f8f8f8;
    transition: border-color 0.3s ease;
}

/* Efecto en el foco */
input:focus {
    border-color: #4c8bca;
    outline: none;
}

/* Botón de acción */
button {
    background-color: #4c8bca;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

/* Hover en botón */
button:hover {
    background-color: #4c8bca;
}

/* Enlace de registro */
p {
    margin-top: 20px;
    font-size: 1rem;
}

a {
    color: #4c8bca;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

/* Mensajes de error */
.error {
    color: red;
    font-size: 0.9rem;
    margin-top: 10px;
}

/* Responsividad */
@media (max-width: 600px) {
    .login-form {
        padding: 20px;
        width: 90%;
    }

    h1 {
        font-size: 1.5rem;
    }
}

    </Style>
</head>
<body>
    
        <div class="login-form">
            <h1>Iniciar Sesión</h1>
            <form method="post">
                <label for="email">Correo Electrónico:</label>
                <input type="email" name="email" required placeholder="Introduce tu correo electrónico">

                <label for="password">Contraseña:</label>
                <input type="password" name="password" required placeholder="Introduce tu contraseña">

                <button type="submit">Iniciar sesión</button>
            </form>
            <p>¿No tienes una cuenta? <a href="../php/register_user.php">Regístrate aquí</a>.</p>
        </div>
</body>
</html>
