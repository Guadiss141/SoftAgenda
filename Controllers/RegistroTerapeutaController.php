<?php

require_once __DIR__ . '/../models/RegistroTerapeutaModel.php';

class TerapeutaController {

    private RegistroTerapeutaModel $model;

    public function __construct() {
        // Iniciar sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params(0, '/');
            session_start();
        }

        // Solo el Administrador (rol 3) puede acceder
        if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
            header("Location: Login.php");
            exit();
        }

        $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        $this->model = new TerapeutaModel($conexion);
    }

    /**
     * Punto de entrada: decide si mostrar el form o procesar el registro.
     */
    public function manejarRequest(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->registrar();
        } else {
            $this->mostrarFormulario();
        }
    }

    // ─────────────────────────────────────────────
    // Acción: Mostrar formulario (GET)
    // ─────────────────────────────────────────────
    private function mostrarFormulario(string $error = ''): void {
        require_once __DIR__ . '/../views/registro_terapeuta_view.php';
    }

    // ─────────────────────────────────────────────
    // Acción: Procesar registro (POST)
    // ─────────────────────────────────────────────
    private function registrar(): void {
        // Leer y sanear inputs
        $nombre               = trim($_POST['nombre']               ?? '');
        $apellido             = trim($_POST['apellido']             ?? '');
        $usuario_nombre       = trim($_POST['usuario_nombre']       ?? '');
        $correo               = trim($_POST['correo']               ?? '');
        $contrasena           = $_POST['contrasena']                ?? '';
        $confirmar_contrasena = $_POST['confirmar_contrasena']      ?? '';
        $especialidad         = trim($_POST['especialidad']         ?? '');
        $cuil                 = trim($_POST['cuil']                 ?? '');

        // Validaciones
        if ($contrasena !== $confirmar_contrasena) {
            $this->mostrarFormulario('Las contraseñas no coinciden.');
            return;
        }

        if ($this->model->usuarioExiste($usuario_nombre, $correo)) {
            $this->mostrarFormulario('El nombre de usuario o correo ya están en uso.');
            return;
        }

        // Hashear contraseña antes de guardar
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

        $exito = $this->model->registrarTerapeuta(
            $nombre, $apellido, $usuario_nombre,
            $correo, $contrasena_hash, $especialidad, $cuil
        );

        if ($exito) {
            header("Location: ../views/admin_usuarios.php?mensaje=Terapeuta registrado con éxito");
            exit();
        } else {
            $this->mostrarFormulario('Ocurrió un error al registrar el terapeuta. Intente nuevamente.');
        }
    }
}