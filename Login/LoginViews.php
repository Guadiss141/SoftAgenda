<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login - Beauty SPA</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

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
    background: url('../Views/img/cascada.jpg') no-repeat center;
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

.header {
    position: absolute;
    top: 0;
    width: 100%;
    padding: 20px 100px;
    display: flex;
    justify-content: space-between;
    z-index: 1;
}

.logo {
    color: #fff;
    font-size: 25px;
    text-decoration: none;
    font-weight: 600;
}

.wrapper {
    position: relative;
    width: 420px;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255,255,255,.2);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    box-shadow: 0 0 30px rgba(0,0,0,.5);
    color: #fff;
    border-radius: 15px;
    padding: 30px 40px;
    z-index: 1;
}

.wrapper h1 {
    font-size: 32px;
    text-align: center;
    margin-bottom: 8px;
}

.input-box {
    position: relative;
    width: 100%;
    height: 50px;
    margin: 20px 0;
}

.input-box input {
    width: 100%;
    height: 100%;
    background: transparent;
    border: 2px solid rgba(255,255,255,.3);
    border-radius: 40px;
    outline: none;
    font-size: 16px;
    color: #fff;
    padding: 0 45px 0 20px;
    transition: border 0.3s;
}

.input-box input::placeholder {
    color: #ddd;
}

.input-box input.input-error {
    border-color: #ff7675;
    box-shadow: 0 0 8px rgba(255, 118, 117, 0.4);
}

.input-box i {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: rgba(255,255,255,0.7);
    font-size: 20px;
}

.btn {
    width: 100%;
    height: 45px;
    border: none;
    border-radius: 40px;
    background: #fff;
    color: #333;
    font-weight: 600;
    cursor: pointer;
    font-size: 0.95rem;
    transition: all 0.3s;
    margin-top: 4px;
}

.btn:hover {
    background: #f0f0f0;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
}

.register-link {
    text-align: center;
    margin-top: 14px;
}

.register-link a {
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.88rem;
    opacity: 0.85;
    transition: opacity 0.2s;
}

.register-link a:hover { opacity: 1; text-decoration: underline; }

/* ====== TOAST DE ERROR ====== */
.toast-container {
    position: fixed;
    top: 24px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    pointer-events: none;
}

.toast {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 22px;
    border-radius: 50px;
    font-size: 0.9rem;
    font-weight: 500;
    color: #fff;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.35);
    pointer-events: all;
    animation: toastIn 0.35s ease forwards;
    max-width: 420px;
    text-align: center;
}

.toast.toast-error {
    background: rgba(220, 53, 69, 0.88);
    border: 1px solid rgba(255,118,117,0.4);
}

.toast.toast-warning {
    background: rgba(230, 120, 20, 0.88);
    border: 1px solid rgba(255,180,60,0.4);
}

.toast-icon { font-size: 1.2rem; flex-shrink: 0; }
.toast-text { flex: 1; }

.toast.hide {
    animation: toastOut 0.4s ease forwards;
}

@keyframes toastIn {
    from { opacity: 0; transform: translateY(-16px) scale(0.95); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

@keyframes toastOut {
    from { opacity: 1; transform: translateY(0) scale(1); }
    to   { opacity: 0; transform: translateY(-12px) scale(0.95); }
}

/* Barra de progreso del toast */
.toast-bar {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    border-radius: 0 0 50px 50px;
    background: rgba(255,255,255,0.5);
    animation: toastBar 4s linear forwards;
}

@keyframes toastBar {
    from { width: 100%; }
    to   { width: 0%; }
}

.toast { position: relative; overflow: hidden; }

</style>
</head>

<body>

<header class="header">
    <a href="../Inicioo/Index.php" class="logo">Beauty SPA</a>
</header>

<!-- Toast container -->
<div class="toast-container" id="toast-container"></div>

<div class="wrapper">
    <form action="../Controllers/UsuarioController.php?action=login" method="POST" id="login-form" novalidate>

        <h1>Login</h1>

        <div class="input-box">
            <input type="text" name="usuario_o_correo" id="input-usuario"
                   placeholder="Usuario o Correo"
                   value="<?php echo htmlspecialchars($_POST['usuario_o_correo'] ?? ''); ?>">
            <i class='bx bx-user'></i>
        </div>

        <div class="input-box">
            <input type="password" name="contraseña" id="input-pass" placeholder="Contraseña">
            <i class='bx bx-lock-alt'></i>
        </div>

        <button type="submit" class="btn">Iniciar sesión</button>

        <div class="register-link" style="margin-top:18px;">
            <p>¿No tenés una cuenta? <a href="../Registro/Registro.php">Registrarse</a></p>
        </div>

        <div class="register-link">
            <p><a href="../NuevaContrasena/recuperar.php">¿Olvidaste tu contraseña?</a></p>
        </div>

    </form>
</div>

<script>
// ===== Toast system =====
function mostrarToast(mensaje, tipo) {
    const container = document.getElementById('toast-container');

    const toast = document.createElement('div');
    toast.className = 'toast toast-' + tipo;

    const iconos = {
        error:   '❌',
        warning: '⚠️',
    };

    toast.innerHTML = `
        <span class="toast-icon">${iconos[tipo] || '⚠️'}</span>
        <span class="toast-text">${mensaje}</span>
        <div class="toast-bar"></div>
    `;

    container.appendChild(toast);

    // Auto-cerrar después de 4s
    setTimeout(function() {
        toast.classList.add('hide');
        setTimeout(() => toast.remove(), 400);
    }, 4000);
}

// ===== Mostrar errores desde PHP (URL param o variable PHP) =====
<?php
// Cualquier error de login se muestra siempre como mensaje genérico
$hay_error = !empty($_GET['error']) || !empty($error);
?>

<?php if ($hay_error): ?>
window.addEventListener('load', function() {
    mostrarToast('Usuario o contraseña incorrectos.', 'error');
});
<?php endif; ?>

// ===== Validación propia del formulario (sin required nativo) =====
document.getElementById('login-form').addEventListener('submit', function(e) {
    const usuario = document.getElementById('input-usuario');
    const pass    = document.getElementById('input-pass');

    usuario.classList.remove('input-error');
    pass.classList.remove('input-error');

    if (!usuario.value.trim()) {
        e.preventDefault();
        usuario.classList.add('input-error');
        mostrarToast('Ingresá tu usuario o correo electrónico.', 'warning');
        usuario.focus();
        return;
    }

    if (!pass.value.trim()) {
        e.preventDefault();
        pass.classList.add('input-error');
        mostrarToast('Ingresá tu contraseña.', 'warning');
        pass.focus();
        return;
    }
});

// Limpiar error al escribir
document.getElementById('input-usuario').addEventListener('input', function() {
    this.classList.remove('input-error');
});
document.getElementById('input-pass').addEventListener('input', function() {
    this.classList.remove('input-error');
});
</script>

</body>
</html>