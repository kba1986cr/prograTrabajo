<?php
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
    $nombre = $_POST['nombre'];
    $apellido1 = $_POST['apellido1'];
    $apellido2 = $_POST['apellido2'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];

    $conn = conectar();
    $sql = "INSERT INTO usuarios (correo, contrasena, nombre, apellido1, apellido2, fecha_nacimiento) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $correo, $contrasena, $nombre, $apellido1, $apellido2, $fecha_nacimiento);
    
    if ($stmt->execute()) {
        header("Location: login.php"); // Redirige a la página de login
        exit();
    } else {
        echo "Error al registrar usuario: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>
