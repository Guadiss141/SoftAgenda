<?php

require_once __DIR__ . '/../models/PerfilModel.php';

class PerfilController {

    private PerfilModel $model;
    private $conexion;

    public function __construct() {
        // Iniciar sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params(0, '/');
            session_start();
        }

        // Verificar que el usuario esté logueado
        if (!isset($_SESSION['id_Usuario']) || !isset($_SESSION['id_Rol'])) {
            header("Location: Login.php");
            exit();
        }

        // Redirigir Admin al módulo de administración
        if ($_SESSION['id_Rol'] == 3) {
            header("Location: admin_usuarios.php");
            exit();
        }

        // Conectar a la base de datos
        $this->conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }

        $this->model = new PerfilModel($this->conexion);
    }

    /**
     * Punto de entrada principal.
     * Decide qué acción ejecutar según el REQUEST_METHOD y el POST 'action'.
     */
    public function manejarRequest(): void {
        $action = $_POST['action'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'cancelar_turno') {
            $this->cancelarTurno();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'editar_perfil') {
            $this->editarPerfil();
        } else {
            $this->mostrarPerfil();
        }
    }

    
    // Acción: Mostrar perfil (GET)

    private function mostrarPerfil(string $mensaje = ''): void {
        $datos = $this->cargarDatosVista();
        $datos['mensaje'] = $mensaje;

        extract($datos, EXTR_SKIP);

        // Pasar datos a la View
        require_once __DIR__ . '/../Views/PerfilView.php';
    }


    // Acción: Cancelar turno (POST)
    // ─────────────────────────────────────────────
    private function cancelarTurno(): void {
        $id_turno  = intval($_POST['id_turno'] ?? 0);
        $id_Paciente = $_SESSION['id_Paciente'] ?? null;
        $mensaje   = '';

        if ($id_turno > 0 && $id_Paciente) {
            $exito = $this->model->cancelarTurno($id_turno, $id_Paciente);
            $mensaje = $exito
                ? "<div class='alert alert-success'>Turno cancelado con éxito!</div>"
                : "<div class='alert alert-danger'>Error al cancelar el turno.</div>";
        }

        $this->mostrarPerfil($mensaje);
    }

    // ─────────────────────────────────────────────
    // Acción: Editar perfil (POST)
    // ─────────────────────────────────────────────
    private function editarPerfil(): void {
        $id_Usuario = $_SESSION['id_Usuario'];
        $id_persona = $_SESSION['id_Persona'] ?? null;
        $mensaje    = '';

        // Leer y sanear inputs
        $nombre      = trim($_POST['nombre']      ?? '');
        $apellido    = trim($_POST['apellido']    ?? '');
        $telefono    = trim($_POST['telefono']    ?? '');
        $domicilio   = trim($_POST['domicilio']   ?? '');
        $correo      = trim($_POST['correo']      ?? '');
        $dni         = trim($_POST['dni']         ?? '');
        $fecha_nac   = trim($_POST['fecha_nac']   ?? '') ?: null;
        $descripcion = trim($_POST['descripcion'] ?? '');

        $exito_persona = $this->model->actualizarPersona(
            $id_persona, $nombre, $apellido,
            $telefono, $domicilio, $dni, $fecha_nac, $descripcion
        );

        if ($exito_persona) {
            $this->model->actualizarCorreo($id_Usuario, $correo);

            // Reflejar cambios en sesión para que la view los vea sin re-login
            $_SESSION['persona_nombre']    = $nombre;
            $_SESSION['persona_apellido']  = $apellido;
            $_SESSION['persona_telefono']  = $telefono;
            $_SESSION['persona_domicilio'] = $domicilio;
            $_SESSION['correo_e']          = $correo;

            $mensaje = "<div class='alert alert-success'>Información actualizada correctamente.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al actualizar la información.</div>";
        }

        $this->mostrarPerfil($mensaje);
    }

    // ─────────────────────────────────────────────
    // Helper: armar el array de datos para la View
    // ─────────────────────────────────────────────
    private function cargarDatosVista(): array {
        $id_Usuario = $_SESSION['id_Usuario'];
        $id_Rol     = $_SESSION['id_Rol'];

        // Datos de persona desde el Model
        $datos_p    = $this->model->getPersonaPorUsuario($id_Usuario, $id_Rol);
        $id_persona = $datos_p['id_Persona'] ?? null;

        // Guardar id_Persona en sesión (útil para editar perfil)
        $_SESSION['id_Persona'] = $id_persona;

        $persona = [
            'Persona_Nombre'       => $datos_p['Persona_Nombre']       ?? '',
            'Persona_Apellido'     => $datos_p['Persona_Apellido']     ?? '',
            'Persona_Telefono'     => $datos_p['Persona_Telefono']     ?? '',
            'Persona_Domicilio'    => $datos_p['Persona_Domicilio']    ?? '',
            'Persona_DNI'          => $datos_p['Persona_DNI']          ?? '',
            'Fecha_Nac'            => $datos_p['Fecha_Nac']            ?? '',
            'Persona_Descripcion'  => $datos_p['Persona_Descripcion']  ?? '',
        ];

        $usuario_data = [
            'Correo_E' => $_SESSION['correo_e'] ?? '',
        ];

        // Turnos solo si es Paciente
        $turnos = [];
        if ($id_Rol == 0 && isset($_SESSION['id_Paciente'])) {
            $turnos = $this->model->getTurnosPorPaciente($_SESSION['id_Paciente']);
        }

        return compact('id_Rol', 'persona', 'usuario_data', 'turnos');
    }
}