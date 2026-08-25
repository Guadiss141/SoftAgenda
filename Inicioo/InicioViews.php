<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuario = $usuario ?? ($_SESSION['nombre_usuario'] ?? null);
$id_Rol  = $_SESSION['id_Rol'] ?? null;

$modulos_inicio = [];
if ($usuario && isset($id_Rol)) {
    require_once __DIR__ . '/../Modulos/ModulosModel.php';
    $conexion_modulos = new mysqli("localhost", "root", "345756", "gestionspabd");
    if (!$conexion_modulos->connect_error) {
        $model_modulos = new ModulosModel($conexion_modulos);
        $modulos_inicio = $model_modulos->obtenerModulosPorRol($id_Rol);
        $conexion_modulos->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beauty SPA - Inicio</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            width: 100%;
            min-height: 100vh;
            background: url('../Views/img/relax.png') no-repeat center center fixed;
            background-size: cover;
        }

        /* ── OVERLAY oscuro sobre la imagen ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.38);
            z-index: 0;
        }

        /* ════════════════════════
           HEADER / NAV
        ════════════════════════ */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 18px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 99;
            background: rgba(0,0,0,0.20);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            font-size: 1.5rem;
            color: #fff;
            text-decoration: none;
            letter-spacing: -0.3px;
        }

        .logo span {
            color: #f5b8d0;
        }

        /* ── Hamburguesa (solo mobile) ── */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            z-index: 101;
            padding: 4px;
        }

        .hamburger span {
            display: block;
            width: 26px;
            height: 2px;
            background: #fff;
            border-radius: 2px;
            transition: all 0.3s;
        }

        .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        /* ── Nav links ── */
        .nav {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav a.nav-link {
            font-size: 0.88rem;
            color: #fff;
            text-decoration: none;
            padding: 9px 20px;
            border-radius: 30px;
            border: 1.5px solid rgba(255,255,255,0.5);
            transition: all 0.3s;
            font-weight: 500;
            white-space: nowrap;
        }

        .nav a.nav-link:hover,
        .nav a.nav-link.primary {
            background: #e879a0;
            border-color: #e879a0;
            color: #fff;
        }

        .nav a.nav-link.primary:hover {
            background: #d4638c;
            border-color: #d4638c;
        }

        /* ── Dropdown usuario ── */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-btn {
            color: #fff;
            font-size: 0.88rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 9px 20px;
            border-radius: 30px;
            background: #e879a0;
            border: 1.5px solid #e879a0;
            transition: background 0.3s;
            white-space: nowrap;
        }

        .dropdown-btn i { font-size: 20px; }
        .dropdown-btn:hover { background: #d4638c; border-color: #d4638c; }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            background: rgba(255,255,255,0.96);
            min-width: 190px;
            border-radius: 14px;
            padding: 8px 0;
            backdrop-filter: blur(8px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.18);
            border: 1.5px solid #fde8f0;
        }

        .dropdown-content a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 11px 18px;
            color: #1a1a1a;
            text-decoration: none;
            font-size: 0.88rem;
            transition: background 0.2s;
        }

        .dropdown-content a:hover {
            background: #fde8f0;
            color: #e879a0;
        }

        .dropdown-content a.logout {
            color: #c0392b;
            border-top: 1.5px solid #fde8f0;
            margin-top: 4px;
        }

        .dropdown-content a.logout:hover {
            background: #fdeaea;
            color: #c0392b;
        }

        .dropdown-content.show { display: block; }

        /* ════════════════════════
           HERO
        ════════════════════════ */
        .home {
            position: relative;
            z-index: 1;
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 100px 60px 60px;
        }

        .content {
            max-width: 580px;
            color: #fff;
            padding-left: 40px;
        }

        .content h2 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 6vw, 3.2rem);
            font-weight: 900;
            line-height: 1.15;
            margin-bottom: 18px;
        }

        .content h2 span {
            color: #f5b8d0;
        }

        .content p {
            font-size: clamp(0.9rem, 2.5vw, 1rem);
            line-height: 1.7;
            color: rgba(255,255,255,0.88);
            margin-bottom: 36px;
            max-width: 480px;
        }

        .btn-reserva {
            display: inline-block;
            color: #fff;
            text-decoration: none;
            border: 2px solid #e879a0;
            background: #e879a0;
            padding: 13px 32px;
            border-radius: 30px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.35s;
            box-shadow: 0 4px 20px rgba(232,121,160,0.4);
        }

        .btn-reserva:hover {
            background: transparent;
            color: #fff;
            border-color: #fff;
            box-shadow: none;
            transform: translateY(-2px);
        }

        /* ════════════════════════
           RESPONSIVE — tablet
        ════════════════════════ */
        @media (max-width: 768px) {
            .header {
                padding: 16px 24px;
            }

            .hamburger {
                display: flex;
            }

            .nav {
                position: fixed;
                top: 0;
                right: -100%;
                width: 75%;
                max-width: 300px;
                height: 100vh;
                background: rgba(20,10,15,0.97);
                backdrop-filter: blur(12px);
                flex-direction: column;
                align-items: flex-start;
                padding: 90px 28px 40px;
                gap: 12px;
                transition: right 0.35s cubic-bezier(0.4,0,0.2,1);
                z-index: 100;
                border-left: 1.5px solid rgba(232,121,160,0.2);
            }

            .nav.open {
                right: 0;
            }

            .nav a.nav-link {
                width: 100%;
                font-size: 1rem;
                padding: 12px 20px;
            }

            .dropdown {
                width: 100%;
            }

            .dropdown-btn {
                width: 100%;
                justify-content: space-between;
                font-size: 1rem;
                padding: 12px 20px;
            }

            .dropdown-content {
                position: static;
                background: rgba(255,255,255,0.08);
                border: 1px solid rgba(232,121,160,0.25);
                border-radius: 10px;
                margin-top: 8px;
                box-shadow: none;
            }

            .dropdown-content a {
                color: #fff;
                font-size: 0.9rem;
            }

            .dropdown-content a:hover {
                background: rgba(232,121,160,0.2);
                color: #f5b8d0;
            }

            .dropdown-content a.logout {
                color: #f5b8d0;
                border-top-color: rgba(232,121,160,0.25);
            }

            .home {
                padding: 80px 24px 40px;
                justify-content: center;
                text-align: center;
            }

            .content {
                padding-left: 0;
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .content p {
                text-align: center;
            }
        }

        /* ════════════════════════
           RESPONSIVE — mobile chico
        ════════════════════════ */
        @media (max-width: 400px) {
            .logo { font-size: 1.2rem; }
            .content h2 { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

<header class="header">
    <a href="/Spaa/Inicioo/Index.php" class="logo">Beauty<span> — </span>SPA</a>
    <!--<a href="/Spaa/Views/Index.php" class="logo">Beauty<span> — </span>SPA</a>-->

    <!-- Botón hamburguesa (mobile) -->
    <div class="hamburger" id="hamburger" aria-label="Abrir menú">
        <span></span><span></span><span></span>
    </div>

    <nav class="nav" id="mainNav">

        <?php if (!$usuario): ?>
            <a href="/Spaa/Login/LoginControlls.php" class="nav-link">Iniciar sesión</a>
            <a href="/Spaa/Registro/Registro.php" class="nav-link primary">Registrarse</a>

        <?php else: ?>
            <div class="dropdown">
                <div class="dropdown-btn" id="dropdownBtn">
                    Hola, <?= htmlspecialchars($usuario) ?>
                    <i class='bx bx-chevron-down'></i>
                </div>
                <div class="dropdown-content" id="dropdownMenu">
                    <?php foreach ($modulos_inicio as $modulo): ?>
                        <a href="<?= htmlspecialchars($modulo['Ruta']) ?>">
                            <i class='bx <?= htmlspecialchars($modulo['Icono']) ?>'></i>
                            <?= htmlspecialchars($modulo['Nombre']) ?>
                        </a>
                    <?php endforeach; ?>
                    <a href="/Spaa/Login/Logout.php" class="logout">
                        <i class='bx bx-log-out'></i> Cerrar sesión
                    </a>
                </div>
            </div>
        <?php endif; ?>

    </nav>
</header>

<section class="home">
    <div class="content">
        <h2>Bienvenido a<br><span>Beauty SPA</span></h2>
        <p>A veces lo único que necesitamos es una pausa.
           Un lugar donde sentirnos bien, y recordar que
           cuidarnos también es una forma de amarnos.</p>
        <a href="/Spaa/Controllers/TurnoController.php" class="btn-reserva">Reserva tu cita</a>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const hamburger  = document.getElementById('hamburger');
    const nav        = document.getElementById('mainNav');
    const dropBtn    = document.getElementById('dropdownBtn');
    const dropMenu   = document.getElementById('dropdownMenu');

    // ── Hamburguesa ──
    if (hamburger) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('open');
            nav.classList.toggle('open');
        });
    }

    // Cerrar menú mobile al hacer click fuera
    document.addEventListener('click', (e) => {
        if (nav && nav.classList.contains('open') &&
            !nav.contains(e.target) &&
            !hamburger.contains(e.target)) {
            nav.classList.remove('open');
            hamburger.classList.remove('open');
        }
    });

    // ── Dropdown usuario ──
    if (dropBtn) {
        dropBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropMenu.classList.toggle('show');
        });
    }

    document.addEventListener('click', (e) => {
        if (dropBtn && dropMenu &&
            !dropBtn.contains(e.target) &&
            !dropMenu.contains(e.target)) {
            dropMenu.classList.remove('show');
        }
    });
});
</script>

</body>
</html>