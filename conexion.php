<?php
function conectar() {
    $host = 'localhost';
    $port = '8889'; // Para MAMP, generalmente es el puerto 8889
    $user = 'root';
    $pass = 'root'; // Intenta con 'root' en lugar de vacío
    $db = 'personas_db';

    $conn = new mysqli($host, $user, $pass, $db, $port);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    return $conn;
}
?>

