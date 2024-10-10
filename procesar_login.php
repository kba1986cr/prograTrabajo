<?php
include("conexion.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    $conn = conectar();
    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($contrasena, $user['contrasena'])) {
            $_SESSION['usuario'] = $user['id'];
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Usuario no encontrado.";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}

