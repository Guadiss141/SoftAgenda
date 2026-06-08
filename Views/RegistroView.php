<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Beauty SPA</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<style>

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: url('img/cascada3.jpg') no-repeat;
  background-size: cover;
}

body::before {
  content: "";
  position: absolute;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.4);
  z-index: 0;
}

.container {
  position: relative;
  z-index: 1;
  width: 420px;
  background: transparent;
  border: 2px solid rgba(255, 255, 255, .2);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  box-shadow: 0 0 10px rgba(0, 0, 0, .2);
  color: #fff;
  border-radius: 10px;
  padding: 30px 40px;
  text-align: center;
}

.container h1 {
  font-size: 36px;
  margin-bottom: 10px;
}

.container input {
  width: 100%;
  height: 50px;
  background: transparent;
  border-radius: 40px;
  outline: none;
  font-size: 16px;
  color: #fff;
  padding: 20px;
  margin: 12px 0 0;
  border: 2px solid rgba(255, 255, 255, 0.3);
  transition: border 0.3s;
}

.container input::placeholder {
  color: #ddd;
}

.container input.input-error {
  border: 2px solid #ff7675;
  box-shadow: 0 0 8px rgba(255,118,117,0.4);
}

.container .btn {
  width: 100%;
  height: 45px;
  background: #fff;
  border: none;
  outline: none;
  border-radius: 40px;
  box-shadow: 0 0 10px rgba(0, 0, 0, .1);
  cursor: pointer;
  font-size: 16px;
  color: #333;
  font-weight: 600;
  margin-top: 18px;
  transition: all 0.3s;
}

.container .btn:hover {
  background: #f0f0f0;
  transform: translateY(-1px);
}

a {
  text-decoration: none;
  color: #fff;
  font-size: 14px;
  display: block;
  margin-top: 10px;
  opacity: 0.85;
  transition: opacity 0.2s;
}

a:hover {
  text-decoration: underline;
  opacity: 1;
}

.mensaje {
  font-size: 13px;
  margin-bottom: 6px;
  text-align: center;
  padding: 8px 14px;
  border-radius: 20px;
  background: rgba(255,118,117,0.25);
  border: 1px solid rgba(255,118,117,0.4);
  color: #ffdede;
}

.input-group {
    position: relative;
    margin-bottom: 2px;
}

.input-group i {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-30%);
    font-size: 20px;
    color: rgba(255,255,255,0.7);
}

/* Indicador de contraseña */
.pass-hint {
  font-size: 0.75rem;
  color: rgba(255,255,255,0.6);
  text-align: left;
  margin: 5px 4px 10px;
  line-height: 1.5;
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.pass-hint span {
  display: flex;
  align-items: center;
  gap: 6px;
  transition: color 0.3s;
}

.pass-hint span.ok {
  color: #55efc4;
}

.pass-hint span::before {
  content: "○";
  font-size: 0.7rem;
}

.pass-hint span.ok::before {
  content: "✓";
}

/* Modal de validación */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.65);
    justify-content: center;
    align-items: center;
    z-index: 3000;
    backdrop-filter: blur(4px);
}

