
<?php
// Inicia la sesión y verifica que el usuario ha iniciado sesión
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Incluye el archivo de conexión
include("conexion.php");
$conn = conectar();

// Procesa el formulario si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dia = $_POST['dia'] ?? '';
    $turno = $_POST['turno'] ?? '';
    $hora_inicio = $_POST['hora_inicio'] ?? '';
    $hora_fin = $_POST['hora_fin'] ?? '';
    $nota = $_POST['nota'] ?? '';
    $accion = $_POST['accion'] ?? ''; // Acción para determinar qué hacer

    // Preparar consulta según la acción
    if ($accion === 'guardar') {
        $sql = "INSERT INTO turnos (dia, turno, hora_inicio, hora_fin, nota) VALUES (?, ?, ?, ?, ?)";
    } elseif ($accion === 'actualizar') {
        $sql = "UPDATE turnos SET hora_inicio = ?, hora_fin = ?, nota = ? WHERE dia = ? AND turno = ?";
    } elseif ($accion === 'eliminar') {
        $sql = "DELETE FROM turnos WHERE dia = ? AND turno = ?";
    } else {
        die("Acción no válida.");
    }

    $stmt = $conn->prepare($sql);
    
    // Asignar parámetros según la acción
    if ($accion === 'guardar') {
        $stmt->bind_param("sssss", $dia, $turno, $hora_inicio, $hora_fin, $nota);
    } elseif ($accion === 'actualizar') {
        $stmt->bind_param("sssss", $hora_inicio, $hora_fin, $nota, $dia, $turno);
    } elseif ($accion === 'eliminar') {
        $stmt->bind_param("ss", $dia, $turno);
    }

    // Ejecutar la consulta y manejar el resultado
    if ($stmt->execute()) {
        echo ucfirst($accion) . " exitoso.";
    } else {
        echo "Error al " . $accion . ": " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendario</title>
  <link rel="stylesheet" href="styleIndex.css">
</head>

<body>
  <header>
    <h1>Encabezado Principal</h1>
  </header>

  <nav class="menu-content">
    <a href="#">Inicio
      <div class="sidebar">
        <h2 onclick="toggleMenu()"></h2>
        <div class="menu-content">
            <div class="menu-item">Opción 1</div>
            <div class="menu-item">Opción 2</div>
            <div class="menu-item">Opción 3</div>
            <div class="menu-item">Opción 4</div>
            <div class="menu-item" onclick="cerrarSesion()">Cerrar Sesión</div>
        </div>
    </div>
    </a>
    <a href="#">Acerca de</a>
    <a href="#">Servicios</a>
    <a href="#">Contacto</a>
  </nav>

  <div class="main-content">
    <!-- Sección Izquierda (60%) -->
    <div class="left-section">
      <header>Encabezado de la Sección Principal</header>
      <div class="div">
        <div class="content">
          <div class="calendario" id="calendario"></div>
        </div>
      </div>
      <section class="retractable-section">
                  <!-- Contenedor de Detalles del Día Seleccionado -->
                  <div class="detalle-dia" id="detalle-dia" style="display: none;">
                    <h3 id="dia-seleccionado">Día Seleccionado:</h3>
                    <select id="turno-select" class="turno-select">
                        <option value="">Seleccionar Turno</option>
                        <!-- Opciones de turno se agregan dinámicamente -->
                    </select>
                    <input id="hora-inicio" type="time" class="hora-inicio hidden">
                    <input id="hora-fin" type="time" class="hora-fin hidden">
                    <textarea id="nota" class="nota" placeholder="Notas del día..."></textarea>
                    <div class="actions">
                        <button class="boton" onclick="guardar()">Guardar</button>
                        <button class="boton" onclick="actualizar()" style="display: none;">Actualizar</button>
                        <button class="boton" onclick="eliminar()" style="display: none;">Eliminar</button>
                    </div>
                </div>
      </section>
      <section class="section">Primera Sección</section>
    </div>

    <!-- Sección Derecha (40%) -->
    <div class="right-section">
      <div class="div">
        <div class="month-scroll" id="month-scroll"></div>
      </div>
      <div class="div">Contenido del Div 2</div>
    </div>
  </div>

  <footer>
    <p>Pie de Página</p>
  </footer>
  <script src="scriptIndex.js"></script>
</body>
</html>
