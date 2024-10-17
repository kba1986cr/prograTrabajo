const calendario = document.getElementById('calendario');
const monthScroll = document.getElementById('month-scroll');
const detalleDia = document.getElementById('detalle-dia');
const diaSeleccionado = document.getElementById('dia-seleccionado');
const turnoSelect = document.getElementById('turno-select');
const horaInicio = document.getElementById('hora-inicio');
const horaFin = document.getElementById('hora-fin');
const nota = document.getElementById('nota');
let selectedDay;
let selectedTurno;
let existeRegistro = false;

const months = [
    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
    'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];
const turnos = [
    { nombre: "Administrativo", horas: { general: ["16:00", "22:00"] }},
    { nombre: "Citas", horas: { general: ["03:00", "06:00"] }},
    { nombre: "1er Turno", horas: { general: ["03:00", "06:00"] }},
    { nombre: "2do Turno", horas: { general: ["03:00", "14:00"] }},
    { nombre: "3er Turno", horas: { general: ["14:00", "22:00"] }}
];
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

function renderCalendar(month, year) {
    calendario.innerHTML = '';
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    for (let i = 1; i <= daysInMonth; i++) {
        const dia = document.createElement('div');
        dia.classList.add('dia');
        dia.innerText = `Día ${i}`;

        dia.addEventListener('click', () => {
            selectedDay = i;
            diaSeleccionado.innerText = `Día seleccionado: ${selectedDay}`;
            detalleDia.style.display = 'block';
            turnoSelect.innerHTML = '<option value="">Seleccionar Turno</option>';
            
            turnos.forEach((turno, index) => {
                const option = document.createElement('option');
                option.value = index;
                option.textContent = turno.nombre;
                turnoSelect.appendChild(option);
            });

            // Reset y esconder inputs al cambiar de día
            turnoSelect.value = '';
            horaInicio.value = '';
            horaInicio.classList.add('hidden');
            horaFin.value = '';
            horaFin.classList.add('hidden');
            nota.value = '';
        });
        
        calendario.appendChild(dia);
    }
}

// Añade eventos al selector de turnos y muestra las horas adecuadas
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

function createDayElement(day) {
    const dia = document.createElement('div');
    dia.classList.add('dia');
    dia.innerText = `Día ${day}`;

    dia.addEventListener('click', () => handleDayClick(day));
    
    return dia;
}

function handleDayClick(day) {
    selectedDay = day;
    diaSeleccionado.innerText = `Día seleccionado: ${selectedDay}`;
    detalleDia.style.display = 'block';
    
    populateTurnoSelect();
    resetInputs();
    checkRegistro(selectedDay, turnoSelect.value);
}

function populateTurnoSelect() {
    turnoSelect.innerHTML = '<option value="">Seleccionar Turno</option>';
    turnos.forEach((turno, index) => {
        const option = document.createElement('option');
        option.value = index;
        option.textContent = turno.nombre;
        turnoSelect.appendChild(option);
    });
}

function resetInputs() {
    turnoSelect.value = '';
    horaInicio.value = '';
    horaInicio.classList.add('hidden');
    horaFin.value = '';
    horaFin.classList.add('hidden');
    nota.value = '';
}

function checkRegistro(dia, turnoIndex) {
    // Aquí deberías hacer una llamada AJAX para comprobar si existe un registro
    const existe = Math.random() < 0.5; // Simulación: 50% de chance de que exista un registro
    existeRegistro = existe;

    toggleActionButtons(existeRegistro);
}

function toggleActionButtons(existe) {
    const buttons = document.querySelectorAll(".actions button");
    buttons[1].style.display = existe ? 'inline' : 'none'; // Botón Actualizar
    buttons[2].style.display = existe ? 'inline' : 'none'; // Botón Eliminar
}

function enviarSolicitud(accion) {
    const turnoIndex = turnoSelect.value;
    if (turnoIndex) {
        const turno = turnos[turnoIndex];
        const params = `dia=${selectedDay}&turno=${turno.nombre}&hora_inicio=${horaInicio.value}&hora_fin=${horaFin.value}&nota=${nota.value}&accion=${accion}`;
        
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "index.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                alert(xhr.responseText); // Mensaje de éxito o error
            }
        };
        xhr.send(params);
    }
}

function guardar() {
    const turnoIndex = turnoSelect.value;
    if (turnoIndex) {
        const turno = turnos[turnoIndex];
        enviarSolicitud('guardar');
    }
}

function actualizar() {
    enviarSolicitud('actualizar');
}

function eliminar() {
    enviarSolicitud('eliminar');
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
turnoSelect.addEventListener('change', function() {
    const turnoIndex = turnoSelect.value;
    if (turnoIndex) {
        horaInicio.classList.remove('hidden');
        horaFin.classList.remove('hidden');
    } else {
        horaInicio.classList.add('hidden');
        horaFin.classList.add('hidden');
    }
})

renderMonthScroll();
renderCalendar(currentMonth, currentYear);
