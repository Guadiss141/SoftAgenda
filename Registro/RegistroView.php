<?php
// Modo inicial: ?modo=registro abre directo en "Crear cuenta"
$modo_inicial = (($_GET['modo'] ?? '') === 'registro') ? 'registro' : 'login';

// Errores
$hay_error_login    = !empty($_GET['error']) || !empty($error);
$error_registro_msg = $_GET['error_registro'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Beauty SPA</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href="https://fonts.googleapis.com/css2?family=Lobster&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

<style>
:root {
    --ink: #3f2b25;
    --muted: #8a6f6c;
    --gap: 40px;
    --dx: 500px; /* lo recalcula JS: distancia que recorre cada lado */
    --mov: 0.75s cubic-bezier(0.65, 0, 0.35, 1);
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    min-height: 100vh;
    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    background: linear-gradient(160deg, #f4d6d9 0%, #e0c3b5 100%) fixed;
    display: grid;
    place-items: center;
    padding: 24px;
    overflow-x: hidden;
}

/* Logo (vuelve al inicio) */
.header { position: absolute; top: 0; left: 0; padding: 22px 32px; }
.logo {
    font-family: 'Lobster', cursive;
    font-size: 1.7rem;
    color: #835e5d;
    text-decoration: none;
}
.logo:hover { text-decoration: underline; }

.login {
    width: 100%;
    max-width: 960px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    align-items: center;
    gap: var(--gap);
}

/* ── Ilustración ── */
.ilustracion {
    display: grid; place-items: center;
    transition: transform var(--mov);
}
.ilustracion svg { width: 100%; max-width: 420px; height: auto; }

/* ── Tarjeta (se desliza de lado) ── */
.panel {
    --ink: #fbf4f1;
    --muted: #f3e1d9;
    --line: rgba(251, 244, 241, 0.28);
    --rose-deep: #f0c7cb;
    max-width: 420px; width: 100%; margin: 0 auto;
    background: linear-gradient(160deg, #a07b79 0%, #956f6d 50%, #8a6563 100%);
    color: var(--ink);
    border-radius: 28px;
    padding: 36px 32px 28px;
    box-shadow: 0 24px 60px rgba(149, 111, 109, 0.4);
    transition: transform var(--mov);
    position: relative;
    z-index: 2;
}

/* Modo registro: se intercambian de lugar */
.login.modo-registro .panel       { transform: translateX(calc(var(--dx) * -1)); }
.login.modo-registro .ilustracion { transform: translateX(var(--dx)); }

/* ── Formularios apilados con cambio suave ── */
.forms { position: relative; transition: height 0.5s ease; }
.form-vista {
    position: absolute; top: 0; left: 0; width: 100%;
    opacity: 0; visibility: hidden;
    transform: translateY(8px);
    transition: opacity 0.35s ease, transform 0.35s ease, visibility 0s linear 0.35s;
}
.form-vista.activa {
    opacity: 1; visibility: visible; transform: none;
    transition: opacity 0.4s ease 0.3s, transform 0.4s ease 0.3s, visibility 0s;
}

.panel h1 {
    font-family: 'Lobster', cursive;
    font-size: 2.5rem; font-weight: 400;
    color: var(--rose-deep);
    text-align: center; line-height: 1.3;
}
.panel .sub {
    text-align: center; font-size: 0.9rem; color: var(--muted);
    margin: 4px 0 24px;
}

.campo { margin-bottom: 16px; }
.campo label {
    display: block; font-size: 0.85rem; font-weight: 500;
    color: var(--ink); margin-bottom: 6px;
}

.input-caja {
    display: flex; align-items: stretch;
    background: rgba(255, 255, 255, 0.08);
    border: 1.5px solid var(--line);
    border-radius: 12px;
    overflow: hidden;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.input-caja:focus-within {
    border-color: #f0c7cb;
    box-shadow: 0 0 0 4px rgba(240, 199, 203, 0.25);
}
.input-caja.input-error {
    border-color: #ffa3a3;
    box-shadow: 0 0 0 4px rgba(255, 163, 163, 0.2);
}
.input-caja input {
    flex: 1; min-width: 0;
    border: none; outline: none; background: transparent;
    padding: 13px 14px;
    font-family: inherit; font-size: 0.92rem; color: var(--ink);
}
.input-caja input::placeholder { color: #e0c9c4; }

.input-caja input:-webkit-autofill,
.input-caja input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 100px #a07c7a inset;
    -webkit-text-fill-color: #fbf4f1;
    caret-color: #fbf4f1;
}

.input-caja .icono {
    width: 48px; flex-shrink: 0;
    display: grid; place-items: center;
    border-left: 1.5px solid var(--line);
    background: rgba(0, 0, 0, 0.14);
    color: var(--rose-deep);
    font-size: 1.15rem;
}


.input-caja .icono svg { width: 18px; height: 18px; }
.input-caja.error { border-color: #ffa3a3; box-shadow: 0 0 0 4px rgba(255, 163, 163, 0.2); }
.mensaje-error { display: none; margin-top: 5px; font-size: 0.78rem; color: #ffd0d0; font-weight: 500; }
.mensaje-error.visible { display: block; }

/* Requisitos de contraseña */
.reglas {
    list-style: none;
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 6px 14px;
    margin: 10px 0 0;
    font-size: 0.78rem; color: var(--muted);
}
.reglas li { display: flex; align-items: flex-start; gap: 8px; line-height: 1.3; }
.reglas li::before {
    content: ''; flex-shrink: 0;
    width: 12px; height: 12px; margin-top: 2px;
    border: 1.5px solid var(--muted); border-radius: 50%;
    transition: background 0.2s, border-color 0.2s;
}
.reglas li.ok { color: #fff; }
.reglas li.ok::before { background: #b9e4c2; border-color: #b9e4c2; }

/* Botón */
.btn {
    width: 100%; margin-top: 6px;
    padding: 14px;
    background: #f0c7cb; color: #5a3a3a;
    border: none; border-radius: 999px;
    font-family: inherit; font-size: 0.95rem; font-weight: 700;
    cursor: pointer;
    transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
}
.btn:hover {
    background: #fbf4f1;
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(40, 24, 24, 0.3);
}
.btn:focus-visible { outline: 3px solid #fbf4f1; outline-offset: 2px; }

/* Enlaces */
.enlaces {
    margin-top: 20px; padding-top: 18px;
    border-top: 1px solid var(--line);
    display: flex; flex-direction: column; align-items: center; gap: 8px;
    font-size: 0.88rem; color: var(--muted);
}
.enlaces a { color: var(--rose-deep); font-weight: 700; text-decoration: none; }
.enlaces a:hover { text-decoration: underline; }
.enlaces a.secundario { font-weight: 500; color: var(--muted); }

/* ====== TOAST ====== */
.toast-container {
    position: fixed; top: 24px; left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    display: flex; flex-direction: column; align-items: center; gap: 10px;
    pointer-events: none;
}
.toast {
    position: relative; overflow: hidden;
    display: flex; align-items: center; gap: 12px;
    padding: 14px 22px;
    border-radius: 50px;
    font-size: 0.9rem; font-weight: 500;
    color: #fff;
    box-shadow: 0 8px 32px rgba(63, 43, 37, 0.35);
    pointer-events: all;
    animation: toastIn 0.35s ease forwards;
    max-width: 420px; text-align: center;
}
.toast.toast-error   { background: rgba(168, 76, 84, 0.96); }
.toast.toast-warning { background: rgba(176, 122, 78, 0.96); }
.toast-icon { font-size: 1.2rem; flex-shrink: 0; }
.toast-text { flex: 1; }
.toast.hide { animation: toastOut 0.4s ease forwards; }

@keyframes toastIn {
    from { opacity: 0; transform: translateY(-16px) scale(0.95); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes toastOut {
    from { opacity: 1; transform: translateY(0) scale(1); }
    to   { opacity: 0; transform: translateY(-12px) scale(0.95); }
}
.toast-bar {
    position: absolute; bottom: 0; left: 0;
    height: 3px; border-radius: 0 0 50px 50px;
    background: rgba(255,255,255,0.5);
    animation: toastBar 4s linear forwards;
}
@keyframes toastBar { from { width: 100%; } to { width: 0%; } }

/* ── Mobile: sin intercambio de lados, solo cambia el formulario ── */
@media (max-width: 800px) {
    body { padding: 80px 16px 24px; }
    .header { padding: 18px 20px; }
    .login { grid-template-columns: 1fr; gap: 8px; }
    .ilustracion svg { max-width: 190px; }
    .reglas { grid-template-columns: 1fr; }
    .login.modo-registro .panel,
    .login.modo-registro .ilustracion { transform: none; }
}
@media (prefers-reduced-motion: reduce) {
    * { transition: none !important; animation: none !important; }
}
</style>
</head>

<body>

<header class="header">
    <a href="../Inicioo/Index.php" class="logo">Beauty SPA</a>
</header>

<!-- Toast container -->
<div class="toast-container" id="toast-container"></div>

<main class="login <?php echo $modo_inicial === 'registro' ? 'modo-registro' : ''; ?>" id="login">

    <!-- Ilustración -->
    <div class="ilustracion" aria-hidden="true">
<svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
      <!-- Mancha de fondo -->
      <path d="M70 190 C58 105 135 55 225 68 C315 80 358 135 345 220 C333 305 272 352 190 347 C108 342 82 280 70 190 Z" fill="#b47878"/>
      <!-- Hojas y detalles -->
      <ellipse cx="96" cy="120" rx="16" ry="7" transform="rotate(-35 96 120)" fill="#835e5d"/>
      <ellipse cx="112" cy="100" rx="12" ry="5" transform="rotate(-70 112 100)" fill="#835e5d" opacity="0.7"/>
      <ellipse cx="318" cy="270" rx="16" ry="7" transform="rotate(30 318 270)" fill="#835e5d"/>
      <ellipse cx="304" cy="292" rx="12" ry="5" transform="rotate(65 304 292)" fill="#835e5d" opacity="0.7"/>
      <circle cx="300" cy="115" r="5" fill="#b47878"/>
      <circle cx="322" cy="140" r="3" fill="#b47878"/>
      <circle cx="92" cy="260" r="4" fill="#b47878"/>

      <!-- Piernas cruzadas -->
      <path d="M108 305 C118 272 160 262 200 266 C240 262 282 272 292 305 C282 328 240 332 200 328 C160 332 118 328 108 305 Z" fill="#e0c3b5"/>
      <path d="M130 312 C160 298 200 304 232 314" stroke="#b47878" stroke-width="3" fill="none" stroke-linecap="round"/>
      <path d="M170 322 C200 312 240 312 270 318" stroke="#b47878" stroke-width="3" fill="none" stroke-linecap="round"/>

      <!-- Torso -->
      <path d="M158 205 L242 205 L254 278 C232 288 168 288 146 278 Z" fill="#f0c7cb"/>
      <path d="M200 256 C185 245 183 231 192 228 C196 227 200 230 200 233 C200 230 204 227 208 228 C217 231 215 245 200 256 Z" fill="#b47878"/>

      <!-- Brazos -->
      <path d="M160 218 C142 246 134 272 140 294" stroke="#f6cdb0" stroke-width="15" fill="none" stroke-linecap="round"/>
      <path d="M240 218 C258 246 266 272 260 294" stroke="#f6cdb0" stroke-width="15" fill="none" stroke-linecap="round"/>
      <circle cx="140" cy="296" r="9" fill="#f6cdb0"/>
      <circle cx="260" cy="296" r="9" fill="#f6cdb0"/>

      <!-- Cuello y cabeza -->
      <rect x="192" y="186" width="16" height="24" rx="6" fill="#f2c4a4"/>
      <ellipse cx="200" cy="160" rx="35" ry="37" fill="#5a3a3a"/>
      <circle cx="200" cy="168" r="27" fill="#f6cdb0"/>
      <path d="M172 158 C176 134 214 128 230 148 C216 144 196 146 172 158 Z" fill="#5a3a3a"/>
      <!-- Cara -->
      <path d="M188 168 q5 4 10 0" stroke="#7a4a3a" stroke-width="2.2" fill="none" stroke-linecap="round"/>
      <path d="M204 168 q5 4 10 0" stroke="#7a4a3a" stroke-width="2.2" fill="none" stroke-linecap="round"/>
      <path d="M195 180 q5 4 10 0" stroke="#b47878" stroke-width="2.2" fill="none" stroke-linecap="round"/>
      <circle cx="184" cy="176" r="4" fill="#e8a5a5" opacity="0.8"/>
      <circle cx="216" cy="176" r="4" fill="#e8a5a5" opacity="0.8"/>
    </svg>
    </div>

    <!-- Panel con los dos formularios -->
    <section class="panel">
        <div class="forms" id="forms">

            <!-- ===== LOGIN ===== -->
            <form class="form-vista <?php echo $modo_inicial === 'login' ? 'activa' : ''; ?>"
                  id="login-form" data-vista="login"
                  action="../Controllers/UsuarioController.php?action=login" method="POST" novalidate>

                <h1>Hola de nuevo</h1>
                <p class="sub">Ingresá para ver y reservar tus turnos</p>

                <div class="campo">
                    <label for="input-usuario">Usuario o correo</label>
                    <div class="input-caja">
                        <input type="text" name="usuario_o_correo" id="input-usuario"
                               placeholder="Escribí tu usuario o correo" autocomplete="username"
                               value="<?php echo htmlspecialchars($_POST['usuario_o_correo'] ?? ''); ?>">
                        <span class="icono"><i class='bx bx-user'></i></span>
                    </div>
                </div>

                <div class="campo">
                    <label for="input-pass">Contraseña</label>
                    <div class="input-caja">
                        <input type="password" name="contraseña" id="input-pass"
                               placeholder="Escribí tu contraseña" autocomplete="current-password">
                        <span class="icono"><i class='bx bx-lock-alt'></i></span>
                    </div>
                </div>

                <button type="submit" class="btn">Iniciar sesión</button>

                <div class="enlaces">
                    <span>¿No tenés una cuenta? <a href="../Registro/Registro.php" data-ir="registro">Registrarse</a></span>
                    <a href="../NuevaContrasena/recuperar.php" class="secundario">¿Olvidaste tu contraseña?</a>
                </div>
            </form>

            <!-- ===== REGISTRO ===== -->
            <!-- Ajustá el action al de tu controlador. Los name= son los de tu diseño -->
            <form class="form-vista <?php echo $modo_inicial === 'registro' ? 'activa' : ''; ?>"
                  id="registro-form" data-vista="registro"
                  action="../Controllers/UsuarioController.php?action=registrar" method="POST" novalidate>

                <h1>Crear cuenta</h1>
                <p class="sub">Registrate para reservar tus turnos</p>

                <div class="campo">
                    <label for="usuario">Nombre de usuario</label>
                    <div class="input-caja">
                        <input type="text" id="usuario" name="usuario" placeholder="Escribí tu nombre de usuario" autocomplete="username">
                        <span class="icono"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm0 2c-4.4 0-8 2.2-8 5v1h16v-1c0-2.8-3.6-5-8-5Z"/></svg></span>
                    </div>
                    <div class="mensaje-error" id="err-usuario">Ingresá un nombre de usuario</div>
                </div>

                <div class="campo">
                    <label for="email">Correo electrónico</label>
                    <div class="input-caja">
                        <input type="email" id="email" name="email" placeholder="Escribí tu correo" autocomplete="email">
                        <span class="icono"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 5h18a1 1 0 0 1 1 1v.5l-10 6.2L2 6.5V6a1 1 0 0 1 1-1Zm-1 4.1V18a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V9.1l-9.5 5.9a1 1 0 0 1-1 0L2 9.1Z"/></svg></span>
                    </div>
                    <div class="mensaje-error" id="err-email">Ingresá un correo válido</div>
                </div>

                <div class="campo">
                    <label for="password">Contraseña</label>
                    <div class="input-caja">
                        <input type="password" id="password" name="password" placeholder="Creá una contraseña" autocomplete="new-password">
                        <span class="icono"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 9h-1V7a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-7-2a2 2 0 0 1 4 0v2h-4V7Z"/></svg></span>
                    </div>
                    <ul class="reglas" id="requisitos">
                        <li data-req="largo">De 8 a 20 caracteres</li>
                        <li data-req="mayus">Al menos 1 mayúscula</li>
                        <li data-req="numero">Al menos 1 número</li>
                        <li data-req="especial">Al menos 1 carácter especial</li>
                    </ul>
                    <div class="mensaje-error" id="err-password">La contraseña no cumple los requisitos</div>
                </div>

                <div class="campo">
                    <label for="confirmar">Repetir contraseña</label>
                    <div class="input-caja">
                        <input type="password" id="confirmar" name="confirmar" placeholder="Repetí tu contraseña" autocomplete="new-password">
                        <span class="icono"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M17 9h-1V7a4 4 0 0 0-8 0v2H7a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2Zm-7-2a2 2 0 0 1 4 0v2h-4V7Z"/></svg></span>
                    </div>
                    <div class="mensaje-error" id="err-confirmar">Las contraseñas no coinciden</div>
                </div>

                <button type="submit" class="btn">Crear cuenta</button>

                <div class="enlaces">
                    <span>¿Ya tenés cuenta? <a href="#login" data-ir="login">Iniciar sesión</a></span>
                    <a href="../Inicioo/Index.php" class="secundario">Volver al inicio</a>
                </div>
            </form>

        </div>
    </section>

</main>

<script>
// ===== Toast system =====
function mostrarToast(mensaje, tipo) {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = 'toast toast-' + tipo;

    const iconos = { error: '❌', warning: '⚠️' };

    toast.innerHTML = `
        <span class="toast-icon">${iconos[tipo] || '⚠️'}</span>
        <span class="toast-text"></span>
        <div class="toast-bar"></div>
    `;
    toast.querySelector('.toast-text').textContent = mensaje; // textContent: evita inyectar HTML
    container.appendChild(toast);

    setTimeout(function() {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 400);
    }, 4000);
}

// ===== Errores desde PHP =====
<?php if ($hay_error_login && $modo_inicial === 'login'): ?>
window.addEventListener('load', function() {
    mostrarToast('Usuario o contraseña incorrectos.', 'error');
});
<?php endif; ?>
<?php if ($error_registro_msg !== ''): ?>
window.addEventListener('load', function() {
    mostrarToast(<?php echo json_encode($error_registro_msg); ?>, 'error');
});
<?php endif; ?>

// ===== Cambio login <-> registro =====
const login      = document.getElementById('login');
const forms      = document.getElementById('forms');
const vistas     = document.querySelectorAll('.form-vista');
const GAP        = 40;
const esMobile   = () => window.matchMedia('(max-width: 800px)').matches;

// Distancia que recorre cada lado = (ancho total + gap) / 2
function calcularDx() {
    login.style.setProperty('--dx', ((login.clientWidth + GAP) / 2) + 'px');
}

// El panel crece/se achica según el formulario activo
function ajustarAltura() {
    const activa = forms.querySelector('.form-vista.activa');
    forms.style.height = activa.offsetHeight + 'px';
}

function irA(modo, pushHash) {
    const esRegistro = modo === 'registro';
    login.classList.toggle('modo-registro', esRegistro);

    vistas.forEach(v => {
        const activa = v.dataset.vista === modo;
        v.classList.toggle('activa', activa);
        // inert: el formulario oculto no recibe foco con Tab
        if (activa) v.removeAttribute('inert'); else v.setAttribute('inert', '');
    });

    document.title = esRegistro ? 'Registro - Beauty SPA' : 'Login - Beauty SPA';
    ajustarAltura();
    if (pushHash) history.replaceState(null, '', esRegistro ? '#registro' : '#login');
}

document.querySelectorAll('[data-ir]').forEach(a => {
    a.addEventListener('click', function(e) {
        e.preventDefault();
        irA(this.dataset.ir, true);
    });
});

// Estado inicial (PHP o hash de la URL)
(function init() {
    let modo = '<?php echo $modo_inicial; ?>';
    if (location.hash === '#registro') modo = 'registro';
    calcularDx();
    // sin animación en la carga inicial
    login.querySelectorAll('.panel, .ilustracion').forEach(el => el.style.transition = 'none');
    irA(modo, false);
    requestAnimationFrame(() => requestAnimationFrame(() => {
        login.querySelectorAll('.panel, .ilustracion').forEach(el => el.style.transition = '');
    }));
})();

window.addEventListener('resize', function() { calcularDx(); ajustarAltura(); });
window.addEventListener('load', ajustarAltura); // por si las fuentes cambian la altura
if (window.ResizeObserver) {
    const ro = new ResizeObserver(ajustarAltura);
    vistas.forEach(v => ro.observe(v));
}

// ===== Helpers de error =====
function marcarError(input)  { input.closest('.input-caja').classList.add('input-error'); }
function limpiarError(input) { input.closest('.input-caja').classList.remove('input-error'); }

// ===== Validación LOGIN =====
const inputUsuario = document.getElementById('input-usuario');
const inputPass    = document.getElementById('input-pass');

document.getElementById('login-form').addEventListener('submit', function(e) {
    limpiarError(inputUsuario);
    limpiarError(inputPass);

    if (!inputUsuario.value.trim()) {
        e.preventDefault();
        marcarError(inputUsuario);
        mostrarToast('Ingresá tu usuario o correo electrónico.', 'warning');
        inputUsuario.focus();
        return;
    }
    if (!inputPass.value.trim()) {
        e.preventDefault();
        marcarError(inputPass);
        mostrarToast('Ingresá tu contraseña.', 'warning');
        inputPass.focus();
    }
});
inputUsuario.addEventListener('input', function() { limpiarError(this); });
inputPass.addEventListener('input', function() { limpiarError(this); });

// ===== Validación REGISTRO =====
const regForm   = document.getElementById('registro-form');
const usuario   = document.getElementById('usuario');
const email     = document.getElementById('email');
const password  = document.getElementById('password');
const confirmar = document.getElementById('confirmar');

const reglas = {
    largo:    v => v.length >= 8 && v.length <= 20,
    mayus:    v => /[A-Z]/.test(v),
    numero:   v => /\d/.test(v),
    especial: v => /[^A-Za-z0-9]/.test(v)
};

password.addEventListener('input', function() {
    document.querySelectorAll('#requisitos li').forEach(li => {
        li.classList.toggle('ok', reglas[li.dataset.req](password.value));
    });
});

function marcar(input, idError, malo) {
    input.closest('.input-caja').classList.toggle('error', malo);
    document.getElementById(idError).classList.toggle('visible', malo);
    return !malo;
}

regForm.addEventListener('submit', function(e) {
    const emailOk = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim());
    const passOk  = Object.values(reglas).every(fn => fn(password.value));

    const resultados = [
        marcar(usuario,   'err-usuario',   usuario.value.trim() === ''),
        marcar(email,     'err-email',     !emailOk),
        marcar(password,  'err-password',  !passOk),
        marcar(confirmar, 'err-confirmar', confirmar.value !== password.value || confirmar.value === '')
    ];

    ajustarAltura(); // los mensajes inline cambian la altura de la tarjeta
    if (resultados.includes(false)) e.preventDefault();
});
</script>

</body>
</html>