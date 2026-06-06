<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Turno - SoftAgenda</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --pink: #e879a0;
    --pink-light: #f5b8d0;
    --pink-pale: #fde8f0;
    --red: #e05555;
    --red-pale: #fdeaea;
    --cream: #faf8f5;
    --dark: #1a1a1a;
    --gray: #888;
  }
  * { margin:0; padding:0; box-sizing:border-box; }
  body {
    background: #f6c8c7;
    min-height: 100vh;
    font-family: 'DM Sans', sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .modal-card {
    background: #fff;
    border-radius: 24px;
    padding: 28px 24px;
    width: 100%;
    max-width: 340px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    position: relative;
    animation: popIn 0.2s ease;
  }
  @keyframes popIn {
    from{opacity:0;transform:scale(0.92);}
    to{opacity:1;transform:scale(1);}
  }
  .header { margin-bottom: 14px; position: relative; }
  .title {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem; font-weight: 700;
    color: var(--dark);
  }
  .title span { color: var(--pink); }
  .modal-date { font-size: 0.8rem; color: var(--gray); margin-bottom: 14px; }
  
  .sparkle {
    display: inline-block; font-size: 1rem; color: var(--pink);
    animation: sparkle 2s ease-in-out infinite; margin-left: 4px;
  }
  @keyframes sparkle {
    0%,100%{opacity:1;transform:scale(1) rotate(0deg);}
    50%{opacity:0.6;transform:scale(1.3) rotate(20deg);}
  }
  .status-msg {
    padding: 10px 14px; border-radius: 10px;
    font-size: 0.83rem; margin-bottom: 12px; text-align: center;
  }
  .status-success { background: #e9edc9; color: #4e5a37; }
  .status-error   { background: var(--red-pale); color: var(--red); }
  .status-warning { background: #fff3cd; color: #856404; }

  label {
    font-size: 0.72rem; font-weight: 500; color: var(--gray);
    letter-spacing: 0.5px; text-transform: uppercase;
    display: block; margin-bottom: 6px;
  }
  select {
    width: 100%; padding: 11px 14px; border: 1.5px solid #eee;
    border-radius: 12px; font-family: 'DM Sans', sans-serif;
    font-size: 0.88rem; color: var(--dark); margin-bottom: 10px;
    outline: none; transition: border 0.2s; background: #fff;
  }
  select:focus { border-color: var(--pink-light); }
  select:disabled { background: #f9f9f9; color: var(--gray); }
  
  /* ── CALENDARIO INTERNO ── */
  .calendar-container {
    background: #fff;
    border: 1.5px solid #eee;
    border-radius: 16px;
    padding: 14px 12px;
    margin-bottom: 10px;
  }
  .calendar-header-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
  }
  .cal-nav-btn {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--pink);
    font-size: 1.1rem;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
  }
  .cal-nav-btn:hover { background: var(--pink-pale); }
  .cal-nav-label {
    font-family: 'Playfair Display', serif;
    font-size: 0.88rem;
    font-weight: 700;
    color: var(--dark);
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }
  .cal-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    margin-bottom: 4px;
  }
  .cal-weekday {
    text-align: center;
    font-size: 0.65rem;
    font-weight: 500;
    color: var(--gray);
    padding: 2px 0;
  }
  .cal-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 2px;
  }
  .day {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 0.78rem;
    color: var(--dark);
    cursor: pointer;
    transition: all 0.15s ease;
    position: relative;
    user-select: none;
  }
  .day:hover:not(.empty):not(.today):not(.blocked):not(.past) {
    background: var(--pink-pale);
    color: var(--pink);
  }
  .day.empty { cursor: default; }
  .day.today {
    background: var(--pink);
    color: #fff;
    font-weight: 700;
  }
  .day.blocked {
    background: var(--red-pale);
    color: var(--red);
    font-weight: 600;
    cursor: default;
  }
  .day.blocked::after {
    content: '';
    position: absolute;
    bottom: 2px;
    width: 3px;
    height: 3px;
    border-radius: 50%;
    background: var(--red);
  }
  .day.selected {
    outline: 2px solid var(--pink);
    outline-offset: 1px;
  }
  .day.weekend {
    color: #ccc;
    cursor: default;
  }
  .day.past {
    color: #ccc;
    cursor: default;
  }

  .horas-loading { 
    font-size: 0.8rem; color: var(--gray); 
    text-align: center; padding: 4px 0; display: none; 
  }
  .modal-btns { display: flex; gap: 8px; margin-top: 14px; }
  
  .btn-save {
    flex: 1; padding: 11px; background: var(--pink); color: #fff; border: none;
    border-radius: 12px; cursor: pointer; font-family: 'DM Sans', sans-serif;
    font-size: 0.88rem; font-weight: 500; transition: background 0.2s;
  }
  .btn-save:hover { background: #d4638c; }
  
  .btn-cancel {
    flex: 1; padding: 11px; border: 1.5px solid #eee; background: none;
    border-radius: 12px; cursor: pointer; font-family: 'DM Sans', sans-serif;
    font-size: 0.88rem; color: var(--gray); transition: background 0.2s;
    text-decoration: none; text-align: center; display: inline-block;
  }
  .btn-cancel:hover { background: #f5f5f5; }

  /* Modal aviso */
  .modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.3); backdrop-filter: blur(4px);
    z-index: 100; align-items: center; justify-content: center;
  }
  .modal-overlay.open { display: flex; }
  .modal-aviso {
    background: #fff; border-radius: 24px; padding: 32px 28px;
    width: 300px; text-align: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    animation: popIn 0.2s ease;
  }
  .modal-aviso .icono { font-size: 2.2rem; margin-bottom: 12px; }
  .modal-aviso h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem; color: var(--dark); margin-bottom: 8px;
  }
  .modal-aviso p {
    font-size: 0.85rem; color: var(--gray);
    margin-bottom: 22px; line-height: 1.5;
  }
  .modal-aviso button {
    background: var(--pink); color: #fff; border: none;
    border-radius: 12px; padding: 12px 24px;
    font-family: 'DM Sans', sans-serif; font-size: 0.9rem;
    cursor: pointer; width: 100%; transition: background 0.2s;
  }
  .modal-aviso button:hover { background: #d4638c; }
</style>
</head>
<body>

<div class="modal-card">
  <div class="header">
    <div class="title">Editar <span>turno</span> <span class="sparkle">✦</span></div>
    <div class="modal-date">Modificá los datos de tu reserva</div>
  </div>

  <?php
  $statusMap = [
    'success'      => ['success', '¡Turno actualizado correctamente!'],
    'error'        => ['error',   'Error al actualizar. Intentá de nuevo.'],
    'error_campos' => ['error',   'Completá todos los campos.'],
    'fin_semana'   => ['warning', 'No se pueden agendar turnos los fines de semana.'],
    'bloqueado'    => ['warning', 'Ese terapeuta no está disponible ese día.'],
    'hora_ocupada' => ['warning', 'Ese horario ya está ocupado. Elegí otro.'],
  ];
  if (!empty($mensaje) && isset($statusMap[$mensaje])):
    [$tipo, $msg] = $statusMap[$mensaje];
  ?>
    <div class="status-msg status-<?= $tipo ?>"><?= $msg ?></div>
  <?php endif; ?>

  <form method="POST" action="../Controllers/EditarTurnoController.php?id=<?= $turno['id_Turno'] ?>">
    <input type="hidden" name="action" value="actualizar_turno">

    <div style="margin-bottom: 8px;">
      <select name="servicio" required>
        <option value="">Seleccioná un servicio...</option>
        <?php foreach ($servicios as $s): ?>
          <option value="<?= $s['id_Servicio'] ?>"
            <?= $s['id_Servicio'] == $turno['id_Servicio'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($s['Nombre_servicio']) ?> — $<?= $s['Costo'] ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div style="margin-bottom: 8px;">
      <select name="empleado" id="selectTerapeuta" required onchange="cargarHoras()">
        <option value="">Seleccioná un terapeuta...</option>
        <?php foreach ($empleados as $e): ?>
          <option value="<?= $e['id_Empleado'] ?>"
            <?= $e['id_Empleado'] == $turno['id_Empleado'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($e['Persona_Nombre'] . ' ' . $e['Persona_Apellido']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div style="margin-bottom: 8px;">
      <label>Fecha del turno</label>
      <input type="hidden" name="fecha" id="inputFecha" value="<?= htmlspecialchars($turno['Fecha_Turno']) ?>" required>
      
      <div class="calendar-container">
        <div class="calendar-header-nav">
          <button type="button" class="cal-nav-btn" onclick="changeMonth(-1)">&#8249;</button>
          <span class="cal-nav-label" id="calendarMonthYear"></span>
          <button type="button" class="cal-nav-btn" onclick="changeMonth(1)">&#8250;</button>
        </div>

        <div class="cal-weekdays">
          <div class="cal-weekday">L</div><div class="cal-weekday">M</div><div class="cal-weekday">M</div>
          <div class="cal-weekday">J</div><div class="cal-weekday">V</div>
          <div class="cal-weekday">S</div><div class="cal-weekday">D</div>
        </div>

        <div class="cal-days" id="daysGrid"></div>
      </div>
    </div>

    <div style="margin-bottom: 8px;">
      <label>Horario disponible</label>
      <select name="hora" id="selectHora" required>
        <option value="<?= substr($turno['Hora_Turno'], 0, 5) ?>">
          <?= substr($turno['Hora_Turno'], 0, 5) ?> hs (actual)
        </option>
      </select>
      <div class="horas-loading" id="horasLoading">Cargando horarios...</div>
    </div>

    <div class="modal-btns">
      <a href="../Controllers/TurnoController.php" class="btn-cancel">Cancelar</a>
      <button type="submit" class="btn-save">Guardar cambios</button>
    </div>
  </form>
</div>

<!-- Modal aviso fin de semana -->
<div class="modal-overlay" id="modalAviso">
  <div class="modal-aviso">
    <div class="icono">🚫</div>
    <h3>Día no disponible</h3>
    <p>Los fines de semana no se atienden turnos.<br>Por favor elegí un día de lunes a viernes.</p>
    <button onclick="cerrarAviso()">Entendido</button>
  </div>
</div>

<script>
  const idTurnoActual      = <?= intval($turno['id_Turno']) ?>;
  const horaActual         = '<?= substr($turno['Hora_Turno'], 0, 5) ?>';
  const fechaOriginal      = '<?= htmlspecialchars($turno['Fecha_Turno']) ?>';
  const idEmpleadoOriginal = <?= intval($turno['id_Empleado']) ?>;
  
  const MONTHS_ES      = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
  const bloqueados     = <?= json_encode($dias_bloqueados ?? []) ?>;

  let today            = new Date();
  let parsedDate       = new Date(fechaOriginal + 'T00:00:00');
  let current          = new Date(parsedDate.getFullYear(), parsedDate.getMonth(), 1);
  let selectedDate     = fechaOriginal;

  function dateKey(y, m, d) {
    return `${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
  }

  function esFinDeSemana(y, m, d) {
    const dow = new Date(y, m, d).getDay();
    return dow === 0 || dow === 6;
  }

  function renderCalendar() {
    const year  = current.getFullYear();
    const month = current.getMonth();
    document.getElementById('calendarMonthYear').textContent = `${MONTHS_ES[month]} ${year}`;

    const grid        = document.getElementById('daysGrid');
    grid.innerHTML    = '';
    const firstDay    = new Date(year, month, 1).getDay();
    const offset      = firstDay === 0 ? 6 : firstDay - 1;
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    for (let i = 0; i < offset; i++) {
      const el = document.createElement('div');
      el.className = 'day empty';
      grid.appendChild(el);
    }

    for (let d = 1; d <= daysInMonth; d++) {
      const el  = document.createElement('div');
      const key = dateKey(year, month, d);
      el.className   = 'day';
      el.textContent = d;

      const isToday   = today.getFullYear() === year && today.getMonth() === month && today.getDate() === d;
      const isWeekend = esFinDeSemana(year, month, d);
      const isBlocked = bloqueados.includes(key);
      const isPast    = new Date(year, month, d) < new Date(today.getFullYear(), today.getMonth(), today.getDate());

      if (isWeekend || isBlocked) {
        el.classList.add('blocked');
      } else if (isPast) {
        el.classList.add('past');
      } else if (isToday) {
        el.classList.add('today');
      }

      if (selectedDate === key) el.classList.add('selected');

      // Solo días laborales y no pasados se pueden seleccionar (a menos que sea el día del turno actual, que siempre es seleccionable)
      if ((!isWeekend && !isPast && !isBlocked) || key === fechaOriginal) {
        el.addEventListener('click', () => selectDay(year, month, d));
      } else {
        el.classList.add('blocked');
      }
      grid.appendChild(el);
    }
  }

  function changeMonth(dir) {
    current.setMonth(current.getMonth() + dir);
    renderCalendar();
  }

  function selectDay(y, m, d) {
    selectedDate = dateKey(y, m, d);
    document.getElementById('inputFecha').value = selectedDate;
    renderCalendar();
    cargarTerapeutas();
  }

  function cerrarAviso() {
    document.getElementById('modalAviso').classList.remove('open');
  }

  function cargarTerapeutas(callback) {
    const fecha = document.getElementById('inputFecha').value;
    const selT  = document.getElementById('selectTerapeuta');
    const selH  = document.getElementById('selectHora');

    if (!fecha) {
      selT.innerHTML = '<option value="">Primero elegí una fecha</option>';
      selT.disabled = true;
      selH.innerHTML = '<option value="">Primero elegí una fecha</option>';
      selH.disabled = true;
      return;
    }

    selT.disabled = true;
    selT.innerHTML = '<option value="">Cargando terapeutas...</option>';

    fetch(`../Controllers/EditarTurnoController.php?id=${idTurnoActual}&action=terapeutasDisponibles&fecha=${fecha}`)
      .then(r => r.json())
      .then(terapeutas => {
        selT.innerHTML = '<option value="">Seleccioná un terapeuta...</option>';
        if (terapeutas.length === 0) {
          selT.innerHTML = '<option value="">Sin terapeutas disponibles</option>';
          selT.disabled = true;
          selH.innerHTML = '<option value="">Sin horarios disponibles</option>';
          selH.disabled = true;
        } else {
          terapeutas.forEach(t => {
            const opt = document.createElement('option');
            opt.value = t.id_Empleado;
            opt.textContent = `${t.Persona_Nombre} ${t.Persona_Apellido}`;
            
            // Si es la fecha original y coincide con el terapeuta original, preseleccionar
            if (fecha === fechaOriginal && t.id_Empleado == idEmpleadoOriginal) {
              opt.selected = true;
            }
            selT.appendChild(opt);
          });
          selT.disabled = false;
          
          if (typeof callback === 'function') {
            callback();
          } else {
            cargarHoras();
          }
        }
      })
      .catch(() => {
        selT.innerHTML = '<option value="">Error al cargar terapeutas</option>';
        selT.disabled = true;
      });
  }

  function cargarHoras() {
    const id_Emp = document.getElementById('selectTerapeuta').value;
    const fecha  = document.getElementById('inputFecha').value;
    const sel    = document.getElementById('selectHora');
    const loader = document.getElementById('horasLoading');

    if (!id_Emp || !fecha) {
      sel.innerHTML = '<option value="">Primero elegí un terapeuta</option>';
      sel.disabled  = true;
      return;
    }

    sel.disabled = true;
    loader.style.display = 'block';

    fetch(`../Controllers/EditarTurnoController.php?id=${idTurnoActual}&action=horasDisponibles&id_Empleado=${id_Emp}&fecha=${fecha}`)
      .then(r => r.json())
      .then(horas => {
        loader.style.display = 'none';
        sel.innerHTML = '';
        if (horas.length === 0) {
          sel.innerHTML = '<option value="">Sin horarios disponibles</option>';
          sel.disabled  = true;
        } else {
          sel.innerHTML = '<option value="">Elegí un horario...</option>';
          
          // Si es el terapeuta original y la fecha original, asegurar que la hora actual esté en la lista
          if (fecha === fechaOriginal && id_Emp == idEmpleadoOriginal && !horas.includes(horaActual)) {
            horas.unshift(horaActual);
          }

          horas.forEach(h => {
            const opt       = document.createElement('option');
            opt.value       = h;
            opt.textContent = h + ' hs';
            if (fecha === fechaOriginal && id_Emp == idEmpleadoOriginal && h === horaActual) {
              opt.selected = true;
            }
            sel.appendChild(opt);
          });
          sel.disabled = false;
        }
      })
      .catch(() => {
        loader.style.display = 'none';
        sel.innerHTML = '<option value="">Error al cargar horarios</option>';
        sel.disabled  = true;
      });
  }

  document.addEventListener('DOMContentLoaded', () => {
    renderCalendar();
    cargarHoras();
  });
</script>
</body>
</html>