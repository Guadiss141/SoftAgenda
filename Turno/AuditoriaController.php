<?php

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/');
    session_start();
}

if (!isset($_SESSION['id_Usuario']) || !isset($_SESSION['id_Rol'])) {
    header("Location: ../Inicioo/Login.php");
    exit();
}

// Por ejemplo, solo admin (3) puede acceder a esta página
if ($_SESSION['id_Rol'] != 3) {
    header("Location: ../Inicioo/index.php");
    exit();
}

$conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$sql = "SELECT a.*, u.Usuario_Nombre AS nombre_usuario
        FROM auditoria_turnos a
        LEFT JOIN usuario u ON a.id_usuario = u.id_Usuario
        ORDER BY a.fecha_hora DESC";

$resultado = $conexion->query($sql);
$registros = $resultado->fetch_all(MYSQLI_ASSOC);

require_once __DIR__ . '/auditoria_vista.php';