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
    background: var(--cream);
    min-height: 100vh;
    font-family: 'DM Sans', sans-serif;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .card {
    background: #fff;
    border-radius: 32px;
    padding: 36px 32px 32px;
    width: 100%;
    max-width: 480px;
    box-shadow: 0 8px 40px rgba(232,121,160,0.12);
    position: relative;
    overflow: hidden;
  }
  .card::before {
    content:'';
    position:absolute; top:-60px; right:-60px;
    width:180px; height:180px;
    background:radial-gradient(circle, var(--pink-pale) 0%, transparent 70%);
    border-radius:50%;
  }
  .header { margin-bottom: 24px; position: relative; }
  .title {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem; font-weight: 900;
    color: var(--dark); letter-spacing: -0.5px; line-height: 1.1;
  }
  .title span { color: var(--pink); }
  .sparkle {
    display: inline-block; font-size: 1rem; color: var(--pink);
    animation: sparkle 2s ease-in-out infinite; margin-left: 4px;
  }
  @keyframes sparkle {
    0%,100%{opacity:1;transform:scale(1) rotate(0deg);}
    50%{opacity:0.6;transform:scale(1.3) rotate(20deg);}
  }
  .info-box {
    background: var(--pink-pale);
    border-radius: 16px; padding: 16px 18px; margin-bottom: 24px;
  }
  .info-box p { font-size: 0.85rem; color: var(--dark); margin: 5px 0; }
  .info-box strong { color: var(--pink); }
  .status-msg {
    padding: 11px 14px; border-radius: 12px;
    font-size: 0.85rem; margin-bottom: 18px; text-align: center;
  }
  .status-success { background: #e9edc9; color: #4e5a37; }
  .status-error   { background: var(--red-pale); color: var(--red); }
  .status-warning { background: #fff3cd; color: #856404; }
  .form-row { display: flex; gap: 12px; margin-bottom: 12px; }
  .form-group {
    flex: 1; display: flex; flex-direction: column;
    gap: 6px; margin-bottom: 12px;
  }
  label {
    font-size: 0.78rem; font-weight: 500; color: var(--gray);
    letter-spacing: 0.5px; text-transform: uppercase;
  }
  select, input[type="date"] {
    width: 100%; padding: 11px 14px; border: 1.5px solid #eee;
    border-radius: 12px; font-family: 'DM Sans', sans-serif;
    font-size: 0.88rem; color: var(--dark); outline: none;
    transition: border 0.2s; background: #fff;
  }
  select:focus, input[type="date"]:focus { border-color: var(--pink-light); }
  select:disabled { background: #f9f9f9; color: var(--gray); }
  .horas-loading {
    font-size: 0.8rem; color: var(--gray);
    text-align: center; padding: 6px 0; display: none;
  }
  .btn-group { display: flex; gap: 10px; margin-top: 20px; }
  .btn-save {
    flex: 1; padding: 13px; background: var(--pink); color: #fff;
    border: none; border-radius: 14px; font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem; font-weight: 500; cursor: pointer; transition: all 0.2s;
  }
  .btn-save:hover { background: #d4638c; transform: translateY(-1px); }
  .btn-cancel {
    flex: 1; padding: 13px; background: #f5f5f5; color: var(--gray);
    border: none; border-radius: 14px; font-family: 'DM Sans', sans-serif;
    font-size: 0.9rem; cursor: pointer; text-decoration: none;
    text-align: center; transition: all 0.2s;
  }
  .btn-cancel:hover { background: #eee; }

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
  @keyframes popIn {
    from{opacity:0;transform:scale(0.92);}
    to{opacity:1;transform:scale(1);}
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

<div class="card">
  <div class="header">
    <div class="title">Editar <span>turno</span> <span class="sparkle">✦</span></div>
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

  <div class="info-box">
    <p><strong>Servicio:</strong> <?= htmlspecialchars($turno['Nombre_servicio']) ?></p>
    <p><strong>Terapeuta:</strong> <?= htmlspecialchars($turno['Terapeuta_Nombre'] . ' ' . $turno['Terapeuta_Apellido']) ?></p>
    <p><strong>Fecha y hora:</strong> <?= date('d/m/Y', strtotime($turno['Fecha_Turno'])) ?> a las <?= substr($turno['Hora_Turno'], 0, 5) ?> hs</p>
    <p><strong>Estado:</strong> <?= htmlspecialchars($turno['Estado_Turno']) ?></p>
  </div>

  <form method="POST" action="../Controllers/EditarTurnoController.php?id=<?= $turno['id_Turno'] ?>">
    <input type="hidden" name="action" value="actualizar_turno">

    <div class="form-group">
      <label>Nueva fecha</label>
      <input type="date" name="fecha" id="inputFecha"
             value="<?= htmlspecialchars($turno['Fecha_Turno']) ?>"
             min="<?= date('Y-m-d') ?>"
             onchange="validarFecha()"
             required>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Servicio</label>
        <select name="servicio" required>
          <option value="">Seleccioná...</option>
          <?php foreach ($servicios as $s): ?>
            <option value="<?= $s['id_Servicio'] ?>"
              <?= $s['id_Servicio'] == $turno['id_Servicio'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($s['Nombre_servicio']) ?> — $<?= $s['Costo'] ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Terapeuta</label>
        <select name="empleado" id="selectTerapeuta" required onchange="cargarHoras()">
          <option value="">Seleccioná...</option>
          <?php foreach ($empleados as $e): ?>
            <option value="<?= $e['id_Empleado'] ?>"
              <?= $e['id_Empleado'] == $turno['id_Empleado'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($e['Persona_Nombre'] . ' ' . $e['Persona_Apellido']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label>Horario disponible</label>
      <select name="hora" id="selectHora" required>
        <option value="<?= substr($turno['Hora_Turno'], 0, 5) ?>">
          <?= substr($turno['Hora_Turno'], 0, 5) ?> hs (actual)
        </option>
      </select>
      <div class="horas-loading" id="horasLoading">Cargando horarios...</div>
    </div>

    <div class="btn-group">
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
  const idTurnoActual = <?= intval($turno['id_Turno']) ?>;
  const horaActual    = '<?= substr($turno['Hora_Turno'], 0, 5) ?>';

  function validarFecha() {
    const fecha = document.getElementById('inputFecha').value;
    if (!fecha) return;

    const dia = new Date(fecha + 'T00:00:00').getDay();
    if (dia === 0 || dia === 6) {
      document.getElementById('inputFecha').value = '';
      document.getElementById('selectHora').innerHTML = '<option value="">Elegí una fecha válida</option>';
      document.getElementById('selectHora').disabled = true;
      document.getElementById('modalAviso').classList.add('open');
      return;
    }
    cargarHoras();
  }

  function cerrarAviso() {
    document.getElementById('modalAviso').classList.remove('open');
  }

  function cargarHoras() {
    const id_Emp = document.getElementById('selectTerapeuta').value;
    const fecha  = document.getElementById('inputFecha').value;
    const sel    = document.getElementById('selectHora');
    const loader = document.getElementById('horasLoading');

    if (!id_Emp || !fecha) {
      sel.innerHTML = `<option value="${horaActual}">${horaActual} hs (actual)</option>`;
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
          horas.forEach(h => {
            const opt       = document.createElement('option');
            opt.value       = h;
            opt.textContent = h + ' hs';
            if (h === horaActual) opt.selected = true;
            sel.appendChild(opt);
          });
          sel.disabled = false;
        }
      })
      .catch(() => {
        loader.style.display = 'none';
        sel.innerHTML = '<option value="">Error al cargar horarios</option>';
      });
  }

  document.addEventListener('DOMContentLoaded', cargarHoras);
</script>
</body>
</html>