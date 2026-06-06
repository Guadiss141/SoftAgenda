<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuario = $usuario ?? ($_SESSION['nombre_usuario'] ?? null);
$id_Rol  = $_SESSION['id_Rol'] ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Beauty SPA - Inicio</title>

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>
:root {
    --pink: #e879a0;
    --pink-light: #f5b8d0;
    --pink-pale: #fde8f0;
    --dark: #1a1a1a;
    --gray: #888;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'DM Sans', sans-serif;
}

body {
    width: 100%;
    height: 100vh;
    background: url('img/relax.png') no-repeat;
    background-size: cover;
    background-position: center;
}

.header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 20px 100px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 99;
    background: transparent;
}

.logo {
    font-family: 'Playfair Display', serif;
    font-weight: 900;
    font-size: 1.8rem;
    color: #fff;
    text-decoration: none;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 10px 24px;
    border-radius: 14px;
    border: 1px solid rgba(255, 255, 255, 0.25);
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.15);
    display: inline-block;
}

.nav a {
    font-size: 1.05rem;
    color: #fff;
    text-decoration: none;
    margin-left: 40px;
    position: relative;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
}

.nav a::after {
    content: "";
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 100%;
    height: 3px;
    background: #fff;
    border-radius: 5px;
    transform: scaleX(0);
    transition: .5s;
}

.nav a:hover::after { transform: scaleX(1); }

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-btn {
    font-family: 'DM Sans', sans-serif;
    font-weight: 500;
    color: #fff;
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 10px 20px;
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.25);
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.15);
}

.dropdown-btn i { font-size: 20px; }

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background: rgba(255,255,255,0.90);
    color: black;
    min-width: 180px;
    border-radius: 12px;
    padding: 10px 0;
    margin-top: 10px;
    backdrop-filter: blur(5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

.dropdown-content a {
    display: block;
    padding: 10px 20px;
    margin: 4px 8px;
    color: black;
    text-decoration: none;
    transition: .3s;
}

.dropdown-content a:hover {
    background: #e879a0;
    color: white;
    border-radius: 8px;
}

.show { display: block; }

.home {
    width: 100%;
    height: 100vh;
    display: flex;
    align-items: center;
    padding: 80px 100px 0;
}

.content {
    max-width: 550px;
    color: #fff;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    padding: 40px;
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 16px 40px 0 rgba(0, 0, 0, 0.2);
    margin-left: 100px;
}

.content h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2.8rem;
    font-weight: 900;
    margin-bottom: 20px;
    line-height: 1.2;
    color: #fff;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.content p {
    font-family: 'DM Sans', sans-serif;
    font-size: 1.05rem;
    line-height: 1.6;
    color: #f5f5f5;
    margin-bottom: 35px;
}

.content a {
    font-family: 'DM Sans', sans-serif;
    font-weight: 500;
    font-size: 1rem;
    color: #fff;
    text-decoration: none;
    background: var(--pink);
    border: none;
    padding: 12px 30px;
    border-radius: 30px;
    transition: all 0.3s ease;
    display: inline-block;
    box-shadow: 0 4px 15px rgba(232, 121, 160, 0.4);
}

.content a:hover {
    background: #d4638c;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(232, 121, 160, 0.6);
}
</style>

</head>
<body>

<header class="header">
    <a href="/Spaa/Views/Index.php" class="logo">Beauty - SPA</a>

    <nav class="nav">

    <?php if (!$usuario): ?>

        <a href="/Spaa/Views/Login.php">Iniciar Sesion</a>
        <a href="/Spaa/Views/Registro.php">Registrarse</a>

    <?php else: ?>

        <div class="dropdown">
            <div class="dropdown-btn">
                Hola, <?php echo htmlspecialchars($usuario); ?>
                <i class='bx bx-chevron-down'></i>
            </div>

            <div class="dropdown-content">
                <a href="/Spaa/Views/perfil.php">
                    <i class='bx bx-user'></i> Perfil
                </a>

                <?php if ($id_Rol == 0): ?>
                    <a href="/Spaa/Controllers/CitasController.php">
                        <i class='bx bx-calendar'></i> Mis Turnos
                    </a>
                <?php elseif ($id_Rol == 2): ?>
                    <a href="/Spaa/Controllers/CitasController.php">
                        <i class='bx bx-calendar-check'></i> Mis Citas
                    </a>
                <?php elseif ($id_Rol == 3): ?>
                    <a href="/Spaa/Controllers/admin_usuariosController.php">
                        <i class='bx bx-cog'></i> Gestion de Usuarios
                    </a>
                <?php endif; ?>

                <a href="/Spaa/Views/Logout.php">
                    <i class='bx bx-log-out'></i> Cerrar Sesion
                </a>
            </div>
        </div>

    <?php endif; ?>

    </nav>
</header>

<section class="home">
    <div class="content">
        <h2>Bienvenido a Beauty SPA</h2>
        <p>A veces lo unico que necesitamos es una pausa.
        Un lugar donde sentirnos bien, y recordar que
        cuidarnos tambien es una forma de amarnos.</p>

        <a href="/Spaa/Controllers/TurnoController.php">Reserva tu cita</a>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const btn  = document.querySelector(".dropdown-btn");
    const menu = document.querySelector(".dropdown-content");

    if (btn) {
        btn.addEventListener("click", () => {
            menu.classList.toggle("show");
        });
    }

    document.addEventListener("click", (e) => {
        if (btn && !btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove("show");
        }
    });
});
</script>

</body>
</html>
