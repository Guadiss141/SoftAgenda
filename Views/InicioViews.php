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

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
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
    font-size: 1cm;
    color: #fff;
    text-decoration: none;
}

.nav a {
    font-size: 0.55cm;
    color: #fff;
    text-decoration: none;
    margin-left: 40px;
    position: relative;
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
    color: #fff;
    font-size: 0.55cm;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
}

.dropdown-btn i { font-size: 26px; }

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
    padding: 12px 20px;
    color: black;
    text-decoration: none;
    transition: .3s;
}

.dropdown-content a:hover {
    background: black;
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
    max-width: 600px;
    color: #fff;
    padding-left: 100px;
}

.content h2 { font-size: 1.5cm; }

.content p { margin: 19px 0 40px; }

.content a {
    color: #fff;
    text-decoration: none;
    border: 2px solid #fff;
    padding: 10px 25px;
    border-radius: 30px;
    transition: .4s;
}

.content a:hover {
    background: #fff;
    color: #222;
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
