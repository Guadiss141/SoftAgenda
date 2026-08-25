<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/');
    session_start();
}

$usuario = isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : null;

include 'InicioViews.php';
?>