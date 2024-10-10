<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <style>
        body { background-color: #121212; color: #ffffff; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: Arial, sans-serif; }
        form { background-color: #1e1e1e; padding: 20px; border-radius: 5px; }
        input, button { margin: 10px 0; width: 100%; padding: 10px; }
    </style>
</head>
<body>
    <form action="procesar_registro.php" method="POST">
        <h2>Registro de Usuario</h2>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido1" placeholder="Apellido" required>
        <input type="text" name="apellido2" placeholder="Segundo Apellido" required>
        <input type="date" name="fecha_nacimiento" required>
        <button type="submit">Registrarse</button>
    </form>
</body>
</html>
