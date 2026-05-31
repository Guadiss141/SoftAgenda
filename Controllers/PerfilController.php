<?php

require_once __DIR__ . '/../models/PerfilModel.php';

class PerfilController {

    private PerfilModel $model;
    private $conexion;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params(0, '/');
            session_start();
        }

        if (!isset($_SESSION['id_Usuario']) || !isset($_SESSION['id_Rol'])) {
            header("Location: Login.php");
            exit();
        }

        if ($_SESSION['id_Rol'] == 3) {
            header("Location: ../Controllers/admin_usuariosController.php");
            exit();
        }

        $this->conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }

        $this->model = new PerfilModel($this->conexion);
    }

  
    public function manejarRequest(): void {
        $action = $_POST['action'] ?? null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'cancelar_turno') {
            $this->cancelarTurno();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'editar_perfil') {
            $this->editarPerfil();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'cambiar_contrasena') {
            $this->cambiarContrasena();
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'eliminar_cuenta') {
            $this->eliminarCuenta();
        } else {
            $this->mostrarPerfil();
        }
    }

    
    // mostrar el perfil

    private function mostrarPerfil(string $mensaje = ''): void {
        $datos = $this->cargarDatosVista();
        $datos['mensaje'] = $mensaje;

        extract($datos, EXTR_SKIP);

        require_once __DIR__ . '/../Views/PerfilView.php';
    }

  
    // cancelar turno 
  
    private function cancelarTurno(): void {
        $id_turno    = intval($_POST['id_turno'] ?? 0);
        $id_Paciente = $_SESSION['id_Paciente']  ?? null;
        $mensaje     = '';

        if ($id_turno > 0 && $id_Paciente) {
            $exito = $this->model->cancelarTurno($id_turno, $id_Paciente);
            $mensaje = $exito
                ? "<div class='alert alert-success'>Turno cancelado con éxito!</div>"
                : "<div class='alert alert-danger'>Error al cancelar el turno.</div>";
        }

        $this->mostrarPerfil($mensaje);
    }

 
    // editar perfil

    private function editarPerfil(): void {
        $id_Usuario = $_SESSION['id_Usuario'];
        $id_persona = $_SESSION['id_Persona'] ?? null;

        $nombre      = trim($_POST['nombre']      ?? '');
        $apellido    = trim($_POST['apellido']     ?? '');
        $telefono    = trim($_POST['telefono']     ?? '');
        $domicilio   = trim($_POST['domicilio']    ?? '');
        $correo      = trim($_POST['correo']       ?? '');
        $dni         = trim($_POST['dni']          ?? '');
        $fecha_nac   = trim($_POST['fecha_nac']    ?? '') ?: null;
        $descripcion = trim($_POST['descripcion']  ?? '');

        $exito = $this->model->actualizarPersona(
            $id_persona, $nombre, $apellido,
            $telefono, $domicilio, $dni, $fecha_nac, $descripcion
        );

        if ($exito) {
            $this->model->actualizarCorreo($id_Usuario, $correo);

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

    // CAMBIAR CONTRASEÑA (nuevo)

    private function cambiarContrasena(): void {
        $id_Usuario = $_SESSION['id_Usuario'];
        $actual     = $_POST['contrasena_actual']    ?? '';
        $nueva      = $_POST['contrasena_nueva']     ?? '';
        $confirmar  = $_POST['confirmar_contrasena'] ?? '';

        if ($nueva !== $confirmar) {
            $this->mostrarPerfil("<div class='alert alert-danger'>Las contraseñas nuevas no coinciden.</div>");
            return;
        }

        $hash_actual = $this->model->getContrasena($id_Usuario);

        if (!password_verify($actual, $hash_actual)) {
            $this->mostrarPerfil("<div class='alert alert-danger'>La contraseña actual es incorrecta.</div>");
            return;
        }

        $ok = $this->model->cambiarContrasena($id_Usuario, password_hash($nueva, PASSWORD_DEFAULT));

        $mensaje = $ok
            ? "<div class='alert alert-success'>Contraseña actualizada correctamente.</div>"
            : "<div class='alert alert-danger'>Error al cambiar la contraseña.</div>";

        $this->mostrarPerfil($mensaje);
    }
  
    // eliminar cuenta
    // Usa IDs de sesión para evitar que un usuario elimine la cuenta de otro manipulando el POSt
  
    private function eliminarCuenta(): void {
        $id_Usuario = $_SESSION['id_Usuario'];
        $id_Rol     = $_SESSION['id_Rol'];
        $id_Persona = $_SESSION['id_Persona'] ?? null;

        if (!$id_Persona) {
            $this->mostrarPerfil("<div class='alert alert-danger'>Error: no se pudo identificar el perfil.</div>");
            return;
        }

        if ($id_Rol == 0) {
            // Paciente — también se borran sus turnos
            $id_Paciente = $_SESSION['id_Paciente'] ?? null;
            if (!$id_Paciente) {
                $this->mostrarPerfil("<div class='alert alert-danger'>Error al eliminar la cuenta.</div>");
                return;
            }
            $ok = $this->model->eliminarPaciente($id_Paciente, $id_Persona, $id_Usuario);
        } else {
            // Empleado / Terapeuta
            $id_Empleado = $_SESSION['id_Empleado'] ?? null;
            if (!$id_Empleado) {
                $this->mostrarPerfil("<div class='alert alert-danger'>Error al eliminar la cuenta.</div>");
                return;
            }
            $ok = $this->model->eliminarEmpleado($id_Empleado, $id_Persona, $id_Usuario);
        }

        if ($ok) {
            session_destroy();
            header("Location: ../Views/Login.php?mensaje=Cuenta+eliminada+correctamente");
            exit();
        } else {
            $this->mostrarPerfil("<div class='alert alert-danger'>Error al eliminar la cuenta.</div>");
        }
    }


    // se cargan datos para el view ns

    private function cargarDatosVista(): array {
        $id_Usuario = $_SESSION['id_Usuario'];
        $id_Rol     = $_SESSION['id_Rol'];

        $datos_p    = $this->model->getPersonaPorUsuario($id_Usuario, $id_Rol);
        $id_persona = $datos_p['id_Persona'] ?? null;

        $_SESSION['id_Persona'] = $id_persona;

        $persona = [
            'Persona_Nombre'       => $datos_p['Persona_Nombre']      ?? '',
            'Persona_Apellido'     => $datos_p['Persona_Apellido']    ?? '',
            'Persona_Telefono'     => $datos_p['Persona_Telefono']    ?? '',
            'Persona_Domicilio'    => $datos_p['Persona_Domicilio']   ?? '',
            'Persona_DNI'          => $datos_p['Persona_DNI']         ?? '',
            'Fecha_Nac'            => $datos_p['Fecha_Nac']           ?? '',
            'Persona_Descripcion'  => $datos_p['Persona_Descripcion'] ?? '',
        ];

        // Datos extra según rol para el ABM
        $extra = [];
        if ($id_Rol == 0) {
            $extra = [
                'id_Paciente'           => $datos_p['id_Paciente']            ?? null,
                'Observaciones_Paciente'=> $datos_p['Observaciones_Paciente'] ?? '',
            ];
        } else {
            $extra = [
                'id_Empleado'  => $datos_p['id_Empleado']  ?? null,
                'Especialidad' => $datos_p['Especialidad'] ?? '',
                'CUIL'         => $datos_p['CUIL']         ?? '',
                'Empleado_CBU' => $datos_p['Empleado_CBU'] ?? '',
            ];
        }

      $usuario_data = [
          'Correo_E' => $_SESSION['correo_e'] ?? $_SESSION['Correo_E'] ?? '',
        ];

        $turnos = [];
        if ($id_Rol == 0 && isset($_SESSION['id_Paciente'])) {
            $turnos = $this->model->getTurnosPorPaciente($_SESSION['id_Paciente']);
        }

        $citas = [];
        return compact('id_Rol', 'persona', 'usuario_data', 'turnos', 'extra', 'citas');
    }
}