.modal-box {
    background: #fff;
    padding: 36px 30px;
    border-radius: 20px;
    width: 90%;
    max-width: 360px;
    text-align: center;
    animation: popIn 0.25s ease;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

@keyframes popIn {
    from { opacity: 0; transform: scale(0.9); }
    to   { opacity: 1; transform: scale(1); }
}

.modal-icon { font-size: 2rem; margin-bottom: 8px; }
.modal-title { font-size: 1.1rem; font-weight: 700; color: #1a1a1a; margin-bottom: 8px; }
.modal-msg { font-size: 0.87rem; color: #555; line-height: 1.5; margin-bottom: 20px; }

.btn-modal-ok {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 30px;
    background: #e879a0;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: opacity 0.3s;
}

.btn-modal-ok:hover { opacity: 0.88; }

</style>

<body>

<div class="container">

    <h1>Crear Cuenta</h1>

    <?php if (!empty($mensaje)): ?>
        <p class="mensaje"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form action="Registro.php" method="POST" id="form-registro" novalidate>

        <div class="input-group">
            <input type="text" name="nombre_usuario" id="inp-nombre" placeholder="Nombre de usuario">
            <i class='bx bxs-user'></i>
        </div>

        <div class="input-group">
            <input type="email" name="correo_usuario" id="inp-correo" placeholder="Correo electrónico">
            <i class='bx bxs-envelope'></i>
        </div>

        <div class="input-group">
            <input type="password" id="inp-pass" name="contraseña" placeholder="Contraseña">
            <i class='bx bxs-lock-alt'></i>
        </div>

        <!-- Indicadores en tiempo real -->
        <div class="pass-hint" id="pass-hint">
            <span id="hint-len">De 8 a 20 caracteres</span>
            <span id="hint-alpha">Solo letras y números (sin espacios)</span>
            <span id="hint-letra">Al menos 1 letra</span>
            <span id="hint-num">Al menos 1 número</span>
        </div>

        <div class="input-group">
            <input type="password" id="inp-confirm" name="confirmar_contraseña" placeholder="Confirmar contraseña">
            <i class='bx bxs-lock'></i>
        </div>

        <button type="submit" class="btn">Registrar</button>
    </form>

    <a href="Login.php">Ya tengo cuenta</a>
    <a href="Index.php">Volver al inicio</a>

</div>

<!-- Modal de error -->
<div id="modal-val" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-icon">⚠️</div>
        <div class="modal-title" id="modal-titulo">Atención</div>
        <p class="modal-msg" id="modal-msg">Revisá los campos.</p>
        <button class="btn-modal-ok" onclick="cerrarModal()">Entendido</button>
    </div>
</div>

<script>
function mostrarModal(titulo, msg) {
    document.getElementById('modal-titulo').textContent = titulo;
    document.getElementById('modal-msg').textContent = msg;
    document.getElementById('modal-val').style.display = 'flex';
}

function cerrarModal() {
    document.getElementById('modal-val').style.display = 'none';
}

document.getElementById('modal-val').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

// Indicadores en tiempo real
const inpPass = document.getElementById('inp-pass');
const hintLen   = document.getElementById('hint-len');
const hintAlpha = document.getElementById('hint-alpha');
const hintLetra = document.getElementById('hint-letra');
const hintNum   = document.getElementById('hint-num');

inpPass.addEventListener('input', function() {
    const val = this.value;
    hintLen.classList.toggle('ok',   val.length >= 8 && val.length <= 20);
    hintAlpha.classList.toggle('ok', val.length > 0 && /^[a-zA-Z0-9]+$/.test(val));
    hintLetra.classList.toggle('ok', /[a-zA-Z]/.test(val));
    hintNum.classList.toggle('ok',   /[0-9]/.test(val));
});

// Validación al enviar
document.getElementById('form-registro').addEventListener('submit', function(e) {
    const nombre  = document.getElementById('inp-nombre').value.trim();
    const correo  = document.getElementById('inp-correo').value.trim();
    const pass    = document.getElementById('inp-pass').value;
    const confirm = document.getElementById('inp-confirm').value;

    // Limpiar errores visuales
    ['inp-nombre','inp-correo','inp-pass','inp-confirm'].forEach(id => {
        document.getElementById(id).classList.remove('input-error');
    });

    if (!nombre) {
        e.preventDefault();
        document.getElementById('inp-nombre').classList.add('input-error');
        mostrarModal('Campo requerido', 'Ingresá tu nombre de usuario.');
        return;
    }

    if (!correo || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)) {
        e.preventDefault();
        document.getElementById('inp-correo').classList.add('input-error');
        mostrarModal('Correo inválido', 'Ingresá un correo electrónico válido (ej: nombre@dominio.com).');
        return;
    }

    if (pass.length < 8 || pass.length > 20) {
        e.preventDefault();
        document.getElementById('inp-pass').classList.add('input-error');
        mostrarModal('Longitud inválida', 'La contraseña debe tener entre 8 y 20 caracteres.');
        return;
    }

    if (!/^[a-zA-Z0-9]+$/.test(pass)) {
        e.preventDefault();
        document.getElementById('inp-pass').classList.add('input-error');
        mostrarModal('Caracteres no permitidos', 'La contraseña solo puede contener letras y números, sin espacios ni símbolos especiales.');
        return;
    }

    if (!/[a-zA-Z]/.test(pass)) {
        e.preventDefault();
        document.getElementById('inp-pass').classList.add('input-error');
        mostrarModal('Contraseña inválida', 'La contraseña debe contener al menos una letra.');
        return;
    }

    if (!/[0-9]/.test(pass)) {
        e.preventDefault();
        document.getElementById('inp-pass').classList.add('input-error');
        mostrarModal('Contraseña inválida', 'La contraseña debe contener al menos un número (0-9).');
        return;
    }

    if (pass !== confirm) {
        e.preventDefault();
        document.getElementById('inp-confirm').classList.add('input-error');
        mostrarModal('Contraseñas no coinciden', 'Las contraseñas ingresadas no son iguales. Verificalas.');
        return;
    }
});

// Limpiar error al escribir
document.querySelectorAll('input').forEach(inp => {
    inp.addEventListener('input', () => inp.classList.remove('input-error'));
});
</script>

</body>
</html>