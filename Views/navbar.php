<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/');
    session_start();
}
$id_Rol = $_SESSION['id_Rol'] ?? null;
?>

<nav class="navbar">
    <div class="nav-left">
        <a href="/Spaa/Views/index.php" class="logo">SoftAgenda</a>
    </div>

    <div class="nav-right">
        <?php if (isset($_SESSION['id_Usuario'])): ?>
        <div class="dropdown">
            <button class="dropdown-btn">
                <?php echo htmlspecialchars($_SESSION['nombre_usuario'] ?? 'Mi Cuenta'); ?> ▼
            </button>
            <div class="dropdown-content">
                <a href="/Spaa/Views/perfil.php">Perfil</a>

                <?php if ($id_Rol == 0): ?>
                    <a href="/Spaa/Controllers/CitasController.php">Mis Turnos</a>
                <?php elseif ($id_Rol == 2): ?>
                    <a href="/Spaa/Controllers/CitasController.php">Mis Citas</a>
                <?php elseif ($id_Rol == 3): ?>
                    <a href="/Spaa/Controllers/admin_usuariosController.php">Gestion de Usuarios</a>
                <?php endif; ?>

                <hr>
                <a href="/Spaa/Views/Logout.php" style="color: #c0392b;">Cerrar sesion</a>
            </div>
        </div>

        <?php else: ?>
            <a href="/Spaa/Views/login.php" class="btn">Iniciar sesion</a>
            <a href="/Spaa/Views/registro.php" class="btn">Registrarse</a>
        <?php endif; ?>
    </div>
</nav>

<style>
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #ffffff;
    padding: 10px 40px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.logo {
    font-weight: bold;
    font-size: 1.2rem;
    text-decoration: none;
    color: #333;
}
.nav-right {
    display: flex;
    align-items: center;
}
.nav-right .btn {
    margin-left: 10px;
    padding: 8px 16px;
    background: #000;
    color: #fff;
    text-decoration: none;
    border-radius: 8px;
    font-size: 0.9rem;
}
.dropdown {
    position: relative;
    display: inline-block;
}
.dropdown-btn {
    background: #000;
    color: white;
    padding: 10px 16px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
}
.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background: white;
    min-width: 180px;
    box-shadow: 0px 8px 16px rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
    z-index: 1000;
}
.dropdown-content a {
    padding: 12px 16px;
    display: block;
    text-decoration: none;
    color: #333;
    font-size: 0.9rem;
}
.dropdown-content a:hover { background: #f8f9fa; }
.dropdown-content hr {
    margin: 0;
    border: 0;
    border-top: 1px solid #eee;
}
.dropdown:hover .dropdown-content { display: block; }
</style>