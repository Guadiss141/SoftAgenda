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

// Solo pacientes
if ($_SESSION['id_Rol'] != 0) {
    header("Location: ../Views/index.php");
    exit();
}

if (!isset($_SESSION['id_Paciente'])) {
    header("Location: ../Views/Login.php");
    exit();
}

require_once '../Models/EditarTurnoModel.php';

$conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$modelo      = new EditarTurnoModel($conexion);
$id_Paciente = $_SESSION['id_Paciente'];
$id_turno    = intval($_GET['id'] ?? 0);

if ($id_turno === 0) {
    header("Location: ../Controllers/TurnoController.php");
    exit();
}

// Obtener turno (verifica que sea del paciente)
$turno = $modelo->obtenerTurnoPorId($id_turno, $id_Paciente);

if (!$turno) {
    header("Location: ../Controllers/TurnoController.php");
    exit();
}

// esto es para ver las horas y horarios disponibles, use ajax
$action = $_GET['action'] ?? '';

if ($action === 'terapeutasDisponibles') {
    header('Content-Type: application/json');

    $fecha = $_GET['fecha'] ?? '';
    if (!$fecha) {
        echo json_encode([]);
        exit();
    }

    // Fin de semana = sin terapeutas
    $diaSemana = date('N', strtotime($fecha));
    if ($diaSemana >= 6) {
        echo json_encode([]);
        exit();
    }

    $terapeutas = $modelo->listarTerapeutasDisponibles($fecha);

    // Asegurarse de incluir al terapeuta actual del turno si la fecha coincide con la original
    if ($fecha === $turno['Fecha_Turno']) {
        $encontrado = false;
        foreach ($terapeutas as $t) {
            if ($t['id_Empleado'] == $turno['id_Empleado']) {
                $encontrado = true;
                break;
            }
        }
        if (!$encontrado) {
            $terapeutas[] = [
                'id_Empleado'      => $turno['id_Empleado'],
                'Persona_Nombre'   => $turno['Terapeuta_Nombre'],
                'Persona_Apellido' => $turno['Terapeuta_Apellido']
            ];
        }
    }

    echo json_encode($terapeutas);
    exit();
}

if ($action === 'horasDisponibles') {
    header('Content-Type: application/json');

    $id_Emp = intval($_GET['id_Empleado'] ?? 0);
    $fecha  = $_GET['fecha'] ?? '';

    if (!$id_Emp || !$fecha) {
        echo json_encode([]);
        exit();
    }

    // Fin de semana = sin horas
    $diaSemana = date('N', strtotime($fecha));
    if ($diaSemana >= 6) {
        echo json_encode([]);
        exit();
    }

    // Día bloqueado = sin horas
    if ($modelo->esDiaBloqueado($id_Emp, $fecha)) {
        echo json_encode([]);
        exit();
    }

    $ocupadas    = $modelo->obtenerHorasOcupadas($id_Emp, $fecha, $id_turno);
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

// esto es actualizar turno
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'actualizar_turno') {

    $fecha    = trim($_POST['fecha']    ?? '');
    $hora     = trim($_POST['hora']     ?? '');
    $servicio = intval($_POST['servicio'] ?? 0);
    $empleado = intval($_POST['empleado'] ?? 0);

    // Validar campos
    if (!$fecha || !$hora || !$servicio || !$empleado) {
        $mensaje = 'error_campos';

    // Validar fin de semana
    } elseif (date('N', strtotime($fecha)) >= 6) {
        $mensaje = 'fin_semana';

    // Validar día bloqueado
    } elseif ($modelo->esDiaBloqueado($empleado, $fecha)) {
        $mensaje = 'bloqueado';

    // Validar hora ocupada (excluyendo el turno actual)
    } else {
        $ocupadas = $modelo->obtenerHorasOcupadas($empleado, $fecha, $id_turno);
        $horaSolo = substr($hora, 0, 5);

        if (in_array($horaSolo, $ocupadas)) {
            $mensaje = 'hora_ocupada';
        } else {
            $actualizado = $modelo->actualizarTurno($fecha, $hora, $servicio, $empleado, $id_turno, $id_Paciente);
            $mensaje     = $actualizado ? 'success' : 'error';

            // Recargar turno actualizado
            if ($actualizado) {
                $turno = $modelo->obtenerTurnoPorId($id_turno, $id_Paciente);
            }
        }
    }
}

// Cargar servicios y terapeutas para los selects
$servicios = $modelo->listarServicios();
$empleados = $modelo->listarTerapeutasDisponibles($turno['Fecha_Turno']);
$dias_bloqueados = $modelo->obtenerDiasBloqueadosParaPaciente();

// Asegurar que el terapeuta actual está incluido (por si se bloqueó posteriormente o por consistencia)
$encontrado = false;
foreach ($empleados as $e) {
    if ($e['id_Empleado'] == $turno['id_Empleado']) {
        $encontrado = true;
        break;
    }
}
if (!$encontrado) {
    $empleados[] = [
        'id_Empleado'      => $turno['id_Empleado'],
        'Persona_Nombre'   => $turno['Terapeuta_Nombre'],
        'Persona_Apellido' => $turno['Terapeuta_Apellido']
    ];
}

require_once '../Views/editar_turno.php';
?>