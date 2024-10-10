<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="styleLogin.css">
</head>
<body>
    <form action="procesar_login.php" method="POST">
        <h2>Iniciar Sesión</h2>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <button type="submit">Ingresar</button>
        <!-- Enlace para registrarse -->
        <a href="registro.php">¿No tienes cuenta?   Regístrate aquí</a>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
    </form>
</body>
</html>
