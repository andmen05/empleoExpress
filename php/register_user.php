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
        $error_message = "Todos los campos son obligatorios.";
    } else {
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
                    $success_message = "Registro exitoso como postulante. Ahora puedes iniciar sesión.";
                } else {
                    $error_message = "Error al registrar la información adicional del postulante: " . $conn->error;
                }

                $stmt_postulante->close();
            }

            if ($tipo_usuario == 'empresa') {
                $direccion = $_POST['direccion'];
                $correo_empresa = $_POST['correo_empresa'];

                $stmt_empresa = $conn->prepare("INSERT INTO empresas_info (usuario_id, direccion, correo_contacto) VALUES (?, ?, ?)");
                $stmt_empresa->bind_param("iss", $user_id, $direccion, $correo_empresa);

                if ($stmt_empresa->execute()) {
                    $success_message = "Registro exitoso como empresa. Ahora puedes iniciar sesión.";
                } else {
                    $error_message = "Error al registrar la información adicional de la empresa: " . $conn->error;
                }

                $stmt_empresa->close();
            }

            // Redirige al login solo si no hay errores
            if (isset($success_message)) {
                header("Location: login.php");
                exit();
            }
        } else {
            $error_message = "Error al registrar el usuario: " . $conn->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario - EmpleoExpress</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    
    <style>
        /* Reset y configuración base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #4c8bca;
            --secondary-color: #3a6f9a;
            --gradient-primary: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
            --bg-light: #f8f9fa;
            --white: #ffffff;
            --shadow-card: 0 20px 40px rgba(0, 0, 0, 0.15);
            --shadow-hover: 0 25px 50px rgba(0, 0, 0, 0.25);
            --border-radius: 20px;
            --border-radius-small: 12px;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--gradient-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Patrón de fondo decorativo */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 1;
        }

        /* Contenedor principal */
        .main-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 700px;
        }

        .registration-card {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-card);
            padding: 3rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .registration-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient-primary);
        }

        /* Header del formulario */
        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-header p {
            color: var(--text-muted);
            font-size: 1.1rem;
            font-weight: 400;
        }

        /* Mensaje de error/éxito */
        .message-container {
            margin-bottom: 1.5rem;
        }

        .error-message {
            background: linear-gradient(135deg, #ff6b6b, #ff5252);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius-small);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: slideInDown 0.3s ease;
        }

        .success-message {
            background: linear-gradient(135deg, #51cf66, #40c057);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius-small);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: slideInDown 0.3s ease;
        }

        /* Formulario */
        .registration-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border: 2px solid #e9ecef;
            border-radius: var(--border-radius-small);
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            background: var(--bg-light);
            transition: all 0.3s ease;
            color: var(--text-dark);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(76, 139, 202, 0.1);
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: var(--text-muted);
            font-weight: 400;
        }

        /* Iconos en inputs */
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus + .input-icon,
        .input-wrapper:hover .input-icon {
            color: var(--primary-color);
        }

        /* Select personalizado */
        .select-wrapper {
            position: relative;
        }

        .form-control select {
            appearance: none;
            cursor: pointer;
        }

        .select-wrapper::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .select-wrapper:hover::after {
            color: var(--primary-color);
        }

        /* Textarea */
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
            padding-top: 15px;
        }

        /* Secciones dinámicas */
        .additional-info {
            background: linear-gradient(135deg, rgba(76, 139, 202, 0.05), rgba(58, 111, 154, 0.05));
            border-radius: var(--border-radius-small);
            padding: 2rem;
            margin-top: 1rem;
            border-left: 4px solid var(--primary-color);
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            transform: translateY(-20px);
            transition: all 0.4s ease;
        }

        .additional-info.show {
            opacity: 1;
            max-height: 800px;
            transform: translateY(0);
        }

        .additional-info h3 {
            color: var(--primary-color);
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Botón principal */
        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: var(--border-radius-small);
            font-size: 1.1rem;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1rem;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: all 0.5s ease;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(76, 139, 202, 0.4);
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        /* Loading state */
        .btn-primary.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-primary.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Footer del formulario */
        .form-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e9ecef;
        }

        .form-footer p {
            color: var(--text-muted);
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .form-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .form-footer a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        /* Animaciones */
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Estados hidden */
        .hidden {
            display: none !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 1rem 0.5rem;
            }
            
            .registration-card {
                padding: 2rem 1.5rem;
                margin: 1rem 0;
            }
            
            .form-header h1 {
                font-size: 2rem;
            }
            
            .form-control {
                padding: 12px 12px 12px 45px;
                font-size: 0.95rem;
            }
            
            .input-icon {
                font-size: 1rem;
                left: 12px;
            }
            
            .additional-info {
                padding: 1.5rem;
            }
            
            .additional-info h3 {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 480px) {
            .registration-card {
                padding: 1.5rem 1rem;
            }
            
            .form-header h1 {
                font-size: 1.8rem;
            }
            
            .btn-primary {
                padding: 12px 25px;
                font-size: 1rem;
            }
        }

        /* Mejoras de accesibilidad */
        .form-control:focus,
        .btn-primary:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }

        /* Estados de validación */
        .form-control.error {
            border-color: #dc3545;
            background-color: #fff5f5;
        }

        .form-control.success {
            border-color: #28a745;
            background-color: #f8fff8;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="registration-card" data-aos="fade-up">
            <!-- Header -->
            <div class="form-header" data-aos="fade-down" data-aos-delay="100">
                <h1><i class="fas fa-user-plus"></i> Registro</h1>
                <p>Crea tu cuenta en EmpleoExpress</p>
            </div>

            <!-- Mensajes de error/éxito -->
            <div class="message-container">
                <?php if (isset($error_message)): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($success_message)): ?>
                    <div class="success-message">
                        <i class="fas fa-check-circle"></i>
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Formulario -->
            <form method="post" class="registration-form" data-aos="fade-up" data-aos-delay="200">
                
                <!-- Nombre -->
                <div class="form-group">
                    <label for="nombre">
                        <i class="fas fa-user"></i> Nombre Completo
                    </label>
                    <div class="input-wrapper">
                        <input type="text" name="nombre" id="nombre" class="form-control" 
                               placeholder="Ingresa tu nombre completo" required>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Correo Electrónico
                    </label>
                    <div class="input-wrapper">
                        <input type="email" name="email" id="email" class="form-control" 
                               placeholder="tu@email.com" required>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Contraseña
                    </label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" class="form-control" 
                               placeholder="Crea una contraseña segura" required>
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>

                <!-- Tipo de usuario -->
                <div class="form-group">
                    <label for="tipo_usuario">
                        <i class="fas fa-users"></i> Tipo de Usuario
                    </label>
                    <div class="input-wrapper select-wrapper">
                        <select name="tipo_usuario" id="tipo_usuario" class="form-control" required>
                            <option value="">Selecciona tu tipo de cuenta</option>
                            <option value="empresa">🏢 Empresa</option>
                            <option value="postulante">👤 Postulante</option>
                        </select>
                        <i class="fas fa-users input-icon"></i>
                    </div>
                </div>

                <!-- Información adicional para postulantes -->
                <div id="informacion_postulante" class="additional-info">
                    <h3>
                        <i class="fas fa-user-tie"></i>
                        Información del Postulante
                    </h3>
                    
                    <div class="form-group">
                        <label for="habilidades">
                            <i class="fas fa-star"></i> Habilidades
                        </label>
                        <div class="input-wrapper">
                            <textarea name="habilidades" id="habilidades" class="form-control" 
                                    placeholder="Describe tus principales habilidades..."></textarea>
                            <i class="fas fa-star input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="experiencia">
                            <i class="fas fa-briefcase"></i> Experiencia Laboral
                        </label>
                        <div class="input-wrapper">
                            <textarea name="experiencia" id="experiencia" class="form-control" 
                                    placeholder="Cuéntanos sobre tu experiencia profesional..."></textarea>
                            <i class="fas fa-briefcase input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="profesion">
                            <i class="fas fa-graduation-cap"></i> Profesión
                        </label>
                        <div class="input-wrapper">
                            <input type="text" name="profesion" id="profesion" class="form-control" 
                                   placeholder="¿Cuál es tu profesión?">
                            <i class="fas fa-graduation-cap input-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Información adicional para empresas -->
                <div id="informacion_empresa" class="additional-info">
                    <h3>
                        <i class="fas fa-building"></i>
                        Información de la Empresa
                    </h3>
                    
                    <div class="form-group">
                        <label for="direccion">
                            <i class="fas fa-map-marker-alt"></i> Dirección de la Empresa
                        </label>
                        <div class="input-wrapper">
                            <input type="text" name="direccion" id="direccion" class="form-control" 
                                   placeholder="Dirección completa de tu empresa">
                            <i class="fas fa-map-marker-alt input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="correo_empresa">
                            <i class="fas fa-envelope-open"></i> Correo de la Empresa
                        </label>
                        <div class="input-wrapper">
                            <input type="email" name="correo_empresa" id="correo_empresa" class="form-control" 
                                   placeholder="contacto@tuempresa.com">
                            <i class="fas fa-envelope-open input-icon"></i>
                        </div>
                    </div>
                </div>

                <!-- Botón de registro -->
                <button type="submit" class="btn-primary" id="submitBtn">
                    <i class="fas fa-user-plus"></i>
                    Crear Cuenta
                </button>
            </form>

            <!-- Footer -->
            <div class="form-footer" data-aos="fade-up" data-aos-delay="400">
                <p>¿Ya tienes una cuenta?</p>
                <a href="login.php">
                    <i class="fas fa-sign-in-alt"></i>
                    Inicia sesión aquí
                </a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Inicializar AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Manejar cambio de tipo de usuario - VERSIÓN CORREGIDA
        document.getElementById('tipo_usuario').addEventListener('change', function() {
            const tipoUsuario = this.value;
            const informacionPostulante = document.getElementById('informacion_postulante');
            const informacionEmpresa = document.getElementById('informacion_empresa');

            // Ocultar ambas secciones primero
            informacionPostulante.classList.remove('show');
            informacionEmpresa.classList.remove('show');

            // Mostrar la sección correspondiente después de un pequeño delay
            setTimeout(() => {
                if (tipoUsuario === 'postulante') {
                    informacionPostulante.classList.add('show');
                } else if (tipoUsuario === 'empresa') {
                    informacionEmpresa.classList.add('show');
                }
            }, 200);
        });

        // Validación en tiempo real
        const formControls = document.querySelectorAll('.form-control');
        
        formControls.forEach(control => {
            control.addEventListener('blur', function() {
                validateField(this);
            });

            control.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    validateField(this);
                }
            });
        });

        function validateField(field) {
            const value = field.value.trim();
            
            // Remover clases previas
            field.classList.remove('error', 'success');
            
            if (field.hasAttribute('required') && value === '') {
                field.classList.add('error');
                return false;
            }
            
            if (field.type === 'email' && value !== '') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    field.classList.add('error');
                    return false;
                }
            }
            
            if (field.type === 'password' && value !== '') {
                if (value.length < 6) {
                    field.classList.add('error');
                    return false;
                }
            }
            
            if (value !== '') {
                field.classList.add('success');
            }
            
            return true;
        }

        // Manejar envío del formulario
        document.querySelector('.registration-form').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            
            // Agregar estado de loading
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creando cuenta...';
            
            // Validar todos los campos
            let isValid = true;
            const requiredFields = document.querySelectorAll('.form-control[required]');
            
            requiredFields.forEach(field => {
                if (!validateField(field)) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                submitBtn.classList.remove('loading');
                submitBtn.innerHTML = '<i class="fas fa-user-plus"></i> Crear Cuenta';
                
                // Mostrar mensaje de error
                showMessage('Por favor, corrige los errores en el formulario', 'error');
            }
        });

        // Función para mostrar mensajes
        function showMessage(message, type) {
            const container = document.querySelector('.message-container');
            const messageClass = type === 'error' ? 'error-message' : 'success-message';
            const icon = type === 'error' ? 'fas fa-exclamation-triangle' : 'fas fa-check-circle';
            
            container.innerHTML = `
                <div class="${messageClass}">
                    <i class="${icon}"></i>
                    ${message}
                </div>
            `;
            
            // Auto-hide después de 5 segundos
            setTimeout(() => {
                container.innerHTML = '';
            }, 5000);
        }

        // Mejorar experiencia de usuario con efectos sutiles
        document.addEventListener('DOMContentLoaded', function() {
            // Agregar efecto de focus a los grupos de formulario
            const formGroups = document.querySelectorAll('.form-group');
            
            formGroups.forEach(group => {
                const input = group.querySelector('.form-control');
                
                input.addEventListener('focus', () => {
                    group.style.transform = 'scale(1.02)';
                    group.style.transition = 'transform 0.2s ease';
                });
                
                input.addEventListener('blur', () => {
                    group.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>