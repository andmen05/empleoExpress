<?php
session_start();
include('db.php');

// ===== VERIFICACIÓN DE SESIÓN =====
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'postulante') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ===== OBTENER INFORMACIÓN DEL USUARIO =====
$sql_user = "SELECT nombre, habilidades, experiencia, profesion FROM usuarios u
             JOIN postulantes_info p ON u.id = p.usuario_id
             WHERE u.id = '$user_id'";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();

$user_name = $user['nombre'];
$user_habilidades = $user['habilidades'];
$user_experiencia = $user['experiencia'];
$user_profesion = $user['profesion'];

// ===== VERIFICAR ESTADO DE POSTULACIÓN =====
$sql_aceptacion_rechazo = "SELECT o.titulo AS oferta_titulo, u.nombre AS empresa_nombre, p.estado
                           FROM postulaciones p
                           JOIN ofertas_laborales o ON p.oferta_id = o.id
                           JOIN usuarios u ON o.empresa_id = u.id
                           WHERE p.postulante_id = '$user_id'";

$result_aceptacion_rechazo = $conn->query($sql_aceptacion_rechazo);

$estado_postulacion = 'sin_postulacion';
$empresa_aceptante = '';
$oferta_aceptada = '';

if ($result_aceptacion_rechazo->num_rows > 0) {
    $row = $result_aceptacion_rechazo->fetch_assoc();
    $estado_postulacion = $row['estado'];
    $empresa_aceptante = $row['empresa_nombre'];
    $oferta_aceptada = $row['oferta_titulo'];
}

// ===== PROCESAR ACTUALIZACIÓN DE DATOS =====
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_datos'])) {
    $nuevas_habilidades = $_POST['habilidades'];
    $nueva_experiencia = $_POST['experiencia'];
    $nueva_profesion = $_POST['profesion'];

    $sql_actualizar = "UPDATE postulantes_info 
                       SET habilidades = '$nuevas_habilidades', 
                           experiencia = '$nueva_experiencia', 
                           profesion = '$nueva_profesion'
                       WHERE usuario_id = '$user_id'";

    if ($conn->query($sql_actualizar) === TRUE) {
        $_SESSION['datos_actualizados'] = 'Datos actualizados correctamente';
        
        // Recargar valores actualizados
        $result_user = $conn->query($sql_user);
        $user = $result_user->fetch_assoc();
        
        $user_habilidades = $user['habilidades'];
        $user_experiencia = $user['experiencia'];
        $user_profesion = $user['profesion'];
    } else {
        echo "<p>Error al actualizar los datos: " . $conn->error . "</p>";
    }
}

// ===== CALCULAR PROGRESO DEL PERFIL =====
$progreso = 0;
$total = 4;

$query = "SELECT u.nombre, u.email, u.password, pi.cv FROM usuarios u 
          LEFT JOIN postulantes_info pi ON u.id = pi.usuario_id 
          WHERE u.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$datos = $result->fetch_assoc();

if (!empty($datos['nombre'])) $progreso++;
if (!empty($datos['email'])) $progreso++;
if (!empty($datos['password'])) $progreso++;
if (!empty($datos['cv'])) $progreso++;

