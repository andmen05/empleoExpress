<?php
session_start();

// Verificar si el usuario es una empresa y está autenticado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'empresa') {
    header("Location: login.php");
    exit();
}

include('db.php'); // Incluir la conexión a la base de datos

// Obtener la oferta específica a la que se están postulando
if (isset($_GET['oferta_id'])) {
    $oferta_id = $_GET['oferta_id'];

    // Verificar si la oferta pertenece a la empresa
    $empresa_id = $_SESSION['user_id'];
    $sql_oferta = "SELECT * FROM ofertas_laborales WHERE id = '$oferta_id' AND empresa_id = '$empresa_id'";
    $result_oferta = $conn->query($sql_oferta);

    if ($result_oferta->num_rows == 0) {
        echo "No tienes acceso a esta oferta.";
        exit();
    }
    
    // Obtener información de la oferta
    $oferta = $result_oferta->fetch_assoc();
} else {
    echo "Oferta no especificada.";
    exit();
}

// Obtener los postulantes a la oferta
$sql_postulantes = "SELECT p.*, u.nombre AS postulante_nombre, u.email AS postulante_email
                    FROM postulaciones p
                    JOIN usuarios u ON p.postulante_id = u.id
                    WHERE p.oferta_id = '$oferta_id'";
$result_postulantes = $conn->query($sql_postulantes);

