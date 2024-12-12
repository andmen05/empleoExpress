<?php
$servername = "localhost";  // La dirección del servidor, en este caso es 'localhost'
$username = "root";         // Nombre de usuario, por defecto es 'root' en localhost
$password = "";             // Contraseña, suele estar vacía en localhost
$dbname = "plataforma_empleo";  // El nombre de la base de datos que creaste en phpMyAdmin

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
