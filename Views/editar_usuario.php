<?php
// Seguridad: solo admin
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
    header("Location: Login.php");
    exit();
}

// Cargar datos del usuario a editar
require_once '../Models/Usuario.php';
$conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
if ($conexion->connect_error) die("Error de conexión");

$modelo  = new Usuario($conexion);
$mensaje = '';

if (!isset($_GET['id'])) {
    header("Location: admin_usuarios.php");
    exit();
}

$id   = intval($_GET['id']);
$user = $modelo->obtenerPorId($id);

if (!$user) {
    header("Location: admin_usuarios.php?error=usuario_no_encontrado");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <title>Editar Usuario - SoftAgenda</title>

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
            --gray-pale: #f5f5f5;
            --gray-mid: #ccc;
            --green: #5a9e6f;
            --green-pale: #eaf4ee;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            padding: 0;
            margin: 0;
            background: var(--cream);
            color: var(--dark);
            min-height: 100vh;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 40px;
            border-radius: 32px;
            box-shadow: 0 8px 40px rgba(232,121,160,0.12);
        }

        h1 {
            font-family: 'Playfair Display', serif;
            color: var(--dark);
            font-weight: 900;
            letter-spacing: -0.5px;
            border-bottom: 2px solid var(--pink-pale);
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-group {
            flex: 1;
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 12px;
            border: 1.5px solid #eee;
            background: #fff;
            transition: border-color 0.3s, background 0.3s;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            color: var(--dark);
            outline: none;
        }

        .form-group input:focus {
            border-color: var(--pink-light);
        }

        .form-group input.input-error {
            border-color: var(--red);
            background: #fff8f8;
        }

        /* Mensaje de error debajo del campo */
        .field-error {
            display: none;
            align-items: center;
            gap: 5px;
            margin-top: 5px;
            font-size: 12px;
            color: var(--red);
            font-weight: 500;
        }

        .field-error.visible {
            display: flex;
        }

        .field-error::before {
            content: '';
            display: inline-block;
            width: 14px;
            height: 14px;
            flex-shrink: 0;
            border-radius: 50%;
            background-color: var(--red);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath d='M8 4v5M8 11v1' stroke='white' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
            background-size: 14px;
            background-repeat: no-repeat;
            background-position: center;
        }

        .btn {
            display: inline-block;
            padding: 11px 24px;
            border-radius: 30px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-size: 14px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-primary {
            background: var(--pink);
            color: white;
            box-shadow: 0 4px 6px rgba(232,121,160,0.3);
        }

        .btn-secondary {
            background: var(--gray-pale);
            color: var(--dark);
            border: 1.5px solid var(--gray-mid);
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .button-group {
            margin-top: 25px;
            display: flex;
            gap: 10px;
            justify-content: space-between;
            align-items: center;
        }

        /* ── Toasts ── */
        .toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .toast {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 500;
            min-width: 260px;
            max-width: 340px;
            pointer-events: all;
            animation: toastIn 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards;
        }

        .toast.toast-success {
            background: var(--green-pale);
            color: var(--green);
            border-left: 4px solid var(--green);
            box-shadow: 0 8px 24px rgba(90,158,111,0.15);
        }

        .toast.toast-error {
            background: var(--red-pale);
            color: var(--red);
            border-left: 4px solid var(--red);
            box-shadow: 0 8px 24px rgba(224,85,85,0.15);
        }

        .toast-icon {
            font-size: 18px;
            flex-shrink: 0;
            line-height: 1;
        }

        .toast-msg {
            flex: 1;
            line-height: 1.4;
        }

        .toast-close {
            background: none;
            border: none;
            cursor: pointer;
            color: inherit;
            opacity: 0.45;
            font-size: 18px;
            line-height: 1;
            padding: 0;
            margin-left: 4px;
            transition: opacity 0.2s;
        }

        .toast-close:hover { opacity: 0.8; }

        @keyframes toastIn {
            from { transform: translateX(70px) scale(0.95); opacity: 0; }
            to   { transform: translateX(0)    scale(1);    opacity: 1; }
        }

        @keyframes toastOut {
            to { transform: translateX(70px) scale(0.95); opacity: 0; }
        }

        .toast.removing {
            animation: toastOut 0.25s ease forwards;
        }

    </style>

</head>

<body>

<!-- ── Contenedor de toasts ── -->
<div class="toast-container" id="toastContainer"></div>


<div class="container">

    <h1>Editar Usuario</h1>

    <?php if (!empty($mensaje)) echo $mensaje; ?>

    <!-- novalidate desactiva los popups nativos del navegador -->
    <form action="../Controllers/UsuarioController.php?action=editar" method="POST" id="editForm" novalidate>

        <input type="hidden" name="id_Usuario" value="<?= htmlspecialchars($user['id_Usuario']) ?>">

        <div class="form-row">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre"
                       value="<?= htmlspecialchars($user['Persona_Nombre'] ?? '') ?>"
                       data-label="Nombre" data-required="true">
                <div class="field-error" id="err-nombre"></div>
            </div>
            <div class="form-group">
                <label>Apellido:</label>
                <input type="text" name="apellido"
                       value="<?= htmlspecialchars($user['Persona_Apellido'] ?? '') ?>"
                       data-label="Apellido" data-required="true">
                <div class="field-error" id="err-apellido"></div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Teléfono:</label>
                <input type="text" name="telefono"
                       value="<?= htmlspecialchars($user['Persona_Telefono'] ?? '') ?>"
                       maxlength="10" inputmode="numeric"
                       data-label="Teléfono" data-required="true"
                       data-pattern="[0-9]{10}"
                       data-pattern-msg="Debe tener exactamente 10 dígitos numéricos">
                <div class="field-error" id="err-telefono"></div>
            </div>
            <div class="form-group">
                <label>Domicilio:</label>
                <input type="text" name="domicilio"
                       value="<?= htmlspecialchars($user['Persona_Domicilio'] ?? '') ?>"
                       data-label="Domicilio" data-required="true">
                <div class="field-error" id="err-domicilio"></div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nombre de Usuario:</label>
                <input type="text" name="usuario_nombre"
                       value="<?= htmlspecialchars($user['Usuario_Nombre'] ?? '') ?>"
                       data-label="Nombre de usuario" data-required="true">
                <div class="field-error" id="err-usuario_nombre"></div>
            </div>
            <div class="form-group">
                <label>Correo Electrónico:</label>
                <input type="email" name="correo"
                       value="<?= htmlspecialchars($user['Correo_E'] ?? '') ?>"
                       data-label="Correo electrónico" data-required="true" data-type="email">
                <div class="field-error" id="err-correo"></div>
            </div>
        </div>

        <?php if ($user['id_Rol'] == 2): ?>
        <div class="form-row">
            <div class="form-group">
                <label>Especialidad:</label>
                <input type="text" name="especialidad"
                       value="<?= htmlspecialchars($user['Especialidad'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>CUIL:</label>
                <input type="text" name="cuil"
                       value="<?= htmlspecialchars($user['CUIL'] ?? '') ?>">
            </div>
        </div>
        <?php endif; ?>

        <div class="button-group">
            <a href="../Controllers/admin_usuariosController.php" class="btn btn-secondary">Cancelar y Volver</a>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>

    </form>

</div>

<script>
    /* ══════════════════════════════════════
       TOASTS
    ══════════════════════════════════════ */
    function showToast(msg, type = 'success') {
        const container = document.getElementById('toastContainer');
        const toast     = document.createElement('div');
        const icon      = type === 'success' ? '✓' : '✕';

        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <span class="toast-icon">${icon}</span>
            <span class="toast-msg">${msg}</span>
            <button class="toast-close" aria-label="Cerrar">×</button>
        `;

        toast.querySelector('.toast-close').addEventListener('click', () => removeToast(toast));
        container.appendChild(toast);

        // Auto-eliminar a los 3.5 s
        setTimeout(() => removeToast(toast), 3500);
    }

    function removeToast(toast) {
        if (toast.classList.contains('removing')) return;
        toast.classList.add('removing');
        toast.addEventListener('animationend', () => toast.remove());
    }

    /* ══════════════════════════════════════
       VALIDACIÓN DE CAMPOS
    ══════════════════════════════════════ */
    function validateField(input) {
        const name    = input.name;
        const label   = input.dataset.label || name;
        const errEl   = document.getElementById('err-' + name);
        if (!errEl) return true;

        let msg = '';
        const val = input.value.trim();

        if (input.dataset.required === 'true' && !val) {
            msg = `El campo "${label}" es obligatorio`;
        } else if (input.dataset.type === 'email' && val) {
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)) {
                msg = 'Ingresá un correo electrónico válido';
            }
        } else if (input.dataset.pattern && val) {
            const re = new RegExp('^' + input.dataset.pattern + '$');
            if (!re.test(val)) {
                msg = input.dataset.patternMsg || `Formato inválido para "${label}"`;
            }
        }

        if (msg) {
            input.classList.add('input-error');
            errEl.textContent = msg;
            errEl.classList.add('visible');
            return false;
        } else {
            input.classList.remove('input-error');
            errEl.textContent = '';
            errEl.classList.remove('visible');
            return true;
        }
    }

    /* Validar al salir del campo (blur) y limpiar error mientras escribe */
    document.querySelectorAll('#editForm input[data-label]').forEach(input => {
        input.addEventListener('blur',  () => validateField(input));
        input.addEventListener('input', () => {
            if (input.classList.contains('input-error')) validateField(input);
            // Solo números en teléfono
            if (input.name === 'telefono') {
                input.value = input.value.replace(/[^0-9]/g, '');
            }
        });
    });

    /* ══════════════════════════════════════
       SUBMIT
    ══════════════════════════════════════ */
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const requiredFields = ['nombre', 'apellido', 'telefono', 'domicilio', 'usuario_nombre', 'correo'];
        let allValid = true;

        requiredFields.forEach(name => {
            const input = this.querySelector(`[name="${name}"]`);
            if (input && !validateField(input)) allValid = false;
        });

        if (!allValid) {
            showToast('Por favor corregí los errores antes de continuar', 'error');
            // Hacer scroll al primer campo con error
            const firstError = this.querySelector('.input-error');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        // Si todo está bien, enviar el formulario
        showToast('¡Cambios guardados correctamente!', 'success');
        setTimeout(() => this.submit(), 800); // pequeña pausa para que se vea el toast
    });
</script>

</body>
</html>