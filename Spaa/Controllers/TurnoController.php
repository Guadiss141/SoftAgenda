<?php

require_once '../Models/Turno.php';

class TurnoController {
    private $modelo;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->modelo = new Turno($db);
    }

    // Listar turnos según quién esté logueado
    public function listarTurnos() {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        
        if (!isset($_SESSION['id_Usuario'])) {
            header("Location: ../Views/Login.php");
            exit();
        }

        $id_Usuario = $_SESSION['id_Usuario'];
        $id_Rol = $_SESSION['id_Rol']; 
        $id_Paciente = $_SESSION['id_Paciente'] ?? null;

        return $this->modelo->leerConFiltros($id_Usuario, $id_Rol, $id_Paciente);
    }

   public function guardarTurno($datos) {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }

    // Validar que todos los datos requeridos estén presentes
    if (empty($datos['id_Paciente']) || empty($datos['id_Empleado']) || 
        empty($datos['id_Servicio']) || empty($datos['Fecha_Turno']) || empty($datos['Hora_Turno'])) {
        error_log('TurnoController: Datos incompletos - ' . json_encode($datos));
        return false; 
    }

    if (isset($_SESSION['id_Rol']) && $_SESSION['id_Rol'] == 0) {
        if (isset($_SESSION['id_Paciente'])) {
            $datos['id_Paciente'] = $_SESSION['id_Paciente'];
        } else {
            error_log('TurnoController: Paciente sin id_Paciente en sesión');
            return false; 
        }
    }

    $resultado = $this->modelo->crear($datos);
    if (!$resultado) {
        error_log('TurnoController: Error en modelo->crear()');
    }
    return $resultado;
}

    public function eliminarTurno($id_Turno) {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        return $this->modelo->borrar($id_Turno, $_SESSION['id_Usuario'], $_SESSION['id_Rol']);
    }
}

// --- LÓGICA DE PROCESAMIENTO DE FORMULARIO ---
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET['action']) && $_GET['action'] === 'crear') {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    
    $conexion = new mysqli("localhost", "root", "", "gestionspabd");

    if ($conexion->connect_error) {
        error_log('TurnoController: Error conexion - ' . $conexion->connect_error);
        header("Location: ../Views/turnos.php?status=error");
        exit();
    }

    $controller = new TurnoController($conexion);
    
    // Validar sesión
    if ($_SESSION['id_Rol'] == 0 && empty($_SESSION['id_Paciente'])) {
        error_log('TurnoController: Paciente sin id_Paciente en sesión');
        header("Location: ../Views/turnos.php?status=error");
        exit();
    }
    
$datosTurno = [
    'id_Paciente' => $_SESSION['id_Paciente'] ?? null,
    'id_Empleado' => isset($_POST['id_Empleado']) ? intval($_POST['id_Empleado']) : null,
    'id_Servicio' => isset($_POST['id_Servicio']) ? intval($_POST['id_Servicio']) : null,
    'Fecha_Turno' => $_POST['Fecha_Turno'] ?? null,
    'Hora_Turno'  => $_POST['Hora_Turno'] ?? null
];
    
    error_log('TurnoController: Datos del turno - ' . json_encode($datosTurno));
    
    if ($controller->guardarTurno($datosTurno)) {
        header("Location: ../Views/turnos.php?status=success");
        exit();
    } else {
        header("Location: ../Views/turnos.php?status=error");
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET['action']) && $_GET['action'] === 'eliminar') {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    $conexion = new mysqli("localhost", "root", "", "gestionspabd");
    if ($conexion->connect_error) {
        header("Location: ../Views/turnos.php?status=error");
        exit();
    }
    $controller = new TurnoController($conexion);
    
    if (isset($_POST['id_turno'])) {
        $id_turno = intval($_POST['id_turno']);
        if ($controller->eliminarTurno($id_turno)) {
            header("Location: ../Views/turnos.php?status=success");
        } else {
            header("Location: ../Views/turnos.php?status=error");
        }
    } else {
        header("Location: ../Views/turnos.php?status=error");
    }
    exit();
} elseif ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_GET['action']) && $_GET['action'] === 'completar') {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    $conexion = new mysqli("localhost", "root", "", "gestionspabd");
    if ($conexion->connect_error) {
        header("Location: ../Views/turnos.php?status=error");
        exit();
    }
    
    if (isset($_POST['id_turno'])) {
        $id_turno = intval($_POST['id_turno']);
        // Actualizar el estado a Completado
        $stmt = $conexion->prepare("UPDATE turno SET Estado_Turno = 'Completado' WHERE id_Turno = ?");
        $stmt->bind_param("i", $id_turno);
        if ($stmt->execute()) {
            header("Location: ../Views/turnos.php?status=success");
        } else {
            header("Location: ../Views/turnos.php?status=error");
        }
    } else {
        header("Location: ../Views/turnos.php?status=error");
    }
    exit();
}