$porcentaje = ($progreso / $total) * 100;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard del Postulante - EmpleoExpress</title>
    
    <!-- ===== FUENTES Y LIBRERÍAS ===== -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        /* ===== RESET Y CONFIGURACIÓN GLOBAL ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            min-height: 100vh;
            color: #2c3e50;
            overflow-x: hidden;
        }

        /* ===== PATRÓN DE FONDO SVG ===== */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: -1;
        }

        /* ===== LAYOUT PRINCIPAL ===== */
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
            animation: fadeInUp 0.8s ease-out;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        /* ===== HEADER ===== */
        .dashboard-header {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .welcome-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .welcome-section p {
            color: #6c757d;
            font-size: 1.1rem;
            font-weight: 400;
        }

        /* ===== PROGRESO DEL PERFIL ===== */
        .profile-progress {
            margin: 20px 0;
        }

        .progress-bar-container {
            background-color: #ddd;
            border-radius: 10px;
            overflow: hidden;
            height: 20px;
            margin-top: 0.5rem;
        }

        .progress-bar {
            height: 100%;
            background-color: #4CAF50;
            width: 0;
            transition: width 0.5s;
        }

        /* ===== CARDS GENERALES ===== */
        .card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-title i {
            color: #4c8bca;
            font-size: 1.25rem;
        }

        /* ===== ESTADO DE POSTULACIÓN ===== */
        .status-card {
            margin-bottom: 2rem;
            text-align: center;
        }

        .status-accepted {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
        }

        .status-rejected {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }

        .status-pending {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }

        .status-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.9;
        }

        /* ===== FORMULARIOS ===== */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #4c8bca;
            background: white;
            box-shadow: 0 0 0 3px rgba(76, 139, 202, 0.1);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        /* ===== BOTONES ===== */
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            color: white;
            width: 100%;
            justify-content: center;
            padding: 15px 30px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(76, 139, 202, 0.3);
        }

        .btn-apply {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            padding: 10px 20px;
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(46, 204, 113, 0.3);
        }

        .btn-editar {
            background-color: #4c8bca;
            color: white;
            margin-top: 10px;
        }

        .btn-editar:hover {
            background-color: #0056b3;
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

        /* ===== OFERTAS LABORALES ===== */
        .offers-grid {
            display: grid;
            gap: 1.5rem;
        }

        .offer-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid #4c8bca;
        }

        .offer-card:hover {
            transform: translateX(5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .offer-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .offer-company {
            color: #4c8bca;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .offer-description {
            color: #6c757d;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .offer-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* ===== MENSAJES DE ESTADO ===== */
        .status-message {
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .already-applied {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .no-apply {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .success-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(39, 174, 96, 0.3);
            z-index: 1000;
            animation: slideInRight 0.5s ease-out;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* ===== ESTADOS DE CARGA ===== */
        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* ===== SECCIÓN VACÍA ===== */
        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* ===== ANIMACIONES ===== */
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

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .header-content {
                text-align: center;
            }

            .welcome-section h1 {
                font-size: 2rem;
            }

            .card {
                padding: 1.5rem;
            }
        }
    </style>

    <script>
        // ===== MENSAJE DE ÉXITO =====
        <?php if (isset($_SESSION['datos_actualizados'])): ?>
            window.onload = function() {
                showSuccessMessage("<?php echo $_SESSION['datos_actualizados']; ?>");
                <?php unset($_SESSION['datos_actualizados']); ?>
            };
        <?php endif; ?>

        function showSuccessMessage(message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'success-message';
            successDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
            document.body.appendChild(successDiv);
            
            setTimeout(() => {
                successDiv.style.animation = 'slideInRight 0.5s ease-out reverse';
                setTimeout(() => successDiv.remove(), 500);
            }, 3000);
        }

        // ===== EFECTO DE CARGA =====
        function addLoadingEffect(button) {
            button.classList.add('btn-loading');
            button.innerHTML = '<span style="opacity: 0;">Cargando...</span>';
        }

        // ===== INICIALIZAR AOS =====
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: true
            });
        });
    </script>
</head>

<body>
    <div class="dashboard-container">
        
        <!-- ===== HEADER ===== -->
        <header class="dashboard-header" data-aos="fade-down">
            <div class="header-content">
                <div class="welcome-section">
                    <h1>
                        <i class="fas fa-user-circle"></i> 
                        Bienvenido, <?php echo htmlspecialchars($user_name); ?>
                    </h1>
                    <p>Gestiona tu perfil profesional y encuentra las mejores oportunidades laborales</p>
                </div>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar Sesión
                </a>
            </div>

            <!-- ===== PROGRESO DEL PERFIL ===== -->
            <div class="profile-progress">
                <label>Progreso del perfil: <?php echo round($porcentaje); ?>%</label>
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: <?php echo $porcentaje; ?>%;"></div>
                </div>
            </div>
        </header>

        <!-- ===== ESTADO DE POSTULACIÓN ===== -->
        <div class="status-card card 
            <?php 
                if ($estado_postulacion == 'aceptado') echo 'status-accepted';
                elseif ($estado_postulacion == 'rechazado') echo 'status-rejected';
                else echo 'status-pending';
            ?>" data-aos="fade-up" data-aos-delay="100">
            
            <?php if ($estado_postulacion == 'aceptado'): ?>
                <div class="status-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 style="margin-bottom: 1rem;">¡Felicidades! Has sido aceptado</h2>
                <p style="font-size: 1.1rem; opacity: 0.9;">
                    La empresa <strong><?php echo htmlspecialchars($empresa_aceptante); ?></strong> 
                    te ha aceptado para la oferta <strong><?php echo htmlspecialchars($oferta_aceptada); ?></strong>
                </p>
                
            <?php elseif ($estado_postulacion == 'rechazado'): ?>
                <div class="status-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h2 style="margin-bottom: 1rem;">Postulación No Aceptada</h2>
                <p style="font-size: 1.1rem; opacity: 0.9;">
                    La empresa <strong><?php echo htmlspecialchars($empresa_aceptante); ?></strong> 
                    ha rechazado tu postulación para <strong><?php echo htmlspecialchars($oferta_aceptada); ?></strong>
                </p>
                <p style="margin-top: 1rem; opacity: 0.8;">¡No te desanimes! Sigue buscando nuevas oportunidades.</p>
                
            <?php else: ?>
                <div class="status-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h2 style="margin-bottom: 1rem;">Estado de Postulaciones</h2>
                <p style="font-size: 1.1rem; opacity: 0.9;">
                    Aún no has realizado postulaciones o están en proceso de revisión
                </p>
            <?php endif; ?>
        </div>

        <!-- ===== GRID PRINCIPAL ===== -->
        <div class="dashboard-grid">
            
            <!-- ===== ACTUALIZAR INFORMACIÓN ===== -->
            <div class="card" data-aos="fade-up" data-aos-delay="200">
                <h2 class="card-title">
                    <i class="fas fa-user-edit"></i>
                    Actualizar Información
                </h2>
                
                <form method="POST" onsubmit="addLoadingEffect(this.querySelector('button'))">
                    <div class="form-group">
                        <label for="habilidades">
                            <i class="fas fa-cogs"></i> Habilidades
                        </label>
                        <textarea name="habilidades" class="form-control" 
                                  placeholder="Describe tus habilidades profesionales..."><?php echo htmlspecialchars($user_habilidades); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="experiencia">
                            <i class="fas fa-briefcase"></i> Experiencia
                        </label>
                        <textarea name="experiencia" class="form-control" 
                                  placeholder="Detalla tu experiencia laboral..."><?php echo htmlspecialchars($user_experiencia); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="profesion">
                            <i class="fas fa-graduation-cap"></i> Profesión
                        </label>
                        <input type="text" name="profesion" class="form-control" 
                               value="<?php echo htmlspecialchars($user_profesion); ?>" 
                               placeholder="Tu profesión actual">
                    </div>

                    <button type="submit" name="actualizar_datos" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Actualizar Datos
                    </button>
                </form>
                
                <a href="subir_cv.php" class="btn btn-editar">
                    <i class="fas fa-upload"></i>
                    Subir hoja de vida
                </a>
            </div>

            <!-- ===== OFERTAS DISPONIBLES ===== -->
            <div class="card" data-aos="fade-up" data-aos-delay="300">
                <h2 class="card-title">
                    <i class="fas fa-briefcase"></i>
                    Ofertas Disponibles
                </h2>
                
                <div class="offers-grid">
                    <?php
                    $sql_ofertas = "SELECT o.id, o.titulo, o.descripcion, o.profesion_solicitada, u.nombre AS empresa_nombre 
                                    FROM ofertas_laborales o 
                                    JOIN usuarios u ON o.empresa_id = u.id";
                    $result_ofertas = $conn->query($sql_ofertas);

                    if ($result_ofertas->num_rows > 0) {
                        while ($oferta = $result_ofertas->fetch_assoc()) {
                            $oferta_id = $oferta['id'];
                            $sql_postulacion = "SELECT * FROM postulaciones WHERE oferta_id = '$oferta_id' AND postulante_id = '$user_id'";
                            $result_postulacion = $conn->query($sql_postulacion);
                            $profesion_solicitada = $oferta['profesion_solicitada'];
                    ?>
                            <div class='offer-card'>
                                <h3 class='offer-title'><?php echo htmlspecialchars($oferta['titulo']); ?></h3>
                                <div class='offer-company'>
                                    <i class='fas fa-building'></i>
                                    <?php echo htmlspecialchars($oferta['empresa_nombre']); ?>
                                </div>
                                <p class='offer-description'><?php echo htmlspecialchars($oferta['descripcion']); ?></p>
                                
                                <div class='offer-actions'>
                                    <?php if ($user_profesion != $profesion_solicitada): ?>
                                        <div class='status-message no-apply'>
                                            <i class='fas fa-exclamation-triangle'></i> 
                                            Profesión requerida: <?php echo htmlspecialchars($profesion_solicitada); ?>
                                        </div>
                                    <?php elseif ($result_postulacion->num_rows > 0): ?>
                                        <div class='status-message already-applied'>
                                            <i class='fas fa-check'></i> Ya postulado
                                        </div>
                                    <?php else: ?>
                                        <form action='apply_offer.php' method='post' style='display: inline;'>
                                            <input type='hidden' name='oferta_id' value='<?php echo $oferta['id']; ?>'>
                                            <button type='submit' class='btn btn-apply'>
                                                <i class='fas fa-paper-plane'></i> Postularme
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                    ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>No hay ofertas disponibles en este momento</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SCRIPTS ===== -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</body>
</html>