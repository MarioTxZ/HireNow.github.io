<?php
// Conexión a la base de datos
require("../../config/db_connection.php");

// Obtener datos del formulario
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$pass = $_POST['password'];
$role_text = trim($_POST['role']); // Obtenemos el texto del rol

// Validar que los campos no estén vacíos
if (empty($name) || empty($email) || empty($pass) || empty($role_text)) {
    echo "<script>alert('Por favor, completa todos los campos.')</script>";
    header('Refresh:0; url=http://127.0.0.1/beta/api/src/signup.html');
    exit();
}

// Validar formato del correo electrónico
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Correo electrónico no válido.')</script>";
    header('Refresh:0; url=http://127.0.0.1/beta/api/src/signup.html');
    exit();
}

// Hashear la contraseña
$enc_pass = password_hash($pass, PASSWORD_DEFAULT);

// Asignar el ID del rol basado en el texto recibido
if ($role_text === 'postulante') {
    $role_id = 1;  // Asignamos ID 1 para 'postulante'
} elseif ($role_text === 'reclutador') {
    $role_id = 2;  // Asignamos ID 2 para 'reclutador'
} else {
    echo "<script>alert('Rol no válido.')</script>";
    header('Refresh:0; url=http://127.0.0.1/hirenow/api/src/signup.html');
    exit();
}

// Validar si el correo ya existe
$query_email = "SELECT id_usuario FROM usuarios WHERE email = ?";
$stmt_email = $conn->prepare($query_email);
$stmt_email->bind_param("s", $email);
$stmt_email->execute();
$stmt_email->store_result();

if ($stmt_email->num_rows > 0) {
    echo "<script>alert('El correo ya está registrado.')</script>";
    header('Refresh:0; url=http://127.0.0.1/hirenow/api/src/signup.html');
    exit();
}
$stmt_email->close();

// Insertar datos en la tabla `usuarios`
$query_user = "INSERT INTO usuarios (usuario, contraseña, id_rol, email) VALUES (?, ?, ?, ?)";
$stmt_user = $conn->prepare($query_user);
$stmt_user->bind_param("ssis", $name, $enc_pass, $role_id, $email);

if ($stmt_user->execute()) {
    echo "<script>alert('Registro exitoso.')</script>";
    header('Refresh:0; url=http://127.0.0.1/hirenow/api/src/signin.html');
} else {
    echo "Error al registrar el usuario: " . $stmt_user->error;
}
$stmt_user->close();

// Cerrar la conexión
$conn->close();
?>
