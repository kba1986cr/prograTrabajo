<?php
// Inicia la sesión y verifica que el usuario ha iniciado sesión
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Incluye el archivo de conexión y procesa el formulario si es una solicitud POST
include("conexion.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dia = $_POST['dia'];
    $turno = $_POST['turno'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $nota = $_POST['nota'];

    $conn = conectar();
    $sql = "INSERT INTO turnos (dia, turno, hora_inicio, hora_fin, nota) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $dia, $turno, $hora_inicio, $hora_fin, $nota);

    if ($stmt->execute()) {
        echo "Registro exitoso";
    } else {
        echo "Error al registrar: " . $conn->error;
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
    <title>Calendario Interactivo con Turnos y Notas</title>
    <link rel="stylesheet" href="stylesIndex.css">
</head>
<body>
    <div class="sidebar">
        <h2 onclick="toggleMenu()">Menú</h2>
        <div class="menu-content">
            <div class="menu-item">Opción 1</div>
            <div class="menu-item">Opción 2</div>
            <div class="menu-item">Opción 3</div>
            <div class="menu-item">Opción 4</div>
            <div class="menu-item" onclick="cerrarSesion()">Cerrar Sesión</div>
        </div>
    </div>

    <div class="content">
        <div class="calendario" id="calendario"></div>
        <div class="month-scroll" id="month-scroll"></div>
    </div>

    <script>
        const calendario = document.getElementById('calendario');
        const monthScroll = document.getElementById('month-scroll');
        const months = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        const turnos = [
            { nombre: "Administrativo", horas: { lun_jue: ["16:00", "22:00"], vie: ["15:00", "22:00"] }},
            { nombre: "Citas", horas: { general: ["03:00", "06:00"], tarde: ["14:00", "22:00"] }},
            { nombre: "1er Turno", horas: { general: ["03:00", "06:00"], tarde: ["14:00", "22:00"] }},
            { nombre: "2do Turno", horas: { manana: ["03:00", "14:00"], noche: ["22:00", "10:00"] }},
            { nombre: "3er Turno", horas: { general: ["14:00", "22:00"], noche: ["06:00", "18:00"] }}
        ];
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();

        function renderCalendar(month, year) {
            calendario.innerHTML = '';
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            for (let i = 1; i <= daysInMonth; i++) {
                const dia = document.createElement('div');
                dia.classList.add('dia');
                const titulo = document.createElement('div');
                titulo.innerText = `Día ${i}`;
                
                const turnoSelect = document.createElement('select');
                turnoSelect.classList.add('turno-select');
                turnoSelect.innerHTML = `<option value="">Seleccionar Turno</option>`;
                turnos.forEach((turno, index) => {
                    turnoSelect.innerHTML += `<option value="${index}">${turno.nombre}</option>`;
                });

                const horaInicio = document.createElement('input');
                horaInicio.type = 'time';
                horaInicio.classList.add('hora-inicio', 'hidden');
                
                const horaFin = document.createElement('input');
                horaFin.type = 'time';
                horaFin.classList.add('hora-fin', 'hidden');

                turnoSelect.addEventListener('change', (e) => {
                    const turnoIndex = e.target.value;
                    if (turnoIndex) {
                        const turnoSeleccionado = turnos[turnoIndex];
                        horaInicio.classList.remove('hidden');
                        horaFin.classList.remove('hidden');
                        horaInicio.min = turnoSeleccionado.horas.general[0];
                        horaInicio.max = turnoSeleccionado.horas.general[1];
                        horaFin.min = turnoSeleccionado.horas.general[0];
                        horaFin.max = turnoSeleccionado.horas.general[1];
                    } else {
                        horaInicio.classList.add('hidden');
                        horaFin.classList.add('hidden');
                    }
                });

                const nota = document.createElement('textarea');
                nota.classList.add('nota', 'hidden');
                nota.placeholder = "Notas del día...";

                const actions = document.createElement('div');
                actions.classList.add('actions', 'hidden');
                actions.innerHTML = `
                    <button class="boton" onclick="guardar(${i})">Guardar</button>
                    <button class="boton" onclick="actualizar(${i})">Actualizar</button>
                    <button class="boton" onclick="eliminar(${i})">Eliminar</button>
                `;

                dia.appendChild(titulo);
                dia.appendChild(turnoSelect);
                dia.appendChild(horaInicio);
                dia.appendChild(horaFin);
                dia.appendChild(nota);
                dia.appendChild(actions);
                calendario.appendChild(dia);

                dia.addEventListener('click', () => {
                    turnoSelect.classList.toggle('hidden');
                    nota.classList.toggle('hidden');
                    actions.classList.toggle('hidden');
                });
            }
        }

        function renderMonthScroll() {
            monthScroll.innerHTML = '';
            months.forEach((month, index) => {
                const monthItem = document.createElement('div');
                monthItem.classList.add('month-item');
                monthItem.innerText = `${month} ${currentYear}`;
                monthItem.addEventListener('click', () => {
                    currentMonth = index;
                    renderCalendar(currentMonth, currentYear);
                });
                monthScroll.appendChild(monthItem);
            });
        }

        monthScroll.addEventListener('wheel', (event) => {
            event.preventDefault();
            currentYear += event.deltaY < 0 ? 1 : -1;
            renderMonthScroll();
        });

        function toggleMenu() {
            const menuContent = document.querySelector('.menu-content');
            menuContent.classList.toggle('active');
        }

        function cerrarSesion() {
            window.location.href = "login.php";
        }

        renderMonthScroll();
        renderCalendar(currentMonth, currentYear);
    </script>
</body>
</html>
