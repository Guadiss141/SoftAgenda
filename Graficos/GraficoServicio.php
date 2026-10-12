<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$usuario = $_SESSION['nombre_usuario'] ?? 'Administrador';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard - Beauty SPA</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<style>
    * {
        box-sizing: border-box;
    }
    body {
        margin: 0;
        font-family: 'DM Sans', Arial, sans-serif;
        background: linear-gradient(135deg, #FFFFFF 0%, #FFF3F8 30%, #FFD6EA 65%, #FF9EC7 100%);
        min-height: 100vh;
        padding: 40px;
    }
    .contenedor {
        max-width: 1100px;
        margin: 0 auto;
    }
    .encabezado {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .bienvenida h1 {
        font-size: 24px;
        font-weight: 700;
        color: #2b2b2b;
        margin: 0 0 4px;
    }
    .bienvenida p {
        font-size: 14px;
        color: #9a94a0;
        margin: 0;
    }
    .selector-mes {
        position: relative;
    }
    .btn-volver {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: 'DM Sans', Arial, sans-serif;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        padding: 12px 20px;
        border-radius: 30px;
        background: #fff;
        color: #89235B;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .btn-volver:hover {
        box-shadow: 0 4px 16px rgba(241,2,145,0.15);
        transform: translateX(-2px);
    }
    .dropdown-mes-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'DM Sans', Arial, sans-serif;
        font-size: 14px;
        font-weight: 600;
        padding: 12px 20px;
        border-radius: 30px;
        background: #fff;
        color: #2b2b2b;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        user-select: none;
        transition: box-shadow 0.2s;
    }
    .dropdown-mes-btn:hover {
        box-shadow: 0 4px 16px rgba(241,2,145,0.15);
    }
    .dropdown-mes-btn .flecha {
        color: #F10291;
        font-size: 11px;
        transition: transform 0.2s;
    }
    .dropdown-mes-btn.abierto .flecha {
        transform: rotate(180deg);
    }
    .dropdown-mes-lista {
        display: none;
        position: absolute;
        right: 0;
        top: calc(100% + 10px);
        background: #fff;
        border-radius: 16px;
        min-width: 200px;
        max-height: 320px;
        overflow-y: auto;
        padding: 8px;
        box-shadow: 0 12px 32px rgba(137,35,91,0.18);
        z-index: 50;
    }
    .dropdown-mes-lista.abierta {
        display: block;
    }
    .dropdown-mes-opcion {
        padding: 11px 16px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        color: #2b2b2b;
        cursor: pointer;
        transition: background 0.15s, color 0.15s;
    }
    .dropdown-mes-opcion:hover {
        background: #FFCAE4;
    }
    .dropdown-mes-opcion.activa {
        background: #F10291;
        color: #fff;
        font-weight: 700;
    }
    /* Scrollbar rosa prolijo en vez del gris del navegador */
    .dropdown-mes-lista::-webkit-scrollbar {
        width: 6px;
    }
    .dropdown-mes-lista::-webkit-scrollbar-thumb {
        background: #FFCAE4;
        border-radius: 10px;
    }
    .grilla-dashboard {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }
    @media (max-width: 800px) {
        .grilla-dashboard { grid-template-columns: 1fr; }
    }
    .tarjeta-blanca {
        background: #fff;
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    }
    .tarjeta-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    .tarjeta-header h3 {
        font-size: 16px;
        font-weight: 700;
        color: #2b2b2b;
        margin: 0 0 4px;
    }
    .tarjeta-header p {
        font-size: 13px;
        color: #9a94a0;
        margin: 0;
    }
    .badge-top {
        background: #FCE9F0;
        color: #E88AAE;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 20px;
        white-space: nowrap;
    }
    .tarjeta-vacia {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 220px;
        color: #c9c2ce;
        font-size: 14px;
        font-weight: 500;
        border: 2px dashed #eee0ea;
        border-radius: 16px;
    }
</style>
</head>
<body>

<div class="contenedor">

    <div class="encabezado">
        <div class="bienvenida">
            <h1>Hola, <?= htmlspecialchars($usuario) ?></h1>
            <p>Este es el resumen del spa este mes</p>
        </div>
        <div style="display: flex; align-items: center; gap: 14px;">
            <a href="/Spaa/Inicioo/Index.php" class="btn-volver">← Volver al inicio</a>
            <div class="selector-mes">
                <form method="GET" id="formMes">
                    <input type="hidden" name="mesAnio" id="inputMesAnio">
                </form>

                <div class="dropdown-mes-btn" id="dropdownMesBtn">
                    <span id="dropdownMesTexto">
                        <?php
                        $nombresMeses = [
                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                        ];
                        echo $nombresMeses[$mes] . ' ' . $anio;
                        ?>
                    </span>
                    <span class="flecha">▼</span>
                </div>

                <div class="dropdown-mes-lista" id="dropdownMesLista">
                    <?php
                    for ($i = 0; $i < 12; $i++) {
                        $timestamp = strtotime("-$i months");
                        $valorMes = (int)date('n', $timestamp);
                        $valorAnio = (int)date('Y', $timestamp);
                        $etiqueta = $nombresMeses[$valorMes] . ' ' . $valorAnio;
                        $claseActiva = ($valorMes == $mes && $valorAnio == $anio) ? 'activa' : '';
                        echo "<div class=\"dropdown-mes-opcion $claseActiva\" data-valor=\"$valorMes-$valorAnio\" data-texto=\"$etiqueta\">$etiqueta</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="grilla-dashboard">

        <!-- Cuadrante 1: Servicios más solicitados -->
        <div class="tarjeta-blanca">
            <div class="tarjeta-header">
                <div>
                    <h3>Servicios más solicitados</h3>
                    <p>Cantidad de turnos por servicio</p>
                </div>
                <?php if (!empty($servicios)): ?>
                    <span class="badge-top">Top: <?= htmlspecialchars($servicios[0]) ?></span>
                <?php endif; ?>
            </div>
            <canvas id="graficoServicios" height="220"></canvas>
        </div>

        <!-- Cuadrante 2: Turnos por empleado -->
        <div class="tarjeta-blanca">
            <div class="tarjeta-header">
                <div>
                    <h3>Turnos por empleado</h3>
                    <p>Ranking de atención por empleado</p>
                </div>
                <?php if (!empty($empleados)): ?>
                    <span class="badge-top">Top: <?= htmlspecialchars($empleados[0]) ?></span>
                <?php endif; ?>
            </div>
            <canvas id="graficoEmpleados" height="220"></canvas>
        </div>

        <!-- Cuadrante 3: Turnos por día de la semana -->
        <div class="tarjeta-blanca">
            <div class="tarjeta-header">
                <div>
                    <h3>Turnos por día de la semana</h3>
                    <p>Qué días se concentran más reservas</p>
                </div>
            </div>
            <canvas id="graficoDiaSemana" height="220"></canvas>
        </div>

        <!-- Cuadrante 4: Turnos por franja horaria -->
        <div class="tarjeta-blanca">
            <div class="tarjeta-header">
                <div>
                    <h3>Turnos por franja horaria</h3>
                    <p>Mañana, tarde o noche</p>
                </div>
            </div>
            <canvas id="graficoFranjaHoraria" height="220"></canvas>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ── Dropdown de mes personalizado ──
const dropdownBtn = document.getElementById('dropdownMesBtn');
const dropdownLista = document.getElementById('dropdownMesLista');
const dropdownTexto = document.getElementById('dropdownMesTexto');
const inputMesAnio = document.getElementById('inputMesAnio');
const formMes = document.getElementById('formMes');

dropdownBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    dropdownLista.classList.toggle('abierta');
    dropdownBtn.classList.toggle('abierto');
});

