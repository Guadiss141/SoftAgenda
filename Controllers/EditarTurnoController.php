<?php

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/');
    session_start();
}

// Verificar sesión
if (!isset($_SESSION['id_Usuario']) || !isset($_SESSION['id_Rol'])) {
    header("Location: ../Views/Login.php");
    exit();
}

// Verificar rol paciente
if ($_SESSION['id_Rol'] != 0) {
    header("Location: ../Views/index.php");
    exit();
}

if (!isset($_SESSION['id_Paciente'])) {
    header("Location: ../Views/Login.php");
    exit();
}

require_once '../Models/admin_usuariosModel.php';
require_once '../Models/Turno.php';

$conexion = admin_usuariosModel::conectar();

$modeloTurno = new Turno($conexion);

$id_Paciente = $_SESSION['id_Paciente'];
$id_turno = intval($_GET['id'] ?? 0);

if ($id_turno === 0) {
    header("Location: ../Views/perfil.php");
    exit();
}

// Obtener turno
$turno = $modeloTurno->obtenerTurnoPorId($id_turno, $id_Paciente);

if (!$turno) {
    header("Location: ../Views/perfil.php");
    exit();
}

// Servicios
$servicios = $modeloTurno->listarServicios();

// Empleados
$empleados = $modeloTurno->listarEmpleados();

$mensaje = "";

// Actualizar turno
if ($_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST['action'])
    && $_POST['action'] === 'actualizar_turno') {

    $fecha = trim($_POST['fecha'] ?? '');
    $hora = trim($_POST['hora'] ?? '');
    $servicio = intval($_POST['servicio'] ?? 0);
    $empleado = intval($_POST['empleado'] ?? 0);
    $estado = trim($_POST['estado'] ?? '');

    if ($fecha && $hora && $servicio && $empleado && $estado) {

        $actualizado = $modeloTurno->actualizarTurno(
            $fecha,
            $hora,
            $servicio,
            $empleado,
            $estado,
            $id_turno,
            $id_Paciente
        );

        if ($actualizado) {

            $mensaje = "
            <div class='alert alert-success'>
                Turno actualizado correctamente.
            </div>";

            $turno = $modeloTurno->obtenerTurnoPorId($id_turno, $id_Paciente);

        } else {

            $mensaje = "
            <div class='alert alert-danger'>
                Error al actualizar el turno.
            </div>";
        }

    } else {

        $mensaje = "
        <div class='alert alert-danger'>
            Completa todos los campos.
        </div>";
    }
}

require_once '../Views/editar_turno.php';