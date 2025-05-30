<?php
session_start();

// Verificar si el usuario es una empresa y está autenticado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'empresa') {
    header("Location: login.php");
    exit();
}

include('db.php'); // Incluir la conexión a la base de datos

$empresa_id = $_SESSION['user_id'];

// Verificar si se recibió un ID de oferta
if (!isset($_GET['oferta_id']) || empty($_GET['oferta_id'])) {
    header("Location: dashboard_empresa.php");
    exit();
}

$oferta_id = $_GET['oferta_id'];

// Obtener los datos de la oferta laboral
$sql_oferta = "SELECT * FROM ofertas_laborales WHERE id = '$oferta_id' AND empresa_id = '$empresa_id'";
$result_oferta = $conn->query($sql_oferta);

if ($result_oferta->num_rows == 0) {
    echo "<p>Oferta no encontrada o no tienes permiso para editarla.</p>";
    exit();
}

$oferta = $result_oferta->fetch_assoc();

// Editar oferta laboral
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_oferta'])) {
    $nuevo_titulo = $_POST['titulo'];
    $nueva_descripcion = $_POST['descripcion'];

    // Actualizar la oferta laboral
    $sql_editar_oferta = "UPDATE ofertas_laborales 
                          SET titulo = '$nuevo_titulo', descripcion = '$nueva_descripcion' 
                          WHERE id = '$oferta_id' AND empresa_id = '$empresa_id'";

    if ($conn->query($sql_editar_oferta) === TRUE) {
        echo "<p>Oferta laboral actualizada exitosamente.</p>";
        header("Location: dashboard_empresa.php"); // Redirigir después de la actualización
        exit();
    } else {
        echo "<p>Error al actualizar la oferta: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Oferta Laboral - EmpleoExpress</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <style>
        /* ===============================================
           RESET Y CONFIGURACIÓN BASE - EMPLEOEXPRESS
           =============================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #4c8bca;
            --secondary-color: #3a6f9a;
            --gradient-primary: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            --gradient-bg: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            --text-primary: #2c3e50;
            --text-secondary: #6c757d;
            --bg-white: #ffffff;
            --shadow-light: 0 5px 25px rgba(0, 0, 0, 0.08);
            --shadow-heavy: 0 20px 40px rgba(0, 0, 0, 0.15);
            --border-radius: 20px;
            --border-radius-small: 12px;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--gradient-bg);
            color: var(--text-primary);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Patrón de fondo decorativo */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            z-index: -1;
        }

        /* ===============================================
           CONTENEDOR PRINCIPAL
           =============================================== */
        .main-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .edit-card {
            background: var(--bg-white);
            width: 100%;
            max-width: 700px;
            padding: 3rem;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-heavy);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease;
            transform: translateY(0);
            transition: var(--transition);
        }

        .edit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        /* Decoración superior de la card */
        .edit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: var(--gradient-primary);
        }

        /* ===============================================
           HEADER DE LA PÁGINA
           =============================================== */
        .page-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            position: relative;
            display: inline-block;
        }

        .page-header h1::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1.1rem;
            font-weight: 400;
            margin-top: 1rem;
        }

        .header-icon {
            display: inline-block;
            width: 60px;
            height: 60px;
            background: var(--gradient-primary);
            border-radius: 50%;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }

        .header-icon i {
            font-size: 1.5rem;
            color: white;
        }

        /* ===============================================
           FORMULARIO ESTILIZADO
           =============================================== */
        .form-container {
            position: relative;
        }

        .form-group {
            margin-bottom: 2rem;
            position: relative;
            animation: fadeInUp 0.6s ease;
            animation-fill-mode: both;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }

        .form-label {
            display: block;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.8rem;
            position: relative;
            padding-left: 2rem;
        }

        .form-label i {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        /* Inputs estilizados */
        .form-input, 
        .form-textarea {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e1e8ed;
            border-radius: var(--border-radius-small);
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            background: #f8f9fa;
            transition: var(--transition);
            position: relative;
        }

        .form-input:focus, 
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(76, 139, 202, 0.1);
            transform: translateY(-2px);
        }

        .form-textarea {
            resize: vertical;
            min-height: 150px;
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
        }

        /* Iconos en inputs */
        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 1rem;
            z-index: 10;
            pointer-events: none;
        }

        .textarea-icon {
            position: absolute;
            left: 1rem;
            top: 1rem;
            color: var(--primary-color);
            font-size: 1rem;
            z-index: 10;
            pointer-events: none;
        }

        /* ===============================================
           BOTONES ESTILIZADOS
           =============================================== */
        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2.5rem;
        }

        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: var(--border-radius-small);
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            text-align: center;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: var(--transition);
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-light);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(76, 139, 202, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            box-shadow: var(--shadow-light);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(108, 117, 125, 0.4);
        }

        /* Estados de loading */
        .btn.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn.loading::after {
            content: '';
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: translateY(-50%) rotate(0deg); }
            100% { transform: translateY(-50%) rotate(360deg); }
        }

        /* ===============================================
           MENSAJES DE ESTADO
           =============================================== */
        .message {
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius-small);
            margin: 1rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            animation: slideInDown 0.5s ease;
        }

        .message-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .message-error {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
        }

        /* ===============================================
           ANIMACIONES PERSONALIZADAS
           =============================================== */
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

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        /* ===============================================
           RESPONSIVE DESIGN
           =============================================== */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem 0.5rem;
            }

            .edit-card {
                padding: 2rem 1.5rem;
                margin: 0.5rem;
            }

            .page-header h1 {
                font-size: 2rem;
            }

            .form-input, 
            .form-textarea {
                padding: 0.875rem 0.875rem 0.875rem 2.5rem;
                font-size: 0.95rem;
            }

            .input-icon,
            .textarea-icon {
                left: 0.875rem;
                font-size: 0.9rem;
            }

            .btn {
                padding: 0.875rem 1.5rem;
                font-size: 0.95rem;
            }

            .btn-container {
                gap: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .edit-card {
                padding: 1.5rem 1rem;
            }

            .page-header h1 {
                font-size: 1.8rem;
            }

            .page-subtitle {
                font-size: 1rem;
            }

            .form-label {
                font-size: 0.95rem;
                padding-left: 1.5rem;
            }

            .form-input, 
            .form-textarea {
                padding: 0.75rem 0.75rem 0.75rem 2.25rem;
            }
        }

        /* ===============================================
           EFECTOS HOVER ADICIONALES
           =============================================== */
        .form-group:hover .form-label {
            color: var(--primary-color);
            transition: var(--transition);
        }

        .form-input:hover,
        .form-textarea:hover {
            border-color: var(--primary-color);
            background: white;
        }

        /* Efecto de escritura */
        .form-input:not(:placeholder-shown),
        .form-textarea:not(:placeholder-shown) {
            background: white;
            border-color: var(--primary-color);
        }

        /* Estados de validación visual */
        .form-input:valid,
        .form-textarea:valid {
            border-color: #28a745;
        }

        .form-input:valid + .input-icon,
        .form-textarea:valid + .textarea-icon {
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="edit-card animate__animated animate__fadeInUp">
            <!-- Header de la página -->
            <div class="page-header">
                <div class="header-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h1>Editar Oferta Laboral</h1>
                <p class="page-subtitle">Actualiza la información de tu oferta de empleo</p>
            </div>

            <!-- Formulario de edición -->
            <div class="form-container">
                <form method="POST" id="editForm">
                    <div class="form-group">
                        <label for="titulo" class="form-label">
                            <i class="fas fa-briefcase"></i>
                            Título de la Oferta
                        </label>
                        <div class="input-wrapper">
                            <input 
                                type="text" 
                                id="titulo"
                                name="titulo" 
                                class="form-input"
                                value="<?php echo htmlspecialchars($oferta['titulo']); ?>" 
                                required
                                placeholder="Ej: Desarrollador Full Stack Senior"
                            >
                            <i class="fas fa-briefcase input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="descripcion" class="form-label">
                            <i class="fas fa-align-left"></i>
                            Descripción de la Oferta
                        </label>
                        <div class="input-wrapper">
                            <textarea 
                                id="descripcion"
                                name="descripcion" 
                                class="form-textarea"
                                required
                                placeholder="Describe los requisitos, responsabilidades y beneficios del puesto..."
                            ><?php echo htmlspecialchars($oferta['descripcion']); ?></textarea>
                            <i class="fas fa-align-left textarea-icon"></i>
                        </div>
                    </div>

                    <div class="btn-container">
                        <button type="submit" name="editar_oferta" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i>
                            Actualizar Oferta
                        </button>
                        
                        <a href="dashboard_empresa.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Volver al Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript para mejorar la experiencia del usuario -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('editForm');
            const submitBtn = document.getElementById('submitBtn');
            
            // Efecto de loading en el botón al enviar
            form.addEventListener('submit', function(e) {
                submitBtn.classList.add('loading');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
            });
            
            // Validación en tiempo real
            const inputs = document.querySelectorAll('.form-input, .form-textarea');
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value.trim() !== '') {
                        this.style.borderColor = '#28a745';
                    } else {
                        this.style.borderColor = '#e1e8ed';
                    }
                });
                
                // Efecto de focus
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'translateY(-2px)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'translateY(0)';
                });
            });
            
            // Animación de entrada escalonada para los elementos del formulario
            const formGroups = document.querySelectorAll('.form-group');
            formGroups.forEach((group, index) => {
                group.style.animationDelay = `${0.1 + (index * 0.1)}s`;
            });
        });
        
        // Efecto de partículas en el fondo (opcional)
        function createParticle() {
            const particle = document.createElement('div');
            particle.style.position = 'fixed';
            particle.style.width = '4px';
            particle.style.height = '4px';
            particle.style.background = 'rgba(255, 255, 255, 0.1)';
            particle.style.borderRadius = '50%';
            particle.style.pointerEvents = 'none';
            particle.style.left = Math.random() * window.innerWidth + 'px';
            particle.style.top = '-4px';
            particle.style.zIndex = '-1';
            
            document.body.appendChild(particle);
            
            const animation = particle.animate([
                { transform: 'translateY(0px)', opacity: 1 },
                { transform: `translateY(${window.innerHeight + 4}px)`, opacity: 0 }
            ], {
                duration: Math.random() * 3000 + 2000,
                easing: 'linear'
            });
            
            animation.addEventListener('finish', () => {
                particle.remove();
            });
        }
        
        // Crear partículas ocasionalmente
        setInterval(createParticle, 2000);
    </script>
</body>
</html>