// Aceptar o rechazar postulante
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postulante_id = $_POST['postulante_id'];

    if (isset($_POST['aceptar_postulante'])) {
        // Actualizar la base de datos para marcar al postulante como aceptado
        $sql_update = "UPDATE postulaciones SET estado = 'aceptado' WHERE postulante_id = '$postulante_id' AND oferta_id = '$oferta_id'";
    } elseif (isset($_POST['rechazar_postulante'])) {
        // Actualizar la base de datos para marcar al postulante como rechazado
        $sql_update = "UPDATE postulaciones SET estado = 'rechazado' WHERE postulante_id = '$postulante_id' AND oferta_id = '$oferta_id'";
    }

    if (isset($sql_update) && $conn->query($sql_update) === TRUE) {
        header("Location: ver_postulaciones.php?oferta_id=$oferta_id"); // Redirigir para recargar la página
        exit();
    } else {
        echo "<p>Error al actualizar el estado del postulante: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postulantes - EmpleoExpress</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        /* Reset y configuración base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            min-height: 100vh;
            padding: 20px 0;
            color: #2c3e50;
        }

        /* Contenedor principal */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header con información de la oferta */
        .offer-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.8s ease;
        }

        .offer-header h1 {
            color: #4c8bca;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .offer-header p {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .offer-stats {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #6c757d;
            font-weight: 600;
        }

        .stat-item i {
            color: #4c8bca;
            font-size: 1.2rem;
        }

        /* Sección principal de postulantes */
        .postulantes-section {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 2rem;
            color: #2c3e50;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .section-title i {
            color: #4c8bca;
        }

        /* Tabla moderna */
        .postulantes-table {
            width: 100%;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .postulantes-table thead {
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
        }

        .postulantes-table th {
            padding: 1.5rem;
            color: white;
            font-weight: 600;
            text-align: left;
            font-size: 1rem;
            letter-spacing: 0.5px;
        }

        .postulantes-table tbody tr {
            border-bottom: 1px solid #f1f3f4;
            transition: all 0.3s ease;
        }

        .postulantes-table tbody tr:hover {
            background: #f8f9ff;
            transform: scale(1.01);
        }

        .postulantes-table td {
            padding: 1.5rem;
            font-size: 0.95rem;
            vertical-align: middle;
        }

        /* Información del postulante */
        .postulante-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .postulante-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .postulante-details h4 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .postulante-details p {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Estados de postulación */
        .estado-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .estado-pendiente {
            background: #fff3cd;
            color: #856404;
            border: 2px solid #ffeaa7;
        }

        .estado-aceptado {
            background: #d4edda;
            color: #155724;
            border: 2px solid #00b894;
        }

        .estado-rechazado {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #e17055;
        }

        /* Botones de acción */
        .action-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-aceptar {
            background: linear-gradient(135deg, #00b894 0%, #00a085 100%);
            color: white;
        }

        .btn-aceptar:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 184, 148, 0.3);
        }

        .btn-rechazar {
            background: linear-gradient(135deg, #e17055 0%, #d63031 100%);
            color: white;
        }

        .btn-rechazar:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(225, 112, 85, 0.3);
        }

        /* Botón de regreso */
        .back-section {
            margin-top: 2rem;
            text-align: center;
            animation: fadeInUp 0.8s ease 0.4s both;
        }

        .btn-back {
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(76, 139, 202, 0.4);
        }

        /* Estado vacío */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        /* Animaciones */
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

        .fade-in {
            animation: fadeInUp 0.6s ease;
        }

        /* Loading state para botones */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                padding: 0 15px;
            }

            .offer-header,
            .postulantes-section {
                padding: 1.5rem;
            }

            .offer-header h1 {
                font-size: 1.8rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .postulantes-table {
                font-size: 0.9rem;
            }

            .postulantes-table th,
            .postulantes-table td {
                padding: 1rem;
            }

            .postulante-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 8px;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .offer-stats {
                flex-direction: column;
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px 0;
            }

            .offer-header h1 {
                font-size: 1.5rem;
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .postulantes-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .btn-back {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Header con información de la oferta -->
        <div class="offer-header">
            <h1>
                <i class="fas fa-briefcase"></i>
                Postulantes para: <?php echo htmlspecialchars($oferta['titulo'] ?? 'Oferta Laboral'); ?>
            </h1>
            <p><?php echo htmlspecialchars($oferta['descripcion'] ?? 'Gestiona los candidatos que se han postulado a esta oferta laboral.'); ?></p>
            
            <div class="offer-stats">
                <div class="stat-item">
                    <i class="fas fa-users"></i>
                    <span><?php echo $result_postulantes->num_rows; ?> Postulantes</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Oferta ID: #<?php echo $oferta_id; ?></span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-building"></i>
                    <span>Mi Empresa</span>
                </div>
            </div>
        </div>

        <!-- Sección principal de postulantes -->
        <div class="postulantes-section">
            <h2 class="section-title">
                <i class="fas fa-clipboard-list"></i>
                Lista de Candidatos
            </h2>

            <?php if ($result_postulantes->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="postulantes-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user"></i> Candidato</th>
                                <th><i class="fas fa-envelope"></i> Contacto</th>
                                <th><i class="fas fa-flag"></i> Estado</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Reiniciar el puntero del resultado
                            $result_postulantes->data_seek(0);
                            while ($postulante = $result_postulantes->fetch_assoc()): 
                            ?>
                                <tr class="fade-in">
                                    <td>
                                        <div class="postulante-info">
                                            <div class="postulante-avatar">
                                                <?php echo strtoupper(substr($postulante['postulante_nombre'], 0, 1)); ?>
                                            </div>
                                            <div class="postulante-details">
                                                <h4><?php echo htmlspecialchars($postulante['postulante_nombre']); ?></h4>
                                                <p>Candidato</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="postulante-details">
                                            <h4><?php echo htmlspecialchars($postulante['postulante_email']); ?></h4>
                                            <p><i class="fas fa-envelope"></i> Email de contacto</p>
                                        </div>
                                    </td>
                                    <td>
                                        <?php 
                                        $estado = $postulante['estado'];
                                        $estado_class = 'estado-pendiente';
                                        $estado_icon = 'fas fa-clock';
                                        
                                        if ($estado == 'aceptado') {
                                            $estado_class = 'estado-aceptado';
                                            $estado_icon = 'fas fa-check-circle';
                                        } elseif ($estado == 'rechazado') {
                                            $estado_class = 'estado-rechazado';
                                            $estado_icon = 'fas fa-times-circle';
                                        }
                                        ?>
                                        <span class="estado-badge <?php echo $estado_class; ?>">
                                            <i class="<?php echo $estado_icon; ?>"></i>
                                            <?php echo ucfirst($estado); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <?php if ($postulante['estado'] == 'aceptado'): ?>
                                                <span class="estado-badge estado-aceptado">
                                                    <i class="fas fa-check-circle"></i>
                                                    Aceptado
                                                </span>
                                            <?php elseif ($postulante['estado'] == 'rechazado'): ?>
                                                <span class="estado-badge estado-rechazado">
                                                    <i class="fas fa-times-circle"></i>
                                                    Rechazado
                                                </span>
                                            <?php else: ?>
                                                <form method="POST" action="ver_postulaciones.php?oferta_id=<?php echo $oferta_id; ?>" style="display: contents;">
                                                    <input type="hidden" name="postulante_id" value="<?php echo $postulante['postulante_id']; ?>">
                                                    <button type="submit" name="aceptar_postulante" class="btn btn-aceptar" onclick="this.classList.add('btn-loading')">
                                                        <i class="fas fa-check"></i>
                                                        Aceptar
                                                    </button>
                                                    <button type="submit" name="rechazar_postulante" class="btn btn-rechazar" onclick="this.classList.add('btn-loading')">
                                                        <i class="fas fa-times"></i>
                                                        Rechazar
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>No hay postulantes aún</h3>
                    <p>Cuando los candidatos se postulen a esta oferta, aparecerán aquí para que puedas revisarlos.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Botón de regreso -->
        <div class="back-section">
            <a href="dashboard_empresa.php" class="btn-back">
                <i class="fas fa-arrow-left"></i>
                Volver al Dashboard
            </a>
        </div>
    </div>

    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inicializar AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Animaciones adicionales para elementos dinámicos
        document.addEventListener('DOMContentLoaded', function() {
            // Animar rows de la tabla
            const rows = document.querySelectorAll('.postulantes-table tbody tr');
            rows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.1}s`;
            });

            // Efecto hover mejorado para botones
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px) scale(1.05)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });

            // Confirmación para acciones críticas
            const rechazarButtons = document.querySelectorAll('.btn-rechazar');
            rechazarButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('¿Estás seguro de que deseas rechazar a este candidato?')) {
                        e.preventDefault();
                        this.classList.remove('btn-loading');
                    }
                });
            });

            const aceptarButtons = document.querySelectorAll('.btn-aceptar');
            aceptarButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!confirm('¿Estás seguro de que deseas aceptar a este candidato?')) {
                        e.preventDefault();
                        this.classList.remove('btn-loading');
                    }
                });
            });
        });

        // Función para mostrar notificaciones (opcional)
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                ${message}
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }
    </script>

    <style>
        /* Estilos para notificaciones */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            z-index: 1000;
            transform: translateX(400px);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification-success {
            background: linear-gradient(135deg, #00b894 0%, #00a085 100%);
        }

        .notification-error {
            background: linear-gradient(135deg, #e17055 0%, #d63031 100%);
        }
    </style>
</body>
</html>