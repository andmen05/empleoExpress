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
    <title>Editar Oferta Laboral</title>
    <link rel="stylesheet" href="css/sty.css">
    <style>
/* Reset general */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: #f0f0f0;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    padding: 20px;
}

/* Contenedor principal */
.container {
    background: #fff;
    width: 100%;
    max-width: 600px;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-align: center;
}

h1 {
    font-size: 2rem;
    color: #6c5ce7;
    margin-bottom: 20px;
}

/* Estilo del formulario */
form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    text-align: left;
}

/* Etiquetas */
label {
    font-size: 1rem;
    font-weight: bold;
    color: #6c5ce7;
    margin-bottom: 8px;
}

/* Campos de entrada */
input, textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    background: #f8f8f8;
    transition: border-color 0.3s ease;
}

/* Efecto en el foco */
input:focus, textarea:focus {
    border-color: #6c5ce7;
    outline: none;
}

/* Área de texto */
textarea {
    resize: vertical;
    min-height: 150px;
}

/* Botón de acción */
button {
    background-color: #6c5ce7;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s ease;
}

/* Hover en botón */
button:hover {
    background-color: #8e44ad;
}

/* Enlace de volver */
a {
    display: inline-block;
    margin-top: 20px;
    padding: 12px;
    text-decoration: none;
    color: white;
    background-color: #6c5ce7;
    border-radius: 8px;
    font-size: 1rem;
    width: 100%;
    text-align: center;
}

a:hover {
    background-color: #8e44ad;
}

/* Responsividad */
@media (max-width: 600px) {
    .container {
        padding: 20px;
    }

    h1 {
        font-size: 1.5rem;
    }
}

    </style>
</head>
<body>

    <!-- Formulario de edición de oferta laboral -->
    <form method="POST">
    <h1>Editar Oferta Laboral</h1>
        <label for="titulo">Título de la Oferta:</label>
        <input type="text" name="titulo" value="<?php echo htmlspecialchars($oferta['titulo']); ?>" required>

        <label for="descripcion">Descripción de la Oferta:</label>
        <textarea name="descripcion" required><?php echo htmlspecialchars($oferta['descripcion']); ?></textarea>

        <button type="submit" name="editar_oferta">Actualizar Oferta</button>

        
    <a href="dashboard_empresa.php">Volver al Dashboard</a>
    </form>

</body>
</html>
