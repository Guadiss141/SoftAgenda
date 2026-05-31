<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../Models/Turno.php';

$conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$modeloTurno = new Turno($conexion);

$id_Rol      = $_SESSION['id_Rol']      ?? null;
$id_Paciente = $_SESSION['id_Paciente'] ?? null;
$id_Empleado = $_SESSION['id_Empleado'] ?? null;
$id_Usuario  = $_SESSION['id_Usuario']  ?? null;

$action = $_GET['action'] ?? 'listar';

// crear turno
if ($action == 'crear' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_Servicio = intval($_POST['id_Servicio']);
    $id_Emp      = intval($_POST['id_Empleado']);
    $fecha       = $_POST['Fecha_Turno'];
    $hora        = $_POST['Hora_Turno'];

    $diaSemana = date('N', strtotime($fecha));
    if ($diaSemana >= 6) {
        header("Location: ../Controllers/TurnoController.php?status=fin_semana");
        exit();
    }

    if ($modeloTurno->esDiaBloqueado($id_Emp, $fecha)) {
        header("Location: ../Controllers/TurnoController.php?status=bloqueado");
        exit();
    }

    $horasOcupadas = $modeloTurno->obtenerHorasOcupadas($id_Emp, $fecha);
    $horaSolo      = substr($hora, 0, 5);
    if (in_array($horaSolo, $horasOcupadas)) {
        header("Location: ../Controllers/TurnoController.php?status=hora_ocupada");
        exit();
    }

    $resultado = $modeloTurno->crearTurno($id_Paciente, $id_Servicio, $id_Emp, $fecha, $hora);
    header("Location: ../Controllers/TurnoController.php?status=" . ($resultado ? 'success' : 'error'));
    exit();
}

// eliminar turno
if ($action == 'eliminar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_Turno  = intval($_POST['id_turno']);
    $resultado = $modeloTurno->eliminarTurno($id_Turno);
    header("Location: ../Controllers/TurnoController.php?status=" . ($resultado ? 'success' : 'error'));
    exit();
}

// completar dias
if ($action == 'completar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_Turno  = intval($_POST['id_turno']);
    $resultado = $modeloTurno->completarTurno($id_Turno);
    header("Location: ../Controllers/TurnoController.php?status=" . ($resultado ? 'success' : 'error'));
    exit();
}

// bloquear dias
if ($action == 'bloquear' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($id_Rol != 2) {
        header("Location: ../Controllers/TurnoController.php");
        exit();
    }

    $fecha  = $_POST['fecha'];
    $motivo = trim($_POST['motivo'] ?? '');

    $diaSemana = date('N', strtotime($fecha));
    if ($diaSemana >= 6) {
        header("Location: ../Controllers/TurnoController.php?status=fin_semana");
        exit();
    }

    if ($modeloTurno->esDiaBloqueado($id_Empleado, $fecha)) {
        header("Location: ../Controllers/TurnoController.php?status=ya_bloqueado");
        exit();
    }

    $pacientes_afectados = $modeloTurno->bloquearDia($id_Empleado, $fecha, $motivo);

    if ($pacientes_afectados !== false) {
        $_SESSION['pacientes_afectados'] = $pacientes_afectados;
        $_SESSION['fecha_bloqueada']     = $fecha;
        header("Location: ../Controllers/TurnoController.php?status=bloqueado_ok");
    } else {
        header("Location: ../Controllers/TurnoController.php?status=error");
    }
    exit();
}

// accion para desbloquear dia, onda el terapeuta quiere desblo y lo hace 
if ($action == 'desbloquear' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($id_Rol != 2) {
        header("Location: ../Controllers/TurnoController.php");
        exit();
    }

    $fecha     = $_POST['fecha'];
    $resultado = $modeloTurno->desbloquearDia($id_Empleado, $fecha);
    header("Location: ../Controllers/TurnoController.php?status=" . ($resultado ? 'desbloqueado_ok' : 'error'));
    exit();
}

