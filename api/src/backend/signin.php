<?php
// Incluir la conexión a la base de datos
require("../../config/db_connection.php");

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validar que los campos no estén vacíos
    if (empty($email) || empty($password)) {
        echo "<script>alert('Por favor, completa todos los campos.')</script>";
        header('Refresh:0; url=http://127.0.0.1/hirenow/api/src/signin.html');
        exit();
    }

    // Validar formato del correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Correo electrónico no válido.')</script>";
        header('Refresh:0; url=http://127.0.0.1/hirenow/api/src/signin.html');
        exit();
    }

    // Preparar la consulta para verificar si el correo existe en la base de datos
    $query = "SELECT id_usuario, contraseña, id_rol FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Si el correo no existe en la base de datos
    if ($stmt->num_rows == 0) {
        echo "<script>alert('Correo no registrado.')</script>";
        header('Refresh:0; url=http://127.0.0.1/hirenow/api/src/signin.html');
        exit();
    }

    // Si el correo existe, obtener la contraseña almacenada
    $stmt->bind_result($id_usuario, $stored_password, $role_id);
    $stmt->fetch();

    // Verificar si la contraseña ingresada es correcta
    if (!password_verify($password, $stored_password)) {
        echo "<script>alert('Contraseña incorrecta.')</script>";
        header('Refresh:0; url=http://127.0.0.1/hirenow/api/src/signin.html');
        exit();
    }

    // Iniciar sesión (si la contraseña es correcta)
    session_start();
    $_SESSION['user_id'] = $id_usuario;
    $_SESSION['role_id'] = $role_id;
    $_SESSION['email'] = $email;

    // Redirigir a la página principal o dashboard
    header('Location: http://127.0.0.1/hirenow/api/src/secciones.html');
    exit();
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
