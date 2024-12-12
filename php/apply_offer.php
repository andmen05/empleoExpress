<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $oferta_id = $_POST['oferta_id'];
    $postulante_id = $_SESSION['user_id'];

    $sql_oferta = "SELECT profesion_solicitada FROM ofertas_laborales WHERE id = '$oferta_id'";
    $result_oferta = $conn->query($sql_oferta);
    $oferta = $result_oferta->fetch_assoc();

    $sql_postulante = "SELECT profesion FROM postulantes_info WHERE usuario_id = '$postulante_id'";
    $result_postulante = $conn->query($sql_postulante);
    $postulante = $result_postulante->fetch_assoc();

    if ($oferta['profesion_solicitada'] != $postulante['profesion']) {
        echo "<p>No puedes postularte a esta oferta porque no cumples con la profesión requerida.</p>";
    } else {
        $sql_postulacion = "INSERT INTO postulaciones (oferta_id, postulante_id, estado, fecha_postulacion)
                            VALUES ('$oferta_id', '$postulante_id', 'pendiente', NOW())";

        if ($conn->query($sql_postulacion) === TRUE) {
            header("Location: dashboard_postulante.php");
            exit();
        } else {
            echo "<p>Error al postularse: " . $conn->error . "</p>";
        }
    }
}
?>
