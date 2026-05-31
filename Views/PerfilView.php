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
    --cream: #faf8f5;
    --dark: #1a1a1a;
    --gray: #888;
  }
  * { margin:0; padding:0; box-sizing:border-box; }
  body {
    background: url('../img/relax.png') no-repeat center center fixed;
    background-size: cover;
    background-color: var(--cream);
    font-family: 'DM Sans', sans-serif;
    color: var(--dark);
    padding: 40px 20px;
  }
  body::before {
    content:""; position:fixed; inset:0;
    background: rgba(253,251,247,0.88); z-index:-1;
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

  .correo-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--cream); padding: 6px 12px;
    border-radius: 8px; font-size: 0.88rem; color: var(--dark);
  }
  .correo-lock { font-size: 0.75rem; color: var(--gray); }

  .alert { padding: 12px 16px; border-radius: 12px; margin-bottom: 16px; font-size: 0.85rem; }
  .alert-success { background: #e9edc9; color: #4e5a37; border-left: 4px solid #a3b18a; }
  .alert-danger  { background: var(--red-pale); color: var(--red); border-left: 4px solid var(--red); }

  .edit-card {
    display: none; background: #fff; border-radius: 24px;
    padding: 24px 26px; margin-bottom: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    border-left: 4px solid var(--pink);
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
    color: var(--dark); outline: none; transition: border 0.2s;
    box-sizing: border-box; background: #fff;
  }
  .form-group input:focus, .form-group textarea:focus { border-color: var(--pink-light); }
  .form-group textarea { resize: vertical; min-height: 80px; }
  .form-group input[readonly] { background: var(--cream); color: var(--gray); cursor: not-allowed; }

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
</style>
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
        <span class="info-label">Correo Electronico <span class="correo-lock">🔒</span></span>
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

  <div class="edit-card" id="edit-mode">
    <div class="card-title">Editar Informacion</div>
    <form method="POST" action="perfil.php">
      <input type="hidden" name="action" value="editar_perfil">

      <div class="form-row">
        <div class="form-group">
          <label>Nombre</label>
          <input type="text" name="nombre" value="<?= htmlspecialchars($persona['Persona_Nombre'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label>Apellido</label>
          <input type="text" name="apellido" value="<?= htmlspecialchars($persona['Persona_Apellido'] ?? '') ?>" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>DNI</label>
          <input type="text" name="dni" value="<?= htmlspecialchars($persona['Persona_DNI'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>Fecha de Nacimiento</label>
          <input type="date" name="fecha_nac" value="<?= htmlspecialchars($persona['Fecha_Nac'] ?? '') ?>">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Telefono</label>
          <input type="number" name="telefono" value="<?= htmlspecialchars($persona['Persona_Telefono'] ?? '') ?>" required>
        </div>
        <div class="form-group">
          <label>Correo Electronico <span class="correo-lock">🔒</span></label>
          <input type="email" value="<?= htmlspecialchars($usuario_data['Correo_E'] ?? '') ?>" readonly>
        </div>
      </div>

      <div class="form-group">
        <label>Domicilio</label>
        <input type="text" name="domicilio" value="<?= htmlspecialchars($persona['Persona_Domicilio'] ?? '') ?>">
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
          <input type="text" name="especialidad" value="<?= htmlspecialchars($extra['Especialidad'] ?? '') ?>">
        </div>
        <div class="form-group">
          <label>CUIL</label>
          <input type="text" name="cuil" value="<?= htmlspecialchars($extra['CUIL'] ?? '') ?>">
        </div>
      </div>
      <div class="form-group">
        <label>CBU</label>
        <input type="text" name="cbu" value="<?= htmlspecialchars($extra['Empleado_CBU'] ?? '') ?>">
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

  <a href="/Spaa/Views/Index.php" class="back-btn">← Volver al inicio</a>

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
    document.getElementById('view-mode').style.display =
      document.getElementById('view-mode').style.display === 'none' ? 'block' : 'none';
    document.getElementById('edit-mode').classList.toggle('active');
  }

  document.getElementById('modal-eliminar').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('open');
  });
</script>
</body>
</html>
