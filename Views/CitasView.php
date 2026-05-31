<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
    <?php echo $_SESSION['id_Rol'] == 2 ? 'Mis Citas' : 'Mis Turnos'; ?> - SoftAgenda
</title>
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
    padding: 40px 20px;
  }

  .page-wrapper {
    max-width: 620px;
    margin: 0 auto;
  }

  .header {
    margin-bottom: 32px;
  }

  .title {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem;
    font-weight: 900;
    color: var(--dark);
    letter-spacing: -1px;
    line-height: 1;
  }

  .title span { color: var(--pink); }

  .sparkle {
    display: inline-block;
    font-size: 1.1rem;
    color: var(--pink);
    animation: sparkle 2s ease-in-out infinite;
    margin-left: 4px;
  }

  @keyframes sparkle {
    0%,100%{opacity:1;transform:scale(1) rotate(0deg);}
    50%{opacity:0.6;transform:scale(1.3) rotate(20deg);}
  }

  .subtitle {
    font-size: 0.88rem;
    color: var(--gray);
    margin-top: 6px;
  }

  .status-msg {
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 0.85rem;
    margin-bottom: 20px;
    text-align: center;
  }
  .status-success { background: #e9edc9; color: #4e5a37; }
  .status-error   { background: var(--red-pale); color: var(--red); }

  /* Tarjeta de cita/turno */
  .cita-card {
    background: #fff;
    border-radius: 20px;
    padding: 22px 24px;
    margin-bottom: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    border-left: 5px solid var(--pink);
    transition: transform 0.2s ease;
    animation: fadeUp 0.3s ease forwards;
    opacity: 0;
  }

  .cita-card:hover { transform: translateY(-2px); }

  @keyframes fadeUp {
    from { opacity:0; transform: translateY(8px); }
    to   { opacity:1; transform: translateY(0); }
  }

  .cita-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
  }

  .cita-fecha {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--dark);
  }

  .cita-hora {
    background: var(--pink-pale);
    color: var(--pink);
    font-size: 0.82rem;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
  }

  .cita-body { display: flex; flex-direction: column; gap: 6px; }

  .cita-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.88rem;
    color: var(--dark);
  }

  .cita-label {
    font-size: 0.75rem;
    font-weight: 500;
    color: var(--gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    min-width: 80px;
  }

  .cita-descripcion {
    margin-top: 12px;
    padding: 10px 14px;
    background: var(--cream);
    border-radius: 10px;
    font-size: 0.83rem;
    color: var(--gray);
    line-height: 1.5;
    border-left: 3px solid var(--pink-light);
  }

  .cita-descripcion strong {
    display: block;
    color: var(--pink);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
  }

  .cita-actions {
    display: flex;
    gap: 8px;
    margin-top: 14px;
  }

  .btn-editar {
    flex: 1; padding: 9px;
    background: var(--pink); color: #fff;
    border: none; border-radius: 12px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem; font-weight: 500;
    text-decoration: none; text-align: center;
    cursor: pointer; transition: all 0.2s;
  }
  .btn-editar:hover { background: #d4638c; }

  .btn-cancelar {
    flex: 1; padding: 9px;
    background: var(--red-pale); color: var(--red);
    border: 1.5px solid var(--red); border-radius: 12px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.85rem; cursor: pointer; transition: all 0.2s;
  }
  .btn-cancelar:hover { background: var(--red); color: #fff; }

  .empty-state {
    text-align: center;
    padding: 50px 20px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
  }

  .empty-state .icono { font-size: 2.5rem; margin-bottom: 12px; }

  .empty-state p {
    font-size: 0.9rem;
    color: var(--gray);
    margin-bottom: 20px;
  }

  /* Volver */
  .back-btn {
    display: block;
    text-align: center;
    margin-top: 28px;
    padding: 13px;
    background: #fff;
    color: var(--gray);
    border-radius: 14px;
    text-decoration: none;
    font-size: 0.9rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: all 0.2s;
  }
  .back-btn:hover { background: #f5f5f5; }

  /* Modal cancelar */
  .modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.3); backdrop-filter: blur(4px);
    z-index: 100; align-items: center; justify-content: center;
  }
  .modal-overlay.open { display: flex; }
  .modal {
    background: #fff; border-radius: 24px; padding: 30px 26px;
    width: 300px; text-align: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    animation: popIn 0.2s ease;
  }
  @keyframes popIn {
    from{opacity:0;transform:scale(0.92);}
    to{opacity:1;transform:scale(1);}
  }
  .modal .icono { font-size: 2rem; margin-bottom: 10px; }
  .modal h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem; color: var(--dark); margin-bottom: 8px;
  }
  .modal p { font-size: 0.85rem; color: var(--gray); margin-bottom: 22px; line-height: 1.5; }
  .modal-btns { display: flex; gap: 8px; }
  .modal-btns button, .modal-btns a {
    flex: 1; padding: 11px; border-radius: 12px;
    font-family: 'DM Sans', sans-serif; font-size: 0.88rem;
    cursor: pointer; border: none; transition: all 0.2s;
    text-decoration: none; text-align: center; display: block;
  }
  .btn-confirmar { background: var(--red); color: #fff; }
  .btn-confirmar:hover { background: #c04444; }
  .btn-volver { background: #f5f5f5; color: var(--gray); }
  .btn-volver:hover { background: #eee; }
</style>
</head>
<body>

<div class="page-wrapper">

  <div class="header">
    <?php if ($id_Rol == 2): ?>
      <div class="title">Mis <span>Citas</span> <span class="sparkle">✦</span></div>
      <div class="subtitle">Tus citas pendientes ordenadas por fecha</div>
    <?php else: ?>
      <div class="title">Mis <span>Turnos</span> <span class="sparkle">✦</span></div>
      <div class="subtitle">Tus turnos pendientes ordenados por fecha</div>
    <?php endif; ?>
  </div>

  <!-- Mensaje status -->
  <?php if ($mensaje === 'success'): ?>
    <div class="status-msg status-success">✓ Turno cancelado correctamente.</div>
  <?php elseif ($mensaje === 'error'): ?>
    <div class="status-msg status-error">Error al cancelar el turno. Intentá de nuevo.</div>
  <?php endif; ?>

  <!--VISTA TERAPEUTA -->
  <?php if ($id_Rol == 2): ?>

    <?php if (!empty($citas)): ?>
      <?php foreach ($citas as $i => $cita): ?>
        <div class="cita-card" style="animation-delay: <?= $i * 0.06 ?>s;">
          <div class="cita-header">
            <div class="cita-fecha">
              <?= date('d \d\e F \d\e Y', strtotime($cita['Fecha_Turno'])) ?>
            </div>
            <div class="cita-hora"><?= htmlspecialchars($cita['Hora_Turno']) ?> hs</div>
          </div>
          <div class="cita-body">
            <div class="cita-row">
              <span class="cita-label">Paciente</span>
              <span><?= htmlspecialchars($cita['Paciente_Nombre'] . ' ' . $cita['Paciente_Apellido']) ?></span>
            </div>
            <div class="cita-row">
              <span class="cita-label">Servicio</span>
              <span><?= htmlspecialchars($cita['Nombre_servicio']) ?></span>
            </div>
            <div class="cita-row">
              <span class="cita-label">Estado</span>
              <span><?= htmlspecialchars($cita['Estado_Turno']) ?></span>
            </div>
          </div>
        </div>
      <?php endforeach; ?>

    <?php else: ?>
      <div class="empty-state">
        <div class="icono">📋</div>
        <p>No tenés citas pendientes por el momento.</p>
      </div>
    <?php endif; ?>

  <?php endif; ?>

  <!--VISTA PACIENTE -->
  <?php if ($id_Rol == 0): ?>

    <?php if (!empty($turnos)): ?>
      <?php foreach ($turnos as $i => $turno): ?>
        <div class="cita-card" style="animation-delay: <?= $i * 0.06 ?>s;">
          <div class="cita-header">
            <div class="cita-fecha">
              <?= date('d \d\e F \d\e Y', strtotime($turno['Fecha_Turno'])) ?>
            </div>
            <div class="cita-hora"><?= htmlspecialchars($turno['Hora_Turno']) ?> hs</div>
          </div>
          <div class="cita-body">
            <div class="cita-row">
              <span class="cita-label">Terapeuta</span>
              <span><?= htmlspecialchars($turno['Terapeuta_Nombre'] . ' ' . $turno['Terapeuta_Apellido']) ?></span>
            </div>
            <div class="cita-row">
              <span class="cita-label">Servicio</span>
              <span><?= htmlspecialchars($turno['Nombre_servicio']) ?></span>
            </div>
            <div class="cita-row">
              <span class="cita-label">Estado</span>
              <span><?= htmlspecialchars($turno['Estado_Turno']) ?></span>
            </div>
          </div>

          <div class="cita-actions">
            <a href="../Controllers/EditarTurnoController.php?id=<?= $turno['id_Turno'] ?>"
               class="btn-editar">✏️ Modificar</a>
            <button class="btn-cancelar"
                    onclick="abrirModal(<?= $turno['id_Turno'] ?>)">
              🗑 Cancelar
            </button>
          </div>
        </div>
      <?php endforeach; ?>

    <?php else: ?>
      <div class="empty-state">
        <div class="icono">📅</div>
        <p>No tenés turnos pendientes por el momento.</p>
        <a href="../Controllers/TurnoController.php" class="btn-editar" style="display:inline-block; padding:11px 24px;">
          + Agendar un turno
        </a>
      </div>
    <?php endif; ?>

  <?php endif; ?>

  <a href="../index.php" class="back-btn">← Volver al inicio</a>

</div>

<!-- modal cancelar turno -->
<div class="modal-overlay" id="modalCancelar">
  <div class="modal">
    <div class="icono">🗑</div>
    <h3>¿Cancelar turno?</h3>
    <p>Esta acción no se puede deshacer. El turno será eliminado permanentemente.</p>
    <form method="POST" action="../Controllers/CitasController.php">
      <input type="hidden" name="action"   value="cancelar_turno">
      <input type="hidden" name="id_turno" id="inputIdTurno">
      <div class="modal-btns">
        <button type="button" class="btn-volver" onclick="cerrarModal()">Volver</button>
        <button type="submit" class="btn-confirmar">Sí, cancelar</button>
      </div>
    </form>
  </div>
</div>

<script>
  function abrirModal(id) {
    document.getElementById('inputIdTurno').value = id;
    document.getElementById('modalCancelar').classList.add('open');
  }

  function cerrarModal() {
    document.getElementById('modalCancelar').classList.remove('open');
  }

  document.getElementById('modalCancelar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') cerrarModal();
  });
</script>
</body>
</html>