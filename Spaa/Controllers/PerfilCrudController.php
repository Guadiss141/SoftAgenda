<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/');
    session_start();
}

if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
    header("Location: ../Views/Login.php");
    exit();
}

$conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$action = $_GET['action'] ?? 'listar';
$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'crear_rol') {
        $id_Rol = intval($_POST['id_Rol']);
        $descripcion = trim($_POST['Rol_Descripcion']);

        if ($id_Rol >= 0 && !empty($descripcion)) {
            // Check if id_Rol exists
            $stmt = $conexion->prepare("SELECT id_Rol FROM rol WHERE id_Rol = ?");
            $stmt->bind_param("i", $id_Rol);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error = "El ID del Rol ya existe.";
            } else {
                $stmt_ins = $conexion->prepare("INSERT INTO rol (id_Rol, Rol_Descripcion) VALUES (?, ?)");
                $stmt_ins->bind_param("is", $id_Rol, $descripcion);
                if ($stmt_ins->execute()) {
                    $mensaje = "Perfil creado correctamente.";
                } else {
                    $error = "Error al crear el perfil.";
                }
            }
        } else {
            $error = "Campos inválidos.";
        }
    } elseif ($action === 'editar_rol') {
        $id_Rol = intval($_POST['id_Rol']);
        $descripcion = trim($_POST['Rol_Descripcion']);

        if (!empty($descripcion)) {
            $stmt = $conexion->prepare("UPDATE rol SET Rol_Descripcion = ? WHERE id_Rol = ?");
            $stmt->bind_param("si", $descripcion, $id_Rol);
            if ($stmt->execute()) {
                $mensaje = "Perfil editado correctamente.";
            } else {
                $error = "Error al editar el perfil.";
            }
        }
    } elseif ($action === 'eliminar_rol') {
        $id_Rol = intval($_POST['id_Rol']);
        if (in_array($id_Rol, [0, 2, 3])) {
            $error = "No se pueden eliminar los perfiles predeterminados del sistema (Paciente, Terapeuta, Admin).";
        } else {
            $stmt = $conexion->prepare("DELETE FROM rol WHERE id_Rol = ?");
            $stmt->bind_param("i", $id_Rol);
            if ($stmt->execute()) {
                $mensaje = "Perfil eliminado correctamente.";
            } else {
                $error = "Error al eliminar el perfil (puede estar asignado a usuarios).";
            }
        }
    } elseif ($action === 'guardar_permisos') {
        $id_Rol = intval($_POST['id_Rol']);
        $modulos = $_POST['modulos'] ?? []; // Array of module IDs

        $conexion->begin_transaction();
        try {
            // Clear existing
            $stmt = $conexion->prepare("DELETE FROM rol_modulo WHERE id_Rol = ?");
            $stmt->bind_param("i", $id_Rol);
            $stmt->execute();

            // Insert new
            if (!empty($modulos)) {
                $stmt_ins = $conexion->prepare("INSERT INTO rol_modulo (id_Rol, id_Modulo) VALUES (?, ?)");
                foreach ($modulos as $modId) {
                    $modId = intval($modId);
                    $stmt_ins->bind_param("ii", $id_Rol, $modId);
                    $stmt_ins->execute();
                }
            }
            $conexion->commit();
            $mensaje = "Permisos actualizados correctamente.";
        } catch (Exception $e) {
            $conexion->rollback();
            $error = "Error al actualizar los permisos.";
        }
    }
}

// Get all roles
$roles = $conexion->query("SELECT * FROM rol ORDER BY id_Rol ASC")->fetch_all(MYSQLI_ASSOC);

// Get all modules
$modulos = $conexion->query("SELECT * FROM modulo ORDER BY id_Modulo ASC")->fetch_all(MYSQLI_ASSOC);

// Get permissions mapping
$permisos = [];
$res = $conexion->query("SELECT id_Rol, id_Modulo FROM rol_modulo");
while ($row = $res->fetch_assoc()) {
    $permisos[$row['id_Rol']][] = $row['id_Modulo'];
}

require_once '../Views/admin_perfiles.php';