dropdownLista.querySelectorAll('.dropdown-mes-opcion').forEach(opcion => {
    opcion.addEventListener('click', () => {
        inputMesAnio.value = opcion.dataset.valor;
        formMes.submit();
    });
});

document.addEventListener('click', (e) => {
    if (!dropdownBtn.contains(e.target) && !dropdownLista.contains(e.target)) {
        dropdownLista.classList.remove('abierta');
        dropdownBtn.classList.remove('abierto');
    }
});

// ── Paleta de tonos rosa/magenta para variar las barras ──
const paletaTonos = ['#F10291', '#FF8FC7', '#B48CE8', '#89235B', '#E88AAE', '#D45C99'];
function generarColores(cantidad) {
    return Array.from({ length: cantidad }, (_, i) => paletaTonos[i % paletaTonos.length]);
}

new Chart(document.getElementById('graficoServicios'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($servicios); ?>,
        datasets: [{
            data: <?php echo json_encode($cantidadesServicios); ?>,
            backgroundColor: generarColores(<?php echo count($servicios); ?>),
            borderRadius: 10,
            maxBarThickness: 40
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f4f1f6' }, ticks: { color: '#9a94a0' } },
            x: { grid: { display: false }, ticks: { color: '#9a94a0' } }
        }
    }
});

new Chart(document.getElementById('graficoEmpleados'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($empleados); ?>,
        datasets: [{
            data: <?php echo json_encode($cantidadesEmpleados); ?>,
            backgroundColor: generarColores(<?php echo count($empleados); ?>),
            borderRadius: 10,
            maxBarThickness: 40
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { beginAtZero: true, grid: { color: '#f4f1f6' }, ticks: { color: '#9a94a0' } },
            y: { grid: { display: false }, ticks: { color: '#9a94a0' } }
        }
    }
});

new Chart(document.getElementById('graficoDiaSemana'), {
    type: 'line',
    data: {
        labels: <?php echo json_encode($diasSemana); ?>,
        datasets: [{
            data: <?php echo json_encode($cantidadesDiaSemana); ?>,
            borderColor: '#E88AAE',
            backgroundColor: 'rgba(232,138,174,0.15)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#E88AAE',
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f4f1f6' }, ticks: { color: '#9a94a0' } },
            x: { grid: { display: false }, ticks: { color: '#9a94a0' } }
        }
    }
});

new Chart(document.getElementById('graficoFranjaHoraria'), {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($franjasHorarias); ?>,
        datasets: [{
            data: <?php echo json_encode($cantidadesFranjaHoraria); ?>,
            backgroundColor: ['#F10291', '#FF8FC7', '#89235B'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#2b2b2b',
                    font: { family: 'DM Sans', size: 12 },
                    generateLabels: (chart) => {
                        const datos = chart.data.datasets[0].data;
                        const colores = chart.data.datasets[0].backgroundColor;
                        return chart.data.labels.map((nombre, i) => ({
                            text: `${nombre} (${datos[i]})`,
                            fillStyle: colores[i],
                            strokeStyle: colores[i],
                            index: i
                        }));
                    }
                }
            }
        }
    }
});
</script>

</body>
</html>