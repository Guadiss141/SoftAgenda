<?php
$id_Rol      = $_SESSION['id_Rol']      ?? null;
$id_Paciente = $_SESSION['id_Paciente'] ?? null;
$id_Empleado = $_SESSION['id_Empleado'] ?? null;

$turnos_json     = json_encode($turnos_mes      ?? []);
$bloqueados_json = json_encode($dias_bloqueados ?? []);

$servicios_arr  = $servicios_db  ?? [];
$terapeutas_arr = $terapeutas_db ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Turnos - SoftAgenda</title>
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
    --gray-pale: #f5f5f5;
    --gray-mid: #ccc;
    --green: #5a9e6f;
    --green-pale: #eaf4ee;
  }
  * { margin:0; padding:0; box-sizing:border-box; }
  body {
    background: var(--cream);
    min-height: 100vh;
    font-family: 'DM Sans', sans-serif;
    padding: 20px;
  }

  .page-wrapper {
    display: flex;
    gap: 24px;
    max-width: 860px;
    margin: 0 auto;
    align-items: flex-start;
    flex-wrap: wrap;
  }

  .admin-wrapper {
    max-width: 860px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 28px;
  }

  .admin-header {
    text-align: center;
    padding: 10px 0 4px;
  }
  .admin-header .title-turnos {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem; font-weight: 900;
    color: var(--dark); letter-spacing: -1px; line-height: 1;
  }
  .admin-header .title-month {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem; font-weight: 900;
    color: var(--pink); letter-spacing: -1px;
  }
  .sparkle {
    display: inline-block; font-size: 1.1rem; color: var(--pink);
    animation: sparkle 2s ease-in-out infinite; margin-left: 4px; vertical-align: top;
  }
  @keyframes sparkle {
    0%,100%{opacity:1;transform:scale(1) rotate(0deg);}
    50%{opacity:0.6;transform:scale(1.3) rotate(20deg);}
  }

  /* ── TABLAS ADMIN ── */
  .table-card {
    background: #fff;
    border-radius: 24px;
    padding: 24px 22px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
  }
  .table-card-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.15rem; font-weight: 700;
    color: var(--dark); margin-bottom: 16px;
    display: flex; align-items: center; gap: 8px;
  }
  .table-card-title .badge {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.72rem; font-weight: 500;
    padding: 3px 9px; border-radius: 20px;
  }
  .badge-pendiente { background: var(--pink-pale); color: var(--pink); }
  .badge-cancelado { background: var(--red-pale);  color: var(--red);  }

  .admin-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.84rem;
  }
  .admin-table thead th {
    text-align: left;
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: var(--gray);
    padding: 0 12px 10px;
    border-bottom: 1.5px solid #f0f0f0;
  }
  .admin-table tbody tr {
    transition: background 0.15s;
  }
  .admin-table tbody tr:hover {
    background: var(--cream);
  }
  .admin-table tbody td {
    padding: 11px 12px;
    color: var(--dark);
    border-bottom: 1px solid #f5f5f5;
    vertical-align: middle;
  }
  .admin-table tbody tr:last-child td {
    border-bottom: none;
  }
  .td-hora {
    font-weight: 500;
    color: var(--pink);
    white-space: nowrap;
  }
  .td-hora-cancelado {
    font-weight: 500;
    color: var(--red);
    white-space: nowrap;
  }
  .td-fecha {
    color: var(--gray);
    font-size: 0.8rem;
    white-space: nowrap;
  }
  .td-nombre { font-weight: 500; }
  .td-servicio { color: var(--gray); font-size: 0.8rem; }
  .empty-table {
    text-align: center;
    color: var(--gray-mid);
    padding: 24px 0;
    font-size: 0.85rem;
  }

  /* calendarioooooo */
  .card {
    background: #fff;
    border-radius: 32px;
    padding: 32px 28px 28px;
    width: 360px;
    flex-shrink: 0;
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
  .header { text-align:center; margin-bottom:24px; position:relative; }
  .title-turnos {
    font-family:'Playfair Display',serif;
    font-size:2.2rem; font-weight:900;
    color:var(--dark); letter-spacing:-1px; line-height:1;
  }
  .title-month {
    font-family:'Playfair Display',serif;
    font-size:2.2rem; font-weight:900;
    color:var(--pink); letter-spacing:-1px; line-height:1.1;
  }
  .nav {
    display:flex; align-items:center;
    justify-content:space-between; margin-bottom:16px;
  }
  .nav-btn {
    background:none; border:none; cursor:pointer; color:var(--pink);
    font-size:1.2rem; width:36px; height:36px; border-radius:50%;
    display:flex; align-items:center; justify-content:center; transition:background 0.2s;
  }
  .nav-btn:hover { background:var(--pink-pale); }
  .nav-label {
    font-family:'Playfair Display',serif; font-size:1rem;
    font-weight:700; color:var(--gray); letter-spacing:1px; text-transform:uppercase;
  }
  .weekdays { display:grid; grid-template-columns:repeat(7,1fr); margin-bottom:6px; }
  .weekday {
    text-align:center; font-size:0.7rem; font-weight:500;
    color:var(--gray); letter-spacing:0.5px; padding:5px 0; text-transform:uppercase;
  }
  .days { display:grid; grid-template-columns:repeat(7,1fr); gap:3px; }
  .day {
    aspect-ratio:1; display:flex; align-items:center; justify-content:center;
    border-radius:50%; font-size:0.85rem; color:var(--dark);
    cursor:pointer; transition:all 0.18s ease; position:relative; user-select:none;
  }
  .day:hover:not(.empty):not(.today):not(.blocked) { background:var(--pink-pale); color:var(--pink); }
  .day.empty  { cursor:default; }
  .day.today  { background:var(--pink); color:#fff; font-weight:700; box-shadow:0 4px 14px rgba(232,121,160,0.45); }
  .day.marked { background:var(--pink-pale); color:var(--pink); font-weight:600; }
  .day.marked::after {
    content:''; position:absolute; bottom:3px;
    width:4px; height:4px; border-radius:50%; background:var(--pink);
  }
  .day.blocked { background:var(--red-pale); color:var(--red); font-weight:600; cursor:default; }
  .day.blocked::after {
    content:''; position:absolute; bottom:3px;
    width:4px; height:4px; border-radius:50%; background:var(--red);
  }
  .day.selected { outline:2px solid var(--pink); outline-offset:1px; }
  .day.weekend  { color:#ccc; cursor:default; }

  /* PANEL LATERAL */
  .side-panel {
    flex:1; min-width:260px;
    display:flex; flex-direction:column; gap:16px;
  }
  .panel-card {
    background:#fff; border-radius:24px;
    padding:22px 20px;
    box-shadow:0 4px 20px rgba(0,0,0,0.05);
  }
  .panel-title {
    font-size:0.7rem; font-weight:500; letter-spacing:1.5px;
    text-transform:uppercase; color:var(--gray); margin-bottom:12px;
  }
  .appt-list { display:flex; flex-direction:column; gap:8px; }
  .appt-item {
    display:flex; align-items:center; gap:10px;
    padding:10px 14px; background:var(--pink-pale);
    border-radius:12px; animation:fadeUp 0.3s ease forwards; opacity:0;
  }
  .appt-item .appt-dot  { width:8px; height:8px; border-radius:50%; background:var(--pink); flex-shrink:0; }
  .appt-item .appt-text { font-size:0.82rem; color:var(--dark); flex:1; }
  .appt-item .appt-time { font-size:0.78rem; color:var(--pink); font-weight:500; }
  .appt-item .appt-cancel { font-size:0.75rem; color:var(--red); cursor:pointer; margin-left:4px; }
  .appt-item .appt-cancel:hover { text-decoration:underline; }
  .appt-item.ocupado { background:var(--gray-pale); }
  .appt-item.ocupado .appt-dot  { background:var(--gray-mid); }
  .appt-item.ocupado .appt-text { color:var(--gray); }
  .appt-item.ocupado .appt-time { color:var(--gray-mid); font-weight:400; }
  .appt-section-label {
    font-size:0.68rem; font-weight:500; letter-spacing:1px;
    text-transform:uppercase; color:var(--gray-mid); padding:4px 2px 2px;
  }
  @keyframes fadeUp {
    from{opacity:0;transform:translateY(6px);}
    to{opacity:1;transform:translateY(0);}
  }
  .empty-appt { text-align:center; font-size:0.8rem; color:#ccc; padding:10px 0; }
  .agenda-item {
    background:#fdfbf7; border-radius:12px;
    padding:14px 16px; border-left:4px solid var(--pink);
    margin-bottom:10px;
  }
  .agenda-item p { font-size:0.85rem; color:var(--dark); margin:4px 0; }
  .agenda-item strong { color:var(--pink); }

  .btn-group { display:flex; gap:8px; margin-top:14px; flex-wrap:wrap; }
  .add-btn {
    flex:1; padding:11px; background:var(--pink); color:#fff;
    border:none; border-radius:14px; font-family:'DM Sans',sans-serif;
    font-size:0.88rem; font-weight:500; cursor:pointer; transition:all 0.2s;
  }
  .add-btn:hover { background:#d4638c; transform:translateY(-1px); }
  .block-btn {
    flex:1; padding:11px; background:var(--red-pale); color:var(--red);
    border:1.5px solid var(--red); border-radius:14px;
    font-family:'DM Sans',sans-serif; font-size:0.88rem; cursor:pointer; transition:all 0.2s;
  }
  .block-btn:hover { background:var(--red); color:#fff; }
  .unblock-btn {
    flex:1; padding:11px; background:#eee; color:var(--gray);
    border:none; border-radius:14px; font-family:'DM Sans',sans-serif;
    font-size:0.88rem; cursor:pointer; transition:all 0.2s;
  }
  .unblock-btn:hover { background:#ddd; }
  .back-btn {
    display:block; text-align:center; padding:11px;
    background:#f5f5f5; color:var(--gray); border:none;
    border-radius:14px; text-decoration:none;
    font-family:'DM Sans',sans-serif; font-size:0.88rem; transition:all 0.2s;
  }
  .back-btn:hover { background:#eee; }

  .status-msg {
    padding:10px 14px; border-radius:10px;
    font-size:0.83rem; margin-bottom:12px; text-align:center;
  }
  .status-success { background:#e9edc9; color:#4e5a37; }
  .status-error   { background:var(--red-pale); color:var(--red); }
  .status-warning { background:#fff3cd; color:#856404; }
  .afectados-box {
    background:#fff7f0; border-left:4px solid var(--red);
    border-radius:12px; padding:12px 14px;
    font-size:0.83rem; color:var(--dark); margin-bottom:12px;
  }
  .afectados-box strong { color:var(--red); }

  .modal-overlay {
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,0.3); backdrop-filter:blur(4px);
    z-index:100; align-items:center; justify-content:center;
  }
  .modal-overlay.open { display:flex; }
  .modal {
    background:#fff; border-radius:24px; padding:28px 24px; width:320px;
    box-shadow:0 20px 60px rgba(0,0,0,0.15); animation:popIn 0.2s ease;
  }
  @keyframes popIn {
    from{opacity:0;transform:scale(0.92);}
    to{opacity:1;transform:scale(1);}
  }
  .modal h3 { font-family:'Playfair Display',serif; font-size:1.3rem; color:var(--dark); margin-bottom:6px; }
  .modal h3 span { color:var(--pink); }
  .modal h3.danger span { color:var(--red); }
  .modal-date { font-size:0.8rem; color:var(--gray); margin-bottom:14px; }
  .modal select, .modal input, .modal textarea {
    width:100%; padding:11px 14px; border:1.5px solid #eee;
    border-radius:12px; font-family:'DM Sans',sans-serif;
    font-size:0.88rem; color:var(--dark); margin-bottom:10px;
    outline:none; transition:border 0.2s; resize:none;
  }
  .modal select:focus, .modal input:focus, .modal textarea:focus { border-color:var(--pink-light); }
  .modal-btns { display:flex; gap:8px; margin-top:6px; }
  .btn-cancel {
    flex:1; padding:11px; border:1.5px solid #eee; background:none;
    border-radius:12px; cursor:pointer; font-family:'DM Sans',sans-serif;
    font-size:0.88rem; color:var(--gray); transition:background 0.2s;
  }
  .btn-cancel:hover { background:#f5f5f5; }
  .btn-save {
    flex:1; padding:11px; background:var(--pink); color:#fff; border:none;
    border-radius:12px; cursor:pointer; font-family:'DM Sans',sans-serif;
    font-size:0.88rem; font-weight:500; transition:background 0.2s;
  }
  .btn-save:hover { background:#d4638c; }
  .btn-block-confirm {
    flex:1; padding:11px; background:var(--red); color:#fff; border:none;
    border-radius:12px; cursor:pointer; font-family:'DM Sans',sans-serif;
    font-size:0.88rem; font-weight:500;
  }
  .btn-block-confirm:hover { background:#c04444; }
  .horas-loading { font-size:0.8rem; color:var(--gray); text-align:center; padding:8px 0; }

  .confirm-modal {
    background:#fff; border-radius:24px; padding:28px 24px; width:300px;
    box-shadow:0 20px 60px rgba(0,0,0,0.15); animation:popIn 0.2s ease;
    text-align: center;
  }
  .confirm-modal .confirm-icon {
    font-size: 2rem; margin-bottom: 10px;
  }
  .confirm-modal h4 {
    font-family:'Playfair Display',serif;
    font-size:1.1rem; font-weight:700;
    color:var(--dark); margin-bottom:8px;
  }
  .confirm-modal p {
    font-size:0.84rem; color:var(--gray);
    margin-bottom:20px; line-height:1.5;
  }
  .confirm-modal .confirm-btns {
    display:flex; gap:8px;
  }
  .confirm-modal .btn-confirm-ok {
    flex:1; padding:11px; border:none; border-radius:12px;
    font-family:'DM Sans',sans-serif; font-size:0.88rem;
    font-weight:500; cursor:pointer; transition:background 0.2s;
  }
  .confirm-modal .btn-confirm-ok.danger {
    background:var(--red); color:#fff;
  }
  .confirm-modal .btn-confirm-ok.danger:hover { background:#c04444; }
  .confirm-modal .btn-confirm-ok.warning {
    background:var(--pink); color:#fff;
  }
  .confirm-modal .btn-confirm-ok.warning:hover { background:#d4638c; }
</style>
</head>
<body>

<?php if ($id_Rol == 3): ?>
<!-- VISTA ADMINISTRADOR — dos tablas -->
<div class="admin-wrapper">

  <div class="admin-header">
    <div class="title-turnos">TURNOS <span class="sparkle">✦</span></div>
    <div class="title-month">HISTORIAL GENERAL</div>
  </div>

  <!-- Tabla pendientes -->
  <div class="table-card">
    <div class="table-card-title">
      Citas pendientes
      <span class="badge badge-pendiente"><?= count($turnos_pendientes) ?></span>
    </div>

    <?php if (empty($turnos_pendientes)): ?>
      <div class="empty-table">No hay citas pendientes</div>
    <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Hora</th>
          <th>Cliente</th>
          <th>Terapeuta</th>
          <th>Servicio</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($turnos_pendientes as $t): ?>
        <tr>
          <td class="td-fecha"><?= date('d/m/Y', strtotime($t['Fecha_Turno'])) ?></td>
          <td class="td-hora"><?= htmlspecialchars($t['Hora_Turno']) ?> hs</td>
          <td class="td-nombre"><?= htmlspecialchars($t['Cliente']) ?></td>
          <td><?= htmlspecialchars($t['Terapeuta']) ?></td>
          <td class="td-servicio"><?= htmlspecialchars($t['Nombre_servicio']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>

  <!-- Tabla cancelados -->
  <div class="table-card">
    <div class="table-card-title">
      Citas canceladas
      <span class="badge badge-cancelado"><?= count($turnos_cancelados) ?></span>
    </div>

    <?php if (empty($turnos_cancelados)): ?>
      <div class="empty-table">No hay citas canceladas</div>
    <?php else: ?>
    <table class="admin-table">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Hora</th>
          <th>Cliente</th>
          <th>Terapeuta</th>
          <th>Servicio</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($turnos_cancelados as $t): ?>
        <tr>
          <td class="td-fecha"><?= date('d/m/Y', strtotime($t['Fecha_Turno'])) ?></td>
          <td class="td-hora-cancelado"><?= htmlspecialchars($t['Hora_Turno']) ?> hs</td>
          <td class="td-nombre"><?= htmlspecialchars($t['Cliente']) ?></td>
          <td><?= htmlspecialchars($t['Terapeuta']) ?></td>
          <td class="td-servicio"><?= htmlspecialchars($t['Nombre_servicio']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>

  <a href="../index.php" class="back-btn">← Volver al inicio</a>

</div>

<?php else: ?>
<div class="page-wrapper">

  <!-- CALENDARIO -->
  <div class="card">
    <div class="header">
      <div class="title-turnos">TURNOS <span class="sparkle">✦</span></div>
      <div class="title-month" id="monthTitle"></div>
    </div>

    <div class="nav">
      <button class="nav-btn" onclick="changeMonth(-1)">&#8249;</button>
      <span class="nav-label" id="yearLabel"></span>
      <button class="nav-btn" onclick="changeMonth(1)">&#8250;</button>
    </div>

    <div class="weekdays">
      <div class="weekday">L</div><div class="weekday">M</div><div class="weekday">M</div>
      <div class="weekday">J</div><div class="weekday">V</div>
      <div class="weekday">S</div><div class="weekday">D</div>
    </div>

    <div class="days" id="daysGrid"></div>
  </div>

  <!-- PANEL LATERAL -->
  <div class="side-panel">

    <?php
    $status = $_GET['status'] ?? '';
    $statusMap = [
      'success'        => ['success', '¡Operación realizada con éxito!'],
      'error'          => ['error',   'Hubo un error. Intentá de nuevo.'],
      'bloqueado'      => ['warning', 'Ese terapeuta no está disponible ese día.'],
      'fin_semana'     => ['warning', 'No se pueden agendar turnos los fines de semana.'],
      'hora_ocupada'   => ['warning', 'Ese horario ya está ocupado. Elegí otro.'],
      'ya_bloqueado'   => ['warning', 'Ese día ya estaba bloqueado.'],
      'bloqueado_ok'   => ['error',   'Día bloqueado. Los turnos fueron cancelados.'],
      'desbloqueado_ok'=> ['success', 'Día desbloqueado correctamente.'],
    ];
    if ($status && isset($statusMap[$status])):
      [$tipo, $msg] = $statusMap[$status];
    ?>
      <div class="status-msg status-<?= $tipo ?>"><?= $msg ?></div>
    <?php endif; ?>

    <?php if (!empty($pacientes_afectados)): ?>
      <div class="afectados-box">
        <strong>Turnos cancelados el <?= htmlspecialchars($fecha_bloqueada) ?>:</strong><br>
        <?php foreach ($pacientes_afectados as $p): ?>
          · <?= htmlspecialchars($p['Persona_Nombre'] . ' ' . $p['Persona_Apellido']) ?><br>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <div class="panel-card">
      <div class="panel-title" id="panelTitulo">Seleccioná un día</div>
      <div class="appt-list" id="apptList">
        <div class="empty-appt">—</div>
      </div>

      <?php if ($id_Rol == 0): ?>
      <div class="btn-group" id="btnGroup" style="display:none;">
        <button class="add-btn" onclick="openModalAgregar()">+ Agregar turno</button>
      </div>
      <?php endif; ?>

      <?php if ($id_Rol == 2): ?>
      <div class="btn-group" id="btnGroup" style="display:none;">
        <button class="block-btn"   id="btnBloquear"    onclick="openModalBloquear()">🔒 Bloquear día</button>
        <button class="unblock-btn" id="btnDesbloquear" onclick="desbloquearDia()" style="display:none;">🔓 Desbloquear</button>
      </div>
      <?php endif; ?>
    </div>

    <?php if ($id_Rol == 2): ?>
    <div class="panel-card">
      <div class="panel-title">Agenda de hoy</div>
      <?php if ($agendaHoy && $agendaHoy->num_rows > 0): ?>
        <?php while ($cita = $agendaHoy->fetch_assoc()): ?>
          <div class="agenda-item">
            <p><strong><?= substr($cita['Hora_Turno'], 0, 5) ?> hs</strong> · <?= htmlspecialchars($cita['Nombre_servicio']) ?></p>
            <p><?= htmlspecialchars($cita['Cliente_Nombre'] . ' ' . $cita['Cliente_Apellido']) ?></p>
            <p>📞 <?= htmlspecialchars($cita['Cliente_Telefono']) ?></p>
            <form method="POST" action="../Controllers/TurnoController.php?action=completar" style="margin-top:8px;">
              <input type="hidden" name="id_turno" value="<?= $cita['id_Turno'] ?>">
              <button type="submit" class="add-btn" style="font-size:0.8rem;padding:8px;">✓ Finalizar servicio</button>
            </form>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="empty-appt">Sin citas para hoy</div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <a href="../index.php" class="back-btn">← Volver al inicio</a>
  </div>
</div>

<!-- MODAL AGREGAR TURNO (Paciente) -->
<?php if ($id_Rol == 0): ?>
<div class="modal-overlay" id="modalAgregar">
  <div class="modal">
    <h3>Nuevo <span>turno</span></h3>
    <div class="modal-date" id="modalDateAgregar"></div>
    <form method="POST" action="../Controllers/TurnoController.php?action=crear">
      <input type="hidden" name="Fecha_Turno" id="inputFechaAgregar">

      <select name="id_Servicio" required>
        <option value="">Seleccioná un servicio...</option>
        <?php foreach ($servicios_arr as $s): ?>
          <option value="<?= $s['id_Servicio'] ?>">
            <?= htmlspecialchars($s['Nombre_servicio']) ?> — $<?= $s['Costo'] ?>
          </option>
        <?php endforeach; ?>
      </select>

      <select name="id_Empleado" id="selectTerapeuta" required onchange="cargarHoras()">
        <option value="">Seleccioná un terapeuta...</option>
      </select>

      <div id="horasContainer">
        <select name="Hora_Turno" id="selectHora" required disabled>
          <option value="">Primero elegí un terapeuta</option>
        </select>
        <div class="horas-loading" id="horasLoading" style="display:none;">Cargando horarios...</div>
      </div>

      <div class="modal-btns">
        <button type="button" class="btn-cancel" onclick="closeModal('modalAgregar')">Cancelar</button>
        <button type="submit" class="btn-save">Confirmar turno</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<!-- MODAL BLOQUEAR DÍA (Terapeuta) -->
<?php if ($id_Rol == 2): ?>
<div class="modal-overlay" id="modalBloquear">
  <div class="modal">
    <h3 class="danger">Bloquear <span>día</span></h3>
    <div class="modal-date" id="modalDateBloquear"></div>
    <form method="POST" action="../Controllers/TurnoController.php?action=bloquear">
      <input type="hidden" name="fecha" id="inputFechaBloquear">
      <textarea name="motivo" placeholder="Motivo (opcional)" rows="3"></textarea>
      <div class="modal-btns">
        <button type="button" class="btn-cancel" onclick="closeModal('modalBloquear')">Cancelar</button>
        <button type="submit" class="btn-block-confirm">🔒 Confirmar bloqueo</button>
      </div>
    </form>
  </div>
</div>

<form method="POST" action="../Controllers/TurnoController.php?action=desbloquear" id="formDesbloquear" style="display:none;">
  <input type="hidden" name="fecha" id="inputFechaDesbloquear">
</form>
<?php endif; ?>

<!-- MODAL CONFIRMACIÓN GENÉRICO -->
<div class="modal-overlay" id="modalConfirm">
  <div class="confirm-modal">
    <div class="confirm-icon" id="confirmIcon">⚠️</div>
    <h4 id="confirmTitle">¿Estás seguro?</h4>
    <p id="confirmMsg"></p>
    <div class="confirm-btns">
      <button class="btn-cancel" onclick="closeModal('modalConfirm')">Cancelar</button>
      <button class="btn-confirm-ok danger" id="confirmOkBtn">Confirmar</button>
    </div>
  </div>
</div>

<script>
  const MONTHS_ES      = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
  const MONTHS_ES_FULL = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];

  const id_Rol     = <?= intval($id_Rol) ?>;
  const turnosMes  = <?= $turnos_json ?>;
  const bloqueados = <?= $bloqueados_json ?>;

  let today        = new Date();
  let current      = new Date(today.getFullYear(), today.getMonth(), 1);
  let selectedDate = null;

  function dateKey(y, m, d) {
    return `${y}-${String(m+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
  }

  function turnosPorFecha() {
    const map = {};
    turnosMes.forEach(t => {
      if (!map[t.Fecha_Turno]) map[t.Fecha_Turno] = [];
      map[t.Fecha_Turno].push(t);
    });
    return map;
  }

  function esFinDeSemana(y, m, d) {
    const dow = new Date(y, m, d).getDay();
    return dow === 0 || dow === 6;
  }

  function renderCalendar() {
    const year  = current.getFullYear();
    const month = current.getMonth();
    document.getElementById('monthTitle').textContent = MONTHS_ES[month];
    document.getElementById('yearLabel').textContent  = year;

    const grid        = document.getElementById('daysGrid');
    grid.innerHTML    = '';
    const firstDay    = new Date(year, month, 1).getDay();
    const offset      = firstDay === 0 ? 6 : firstDay - 1;
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const map         = turnosPorFecha();

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
      const hasTurnos = map[key] && map[key].length > 0;

      if (isWeekend || isBlocked) {
        el.classList.add('blocked');
      } else if (isToday) {
        el.classList.add('today');
      } else if (hasTurnos) {
        el.classList.add('marked');
      }

      if (selectedDate === key) el.classList.add('selected');
      if (!isWeekend) el.addEventListener('click', () => selectDay(year, month, d));
      grid.appendChild(el);
    }
  }

  function selectDay(y, m, d) {
    selectedDate = dateKey(y, m, d);
    renderCalendar();
    renderAppointments();

    const isBlocked = bloqueados.includes(selectedDate) || esFinDeSemana(y, m, d);
    const btnGroup  = document.getElementById('btnGroup');
    if (btnGroup) btnGroup.style.display = 'flex';

    if (id_Rol === 2) {
      const btnB = document.getElementById('btnBloquear');
      const btnD = document.getElementById('btnDesbloquear');
      if (btnB) btnB.style.display = isBlocked ? 'none' : 'flex';
      if (btnD) btnD.style.display = bloqueados.includes(selectedDate) ? 'flex' : 'none';
    }
  }

  function renderAppointments() {
    const list  = document.getElementById('apptList');
    const title = document.getElementById('panelTitulo');
    list.innerHTML = '';

    if (!selectedDate) {
      title.textContent = 'Seleccioná un día';
      list.innerHTML = '<div class="empty-appt">—</div>';
      return;
    }

    const [y, m, d] = selectedDate.split('-').map(Number);
    title.textContent = `${d} de ${MONTHS_ES_FULL[m-1]} de ${y}`;

    const isBlocked = bloqueados.includes(selectedDate);
    const isWeekend = esFinDeSemana(y, m-1, d);

    if (isWeekend) {
      list.innerHTML = '<div class="empty-appt">🚫 Fin de semana</div>';
      return;
    }
    if (isBlocked && id_Rol === 2) {
      list.innerHTML = '<div class="empty-appt" style="color:var(--red)">🔒 Día bloqueado</div>';
      return;
    }
    if (isBlocked && id_Rol === 0) {
      list.innerHTML = '<div class="empty-appt" style="color:var(--red)">🔒 Sin disponibilidad este día</div>';
      return;
    }

    const map   = turnosPorFecha();
    const appts = map[selectedDate] || [];

    // Vista TERAPEUTA carga via AJAX para asegurar datos frescos
    if (id_Rol === 2) {
      list.innerHTML = '<div class="empty-appt" style="font-size:0.75rem;">Cargando turnos...</div>';

      fetch(`../Controllers/TurnoController.php?action=turnosDelDiaTerapeuta&fecha=${selectedDate}`)
        .then(r => r.json())
        .then(turnos => {
          list.innerHTML = '';
          if (turnos.length === 0) {
            list.innerHTML = '<div class="empty-appt">Sin turnos este día ✦</div>';
            return;
          }
          turnos.forEach((a, i) => {
            const el = document.createElement('div');
            el.className = 'appt-item';
            el.style.animationDelay = `${i * 0.06}s`;
            el.innerHTML = `
              <div class="appt-dot"></div>
              <span class="appt-text">${a.Paciente_Nombre} ${a.Paciente_Apellido} · ${a.Nombre_servicio}</span>
              <span class="appt-time">${a.Hora_Turno}</span>
            `;
            list.appendChild(el);
          });
        })
        .catch(() => {
          list.innerHTML = '<div class="empty-appt">Sin turnos este día ✦</div>';
        });
      return;
    }

    //Vista PACIENTE
    if (appts.length > 0) {
      const labelMio = document.createElement('div');
      labelMio.className = 'appt-section-label';
      labelMio.textContent = 'Mis turnos';
      list.appendChild(labelMio);

      appts.forEach((a, i) => {
        const el = document.createElement('div');
        el.className = 'appt-item';
        el.style.animationDelay = `${i * 0.06}s`;
        const nombre = `${a.Terapeuta_Nombre} ${a.Terapeuta_Apellido}`;
        el.innerHTML = `
          <div class="appt-dot"></div>
          <span class="appt-text">${nombre} · ${a.Nombre_servicio}</span>
          <span class="appt-time">${a.Hora_Turno}</span>
          ${a.Estado_Turno !== 'Completado'
            ? `<span class="appt-cancel" onclick="cancelarTurno(${a.id_Turno}, '${nombre}')">✕</span>`
            : ''}
        `;
        list.appendChild(el);
      });
    }

    // Horarios ocupados via AJAX
    const labelOcupados = document.createElement('div');
    labelOcupados.className = 'appt-section-label';
    labelOcupados.id = 'labelOcupados';
    labelOcupados.textContent = 'Horarios ocupados';
    labelOcupados.style.display = 'none';
    list.appendChild(labelOcupados);

    const loadingEl = document.createElement('div');
    loadingEl.className = 'empty-appt';
    loadingEl.id = 'ocupadosLoading';
    loadingEl.style.fontSize = '0.75rem';
    loadingEl.textContent = appts.length === 0 ? 'Cargando disponibilidad...' : '';
    list.appendChild(loadingEl);

    fetch(`../Controllers/TurnoController.php?action=horasOcupadasDelDia&fecha=${selectedDate}`)
      .then(r => r.json())
      .then(ocupados => {
        const loading = document.getElementById('ocupadosLoading');
        if (loading) loading.remove();

        if (ocupados.length === 0) {
          if (appts.length === 0) {
            list.innerHTML = '<div class="empty-appt">Sin turnos este día ✦</div>';
          }
          return;
        }

        const lbl = document.getElementById('labelOcupados');
        if (lbl) lbl.style.display = 'block';

        ocupados.forEach((o, i) => {
          const el = document.createElement('div');
          el.className = 'appt-item ocupado';
          el.style.animationDelay = `${i * 0.05}s`;
          el.innerHTML = `
            <div class="appt-dot"></div>
            <span class="appt-text">${o.Terapeuta_Nombre} ${o.Terapeuta_Apellido}</span>
            <span class="appt-time">${o.Hora_Turno} hs · ocupado</span>
          `;
          list.appendChild(el);
        });
      })
      .catch(() => {
        const loading = document.getElementById('ocupadosLoading');
        if (loading) loading.remove();
        if (appts.length === 0) {
          list.innerHTML = '<div class="empty-appt">Sin turnos este día ✦</div>';
        }
      });
  }

  //Modal de confirmación genérico
  function showConfirm({ icon = '⚠️', title, msg, btnClass = 'danger', btnText = 'Confirmar', onOk }) {
    document.getElementById('confirmIcon').textContent  = icon;
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMsg').textContent   = msg;
    const btn = document.getElementById('confirmOkBtn');
    btn.textContent = btnText;
    btn.className   = `btn-confirm-ok ${btnClass}`;
    // Clonar para limpiar listeners anteriores
    const newBtn = btn.cloneNode(true);
    btn.parentNode.replaceChild(newBtn, btn);
    newBtn.addEventListener('click', () => {
      closeModal('modalConfirm');
      onOk();
    });
    document.getElementById('modalConfirm').classList.add('open');
  }

  function cancelarTurno(id, nombre) {
    showConfirm({
      icon: '🗑️',
      title: 'Cancelar turno',
      msg: `¿Querés cancelar el turno con ${nombre}?`,
      btnClass: 'danger',
      btnText: 'Sí, cancelar',
      onOk: () => {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../Controllers/TurnoController.php?action=eliminar';
        const input = document.createElement('input');
        input.type = 'hidden'; input.name = 'id_turno'; input.value = id;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
      }
    });
  }

  function openModalAgregar() {
    if (!selectedDate) { alert('Seleccioná un día primero.'); return; }
    if (bloqueados.includes(selectedDate)) { alert('Ese día no tiene disponibilidad.'); return; }

    const [y, m, d] = selectedDate.split('-').map(Number);
    document.getElementById('modalDateAgregar').textContent = `${d} de ${MONTHS_ES_FULL[m-1]} de ${y}`;
    document.getElementById('inputFechaAgregar').value = selectedDate;

    const selH = document.getElementById('selectHora');
    selH.innerHTML = '<option value="">Primero elegí un terapeuta</option>';
    selH.disabled  = true;

    const selT = document.getElementById('selectTerapeuta');
    selT.innerHTML = '<option value="">Cargando terapeutas...</option>';
    selT.disabled  = true;

    fetch(`../Controllers/TurnoController.php?action=terapeutasDisponibles&fecha=${selectedDate}`)
      .then(r => r.json())
      .then(terapeutas => {
        selT.innerHTML = '<option value="">Seleccioná un terapeuta...</option>';
        if (terapeutas.length === 0) {
          selT.innerHTML = '<option value="">Sin terapeutas disponibles</option>';
        } else {
          terapeutas.forEach(t => {
            const opt = document.createElement('option');
            opt.value = t.id_Empleado;
            opt.textContent = `${t.Persona_Nombre} ${t.Persona_Apellido}`;
            selT.appendChild(opt);
          });
          selT.disabled = false;
        }
      })
      .catch(() => {
        selT.innerHTML = '<option value="">Error al cargar terapeutas</option>';
      });

    document.getElementById('modalAgregar').classList.add('open');
  }

  function cargarHoras() {
    const id_Emp = document.getElementById('selectTerapeuta').value;
    const fecha  = document.getElementById('inputFechaAgregar').value;
    const sel    = document.getElementById('selectHora');
    const loader = document.getElementById('horasLoading');

    if (!id_Emp || !fecha) {
      sel.innerHTML = '<option value="">Primero elegí un terapeuta</option>';
      sel.disabled  = true;
      return;
    }

    sel.disabled = true;
    loader.style.display = 'block';

    fetch(`../Controllers/TurnoController.php?action=horasDisponibles&id_Empleado=${id_Emp}&fecha=${fecha}`)
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
            const opt = document.createElement('option');
            opt.value = h; opt.textContent = h + ' hs';
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

  function openModalBloquear() {
    if (!selectedDate) { alert('Seleccioná un día primero.'); return; }
    const [y, m, d] = selectedDate.split('-').map(Number);
    document.getElementById('modalDateBloquear').textContent = `${d} de ${MONTHS_ES_FULL[m-1]} de ${y}`;
    document.getElementById('inputFechaBloquear').value = selectedDate;
    document.getElementById('modalBloquear').classList.add('open');
  }

  function desbloquearDia() {
    if (!selectedDate) return;
    const [y, m, d] = selectedDate.split('-').map(Number);
    showConfirm({
      icon: '🔓',
      title: 'Desbloquear día',
      msg: `¿Desbloquear el ${d} de ${MONTHS_ES_FULL[m-1]} de ${y}? Los turnos cancelados no se restauran automáticamente.`,
      btnClass: 'warning',
      btnText: 'Sí, desbloquear',
      onOk: () => {
        document.getElementById('inputFechaDesbloquear').value = selectedDate;
        document.getElementById('formDesbloquear').submit();
      }
    });
  }

  function closeModal(id) {
    document.getElementById(id).classList.remove('open');
  }

  function changeMonth(dir) {
    current.setMonth(current.getMonth() + dir);
    selectedDate = null;
    const bg = document.getElementById('btnGroup');
    if (bg) bg.style.display = 'none';
    renderCalendar();
    renderAppointments();
  }

  document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
  });

  selectedDate = dateKey(today.getFullYear(), today.getMonth(), today.getDate());
  renderCalendar();
  renderAppointments();
  const bg = document.getElementById('btnGroup');
  if (bg) bg.style.display = 'flex';
  if (id_Rol === 2) {
    const isBlocked = bloqueados.includes(selectedDate);
    const btnB = document.getElementById('btnBloquear');
    const btnD = document.getElementById('btnDesbloquear');
    if (btnB) btnB.style.display = isBlocked ? 'none' : 'flex';
    if (btnD) btnD.style.display = isBlocked ? 'flex'  : 'none';
  }
</script>

<?php endif; ?>
</body>
</html>