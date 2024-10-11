<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="stylesLogin.css">
</head>
<body>
    <div class="form">
    <form action="procesar_login.php" method="POST">
        <h2>Iniciar Sesión</h2>
        <div class="form-element">
            <label for="text"> Correo</label>
            <input type="email" name="correo" placeholder="Ingrese un correo" required>
        </div>
        <div class="form-element">
            <label for="text"> Contraseña</label>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
        </div>
        <div class="form-element">
            <button type="submit">Ingresar</button>
        </div>
        <div class="form-element">
            <!-- Enlace para registrarse -->
            <a href="registro.php">¿No tienes cuenta?   Regístrate aquí</a>
        </div>
    </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
    </form>
</body>
</html>
