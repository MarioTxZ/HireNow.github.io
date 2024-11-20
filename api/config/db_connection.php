<?php
$host = "localhost";
$username = "root"; // Usuario por defecto en XAMPP
$password = ""; // Contraseña vacía por defecto
$dbname = "inter";

// Crear conexión
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
