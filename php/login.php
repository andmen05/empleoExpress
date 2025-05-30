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
            echo "<div class='error-message'><i class='fas fa-exclamation-circle'></i> Contraseña incorrecta.</div>";
        }
    } else {
        echo "<div class='error-message'><i class='fas fa-exclamation-circle'></i> Usuario no encontrado.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - EmpleoExpress</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="0,100 1000,0 1000,100"/></svg>');
            background-size: cover;
            pointer-events: none;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            position: relative;
            animation: fadeInUp 1s ease-out;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #4c8bca;
            margin-bottom: 0.5rem;
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        .logo-section p {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 0;
            animation: fadeInUp 1s ease-out 0.4s both;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #4c8bca;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 1rem;
            background: #f8f9fa;
            transition: all 0.3s ease;
            font-family: 'Montserrat', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: #4c8bca;
            background: white;
            box-shadow: 0 0 0 3px rgba(76, 139, 202, 0.1);
            transform: translateY(-2px);
        }

        .form-control:focus + i {
            color: #3a6f9a;
            transform: translateY(-50%) scale(1.1);
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Montserrat', sans-serif;
            margin-top: 1rem;
            animation: fadeInUp 1s ease-out 0.8s both;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #3a6f9a 0%, #2d5a7b 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(76, 139, 202, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            margin-top: 2rem;
            animation: fadeInUp 1s ease-out 1s both;
        }

        .register-link p {
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .register-link a {
            color: #4c8bca;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .register-link a:hover {
            color: #3a6f9a;
            background: rgba(76, 139, 202, 0.1);
            text-decoration: none;
        }

        .error-message {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: shake 0.5s ease-in-out;
        }

        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.95);
            color: #4c8bca;
            padding: 10px 15px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .back-link:hover {
            background: white;
            color: #3a6f9a;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            text-decoration: none;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                padding: 2rem;
                margin: 10px;
            }

            .logo-section h1 {
                font-size: 2rem;
            }

            .back-link {
                position: relative;
                top: auto;
                left: auto;
                display: block;
                width: fit-content;
                margin: 0 auto 2rem auto;
            }
        }

        /* Loading animation for button */
        .btn-login.loading {
            position: relative;
            color: transparent;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <a href="../index.php" class="back-link">
        <i class="fas fa-arrow-left me-2"></i>Volver al inicio
    </a>

    <div class="login-container">
        <div class="logo-section">
            <h1><i class="fas fa-briefcase"></i> EmpleoExpress</h1>
            <p>Bienvenido de nuevo</p>
        </div>

        <form method="post" id="loginForm">
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <div class="input-wrapper">
                    <input type="email" name="email" id="email" class="form-control" required placeholder="tu@email.com">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-wrapper">
                    <input type="password" name="password" id="password" class="form-control" required placeholder="Tu contraseña">
                    <i class="fas fa-lock"></i>
                </div>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">
                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
            </button>
        </form>

        <div class="register-link">
            <p>¿No tienes una cuenta?</p>
            <a href="../php/register_user.php">
                <i class="fas fa-user-plus me-1"></i>Regístrate aquí
            </a>
        </div>
    </div>

    <script>
        // Form submission animation
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });

        // Input focus animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('i').style.transform = 'translateY(-50%) scale(1.1)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.querySelector('i').style.transform = 'translateY(-50%) scale(1)';
            });
        });

        // Auto-hide error messages
        const errorMessage = document.querySelector('.error-message');
        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.opacity = '0';
                errorMessage.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    errorMessage.remove();
                }, 300);
            }, 5000);
        }

        // Smooth animations on load
        window.addEventListener('load', function() {
            document.body.style.opacity = '1';
        });
    </script>
</body>
</html>