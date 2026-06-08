<?php

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/');
    session_start();
}

if (!isset($_SESSION['id_Usuario']) || !isset($_SESSION['id_Rol'])) {
    header("Location: ../Views/Login.php");
    exit();
}

// Solo paciente (0) y terapeuta (2)
if ($_SESSION['id_Rol'] == 3) {
    header("Location: ../Controllers/admin_usuariosController.php");
    exit();
}

require_once '../Models/CitasModel.php';

$conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$modelo  = new CitasModel($conexion);
$id_Rol  = $_SESSION['id_Rol'];
$mensaje = '';

//se cancela turno de parte del paciente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancelar_turno') {
    $id_Turno   = intval($_POST['id_turno']  ?? 0);
    $id_Paciente = $_SESSION['id_Paciente']  ?? null;

    if ($id_Turno > 0 && $id_Paciente) {
        $ok      = $modelo->cancelarTurno($id_Turno, $id_Paciente);
        $mensaje = $ok ? 'success' : 'error';
    } else {
        $mensaje = 'error';
    }
}

// cargar datos segun roles esto se hace despues de cancelar turno para que se actualice la vista con los cambios realizados
$citas  = [];
$turnos = [];

if ($id_Rol == 2 && isset($_SESSION['id_Empleado'])) {
    $citas = $modelo->getCitasPorEmpleado($_SESSION['id_Empleado']);

} elseif ($id_Rol == 0 && isset($_SESSION['id_Paciente'])) {
    $turnos = $modelo->getTurnosPorPaciente($_SESSION['id_Paciente']);
}

require_once '../Views/CitasView.php';
?>