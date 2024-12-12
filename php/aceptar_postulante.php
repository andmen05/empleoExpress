<?php
session_start();
include('db.php');

// Verificar si el usuario está autenticado y es una empresa
if (!isset($_SESSION['user_id']) || $_SESSION['tipo_usuario'] !== 'empresa') {
    header("Location: login.php");
    exit();
}

// Verificar si se ha recibido una solicitud de aceptación
if (isset($_GET['postulacion_id']) && isset($_GET['oferta_id'])) {
    $postulacion_id = $_GET['postulacion_id'];
    $oferta_id = $_GET['oferta_id'];

    // Actualizar la postulación a "aceptado"
    $sql = "UPDATE postulaciones SET estado = 'aceptado' WHERE id = ? AND oferta_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $postulacion_id, $oferta_id);

    if ($stmt->execute()) {
        // Obtener el id de la oferta y el id del postulante
        $sql_postulante = "SELECT postulante_id FROM postulaciones WHERE id = ? AND oferta_id = ?";
        $stmt_postulante = $conn->prepare($sql_postulante);
        $stmt_postulante->bind_param("ii", $postulacion_id, $oferta_id);
        $stmt_postulante->execute();
        $result = $stmt_postulante->get_result();
        
        if ($result->num_rows > 0) {
            $postulante = $result->fetch_assoc();
            $postulante_id = $postulante['postulante_id'];

            // Obtener la información de la empresa para notificar al postulante
            $sql_empresa = "SELECT e.direccion, e.correo_contacto, u.nombre AS empresa_nombre FROM empresas_info e 
                            JOIN usuarios u ON e.usuario_id = u.id WHERE u.id = ?";
            $stmt_empresa = $conn->prepare($sql_empresa);
            $stmt_empresa->bind_param("i", $_SESSION['user_id']);
            $stmt_empresa->execute();
            $empresa_result = $stmt_empresa->get_result();

            if ($empresa_result->num_rows > 0) {
                $empresa_info = $empresa_result->fetch_assoc();
                $empresa_nombre = $empresa_info['empresa_nombre'];
                $direccion_empresa = $empresa_info['direccion'];
                $correo_empresa = $empresa_info['correo_contacto'];

                // Enviar notificación al postulante (esto puede ser un mensaje en la página o por email)
                echo "<h2>¡Postulante aceptado!</h2>";
                echo "<p>El postulante ha sido aceptado para la oferta. A continuación, se le ha notificado con la dirección de la empresa y el correo:</p>";
                echo "<p><strong>Empresa:</strong> $empresa_nombre</p>";
                echo "<p><strong>Dirección:</strong> $direccion_empresa</p>";
                echo "<p><strong>Correo de contacto:</strong> $correo_empresa</p>";
                echo "<p><a href='dashboard_empresa.php'>Volver al panel de la empresa</a></p>";

                // También puedes enviar un correo al postulante notificándole sobre la aceptación
                // (aquí puedes agregar una función de envío de correo si lo deseas)
            }
        }
    } else {
        echo "Error al aceptar la postulación.";
    }

    $stmt->close();
    $stmt_postulante->close();
    $stmt_empresa->close();
} else {
    echo "Datos insuficientes para procesar la aceptación.";
}
?>
