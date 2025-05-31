<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die('<html><head><title>Error</title><style>body{font-family:Arial;text-align:center;margin-top:50px;}</style></head><body><h2>No has iniciado sesión.</h2><a href="login.php">Iniciar Sesión</a></body></html>');
}

$usuario_id = $_SESSION['user_id'];
$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = $_FILES['cv']['name'];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);

        if (strtolower($extension) !== 'pdf') {
            $mensaje = "El archivo debe ser un PDF.";
            $tipo_mensaje = 'error';
        } else {
            $carpetaDestino = 'uploads/cv/';
            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }

            $nuevoNombre = 'cv_' . $usuario_id . '_' . time() . '.pdf';
            $rutaDestino = $carpetaDestino . $nuevoNombre;

            if (move_uploaded_file($_FILES['cv']['tmp_name'], $rutaDestino)) {
                $stmt = $conn->prepare("UPDATE postulantes_info SET cv = ? WHERE usuario_id = ?");
                $stmt->bind_param("si", $rutaDestino, $usuario_id);
                $stmt->execute();

                $mensaje = "Hoja de vida subida con éxito.";
                $tipo_mensaje = 'success';
            } else {
                $mensaje = "Error al mover el archivo.";
                $tipo_mensaje = 'error';
            }
        }
    } else {
        $mensaje = "Error al subir el archivo.";
        $tipo_mensaje = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Hoja de Vida - EmpleoExpress</title>
    
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
            position: relative;
            overflow-x: hidden;
        }

        /* Background Pattern - Patrón SVG de fondo */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.08) 0%, transparent 50%),
                linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.02) 50%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* Main Container */
        .main-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        /* Content Card */
        .content-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            max-width: 600px;
            width: 100%;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease;
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

        /* Header Section */
        .header-section {
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            padding: 2.5rem;
            text-align: center;
            color: white;
            position: relative;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
            pointer-events: none;
        }

        .header-section h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .header-section p {
            font-size: 1.1rem;
            font-weight: 400;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .header-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.9;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* Form Section */
        .form-section {
            padding: 3rem;
            animation: fadeInUp 0.8s ease 0.4s both;
        }

        /* Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            margin-bottom: 2rem;
            box-shadow: 0 5px 15px rgba(76, 139, 202, 0.2);
        }

        .back-button:hover {
            background: linear-gradient(135deg, #3a6f9a 0%, #2c5282 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(76, 139, 202, 0.3);
            color: white;
        }

        .back-button i {
            transition: transform 0.3s ease;
        }

        .back-button:hover i {
            transform: translateX(-3px);
        }

        /* Upload Area */
        .upload-container {
            margin-bottom: 2rem;
        }

        .file-input-wrapper {
            position: relative;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 10;
        }

        .upload-area {
            border: 3px dashed #4c8bca;
            border-radius: 12px;
            padding: 3rem 2rem;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .upload-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(76, 139, 202, 0.1), transparent);
            transition: left 0.6s ease;
        }

        .upload-area:hover::before {
            left: 100%;
        }

        .upload-area:hover,
        .upload-area.dragover {
            border-color: #3a6f9a;
            background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(76, 139, 202, 0.15);
        }

        .upload-icon {
            font-size: 3.5rem;
            color: #4c8bca;
            margin-bottom: 1.5rem;
            display: block;
            transition: all 0.3s ease;
        }

        .upload-area:hover .upload-icon {
            transform: scale(1.1);
            color: #3a6f9a;
        }

        .upload-text {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .upload-subtext {
            color: #6c757d;
            font-size: 0.95rem;
            font-weight: 400;
        }

        /* File Selected State */
        .file-selected {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 0.8rem;
            margin-top: 1.5rem;
            padding: 1rem;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-radius: 8px;
            color: #155724;
            font-weight: 600;
            animation: slideInDown 0.4s ease;
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

        .file-selected i {
            font-size: 1.2rem;
            color: #28a745;
        }

        /* Submit Button */
        .submit-button {
            width: 100%;
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 18px;
            font-size: 1.1rem;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(76, 139, 202, 0.2);
        }

        .submit-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .submit-button:hover::before {
            left: 100%;
        }

        .submit-button:hover {
            background: linear-gradient(135deg, #3a6f9a 0%, #2c5282 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(76, 139, 202, 0.3);
        }

        .submit-button:active {
            transform: translateY(0);
        }

        .submit-button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .button-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .loading-spinner {
            display: none;
            width: 22px;
            height: 22px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Messages */
        .message {
            margin-top: 1.5rem;
            padding: 1.2rem;
            border-radius: 12px;
            font-weight: 600;
            display: none;
            animation: slideIn 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .message::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: currentColor;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .message.success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .message i {
            margin-right: 0.8rem;
            font-size: 1.1rem;
        }

        /* Progress Bar */
        .progress-container {
            display: none;
            margin-top: 1rem;
            background: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
            height: 6px;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            width: 0%;
            transition: width 0.3s ease;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                padding: 1rem;
            }

            .content-card {
                margin: 0;
            }

            .header-section {
                padding: 2rem;
            }

            .header-section h1 {
                font-size: 1.8rem;
            }

            .header-section p {
                font-size: 1rem;
            }

            .form-section {
                padding: 2rem;
            }

            .upload-area {
                padding: 2rem 1rem;
            }

            .upload-icon {
                font-size: 2.5rem;
            }

            .upload-text {
                font-size: 1rem;
            }

            .back-button {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .header-section {
                padding: 1.5rem;
            }

            .header-section h1 {
                font-size: 1.5rem;
            }

            .form-section {
                padding: 1.5rem;
            }

            .upload-area {
                padding: 1.5rem 1rem;
            }

            .upload-icon {
                font-size: 2rem;
            }

            .header-icon {
                font-size: 2.5rem;
            }
        }

        /* Hover effects for enhanced UX */
        .content-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
            transition: all 0.4s ease;
        }

        /* Focus states for accessibility */
        .file-input:focus + .upload-area {
            outline: 2px solid #4c8bca;
            outline-offset: 2px;
        }

        .submit-button:focus {
            outline: 2px solid #4c8bca;
            outline-offset: 2px;
        }

        .back-button:focus {
            outline: 2px solid #ffffff;
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="content-card">
            <!-- Header Section -->
            <div class="header-section">
                <i class="fas fa-file-upload header-icon"></i>
                <h1>Subir Hoja de Vida</h1>
                <p>Carga tu CV en formato PDF para completar tu perfil profesional</p>
            </div>

            <!-- Form Section -->
            <div class="form-section">
                <a href="dashboard_postulante.php" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    Volver al Dashboard
                </a>

                <form method="POST" enctype="multipart/form-data" id="uploadForm">
                    <div class="upload-container">
                        <div class="file-input-wrapper">
                            <input type="file" name="cv" accept=".pdf" required class="file-input" id="fileInput">
                            <div class="upload-area" id="uploadArea">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                <div class="upload-text">Arrastra tu CV aquí o haz clic para seleccionar</div>
                                <div class="upload-subtext">Solo archivos PDF • Máximo 10MB</div>
                                <div class="file-selected" id="fileSelected">
                                    <i class="fas fa-check-circle"></i>
                                    <span id="fileName"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-button" id="submitButton">
                        <div class="button-content">
                            <span id="buttonText">
                                <i class="fas fa-upload"></i>
                                Subir Hoja de Vida
                            </span>
                            <div class="loading-spinner" id="loadingSpinner"></div>
                        </div>
                    </button>

                    <div class="progress-container" id="progressContainer">
                        <div class="progress-bar" id="progressBar"></div>
                    </div>
                </form>

                <!-- Mostrar mensajes PHP -->
                <?php if ($mensaje): ?>
                    <div class="message <?php echo $tipo_mensaje; ?>" style="display: block;">
                        <i class="fas fa-<?php echo $tipo_mensaje === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
                        <?php echo htmlspecialchars($mensaje); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const fileInput = document.getElementById('fileInput');
            const uploadArea = document.getElementById('uploadArea');
            const fileSelected = document.getElementById('fileSelected');
            const fileName = document.getElementById('fileName');
            const submitButton = document.getElementById('submitButton');
            const buttonText = document.getElementById('buttonText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const uploadForm = document.getElementById('uploadForm');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');

            // Click handler for upload area
            uploadArea.addEventListener('click', function(e) {
                e.preventDefault();
                fileInput.click();
            });

            // Drag and drop functionality
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                if (!uploadArea.contains(e.relatedTarget)) {
                    uploadArea.classList.remove('dragover');
                }
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    handleFileSelect(files[0]);
                }
            });

            // File input change handler
            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    handleFileSelect(e.target.files[0]);
                }
            });

            // File selection handler
            function handleFileSelect(file) {
                // Validate file type
                if (file.type !== 'application/pdf') {
                    showMessage('Por favor, selecciona un archivo PDF válido.', 'error');
                    fileInput.value = '';
                    return;
                }

                // Validate file size (10MB limit)
                if (file.size > 10 * 1024 * 1024) {
                    showMessage('El archivo es demasiado grande. El tamaño máximo permitido es 10MB.', 'error');
                    fileInput.value = '';
                    return;
                }

                // Show selected file
                fileName.textContent = file.name;
                fileSelected.style.display = 'flex';
                uploadArea.style.borderColor = '#28a745';
                uploadArea.style.background = 'linear-gradient(135deg, #d4edda 0%, #f8f9fa 100%)';
            }

            // Form submission handler
            uploadForm.addEventListener('submit', function(e) {
                if (fileInput.files.length === 0) {
                    e.preventDefault();
                    showMessage('Por favor, selecciona un archivo antes de continuar.', 'error');
                    return;
                }

                // Show loading state
                submitButton.disabled = true;
                buttonText.style.display = 'none';
                loadingSpinner.style.display = 'block';
                progressContainer.style.display = 'block';

                // Simulate progress (in real implementation, you'd track actual upload progress)
                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += Math.random() * 15;
                    if (progress > 90) progress = 90;
                    progressBar.style.width = progress + '%';
                }, 200);

                // Clean up on form completion (this will be interrupted by page reload)
                setTimeout(() => {
                    clearInterval(progressInterval);
                    progressBar.style.width = '100%';
                }, 2000);
            });

            // Message display function
            function showMessage(text, type) {
                // Remove existing messages
                const existingMessages = document.querySelectorAll('.message:not([style*="display: block"])');
                existingMessages.forEach(msg => msg.remove());

                // Create new message
                const message = document.createElement('div');
                message.className = `message ${type}`;
                message.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
                    ${text}
                `;
                message.style.display = 'block';
                
                uploadForm.appendChild(message);
                
                // Auto-hide success messages after 5 seconds
                if (type === 'success') {
                    setTimeout(() => {
                        message.style.opacity = '0';
                        setTimeout(() => message.remove(), 300);
                    }, 5000);
                }
            }

            // Reset file selection
            function resetFileSelection() {
                fileSelected.style.display = 'none';
                uploadArea.style.borderColor = '#4c8bca';
                uploadArea.style.background = '#f8f9fa';
                fileInput.value = '';
            }

            // Enhanced keyboard navigation
            uploadArea.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    fileInput.click();
                }
            });

            // Add tabindex for accessibility
            uploadArea.setAttribute('tabindex', '0');
            uploadArea.setAttribute('role', 'button');
            uploadArea.setAttribute('aria-label', 'Seleccionar archivo PDF');
        });
    </script>
</body>
</html>