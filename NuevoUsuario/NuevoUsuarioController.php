<?php

require_once __DIR__ . '/NuevoUsuarioModel.php';

class NuevoUsuarioController {

    private NuevoUsuarioModel $model;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params(0, '/');
            session_start();
        }

        // Solo el Administrador (rol 3) puede acceder
        if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
            header("Location: ../Login/Login.php");
            exit();
        }

        $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        $this->model = new NuevoUsuarioModel($conexion);
    }

    public function manejarRequest(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->registrar();
        } else {
            $error = $_GET['error'] ?? '';
            $this->mostrarFormulario($error);
        }
    }

    private function mostrarFormulario(string $error = ''): void {
        $roles_empleado = $this->model->obtenerEspecialidades();
        require_once __DIR__ . '/nuevo_usuario.php';
    }

    private function registrar(): void {
        $tipo_usuario         = $_POST['tipo_usuario']         ?? 'cliente';
        $nombre               = trim($_POST['nombre']               ?? '');
        $apellido             = trim($_POST['apellido']             ?? '');
        $usuario_nombre       = trim($_POST['usuario_nombre']       ?? '');
        $correo               = trim($_POST['correo']               ?? '');
        $contrasena           = $_POST['contrasena']                ?? '';
        $confirmar_contrasena = $_POST['confirmar_contrasena']      ?? '';
        $especialidad         = trim($_POST['especialidad']         ?? '');

        if ($contrasena !== $confirmar_contrasena) {
            $this->mostrarFormulario('Las contraseñas no coinciden.');
            return;
        }

        if ($this->model->usuarioExiste($usuario_nombre, $correo)) {
            $this->mostrarFormulario('El nombre de usuario o correo ya están en uso.');
            return;
        }

        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

        $exito = $this->model->registrarUsuario(
            $tipo_usuario, $nombre, $apellido, $usuario_nombre,
            $correo, $contrasena_hash, $especialidad
        );

        if ($exito) {
            header("Location: ../Controllers/admin_usuariosController.php?msg=usuario_registrado");
            exit();
        } else {
            $this->mostrarFormulario('Ocurrió un error al registrar el usuario. Intente nuevamente.');
        }
    }
}

$controller = new NuevoUsuarioController();
$controller->manejarRequest();
?>