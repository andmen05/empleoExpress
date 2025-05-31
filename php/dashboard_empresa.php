<?php
session_start();

// Verificar si el usuario es una empresa y está autenticado
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'empresa') {
    header("Location: login.php");
    exit();
}

include('db.php'); // Incluir la conexión a la base de datos

$empresa_id = $_SESSION['user_id'];

// Obtener las ofertas laborales de la empresa
$sql_ofertas = "SELECT * FROM ofertas_laborales WHERE empresa_id = '$empresa_id'";
$result_ofertas = $conn->query($sql_ofertas);

// Crear una nueva oferta laboral
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_oferta'])) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $profesion_solicitada = $_POST['profesion_solicitada'];

    $sql_crear_oferta = "INSERT INTO ofertas_laborales (empresa_id, titulo, descripcion, profesion_solicitada) 
                         VALUES ('$empresa_id', '$titulo', '$descripcion', '$profesion_solicitada')";

    if ($conn->query($sql_crear_oferta) === TRUE) {
        header("Location: dashboard_empresa.php");
        exit();
    } else {
        echo "<p>Error al crear la oferta: " . $conn->error . "</p>";
    }
}

// Editar oferta laboral
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_oferta'])) {
    $oferta_id = $_POST['oferta_id'];
    $nuevo_titulo = $_POST['titulo'];
    $nueva_descripcion = $_POST['descripcion'];
    $nueva_profesion = $_POST['profesion_solicitada'];

    $sql_editar_oferta = "UPDATE ofertas_laborales 
                          SET titulo = '$nuevo_titulo', descripcion = '$nueva_descripcion', profesion_solicitada = '$nueva_profesion' 
                          WHERE id = '$oferta_id' AND empresa_id = '$empresa_id'";

    if ($conn->query($sql_editar_oferta) === TRUE) {
        header("Location: dashboard_empresa.php");
        exit();
    } else {
        echo "<p>Error al actualizar la oferta: " . $conn->error . "</p>";
    }
}

// Eliminar oferta laboral
if (isset($_GET['eliminar_oferta_id'])) {
    $oferta_id = $_GET['eliminar_oferta_id'];

    $sql_eliminar_oferta = "DELETE FROM ofertas_laborales WHERE id = '$oferta_id' AND empresa_id = '$empresa_id'";

    if ($conn->query($sql_eliminar_oferta) === TRUE) {
        header("Location: dashboard_empresa.php");
        exit();
    } else {
        echo "<p>Error al eliminar la oferta: " . $conn->error . "</p>";
    }
}

// Obtener las postulaciones para las ofertas de esta empresa
$sql_postulaciones = "SELECT p.*, u.nombre AS postulante_nombre, o.titulo AS oferta_titulo, p.estado, p.fecha_postulacion
                      FROM postulaciones p
                      JOIN usuarios u ON p.postulante_id = u.id
                      JOIN ofertas_laborales o ON p.oferta_id = o.id
                      WHERE o.empresa_id = '$empresa_id'";
$result_postulaciones = $conn->query($sql_postulaciones);

