<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/');
    session_start();
}
$id_Rol = $_SESSION['id_Rol'] ?? null;

$modulos_navbar = [];
if (isset($_SESSION['id_Usuario']) && isset($id_Rol)) {
    require_once __DIR__ . '/../Modulos/ModulosModel.php';
    $conexion_modulos = new mysqli("localhost", "root", "345756", "gestionspabd");
    if (!$conexion_modulos->connect_error) {
        $model_modulos = new ModulosModel($conexion_modulos);
        $modulos_navbar = $model_modulos->obtenerModulosPorRol($id_Rol);
        $conexion_modulos->close();
    }
}
?>

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

<nav class="navbar">
    <div class="nav-left">
        <a href="/Spaa/Inicioo/index.php" class="logo">
            Soft<span>Agenda</span>
        </a>
    </div>

    <!-- Hamburguesa (mobile) -->
    <div class="hamburger" id="navHamburger" aria-label="Abrir menú">
        <span></span><span></span><span></span>
    </div>

    <div class="nav-right" id="navRight">
        <?php if (isset($_SESSION['id_Usuario'])): ?>
            <div class="dropdown">
                <button class="dropdown-btn" id="navDropBtn">
                    <?= htmlspecialchars($_SESSION['nombre_usuario'] ?? 'Mi Cuenta') ?>
                    <span class="arrow">▼</span>
                </button>
                <div class="dropdown-content" id="navDropMenu">
                    <?php foreach ($modulos_navbar as $modulo): ?>
                        <a href="<?= htmlspecialchars($modulo['Ruta']) ?>">
                            <?= htmlspecialchars($modulo['Nombre']) ?>
                        </a>
                    <?php endforeach; ?>
                    <hr>
                    <a href="/Spaa/Login/Logout.php" class="logout-link">Cerrar sesión</a>
                </div>
            </div>

        <?php else: ?>
            <a href="/Spaa/Login/login.php" class="btn btn-outline">Iniciar sesión</a>
            <a href="/Spaa/Registro/Registro.php" class="btn btn-pink">Registrarse</a>
        <?php endif; ?>
    </div>
</nav>

<style>
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
    padding: 12px 48px;
    box-shadow: 0 4px 15px rgba(232,121,160,0.08);
    border-bottom: 1.5px solid #fde8f0;
    position: relative;
    z-index: 200;
}

.logo {
    font-family: 'Playfair Display', serif;
    font-weight: 900;
    font-size: 1.25rem;
    text-decoration: none;
    color: #1a1a1a;
    letter-spacing: -0.3px;
}

.logo span { color: #e879a0; }

/* ── Hamburguesa ── */
.hamburger {
    display: none;
    flex-direction: column;
    gap: 5px;
    cursor: pointer;
    padding: 4px;
    z-index: 201;
}

.hamburger span {
    display: block;
    width: 24px;
    height: 2px;
    background: #1a1a1a;
    border-radius: 2px;
    transition: all 0.3s;
}

.hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.hamburger.open span:nth-child(2) { opacity: 0; }
.hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

/* ── Nav right ── */
.nav-right {
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn {
    padding: 9px 20px;
    border-radius: 30px;
    font-size: 0.88rem;
    font-weight: 500;
    font-family: 'DM Sans', sans-serif;
    text-decoration: none;
    cursor: pointer;
    border: none;
    transition: all 0.3s;
    white-space: nowrap;
}

.btn-pink {
    background: #e879a0;
    color: #fff;
    box-shadow: 0 3px 10px rgba(232,121,160,0.3);
}

.btn-pink:hover { background: #d4638c; transform: translateY(-1px); }

.btn-outline {
    background: transparent;
    color: #e879a0;
    border: 1.5px solid #e879a0;
}

.btn-outline:hover { background: #fde8f0; }

/* ── Dropdown ── */
.dropdown { position: relative; display: inline-block; }

.dropdown-btn {
    background: #e879a0;
    color: white;
    padding: 9px 20px;
    border-radius: 30px;
    border: none;
    cursor: pointer;
    font-size: 0.88rem;
    font-weight: 500;
    font-family: 'DM Sans', sans-serif;
    display: flex;
    align-items: center;
    gap: 7px;
    transition: background 0.3s;
    white-space: nowrap;
}

.dropdown-btn .arrow {
    font-size: 0.65rem;
    transition: transform 0.3s;
}

.dropdown-btn.active .arrow { transform: rotate(180deg); }
.dropdown-btn:hover { background: #d4638c; }

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    top: calc(100% + 8px);
    background: white;
    min-width: 185px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    border-radius: 14px;
    overflow: hidden;
    z-index: 1000;
    border: 1.5px solid #fde8f0;
}

.dropdown-content.show { display: block; }

.dropdown-content a {
    padding: 11px 16px;
    display: block;
    text-decoration: none;
    color: #1a1a1a;
    font-size: 0.88rem;
    font-family: 'DM Sans', sans-serif;
    transition: background 0.2s;
}

.dropdown-content a:hover { background: #fde8f0; color: #e879a0; }

.dropdown-content hr {
    margin: 0;
    border: 0;
    border-top: 1.5px solid #fde8f0;
}

.logout-link { color: #c0392b !important; }
.logout-link:hover { background: #fdeaea !important; color: #c0392b !important; }

/* ════════════════════════
   RESPONSIVE
════════════════════════ */
@media (max-width: 768px) {
    .navbar { padding: 12px 20px; }

    .hamburger { display: flex; }

    .nav-right {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: #fff;
        flex-direction: column;
        align-items: stretch;
        padding: 16px 20px 20px;
        border-top: 1.5px solid #fde8f0;
        box-shadow: 0 8px 24px rgba(232,121,160,0.1);
        gap: 10px;
    }

    .nav-right.open { display: flex; }

    .btn { text-align: center; width: 100%; }

    .dropdown { width: 100%; }

    .dropdown-btn {
        width: 100%;
        justify-content: space-between;
    }

    .dropdown-content {
        position: static;
        box-shadow: none;
        border-radius: 10px;
        margin-top: 6px;
        border: 1.5px solid #fde8f0;
    }
}
</style>

<script>
(function () {
    const hamburger = document.getElementById('navHamburger');
    const navRight  = document.getElementById('navRight');
    const dropBtn   = document.getElementById('navDropBtn');
    const dropMenu  = document.getElementById('navDropMenu');

    if (hamburger) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('open');
            navRight.classList.toggle('open');
        });
    }

    if (dropBtn) {
        dropBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropBtn.classList.toggle('active');
            dropMenu.classList.toggle('show');
        });
    }

    document.addEventListener('click', (e) => {
        if (dropBtn && dropMenu &&
            !dropBtn.contains(e.target) &&
            !dropMenu.contains(e.target)) {
            dropBtn.classList.remove('active');
            dropMenu.classList.remove('show');
        }
    });
})();
</script>