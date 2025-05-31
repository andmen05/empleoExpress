<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'postulante') {
    header("Location: login.php");
    exit();
}

$conexion = new mysqli("localhost", "root", "", "empleoexpress"); // Cambia si usas otra config
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id = $_SESSION['id'];

if ($_FILES['cv']['error'] === UPLOAD_ERR_OK) {
    $nombre = $_FILES['cv']['name'];
    $tmp = $_FILES['cv']['tmp_name'];
    $ext = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

    if (in_array($ext, ['pdf', 'docx'])) {
        $nombre_archivo = "cv_" . $id . "." . $ext;
        $ruta = "uploads/cv/" . $nombre_archivo;

        if (!file_exists("uploads/cv")) {
            mkdir("uploads/cv", 0777, true);
        }

        move_uploaded_file($tmp, $ruta);
        $conexion->query("UPDATE postulantes SET cv = '$ruta' WHERE id = $id");

        header("Location: dashboard_postulante.php?cv=ok");
        exit();
    } else {
        echo "Formato no permitido. Solo .pdf y .docx.";
    }
} else {
    echo "Error al subir el archivo.";
}
