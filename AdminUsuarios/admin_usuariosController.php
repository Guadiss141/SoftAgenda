<?php

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/');
    session_start();
}

if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
    header("Location: ../Login/Login.php");
    exit();
}

require_once __DIR__ . '/admin_usuariosModel.php';
require_once '../Models/Usuario.php';

$conexion = admin_usuariosModel::conectar();

$modeloUsuario = new Usuario($conexion);

$filtro = $_GET['filtro'] ?? 'todos';
$usuarios = $modeloUsuario->listarTodos($filtro);

require_once __DIR__ . '/admin_usuarios.php';