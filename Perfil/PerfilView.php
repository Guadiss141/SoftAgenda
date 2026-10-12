<?php
$id_Rol       = $id_Rol       ?? ($_SESSION['id_Rol'] ?? null);
$persona      = $persona      ?? [];
$usuario_data = $usuario_data ?? [];
$turnos       = $turnos       ?? [];
$citas        = $citas        ?? [];
$mensaje      = $mensaje      ?? '';
$extra        = $extra        ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mi Perfil - SoftAgenda</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --pink: #e879a0;
    --pink-light: #f5b8d0;
    --pink-pale: #fde8f0;
    --red: #e05555;
    --red-pale: #fdeaea;
    --cream: #f7c6d0;
    --dark: #1a1a1a;
    --gray: #888;
    --green-pale: #e9edc9;
    --green-text: #4e5a37;
  }
  * { margin:0; padding:0; box-sizing:border-box; }
  body {
    background: var(--cream);
    font-family: 'DM Sans', sans-serif;
    color: var(--dark);
    padding: 40px 20px;
    transition: background 0.3s, color 0.3s;
  }
  .container { max-width: 700px; margin: 0 auto; }

  .page-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem; font-weight: 900;
    color: var(--dark); letter-spacing: -1px;
    margin-bottom: 6px;
  }
  .page-title span { color: var(--pink); }
  .sparkle {
    display:inline-block; font-size:1.1rem; color:var(--pink);
    animation:sparkle 2s ease-in-out infinite; margin-left:4px;
  }
  @keyframes sparkle {
    0%,100%{opacity:1;transform:scale(1) rotate(0deg);}
    50%{opacity:0.6;transform:scale(1.3) rotate(20deg);}
  }
  .badge-rol {
    display: inline-block; padding: 5px 14px;
    background: var(--pink-pale); color: var(--pink);
    border-radius: 20px; font-size: 0.82rem;
    font-weight: 500; margin-bottom: 28px;
    border: 1px solid var(--pink-light);
  }

  .card {
    background: #fff; border-radius: 24px;
    padding: 24px 26px; margin-bottom: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    transition: background 0.3s;
  }
  .card-title {
    font-family: 'Playfair Display', serif;
    font-size: 1rem; font-weight: 700;
    color: var(--dark); margin-bottom: 16px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f0eee8;
  }

  .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  .info-full  { grid-column: 1 / -1; }
  .info-label {
    font-size: 0.72rem; font-weight: 500;
    color: var(--gray); text-transform: uppercase;
    letter-spacing: 0.5px; display: block; margin-bottom: 4px;
  }
  .info-value { font-size: 0.92rem; color: var(--dark); }
  .info-value.empty { color: #ccc; font-style: italic; }

  .alert { padding: 12px 16px; border-radius: 12px; margin-bottom: 16px; font-size: 0.85rem; }
  .alert-success { background: var(--green-pale); color: var(--green-text); border-left: 4px solid #a3b18a; }
  .alert-danger  { background: var(--red-pale); color: var(--red); border-left: 4px solid var(--red); }

  .edit-card {
    display: none; background: #fff; border-radius: 24px;
    padding: 24px 26px; margin-bottom: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    border-left: 4px solid var(--pink);
    transition: background 0.3s;
  }
  .edit-card.active { display: block; }
  .form-row { display: flex; gap: 14px; }
  .form-group { flex: 1; margin-bottom: 14px; }
  .form-group label {
    display: block; font-size: 0.78rem; font-weight: 500;
    color: var(--gray); text-transform: uppercase;
    letter-spacing: 0.5px; margin-bottom: 6px;
  }
  .form-group input, .form-group textarea {
    width: 100%; padding: 11px 14px;
    border: 1.5px solid #eee; border-radius: 12px;
    font-family: 'DM Sans', sans-serif; font-size: 0.88rem;
    color: var(--dark); outline: none; transition: border 0.2s, background 0.3s, color 0.3s;
    box-sizing: border-box; background: #fff;
  }
  .form-group input:focus, .form-group textarea:focus { border-color: var(--pink-light); }
  .form-group textarea { resize: vertical; min-height: 80px; }
  .form-group input[readonly] { background: var(--cream); color: var(--gray); cursor: not-allowed; }

  /* Estilos de error custom */
  .form-group input.input-error,
  .form-group textarea.input-error {
    border-color: var(--red) !important;
  }
  .field-error {
    display: none;
    font-size: 0.75rem;
    color: var(--red);
    margin-top: 5px;
    padding-left: 2px;
  }
  .field-error.visible { display: block; }

  .btn-pink {
    padding: 11px 22px; background: var(--pink); color: #fff;
    border: none; border-radius: 14px; font-family: 'DM Sans', sans-serif;
    font-size: 0.88rem; font-weight: 500; cursor: pointer;
    text-decoration: none; display: inline-block; transition: all 0.2s;
  }
  .btn-pink:hover { background: #d4638c; transform: translateY(-1px); }
  .btn-ghost {
    padding: 11px 22px; background: #f5f5f5; color: var(--gray);
    border: none; border-radius: 14px; font-family: 'DM Sans', sans-serif;
    font-size: 0.88rem; cursor: pointer; transition: all 0.2s;
  }
  .btn-ghost:hover { background: #eee; }
  .btn-red {
    padding: 11px 22px; background: var(--red-pale); color: var(--red);
    border: 1.5px solid var(--red); border-radius: 14px;
    font-family: 'DM Sans', sans-serif; font-size: 0.88rem;
    cursor: pointer; transition: all 0.2s;
  }
  .btn-red:hover { background: var(--red); color: #fff; }
  .btn-group { display: flex; gap: 10px; margin-top: 16px; }

  .danger-card {
    background: var(--red-pale); border-radius: 24px;
    padding: 22px 26px; margin-bottom: 16px;
    border-left: 4px solid var(--red);
    transition: background 0.3s;
  }
  .danger-card .card-title { color: var(--red); border-bottom-color: #f5c6c2; }
  .danger-card p { font-size: 0.85rem; color: var(--gray); margin-bottom: 14px; }

  .back-btn {
    display: block; text-align: center; padding: 13px;
    background: #fff; color: var(--gray); border-radius: 14px;
    text-decoration: none; font-size: 0.9rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: all 0.2s;
    margin-top: 8px;
  }
  .back-btn:hover { background: #f5f5f5; }

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
  .modal h3 { font-family:'Playfair Display',serif; font-size:1.2rem; color:var(--dark); margin-bottom:8px; }
  .modal p { font-size:0.85rem; color:var(--gray); margin-bottom:22px; line-height:1.5; }
  .modal-btns { display:flex; gap:8px; }
  .modal-btns button {
    flex:1; padding:11px; border-radius:12px;
    font-family:'DM Sans',sans-serif; font-size:0.88rem;
    cursor:pointer; border:none; transition:all 0.2s;
  }
  .btn-confirmar-red { background:var(--red); color:#fff; }
  .btn-confirmar-red:hover { background:#c04444; }
  .btn-volver-gray { background:#f5f5f5; color:var(--gray); }
  .btn-volver-gray:hover { background:#eee; }

  /* ════════════════════════
     TEMA OSCURO
     Se activa agregando la clase .tema-oscuro al <html>
  ════════════════════════ */
  html.tema-oscuro body {
    background: #15151a;
    color: #eee;
  }

  html.tema-oscuro .page-title {
    color: #f2f2f2;
  }

  html.tema-oscuro .badge-rol {
    background: rgba(232,121,160,0.15);
    border-color: rgba(232,121,160,0.3);
  }

  html.tema-oscuro .card,
  html.tema-oscuro .edit-card,
  html.tema-oscuro .modal {
    background: #1e1e24;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
  }

  html.tema-oscuro .card-title {
    color: #f2f2f2;
    border-bottom-color: #2a2a31;
  }

  html.tema-oscuro .info-label {
    color: #999;
  }

  html.tema-oscuro .info-value {
    color: #eee;
  }

  html.tema-oscuro .info-value.empty {
    color: #666;
  }

  html.tema-oscuro .alert-success {
    background: rgba(163,177,138,0.15);
    color: #b9c99e;
    border-left-color: #a3b18a;
  }

  html.tema-oscuro .alert-danger {
    background: rgba(224,85,85,0.15);
    color: #f19d9d;
  }

  html.tema-oscuro .form-group label {
    color: #999;
  }

  html.tema-oscuro .form-group input,
  html.tema-oscuro .form-group textarea {
    background: #26262e;
    border-color: #33333c;
    color: #eee;
  }

  html.tema-oscuro .form-group input[readonly] {
    background: #1a1a1f;
    color: #777;
  }

  html.tema-oscuro .btn-ghost {
    background: #2a2a31;
    color: #ccc;
  }

  html.tema-oscuro .btn-ghost:hover {
    background: #33333c;
  }

  html.tema-oscuro .danger-card {
    background: rgba(224,85,85,0.1);
  }

  html.tema-oscuro .danger-card p {
    color: #aaa;
  }

  html.tema-oscuro .back-btn {
    background: #1e1e24;
    color: #aaa;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
  }

  html.tema-oscuro .back-btn:hover {
    background: #26262e;
  }

  html.tema-oscuro .modal-overlay {
    background: rgba(0,0,0,0.6);
  }

  html.tema-oscuro .modal h3 {
    color: #f2f2f2;
  }

  html.tema-oscuro .modal p {
    color: #aaa;
  }

  html.tema-oscuro .btn-volver-gray {
    background: #2a2a31;
    color: #ccc;
  }

  html.tema-oscuro .btn-volver-gray:hover {
    background: #33333c;
  }
</style>
<?php include __DIR__ . '/../Tema/TemaHead.php'; ?>
</head>
<body>
<div class="container">

  <div class="page-title">
    Mi <span>Perfil</span> <span class="sparkle">✦</span>
  </div>
  <div class="badge-rol">
    <?php
      if    ($id_Rol == 0) echo 'Paciente';
      elseif($id_Rol == 2) echo 'Terapeuta';
      elseif($id_Rol == 3) echo 'Administrador';
      else                 echo 'Personal';
    ?>
  </div>

  <?php if (!empty($mensaje)) echo $mensaje; ?>

  <div class="card" id="view-mode">
    <div class="card-title">Informacion Personal</div>
    <div class="info-grid">

      <div>
        <span class="info-label">Nombre</span>
        <span class="info-value <?= empty($persona['Persona_Nombre']) ? 'empty' : '' ?>">
          <?= !empty($persona['Persona_Nombre']) ? htmlspecialchars($persona['Persona_Nombre']) : 'Sin completar' ?>
        </span>
      </div>

      <div>
        <span class="info-label">Apellido</span>
        <span class="info-value <?= empty($persona['Persona_Apellido']) ? 'empty' : '' ?>">
          <?= !empty($persona['Persona_Apellido']) ? htmlspecialchars($persona['Persona_Apellido']) : 'Sin completar' ?>
        </span>
      </div>

      <div>
        <span class="info-label">Telefono</span>
        <span class="info-value <?= empty($persona['Persona_Telefono']) ? 'empty' : '' ?>">
          <?= !empty($persona['Persona_Telefono']) ? htmlspecialchars($persona['Persona_Telefono']) : 'Sin completar' ?>
        </span>
      </div>

      <div>
        <span class="info-label">DNI</span>
        <span class="info-value <?= empty($persona['Persona_DNI']) ? 'empty' : '' ?>">
          <?= !empty($persona['Persona_DNI']) ? htmlspecialchars($persona['Persona_DNI']) : 'Sin completar' ?>
        </span>
      </div>

      <div>
        <span class="info-label">Fecha de Nacimiento</span>
        <span class="info-value <?= empty($persona['Fecha_Nac']) ? 'empty' : '' ?>">
          <?= !empty($persona['Fecha_Nac']) ? date('d/m/Y', strtotime($persona['Fecha_Nac'])) : 'No especificada' ?>
        </span>
      </div>

      <div>
        <span class="info-label">Correo Electronico</span>
        <span class="info-value"><?= htmlspecialchars($usuario_data['Correo_E'] ?? '') ?></span>
      </div>

      <div class="info-full">
        <span class="info-label">Descripcion personal</span>
        <span class="info-value <?= empty($persona['Persona_Descripcion']) ? 'empty' : '' ?>" style="white-space:pre-wrap;">
          <?= !empty($persona['Persona_Descripcion']) ? htmlspecialchars($persona['Persona_Descripcion']) : 'Sin descripcion' ?>
        </span>
      </div>

      <?php if ($id_Rol == 0 && !empty($extra['Observaciones_Paciente'])): ?>
      <div class="info-full">
        <span class="info-label">Observaciones medicas</span>
        <span class="info-value" style="white-space:pre-wrap;">
          <?= htmlspecialchars($extra['Observaciones_Paciente']) ?>
        </span>
      </div>
      <?php endif; ?>

      <?php if ($id_Rol == 2 && !empty($extra)): ?>
      <div>
        <span class="info-label">Especialidad</span>
        <span class="info-value"><?= htmlspecialchars($extra['Especialidad'] ?? '') ?></span>
      </div>
      <div>
        <span class="info-label">CUIL</span>
        <span class="info-value"><?= htmlspecialchars($extra['CUIL'] ?? '') ?></span>
      </div>
      <?php endif; ?>

    </div>
    <div class="btn-group">
      <button class="btn-pink" onclick="toggleEdit()">Editar Informacion</button>
    </div>
  </div>

  <!-- novalidate desactiva todas las validaciones nativas del browser -->
  <div class="edit-card" id="edit-mode">
    <div class="card-title">Editar Informacion</div>
    <form method="POST" action="perfil.php" id="form-perfil" novalidate>
      <input type="hidden" name="action" value="editar_perfil">

      <div class="form-row">
        <div class="form-group">
          <label>Nombre</label>
          <input type="text" name="nombre" id="campo-nombre"
                 value="<?= htmlspecialchars($persona['Persona_Nombre'] ?? '') ?>">
          <span class="field-error" id="error-nombre">El nombre es obligatorio.</span>
        </div>
        <div class="form-group">
          <label>Apellido</label>
          <input type="text" name="apellido" id="campo-apellido"
                 value="<?= htmlspecialchars($persona['Persona_Apellido'] ?? '') ?>">
          <span class="field-error" id="error-apellido">El apellido es obligatorio.</span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>DNI</label>
          <input type="text" name="dni" id="campo-dni"
                 value="<?= htmlspecialchars($persona['Persona_DNI'] ?? '') ?>"
                 maxlength="8" inputmode="numeric"
                 oninput="this.value=this.value.replace(/[^0-9]/g,'')">
          <span class="field-error" id="error-dni">El DNI debe tener hasta 8 dígitos numéricos.</span>
        </div>
        <div class="form-group">
          <label>Fecha de Nacimiento</label>
          <input type="date" name="fecha_nac" id="campo-fecha"
                 value="<?= htmlspecialchars($persona['Fecha_Nac'] ?? '') ?>">
          <span class="field-error" id="error-fecha">La fecha no puede ser en el futuro.</span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Telefono</label>
          <input type="text" name="telefono" id="campo-telefono"
                 value="<?= htmlspecialchars($persona['Persona_Telefono'] ?? '') ?>"
                 maxlength="10" inputmode="numeric"
                 oninput="this.value=this.value.replace(/[^0-9]/g,'')">
          <span class="field-error" id="error-telefono">El teléfono debe tener exactamente 10 dígitos.</span>
        </div>
        <div class="form-group">
          <label>Correo Electronico</label>
          <input type="text" name="correo" id="campo-correo"
                 value="<?= htmlspecialchars($usuario_data['Correo_E'] ?? '') ?>">
          <span class="field-error" id="error-correo">Ingresá un correo electrónico válido.</span>
        </div>
      </div>

      <div class="form-group">
        <label>Domicilio</label>
        <input type="text" name="domicilio" id="campo-domicilio"
               value="<?= htmlspecialchars($persona['Persona_Domicilio'] ?? '') ?>">
        <span class="field-error" id="error-domicilio"></span>
      </div>

      <div class="form-group">
        <label>Descripcion personal</label>
        <textarea name="descripcion"><?= htmlspecialchars($persona['Persona_Descripcion'] ?? '') ?></textarea>
      </div>

      <?php if ($id_Rol == 0): ?>
      <div class="form-group">
        <label>Observaciones medicas</label>
        <textarea name="observaciones"><?= htmlspecialchars($extra['Observaciones_Paciente'] ?? '') ?></textarea>
      </div>
      <?php endif; ?>

      <?php if ($id_Rol == 2): ?>
      <div class="form-row">
        <div class="form-group">
          <label>Especialidad</label>
          <input type="text" name="especialidad"
                 value="<?= htmlspecialchars($extra['Especialidad'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>CUIL</label>
          <input type="text" name="cuil"
                 value="<?= htmlspecialchars($extra['CUIL'] ?? '') ?>">
        </div>
      </div>
      <div class="form-group">
        <label>CBU</label>
        <input type="text" name="cbu"
               value="<?= htmlspecialchars($extra['Empleado_CBU'] ?? '') ?>">
      </div>
      <?php endif; ?>

      <div class="btn-group">
        <button type="submit" class="btn-pink">Guardar Cambios</button>
        <button type="button" class="btn-ghost" onclick="toggleEdit()">Cancelar</button>
      </div>
    </form>
  </div>

  <div class="danger-card">
    <div class="card-title">Zona de Peligro</div>
    <p>Eliminar tu cuenta es permanente. Se borraran todos tus datos y no podras recuperarlos.</p>
    <button class="btn-red" onclick="document.getElementById('modal-eliminar').classList.add('open')">
      Eliminar mi cuenta
    </button>
  </div>

  <a href="/Spaa/Inicioo/Index.php" class="back-btn">← Volver al inicio</a>

</div>

<div class="modal-overlay" id="modal-eliminar">
  <div class="modal">
    <div class="icono">⚠️</div>
    <h3>Eliminar cuenta</h3>
    <p>Esta accion es permanente. Se borraran todos tus datos del sistema.</p>
    <form method="POST" action="perfil.php">
      <input type="hidden" name="action" value="eliminar_cuenta">
      <div class="modal-btns">
        <button type="button" class="btn-volver-gray"
                onclick="document.getElementById('modal-eliminar').classList.remove('open')">
          Cancelar
        </button>
        <button type="submit" class="btn-confirmar-red">Si, eliminar</button>
      </div>
    </form>
  </div>
</div>

<script>
  function toggleEdit() {
    const viewMode = document.getElementById('view-mode');
    const editMode = document.getElementById('edit-mode');
    viewMode.style.display = viewMode.style.display === 'none' ? 'block' : 'none';
    editMode.classList.toggle('active');
    // Limpiar errores al cancelar
    limpiarErrores();
  }

  document.getElementById('modal-eliminar').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('open');
  });

  // ─── Validación custom ───────────────────────────────────────────

  function mostrarError(campoId, errorId, mensaje) {
    const campo = document.getElementById(campoId);
    const error = document.getElementById(errorId);
    if (!campo || !error) return;
    campo.classList.add('input-error');
    error.textContent = mensaje;
    error.classList.add('visible');
  }

  function limpiarError(campoId, errorId) {
    const campo = document.getElementById(campoId);
    const error = document.getElementById(errorId);
    if (!campo || !error) return;
    campo.classList.remove('input-error');
    error.classList.remove('visible');
  }

  function limpiarErrores() {
    const pares = [
      ['campo-nombre',   'error-nombre'],
      ['campo-apellido', 'error-apellido'],
      ['campo-dni',      'error-dni'],
      ['campo-fecha',    'error-fecha'],
      ['campo-telefono', 'error-telefono'],
      ['campo-correo',   'error-correo'],
    ];
    pares.forEach(([c, e]) => limpiarError(c, e));
  }

  function validarFormulario() {
    let valido = true;

    // Nombre (obligatorio)
    const nombre = document.getElementById('campo-nombre').value.trim();
    if (!nombre) {
      mostrarError('campo-nombre', 'error-nombre', 'El nombre es obligatorio.');
      valido = false;
    } else {
      limpiarError('campo-nombre', 'error-nombre');
    }

    // Apellido (obligatorio)
    const apellido = document.getElementById('campo-apellido').value.trim();
    if (!apellido) {
      mostrarError('campo-apellido', 'error-apellido', 'El apellido es obligatorio.');
      valido = false;
    } else {
      limpiarError('campo-apellido', 'error-apellido');
    }

    // DNI (opcional, pero si tiene algo debe ser numérico de hasta 8 dígitos)
    const dni = document.getElementById('campo-dni').value.trim();
    if (dni && !/^[0-9]{1,8}$/.test(dni)) {
      mostrarError('campo-dni', 'error-dni', 'El DNI debe tener hasta 8 dígitos numéricos.');
      valido = false;
    } else {
      limpiarError('campo-dni', 'error-dni');
    }

    // Fecha de nacimiento (opcional, pero no puede ser en el futuro)
    const fecha = document.getElementById('campo-fecha').value;
    if (fecha) {
      const hoy = new Date().toISOString().split('T')[0];
      if (fecha > hoy) {
        mostrarError('campo-fecha', 'error-fecha', 'La fecha no puede ser en el futuro.');
        valido = false;
      } else {
        limpiarError('campo-fecha', 'error-fecha');
      }
    } else {
      limpiarError('campo-fecha', 'error-fecha');
    }

    // Teléfono (obligatorio, exactamente 10 dígitos)
    const telefono = document.getElementById('campo-telefono').value.trim();
    if (!telefono) {
      mostrarError('campo-telefono', 'error-telefono', 'El teléfono es obligatorio.');
      valido = false;
    } else if (!/^[0-9]{10}$/.test(telefono)) {
      mostrarError('campo-telefono', 'error-telefono', 'El teléfono debe tener exactamente 10 dígitos.');
      valido = false;
    } else {
      limpiarError('campo-telefono', 'error-telefono');
    }

    // Correo (obligatorio, formato válido)
    const correo = document.getElementById('campo-correo').value.trim();
    const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!correo) {
      mostrarError('campo-correo', 'error-correo', 'El correo electrónico es obligatorio.');
      valido = false;
    } else if (!regexCorreo.test(correo)) {
      mostrarError('campo-correo', 'error-correo', 'Ingresá un correo electrónico válido.');
      valido = false;
    } else {
      limpiarError('campo-correo', 'error-correo');
    }

    return valido;
  }

  document.getElementById('form-perfil').addEventListener('submit', function(e) {
    e.preventDefault();
    if (validarFormulario()) {
      this.submit();
    }
  });

  // Limpiar error de un campo cuando el usuario empieza a escribir
  ['campo-nombre', 'campo-apellido', 'campo-dni', 'campo-fecha', 'campo-telefono', 'campo-correo'].forEach(function(id) {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('input', function() {
        const errorId = 'error-' + id.replace('campo-', '');
        limpiarError(id, errorId);
      });
    }
  });
</script>
<?php include __DIR__ . '/../Views/ayuda_footer.php'; ?>

</body>
</html>