<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../Models/Turno.php';


// CONEXIÓN

$conexion = new mysqli(
    "localhost",
    "root",
    "345756",
    "gestionspabd"
);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$modeloTurno = new Turno($conexion);


// DATOS DE SESIÓN

$id_Rol      = $_SESSION['id_Rol']      ?? null;
$id_Paciente = $_SESSION['id_Paciente'] ?? null;
$id_Empleado = $_SESSION['id_Empleado'] ?? null;

$action = $_GET['action'] ?? 'listar';


// CREAR TURNO

if ($action == 'crear') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id_Servicio = $_POST['id_Servicio'];
        $id_Empleado = $_POST['id_Empleado'];
        $fecha       = $_POST['Fecha_Turno'];
        $hora        = $_POST['Hora_Turno'];

        $resultado = $modeloTurno->crearTurno(
            $id_Paciente,
            $id_Servicio,
            $id_Empleado,
            $fecha,
            $hora
        );

        if ($resultado) {
            header("Location: ../Controllers/TurnoController.php?status=success");
        } else {
            header("Location: ../Controllers/TurnoController.php?status=error");
        }

        exit();
    }
}


// ELIMINAR TURNO

if ($action == 'eliminar') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id_Turno  = $_POST['id_turno'];
        $resultado = $modeloTurno->eliminarTurno($id_Turno);

        if ($resultado) {
            header("Location: ../Controllers/TurnoController.php?status=success");
        } else {
            header("Location: ../Controllers/TurnoController.php?status=error");
        }

        exit();
    }
}


// COMPLETAR TURNO

if ($action == 'completar') {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id_Turno  = $_POST['id_turno'];
        $resultado = $modeloTurno->completarTurno($id_Turno);

        if ($resultado) {
            header("Location: ../Controllers/TurnoController.php?status=success");
        } else {
            header("Location: ../Controllers/TurnoController.php?status=error");
        }

        exit();
    }
}


// LISTAR TURNOS

$turnos_resultado = null;
$agendaHoy        = null;

if ($id_Rol == 0 && $id_Paciente) {

    $turnos_resultado = $modeloTurno->obtenerTurnosPorPaciente($id_Paciente);

} elseif ($id_Rol == 2 && $id_Empleado) {

    $agendaHoy = $modeloTurno->obtenerAgendaDelDia($id_Empleado);

} elseif ($id_Rol == 3) {

    $turnos_resultado = $modeloTurno->obtenerTodosLosTurnos();
}


// DATOS PARA SELECTS

$servicios_db  = $modeloTurno->obtenerServicios();
$terapeutas_db = $modeloTurno->obtenerTerapeutas();


// CARGAR VISTA

require_once '../Views/turnos.php';