// Aceptar postulante
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['postulante_id']) && isset($_POST['oferta_id'])) {
    $postulante_id = $_POST['postulante_id'];
    $oferta_id = $_POST['oferta_id'];

    $sql_aceptar = "UPDATE postulaciones SET estado = 'aceptado' WHERE postulante_id = '$postulante_id' AND oferta_id = '$oferta_id'";

    if ($conn->query($sql_aceptar) === TRUE) {
        header("Location: dashboard_empresa.php");
        exit();
    } else {
        echo "<p>Error al aceptar al postulante: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Empresa - EmpleoExpress</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        /* Reset y Variables CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #4c8bca;
            --secondary-color: #3a6f9a;
            --text-primary: #2c3e50;
            --text-secondary: #6c757d;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --background-white: #ffffff;
            --shadow-light: 0 5px 25px rgba(0, 0, 0, 0.08);
            --shadow-medium: 0 20px 40px rgba(0, 0, 0, 0.15);
            --gradient-primary: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            --gradient-background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            --border-radius-card: 20px;
            --border-radius-input: 12px;
            --transition: all 0.3s ease;
        }

        /* Estilos Base */
        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--gradient-background);
            min-height: 100vh;
            padding: 2rem 1rem;
            color: var(--text-primary);
            position: relative;
            overflow-x: hidden;
        }

        /* Patrón de fondo SVG */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.1) 2px, transparent 2px);
            background-size: 60px 60px;
            z-index: -1;
            pointer-events: none;
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

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        /* Contenedor Principal */
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
            animation: fadeInUp 0.8s ease-out;
        }

        /* Header */
        .dashboard-header {
            background: var(--background-white);
            border-radius: var(--border-radius-card);
            box-shadow: var(--shadow-light);
            padding: 2.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out 0.1s both;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient-primary);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .welcome-section h1 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.2rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .welcome-section h1 i {
            color: var(--primary-color);
            font-size: 2rem;
        }

        .welcome-section p {
            color: var(--text-secondary);
            font-size: 1.1rem;
            font-weight: 400;
        }

        .logout-btn {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: var(--border-radius-input);
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
            cursor: pointer;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(231, 76, 60, 0.3);
        }

        /* Cards Principales */
        .dashboard-card {
            background: var(--background-white);
            border-radius: var(--border-radius-card);
            box-shadow: var(--shadow-light);
            padding: 2.5rem;
            margin-bottom: 2rem;
            transition: var(--transition);
            animation: fadeInUp 0.8s ease-out both;
        }

        .dashboard-card:nth-child(2) { animation-delay: 0.2s; }
        .dashboard-card:nth-child(3) { animation-delay: 0.3s; }
        .dashboard-card:nth-child(4) { animation-delay: 0.4s; }

        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f8f9fa;
        }

        .card-header h2 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--text-primary);
        }

        .card-header i {
            color: var(--primary-color);
            font-size: 1.8rem;
        }

        /* Formularios */
        .form-grid {
            display: grid;
            gap: 1.5rem;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 15px 50px 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: var(--border-radius-input);
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            transition: var(--transition);
            background: #f8f9fa;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 20px rgba(76, 139, 202, 0.2);
            background: white;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 120px;
            padding-right: 20px;
        }

        .form-group .input-icon {
            position: absolute;
            right: 20px;
            top: 50px;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 1.1rem;
        }

        /* Botones */
        .btn {
            background: var(--gradient-primary);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: var(--border-radius-input);
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            justify-content: center;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(76, 139, 202, 0.4);
        }

        .btn-full {
            width: 100%;
            margin-top: 1rem;
        }

        .btn-danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        .btn-danger:hover {
            box-shadow: 0 10px 30px rgba(231, 76, 60, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f39c12 0%, #d68910 100%);
        }

        .btn-warning:hover {
            box-shadow: 0 10px 30px rgba(243, 156, 18, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%);
        }

        .btn-success:hover {
            box-shadow: 0 10px 30px rgba(39, 174, 96, 0.4);
        }

        /* Tablas */
        .table-container {
            overflow-x: auto;
            border-radius: var(--border-radius-input);
            box-shadow: var(--shadow-light);
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: var(--border-radius-input);
            overflow: hidden;
        }

        .modern-table thead {
            background: var(--gradient-primary);
        }

        .modern-table th {
            padding: 1.2rem 1rem;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            text-align: left;
            border: none;
        }

        .modern-table td {
            padding: 1.2rem 1rem;
            border: none;
            border-bottom: 1px solid #f1f3f4;
            color: var(--text-primary);
            vertical-align: middle;
        }

        .modern-table tbody tr {
            transition: var(--transition);
        }

        .modern-table tbody tr:hover {
            background-color: rgba(76, 139, 202, 0.05);
        }

        .modern-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Estados */
        .status-badge {
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .status-accepted {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
        }

        .status-pending {
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }

        /* Acciones de tabla */
        .table-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .table-actions .btn {
            padding: 8px 16px;
            font-size: 0.85rem;
        }

        /* Grid responsivo */
        .dashboard-grid {
            display: grid;
            gap: 2rem;
            grid-template-columns: 1fr;
        }

        /* Loading States */
        .btn.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn.loading::after {
            content: '';
            width: 16px;
            height: 16px;
            margin-left: 8px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 1rem 0.5rem;
            }

            .dashboard-header {
                padding: 1.5rem;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .welcome-section h1 {
                font-size: 1.8rem;
            }

            .dashboard-card {
                padding: 1.5rem;
            }

            .card-header h2 {
                font-size: 1.5rem;
            }

            .modern-table {
                font-size: 0.9rem;
            }

            .modern-table th,
            .modern-table td {
                padding: 0.8rem 0.5rem;
            }

            .table-actions {
                flex-direction: column;
            }

            .table-actions .btn {
                width: 100%;
                margin-bottom: 0.3rem;
            }
        }

        @media (max-width: 480px) {
            .welcome-section h1 {
                font-size: 1.5rem;
            }

            .dashboard-card {
                padding: 1rem;
            }

            .form-group input,
            .form-group textarea {
                padding: 12px 15px;
            }

            .form-group .input-icon {
                display: none;
            }
        }

        /* Micro-interacciones */
        .dashboard-card:hover .card-header i {
            animation: pulse 2s infinite;
        }

        .btn:active {
            transform: translateY(0);
        }

        /* Mensajes de estado */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius-input);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert-error {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Header -->
        <header class="dashboard-header">
            <div class="header-content">
                <div class="welcome-section">
                    <h1>
                        <i class="fas fa-building"></i>
                        Bienvenido, <?php echo $_SESSION['user_name']; ?>
                    </h1>
                    <p>Panel de Control Empresarial - Gestiona tus ofertas laborales</p>
                </div>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar Sesión
                </a>
            </div>
        </header>

        <div class="dashboard-grid">
            <!-- Crear Nueva Oferta -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-plus-circle"></i>
                    <h2>Crear Nueva Oferta Laboral</h2>
                </div>
                
                <form method="POST" class="form-grid">
                    <div class="form-group">
                        <label for="titulo">
                            <i class="fas fa-briefcase"></i>
                            Título de la Oferta
                        </label>
                        <input type="text" id="titulo" name="titulo" required placeholder="Ej: Desarrollador Full Stack">
                        <i class="fas fa-edit input-icon"></i>
                    </div>

                    <div class="form-group">
                        <label for="descripcion">
                            <i class="fas fa-align-left"></i>
                            Descripción de la Oferta
                        </label>
                        <textarea id="descripcion" name="descripcion" required placeholder="Describe las responsabilidades, requisitos y beneficios de la posición..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="profesion_solicitada">
                            <i class="fas fa-user-tie"></i>
                            Profesión Solicitada
                        </label>
                        <input type="text" id="profesion_solicitada" name="profesion_solicitada" required placeholder="Ej: Ingeniero de Software">
                        <i class="fas fa-graduation-cap input-icon"></i>
                    </div>

                    <button type="submit" name="crear_oferta" class="btn btn-full">
                        <i class="fas fa-paper-plane"></i>
                        Crear Oferta Laboral
                    </button>
                </form>
            </div>

            <!-- Mis Ofertas Laborales -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-list-alt"></i>
                    <h2>Mis Ofertas Laborales</h2>
                </div>
                
                <div class="table-container">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-briefcase"></i> Título</th>
                                <th><i class="fas fa-align-left"></i> Descripción</th>
                                <th><i class="fas fa-user-tie"></i> Profesión</th>
                                <th><i class="fas fa-cogs"></i> Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($oferta = $result_ofertas->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo $oferta['titulo']; ?></strong>
                                    </td>
                                    <td>
                                        <span class="text-truncate">
                                            <?php echo strlen($oferta['descripcion']) > 100 ? substr($oferta['descripcion'], 0, 100) . '...' : $oferta['descripcion']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="profession-tag">
                                            <?php echo $oferta['profesion_solicitada']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="editar_oferta.php?oferta_id=<?php echo $oferta['id']; ?>" class="btn btn-warning">
                                                <i class="fas fa-edit"></i>
                                                Editar
                                            </a>
                                            <a href="?eliminar_oferta_id=<?php echo $oferta['id']; ?>" 
                                               class="btn btn-danger" 
                                               onclick="return confirm('¿Estás seguro de eliminar esta oferta?')">
                                                <i class="fas fa-trash"></i>
                                                Eliminar
                                            </a>
                                            <a href="ver_postulaciones.php?oferta_id=<?php echo $oferta['id']; ?>" class="btn">
                                                <i class="fas fa-users"></i>
                                                Ver Postulantes
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Postulantes a Mis Ofertas -->
            <div class="dashboard-card">
                <div class="card-header">
                    <i class="fas fa-user-friends"></i>
                    <h2>Postulantes a Mis Ofertas</h2>
                </div>
                
                <div class="table-container">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user"></i> Postulante</th>
                                <th><i class="fas fa-briefcase"></i> Oferta</th>
                                <th><i class="fas fa-calendar"></i> Fecha</th>
                                <th><i class="fas fa-info-circle"></i> Estado</th>
                                
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($postulacion = $result_postulaciones->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <i class="fas fa-user-circle" style="color: var(--primary-color); margin-right: 0.5rem;"></i>
                                            <strong><?php echo $postulacion['postulante_nombre']; ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="offer-title">
                                            <?php echo $postulacion['oferta_titulo']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="date-text">
                                            <i class="fas fa-clock" style="color: var(--text-secondary); margin-right: 0.3rem;"></i>
                                            <?php echo date('d/m/Y', strtotime($postulacion['fecha_postulacion'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($postulacion['estado'] == 'aceptado'): ?>
    <span class="status-badge status-accepted">
        <i class="fas fa-check-circle"></i> Aceptado
    </span>
<?php elseif ($postulacion['estado'] == 'rechazado'): ?>
    <span class="status-badge status-rejected">
        <i class="fas fa-times-circle"></i> Rechazado
    </span>
<?php else: ?>
    <span class="status-badge status-pending">
        <i class="fas fa-clock"></i> Pendiente
    </span>
<?php endif; ?>

                                    
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animaciones y efectos interactivos
        document.addEventListener('DOMContentLoaded', function() {
            // Efecto de loading en botones
            const buttons = document.querySelectorAll('button[type="submit"]');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    this.classList.add('loading');
                });
            });

            // Efecto de hover en cards
            const cards = document.querySelectorAll('.dashboard-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Auto-resize para textareas
            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(textarea => {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = this.scrollHeight + 'px';
                });
            });

            // Confirmación mejorada para eliminar
            const deleteButtons = document.querySelectorAll('a[onclick*="confirm"]');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    
                    // Crear modal de confirmación personalizado
                    const modal = document.createElement('div');
                    modal.style.cssText = `
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.5);
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        z-index: 1000;
                        animation: fadeIn 0.3s ease;
                    `;
                    
                    modal.innerHTML = `
                        <div style="
                            background: white;
                            padding: 2rem;
                            border-radius: 20px;
                            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
                            text-align: center;
                            max-width: 400px;
                            margin: 1rem;
                            animation: fadeInUp 0.3s ease;
                        ">
                            <i class="fas fa-exclamation-triangle" style="
                                font-size: 3rem;
                                color: #f39c12;
                                margin-bottom: 1rem;
                            "></i>
                            <h3 style="
                                color: var(--text-primary);
                                margin-bottom: 1rem;
                                font-family: 'Montserrat', sans-serif;
                            ">¿Confirmar eliminación?</h3>
                            <p style="
                                color: var(--text-secondary);
                                margin-bottom: 2rem;
                                line-height: 1.5;
                            ">Esta acción no se puede deshacer. ¿Estás seguro de que deseas eliminar esta oferta laboral?</p>
                            <div style="display: flex; gap: 1rem; justify-content: center;">
                                <button onclick="this.closest('div').remove()" style="
                                    background: #6c757d;
                                    color: white;
                                    border: none;
                                    padding: 12px 24px;
                                    border-radius: 12px;
                                    cursor: pointer;
                                    font-family: 'Montserrat', sans-serif;
                                    font-weight: 600;
                                    transition: all 0.3s ease;
                                " onmouseover="this.style.background='#5a6268'" onmouseout="this.style.background='#6c757d'">
                                    Cancelar
                                </button>
                                <button onclick="window.location.href='${href}'" style="
                                    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
                                    color: white;
                                    border: none;
                                    padding: 12px 24px;
                                    border-radius: 12px;
                                    cursor: pointer;
                                    font-family: 'Montserrat', sans-serif;
                                    font-weight: 600;
                                    transition: all 0.3s ease;
                                " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    `;
                    
                    document.body.appendChild(modal);
                    
                    // Cerrar modal al hacer clic fuera
                    modal.addEventListener('click', function(e) {
                        if (e.target === modal) {
                            modal.remove();
                        }
                    });
                });
            });

            // Efectos de focus en inputs
            const inputs = document.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });

            // Contador de caracteres para textarea
            const descripcionTextarea = document.getElementById('descripcion');
            if (descripcionTextarea) {
                const counter = document.createElement('div');
                counter.style.cssText = `
                    text-align: right;
                    color: var(--text-secondary);
                    font-size: 0.85rem;
                    margin-top: 0.5rem;
                `;
                descripcionTextarea.parentElement.appendChild(counter);
                
                descripcionTextarea.addEventListener('input', function() {
                    const length = this.value.length;
                    counter.textContent = `${length} caracteres`;
                    
                    if (length > 500) {
                        counter.style.color = 'var(--warning-color)';
                    } else {
                        counter.style.color = 'var(--text-secondary)';
                    }
                });
            }

            // Validación en tiempo real del formulario
            const form = document.querySelector('form[method="POST"]');
            if (form) {
                const submitButton = form.querySelector('button[type="submit"]');
                const requiredInputs = form.querySelectorAll('[required]');
                
                function validateForm() {
                    let isValid = true;
                    requiredInputs.forEach(input => {
                        if (!input.value.trim()) {
                            isValid = false;
                        }
                    });
                    
                    if (isValid) {
                        submitButton.style.opacity = '1';
                        submitButton.style.pointerEvents = 'auto';
                    } else {
                        submitButton.style.opacity = '0.6';
                        submitButton.style.pointerEvents = 'none';
                    }
                }
                
                requiredInputs.forEach(input => {
                    input.addEventListener('input', validateForm);
                });
                
                validateForm(); // Validación inicial
            }

            // Efecto de escritura para el título de bienvenida
            const welcomeTitle = document.querySelector('.welcome-section h1');
            if (welcomeTitle) {
                const originalText = welcomeTitle.textContent;
                welcomeTitle.style.overflow = 'hidden';
                welcomeTitle.style.borderRight = '2px solid var(--primary-color)';
                welcomeTitle.style.whiteSpace = 'nowrap';
                welcomeTitle.style.animation = 'typing 2s steps(40, end), blink-caret 0.75s step-end infinite';
            }

            // Añadir estilos de animación de escritura
            const style = document.createElement('style');
            style.textContent = `
                @keyframes typing {
                    from { width: 0 }
                    to { width: 100% }
                }
                
                @keyframes blink-caret {
                    from, to { border-color: transparent }
                    50% { border-color: var(--primary-color) }
                }
                
                .focused .input-icon {
                    color: var(--primary-color) !important;
                    transform: translateY(-50%) scale(1.1);
                }
                
                .table-actions .btn:hover {
                    transform: translateY(-2px) scale(1.05);
                }
                
                .status-badge {
                    animation: fadeInUp 0.5s ease-out;
                }
                
                .user-info:hover {
                    color: var(--primary-color);
                    transition: color 0.3s ease;
                }
            `;
            document.head.appendChild(style);

            // Efecto de progreso de carga de la página
            const progressBar = document.createElement('div');
            progressBar.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 0%;
                height: 3px;
                background: var(--gradient-primary);
                z-index: 9999;
                transition: width 0.3s ease;
            `;
            document.body.appendChild(progressBar);
            
            // Simular progreso de carga
            let width = 0;
            const interval = setInterval(() => {
                width += Math.random() * 30;
                if (width >= 100) {
                    width = 100;
                    clearInterval(interval);
                    setTimeout(() => {
                        progressBar.style.opacity = '0';
                        setTimeout(() => progressBar.remove(), 300);
                    }, 200);
                }
                progressBar.style.width = width + '%';
            }, 200);

            // Lazy loading para imágenes (si las hubiera en el futuro)
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate');
                    }
                });
            }, observerOptions);

            // Observar elementos para animaciones al scroll
            document.querySelectorAll('.dashboard-card').forEach(card => {
                observer.observe(card);
            });

            // Efecto de partículas en el fondo (opcional, sutil)
            function createParticle() {
                const particle = document.createElement('div');
                particle.style.cssText = `
                    position: fixed;
                    width: 4px;
                    height: 4px;
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: -1;
                    left: ${Math.random() * 100}vw;
                    top: 100vh;
                    animation: floatUp ${5 + Math.random() * 5}s linear infinite;
                `;
                
                document.body.appendChild(particle);
                
                setTimeout(() => {
                    particle.remove();
                }, 10000);
            }

            // Crear partículas ocasionalmente
            setInterval(createParticle, 3000);

            // Añadir animación de partículas
            const particleStyle = document.createElement('style');
            particleStyle.textContent = `
                @keyframes floatUp {
                    to {
                        transform: translateY(-100vh) rotate(360deg);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(particleStyle);

            console.log('🚀 Dashboard EmpleoExpress cargado correctamente');
        });

        // Función para mostrar notificaciones toast
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'var(--gradient-primary)' : 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)'};
                color: white;
                padding: 1rem 2rem;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                z-index: 1000;
                animation: slideInRight 0.3s ease-out;
                font-family: 'Montserrat', sans-serif;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            `;
            
            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                ${message}
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.3s ease-in forwards';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Añadir animaciones de toast
        const toastStyle = document.createElement('style');
        toastStyle.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(toastStyle);
    </script>
</body>
</html>
