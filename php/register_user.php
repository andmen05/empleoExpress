<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('db.php');

    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $tipo_usuario = $_POST['tipo_usuario']; // 'empresa' o 'postulante'

    // Validación de entrada
    if (empty($nombre) || empty($email) || empty($password) || empty($tipo_usuario)) {
        echo "Todos los campos son obligatorios.";
        exit();
    }

    // Usar sentencias preparadas para insertar en la tabla `usuarios`
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, tipo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $password, $tipo_usuario);

    if ($stmt->execute()) {
        // Obtener el ID del nuevo usuario
        $user_id = $conn->insert_id;

        // Procesar datos adicionales según el tipo de usuario
        if ($tipo_usuario == 'postulante') {
            $habilidades = $_POST['habilidades'];
            $experiencia = $_POST['experiencia'];
            $profesion = $_POST['profesion'];

            $stmt_postulante = $conn->prepare("INSERT INTO postulantes_info (usuario_id, habilidades, experiencia, profesion) VALUES (?, ?, ?, ?)");
            $stmt_postulante->bind_param("isss", $user_id, $habilidades, $experiencia, $profesion);

            if ($stmt_postulante->execute()) {
                echo "Registro exitoso como postulante. Ahora puedes iniciar sesión.";
            } else {
                echo "Error al registrar la información adicional del postulante: " . $conn->error;
            }

            $stmt_postulante->close();
        }

        if ($tipo_usuario == 'empresa') {
            $direccion = $_POST['direccion'];
            $correo_empresa = $_POST['correo_empresa'];

            $stmt_empresa = $conn->prepare("INSERT INTO empresas_info (usuario_id, direccion, correo_contacto) VALUES (?, ?, ?)");
            $stmt_empresa->bind_param("iss", $user_id, $direccion, $correo_empresa);

            if ($stmt_empresa->execute()) {
                echo "Registro exitoso como empresa. Ahora puedes iniciar sesión.";
            } else {
                echo "Error al registrar la información adicional de la empresa: " . $conn->error;
            }

            $stmt_empresa->close();
        }

        // Redirige al login
        header("Location: login.php");
        exit();
    } else {
        echo "Error al registrar el usuario: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
/* Reset general */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: #f0f0f0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    padding: 20px;
}

/* Contenedor principal */
.container {
    background-color: #fff;
    width: 100%;
    max-width: 600px;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Título */
h1 {
    font-size: 1.8rem;
    color: #6c5ce7;
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
    color: #6c5ce7;
    margin-bottom: 8px;
}

/* Campos de entrada */
input, textarea, select {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    background: #f8f8f8;
    transition: border-color 0.3s ease;
}

/* Efecto en el foco */
input:focus, textarea:focus, select:focus {
    border-color: #6c5ce7;
    outline: none;
}

/* Botones */
button {
    background-color: #6c5ce7;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

/* Hover en botones */
button:hover {
    background-color: #8e44ad;
}

/* Mensajes de error */
.error-message {
    color: red;
    font-size: 0.9rem;
    margin-top: 10px;
}

/* Estilos para mostrar/ocultar información adicional según el tipo de usuario */
#informacion_postulante, #informacion_empresa {
    display: none;
}

h3 {
    margin-top: 20px;
    color: #6c5ce7;
    font-size: 1.2rem;
}

/* Enlace de login */
p {
    margin-top: 20px;
    font-size: 1rem;
}

a {
    color: #6c5ce7;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

/* Responsividad */
@media (max-width: 600px) {
    .container {
        width: 90%;
        padding: 20px;
    }

    h1 {
        font-size: 1.5rem;
    }
}

    </style>
</head>
<body>

    <div class="container">
        <h1>Registro de Usuario</h1>
        
        <!-- Agregar mensaje de error en caso de que haya problemas -->
        <div class="error-message">
            <?php
            if (isset($error_message)) {
                echo $error_message;
            }
            ?>
        </div>

        <form method="post">
            <label for="nombre">Nombre Completo:</label>
            <input type="text" name="nombre" required>

            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" required>

            <label for="tipo_usuario">Tipo de Usuario:</label>
            <select name="tipo_usuario" required>
                <option value="empresa">Empresa</option>
                <option value="postulante">Postulante</option>
            </select>

            <!-- Campos adicionales solo para postulantes -->
            <div id="informacion_postulante">
                <center><h3>Información del Postulante</h3></center>
                <label for="habilidades">Habilidades:</label>
                <textarea name="habilidades"></textarea>

                <label for="experiencia">Experiencia Laboral:</label>
                <textarea name="experiencia"></textarea>

                <label for="profesion">Profesión:</label>
                <input type="text" name="profesion">

            </div>

            <!-- Campos adicionales solo para empresas -->
            <div id="informacion_empresa">
                <center><h3>Información de la Empresa</h3></center>
                <label for="direccion">Dirección de la Empresa:</label>
                <input type="text" name="direccion">

                <label for="correo_empresa">Correo de la Empresa:</label>
                <input type="email" name="correo_empresa">

            </div>

            <button type="submit">Registrar</button>
        </form>

        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>.</p>
    </div>

    <script>
// Mostrar campos adicionales según el tipo de usuario
document.querySelector('select[name="tipo_usuario"]').addEventListener('change', function() {
    var tipoUsuario = this.value;
    var informacionPostulante = document.getElementById('informacion_postulante');
    var informacionEmpresa = document.getElementById('informacion_empresa');

    if (tipoUsuario === 'postulante') {
        informacionPostulante.style.display = 'block';
        informacionEmpresa.style.display = 'none';
    } else if (tipoUsuario === 'empresa') {
        informacionEmpresa.style.display = 'block';
        informacionPostulante.style.display = 'none';
    }
});

    </script>

</body>
</html>