// turnos del dia para un terapeuta dado (ajax)
if ($action == 'turnosDelDiaTerapeuta') {
    header('Content-Type: application/json');

    $fecha = $_GET['fecha'] ?? '';
    if (!$fecha || !$id_Empleado) { echo json_encode([]); exit(); }

    echo json_encode($modeloTurno->turnosDelDiaTerapeuta($id_Empleado, $fecha));
    exit();
}

//horas disponibles para un terapeuta en una fecha dada (ajax)
if ($action == 'horasDisponibles') {
    header('Content-Type: application/json');

    $id_Emp = intval($_GET['id_Empleado'] ?? 0);
    $fecha  = $_GET['fecha'] ?? '';

    if (!$id_Emp || !$fecha) { echo json_encode([]); exit(); }

    $diaSemana = date('N', strtotime($fecha));
    if ($diaSemana >= 6) { echo json_encode([]); exit(); }

    if ($modeloTurno->esDiaBloqueado($id_Emp, $fecha)) { echo json_encode([]); exit(); }

    $ocupadas    = $modeloTurno->obtenerHorasOcupadas($id_Emp, $fecha);
    $disponibles = [];

    for ($h = 7; $h <= 20; $h++) {
        $hora = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
        if (!in_array($hora, $ocupadas)) {
            $disponibles[] = $hora;
        }
    }

    echo json_encode($disponibles);
    exit();
}

//terapeutas disponibles para una fecha dada (ajax)
if ($action == 'terapeutasDisponibles') {
    header('Content-Type: application/json');

    $fecha = $_GET['fecha'] ?? '';
    if (!$fecha) { echo json_encode([]); exit(); }

    $diaSemana = date('N', strtotime($fecha));
    if ($diaSemana >= 6) { echo json_encode([]); exit(); }

    echo json_encode($modeloTurno->obtenerTerapeutasDisponibles($fecha));
    exit();
}

//horas ocupadas del dia, s uso ajax otra vez (fijate si te parece, si tenes otra forma cambiale)
if ($action == 'horasOcupadasDelDia') {
    header('Content-Type: application/json');

    $fecha = $_GET['fecha'] ?? '';
    if (!$fecha) { echo json_encode([]); exit(); }

    echo json_encode($modeloTurno->obtenerHorasOcupadasDelDia($fecha));
    exit();
}

// estos son los datos del calendario y tablas
$anio = intval($_GET['anio'] ?? date('Y'));
$mes  = intval($_GET['mes']  ?? date('n'));

$turnos_mes          = [];
$dias_bloqueados     = [];
$agendaHoy           = null;
$turnos_pendientes   = [];
$turnos_cancelados   = [];

if ($id_Rol == 0 && $id_Paciente) {
    $turnos_mes      = $modeloTurno->obtenerTurnosMesPaciente($id_Paciente, $anio, $mes);
    $dias_bloqueados = $modeloTurno->obtenerDiasBloqueadosParaPaciente();

} elseif ($id_Rol == 2 && $id_Empleado) {
    $turnos_mes      = $modeloTurno->obtenerTurnosMesTerapeuta($id_Empleado, $anio, $mes);
    $dias_bloqueados = array_column($modeloTurno->obtenerDiasBloqueados($id_Empleado), 'Fecha_Bloqueada');
    $agendaHoy       = $modeloTurno->obtenerAgendaDelDia($id_Empleado);

} elseif ($id_Rol == 3) {
    // Admin: solo carga las tablas, no necesita el calendario
    $turnos_pendientes  = $modeloTurno->obtenerTurnosPendientesAdmin();
    $turnos_cancelados  = $modeloTurno->obtenerTurnosCanceladosAdmin();
}

$servicios_db  = $modeloTurno->obtenerServicios()->fetch_all(MYSQLI_ASSOC);
$terapeutas_db = $modeloTurno->obtenerTerapeutas()->fetch_all(MYSQLI_ASSOC);

$pacientes_afectados = $_SESSION['pacientes_afectados'] ?? [];
$fecha_bloqueada     = $_SESSION['fecha_bloqueada']     ?? '';
unset($_SESSION['pacientes_afectados'], $_SESSION['fecha_bloqueada']);

require_once '../Views/turnos.php';