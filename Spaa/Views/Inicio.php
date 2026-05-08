<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Beauty SPA - Inicio</title>
<!-- quitar css del codigo -->

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Arial', sans-serif;
        background-color: #ffffffff;
        color: #222;
    }

    /* NAVBAR */
    .navbar {
        width: 100%;
        padding: 20px 50px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-sizing: border-box;
    }

    .logo {
        font-size: 20px;
        font-weight: bold;
    }

    .nav-links a {
        margin-left: 25px;
        text-decoration: none;
        color: black;
        font-weight: 500;
        padding: 8px 18px;
        border-radius: 8px;
        transition: 0.3s;
    }

    .nav-links a:hover {
        background: black;
        color: white;
    }

    .btn-black {
        background: black;
        color: white !important;
        border-radius: 8px;
        padding: 8px 18px;
    }

    /* HERO SECTION */
    .hero {
        text-align: left;
        padding: 40px 50px;
    }

    .hero h1 {
        font-size: 42px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .hero p {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 16px;
        color: #444;
        max-width: 450px;
    }

    .btn-agendar {
        display: inline-block;
        padding: 12px 20px;
        background: black;
        color: white;
        font-size: 16px;
        border-radius: 10px;
        text-decoration: none;
        transition: 0.3s;
    }

    .btn-agendar:hover {
        transform: scale(1.05);
        background: #333;
    }

    /* IMAGE */
    .spa-image {
        width: 100%;
        margin-top: 30px;
        border-radius: 12px;
        overflow: hidden;

    }

    .spa-image img {
        width: 50%;
        height: auto;
        display: flex;
        justify-content: center;
    }
/* MENU DESPLEGABLE */
.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-btn {
    background: black;
    color: white;
    padding: 8px 18px;
    border-radius: 8px;
    cursor: pointer;
    user-select: none;
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background: white;
    min-width: 180px;
    padding: 10px 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-radius: 10px;
    z-index: 20;
}

.dropdown-content a {
    display: block;
    padding: 10px 20px;
    text-decoration: none;
    color: black;
    font-size: 15px;
    transition: 0.3s;
}

.dropdown-content a:hover {
    background: black;
    color: white;
}

/* Animación suave */
.dropdown-content.show {
    display: block;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}

</style>

</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">Beauty SPA</div>

    <div class="nav-links">
        
        <?php if (!isset($_SESSION)) { session_start(); } ?>

        <?php if (isset($_SESSION['id_Usuario'])): ?>
            
            <!-- Cuando está logueado -->
            <span style="font-weight:600; margin-left:20px;">
                Hola, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?> 👋
            </span>

        <?php else: ?>

            <!-- Cuando NO está logueado -->
            <a href="login.php">Iniciar Sesión</a>
            <a href="registro.php">Registrarse</a>

        <?php endif; ?>

        <div class="dropdown">

    <div class="dropdown-btn">Opciones</div>

    <div class="dropdown-content">

        <?php if (isset($_SESSION['id_Usuario'])): ?>

            <a href="#">Perfil</a>
            <a href="turnos.php">Turnos programados</a>
            <a href="logout.php">Cerrar sesión</a>

        <?php else: ?>

            <a href="login.php">Iniciar sesión</a>
            <a href="registro.php">Registrarse</a>

        <?php endif; ?>

    </div>
</div>

    </div>
</div>


<!-- HERO -->
<div class="hero">
    <h1>Agenda tu turno 100% online</h1>
    <p>¡Aquí podrás agendar y visualizar nuestros servicios con sus respectivos precios
       de manera súper fácil!</p>

    <a href="#" class="btn-agendar">Agendar Turno</a>
</div>

<!-- IMAGE -->
<div class="spa-image">
    <img src="https://images.pexels.com/photos/3865792/pexels-photo-3865792.jpeg" alt="SPA">
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const btn = document.querySelector(".dropdown-btn");
    const menu = document.querySelector(".dropdown-content");

    btn.addEventListener("click", () => {
        menu.classList.toggle("show");
    });

    // Cerrar al hacer clic afuera
    document.addEventListener("click", (e) => {
        if (!btn.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove("show");
        }
    });

});
</script>

</body>
</html>

