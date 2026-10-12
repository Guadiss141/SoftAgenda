<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_Usuario']) || !isset($_SESSION['id_Rol'])) {
    header("Location: ../Login/LoginControlls.php");
    exit();
}

require_once __DIR__ . '/Turno.php';
require_once __DIR__ . '/Auditoria.php';

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


// CREAR TURNO (Paciente)
if ($action == 'crear' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_Servicio = intval($_POST['id_Servicio']);
    $id_Emp      = intval($_POST['id_Empleado']);
    $fecha       = $_POST['Fecha_Turno'];
    $hora        = $_POST['Hora_Turno'];

    $diaSemana = date('N', strtotime($fecha));
    if ($diaSemana >= 6) {
        header("Location: TurnoController.php?status=fin_semana");
        exit();
    }

    // Validar que la fecha no sea pasada
    if ($fecha < date('Y-m-d')) {
        header("Location: TurnoController.php?status=fecha_pasada");
        exit();
    }

    if ($modeloTurno->esDiaBloqueado($id_Emp, $fecha)) {
        header("Location: TurnoController.php?status=bloqueado");
        exit();
    }

    $horasOcupadas = $modeloTurno->obtenerHorasOcupadas($id_Emp, $fecha);
    $horaSolo      = substr($hora, 0, 5);
    if (in_array($horaSolo, $horasOcupadas)) {
        header("Location: TurnoController.php?status=hora_ocupada");
        exit();
    }

   $resultado = $modeloTurno->crearTurno($id_Paciente, $id_Servicio, $id_Emp, $fecha, $hora);

if ($resultado) {
    $auditoria = new Auditoria($conexion);
    $auditoria->registrar(  // Registrar en auditoría la acción de crear turno
        $resultado, // si crearTurno() devuelve el id del turno creado; si devuelve true/false, ver nota abajo
        $id_Usuario,
        'Turno',
        'CREAR',
        null, // no hay dato anterior, es nuevo
        json_encode([
            'id_Paciente' => $id_Paciente,
            'id_Servicio' => $id_Servicio,
            'id_Empleado' => $id_Emp,
            'Fecha' => $fecha,
            'Hora' => $hora
        ])
    );
}

header("Location: TurnoController.php?status=" . ($resultado ? 'success' : 'error'));
exit();
}


// ELIMINAR TURNO
if ($action == 'eliminar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_Turno = intval($_POST['id_turno']);

    $turnoAnterior = $modeloTurno->obtenerTurnoPorIdSimple($id_Turno);
    $resultado = $modeloTurno->eliminarTurno($id_Turno);

    if ($resultado) {
        $auditoria = new Auditoria($conexion);
        $auditoria->registrar(
            $id_Turno,
            $id_Usuario,
            'Turno',
            'ELIMINAR',
            json_encode($turnoAnterior),
            null
        );
    }

    header("Location: TurnoController.php?status=" . ($resultado ? 'success' : 'error'));
    exit();
}


// COMPLETAR TURNO (Terapeuta)
if ($action == 'completar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_Turno  = intval($_POST['id_turno']);
    $resultado = $modeloTurno->completarTurno($id_Turno);
    header("Location: TurnoController.php?status=" . ($resultado ? 'success' : 'error'));
    exit();
}


// BLOQUEAR DÍA (Terapeuta)
if ($action == 'bloquear' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($id_Rol != 2) {
        header("Location: TurnoController.php");
        exit();
    }

    $fecha  = $_POST['fecha'];
    $motivo = trim($_POST['motivo'] ?? '');

    $diaSemana = date('N', strtotime($fecha));
    if ($diaSemana >= 6) {
        header("Location: TurnoController.php?status=fin_semana");
        exit();
    }

    if ($modeloTurno->esDiaBloqueado($id_Empleado, $fecha)) {
        header("Location: TurnoController.php?status=ya_bloqueado");
        exit();
    }

   $turnos_afectados = $modeloTurno->bloquearDia($id_Empleado, $fecha, $motivo);

if ($turnos_afectados !== false) {
    // Registrar en auditoría cada turno cancelado por el bloqueo del día
    require_once __DIR__ . '/Auditoria.php';
    $auditoria = new Auditoria($conexion);
    foreach ($turnos_afectados as $turno) {
        $auditoria->registrar(
            $turno['id_Turno'],
            $id_Usuario,
            'Turno',
            'CANCELAR',
            json_encode($turno),
            null
        );
    }

    $_SESSION['pacientes_afectados'] = $turnos_afectados;
    $_SESSION['fecha_bloqueada']     = $fecha;
    header("Location: TurnoController.php?status=bloqueado_ok");
} else {
    header("Location: TurnoController.php?status=error");
}
exit();

}


// DESBLOQUEAR DÍA (Terapeuta)
if ($action == 'desbloquear' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($id_Rol != 2) {
        header("Location: TurnoController.php");
        exit();
    }

    $fecha     = $_POST['fecha'];
    $resultado = $modeloTurno->desbloquearDia($id_Empleado, $fecha);
    header("Location: TurnoController.php?status=" . ($resultado ? 'desbloqueado_ok' : 'error'));
    exit();
}


// TURNOS DEL DÍA DEL TERAPEUTA (AJAX panel lateral)
if ($action == 'turnosDelDiaTerapeuta') {
    header('Content-Type: application/json');

    $fecha = $_GET['fecha'] ?? '';
    if (!$fecha || !$id_Empleado) { echo json_encode([]); exit(); }

    echo json_encode($modeloTurno->turnosDelDiaTerapeuta($id_Empleado, $fecha));
    exit();
}

// HORAS DISPONIBLES (AJAX)
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


// TERAPEUTAS DISPONIBLES PARA UNA FECHA (AJAX)
if ($action == 'terapeutasDisponibles') {
    header('Content-Type: application/json');

    $fecha = $_GET['fecha'] ?? '';
    if (!$fecha) { echo json_encode([]); exit(); }

    $diaSemana = date('N', strtotime($fecha));
    if ($diaSemana >= 6) { echo json_encode([]); exit(); }

    echo json_encode($modeloTurno->obtenerTerapeutasDisponibles($fecha));
    exit();
}

// HORAS OCUPADAS DEL DÍA — todos los terapeutas (AJAX)
if ($action == 'horasOcupadasDelDia') {
    header('Content-Type: application/json');

    $fecha = $_GET['fecha'] ?? '';
    if (!$fecha) { echo json_encode([]); exit(); }

    echo json_encode($modeloTurno->obtenerHorasOcupadasDelDia($fecha));
    exit();
}


// LISTAR: cargar datos para el calendario
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

require_once __DIR__ . '/turnos.php';