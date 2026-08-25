<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
    header("Location: ../Login/LoginControlls.php");
    exit();
}

require_once __DIR__ . '/ModulosModel.php';

$conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$model = new ModulosModel($conexion);

$action = $_GET['action'] ?? 'listar';

if ($action == 'guardar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Array para guardar las asignaciones por rol [id_Rol => [id_Modulo1, id_Modulo2, ...]]
    $roles_modulos = [
        0 => [], // Paciente
        2 => [], // Terapeuta
        3 => []  // Admin
    ];

    if (isset($_POST['modulos']) && is_array($_POST['modulos'])) {
        foreach ($_POST['modulos'] as $id_Rol => $modulos) {
            if (array_key_exists($id_Rol, $roles_modulos)) {
                foreach ($modulos as $id_Modulo => $value) {
                    $roles_modulos[$id_Rol][] = intval($id_Modulo);
                }
            }
        }
    }

    $exito = true;
    foreach ($roles_modulos as $id_Rol => $modulos_ids) {
        if (!$model->reemplazarAsignacionesPorRol($id_Rol, $modulos_ids)) {
            $exito = false;
        }
    }

    header("Location: ModulosController.php?status=" . ($exito ? 'success' : 'error'));
    exit();
}

// Listar
$modulos = $model->obtenerModulos();
$asignaciones = $model->obtenerModulosAsignados();
$roles = [
    0 => 'Paciente',
    2 => 'Terapeuta',
    3 => 'Administrador'
];

require_once __DIR__ . '/modulos_view.php';?>
