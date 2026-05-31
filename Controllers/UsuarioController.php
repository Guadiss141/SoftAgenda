<?php

class UsuarioController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
            if ($conexion->connect_error) {
                die("Error de conexión: " . $conexion->connect_error);
            }

            require_once '../Models/Usuario.php';
            $modelo = new Usuario($conexion);

            $usuario  = $_POST['usuario_o_correo'];
            $password = $_POST['contraseña'];

            $user_data = $modelo->validarLogin($usuario, $password);

            if ($user_data) {
                if (session_status() === PHP_SESSION_NONE) session_start();

                $_SESSION['id_Usuario']     = $user_data['id_Usuario'];
                $_SESSION['nombre_usuario'] = $user_data['Usuario_Nombre'];
                $_SESSION['usuario']        = $user_data['Usuario_Nombre'];
                $_SESSION['Correo_E']       = $user_data['Correo_E'];
                $_SESSION['correo_e']       = $user_data['Correo_E'];
                $_SESSION['id_Rol']         = $user_data['id_Rol'];

                // Buscar en Empleado
                $sql_emp = "SELECT e.id_Empleado, e.id_Rol,
                                   p.Persona_Nombre, p.Persona_Apellido,
                                   p.Persona_Telefono, p.Persona_Domicilio
                            FROM empleado e
                            JOIN persona p ON e.id_Persona = p.id_Persona
                            WHERE e.id_Usuario = ?";
                $stmt_emp = $conexion->prepare($sql_emp);
                $stmt_emp->bind_param("i", $user_data['id_Usuario']);
                $stmt_emp->execute();
                $res_emp = $stmt_emp->get_result();

                if ($res_emp->num_rows > 0) {
                    $datos_emp = $res_emp->fetch_assoc();
                    $_SESSION['id_Empleado']      = $datos_emp['id_Empleado'];
                    $_SESSION['id_Rol']            = $datos_emp['id_Rol'];
                    $_SESSION['persona_nombre']    = $datos_emp['Persona_Nombre'];
                    $_SESSION['persona_apellido']  = $datos_emp['Persona_Apellido'];
                    $_SESSION['persona_telefono']  = $datos_emp['Persona_Telefono'];
                    $_SESSION['persona_domicilio'] = $datos_emp['Persona_Domicilio'];
                } else {
                    // Buscar en Paciente
                    $sql_pac = "SELECT pac.id_Paciente,
                                       p.Persona_Nombre, p.Persona_Apellido,
                                       p.Persona_Telefono, p.Persona_Domicilio
                                FROM paciente pac
                                JOIN persona p ON pac.id_Persona = p.id_Persona
                                WHERE pac.id_Usuario = ?";
                    $stmt_pac = $conexion->prepare($sql_pac);
                    $stmt_pac->bind_param("i", $user_data['id_Usuario']);
                    $stmt_pac->execute();
                    $res_pac = $stmt_pac->get_result();

                    if ($res_pac->num_rows > 0) {
                        $datos_pac = $res_pac->fetch_assoc();
                        $_SESSION['id_Paciente']       = $datos_pac['id_Paciente'];
                        $_SESSION['id_Rol']             = 0; // 0 = Paciente
                        $_SESSION['persona_nombre']    = $datos_pac['Persona_Nombre'];
                        $_SESSION['persona_apellido']  = $datos_pac['Persona_Apellido'];
                        $_SESSION['persona_telefono']  = $datos_pac['Persona_Telefono'];
                        $_SESSION['persona_domicilio'] = $datos_pac['Persona_Domicilio'];
                    }
                }

                header("Location: ../Views/Index.php");
                exit;
            } else {
                header("Location: ../Views/Login.php?error=Usuario o clave incorrecta");
                exit;
            }
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        header("Location: ../Views/Login.php");
        exit();
    }

    // ve todos los usuarios
    public function listar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
            header("Location: ../Views/Login.php");
            exit();
        }

        $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
        require_once '../Models/Usuario.php';
        $modelo = new Usuario($conexion);

        $usuarios = $modelo->listarTodos();

        // Pasar datos a la vista
        require_once '../Views/admin_usuarios.php';
        exit();
    }

    //admin registra nuevo terapeuta 

    public function registrar_terapeuta() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
                header("Location: ../Views/Login.php");
                exit();
            }

            $nombre              = trim($_POST['nombre']);
            $apellido            = trim($_POST['apellido']);
            $usuario_nombre      = trim($_POST['usuario_nombre']);
            $correo              = trim($_POST['correo']);
            $contrasena          = $_POST['contrasena'];
            $confirmar_contrasena = $_POST['confirmar_contrasena'];
            $especialidad        = trim($_POST['especialidad']);
            $cuil                = trim($_POST['cuil']);

            if ($contrasena !== $confirmar_contrasena) {
                header("Location: ../Views/registro_terapeuta.php?error=Las contraseñas no coinciden");
                exit();
            }

            $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
            if ($conexion->connect_error) die("Error de conexión");

            $sql_check = "SELECT id_Usuario FROM usuario WHERE Usuario_Nombre = ? OR Correo_E = ?";
            $stmt_check = $conexion->prepare($sql_check);
            $stmt_check->bind_param("ss", $usuario_nombre, $correo);
            $stmt_check->execute();
            if ($stmt_check->get_result()->num_rows > 0) {
                header("Location: ../Views/registro_terapeuta.php?error=El usuario o correo ya existe");
                exit();
            }

            $conexion->begin_transaction();
            try {
                $hash    = password_hash($contrasena, PASSWORD_DEFAULT);
                $id_Rol  = 2; // Terapeuta

                $sql_u = "INSERT INTO usuario (id_Rol, Usuario_Nombre, Correo_E, Usuario_Contraseña) VALUES (?, ?, ?, ?)";
                $stmt_u = $conexion->prepare($sql_u);
                $stmt_u->bind_param("isss", $id_Rol, $usuario_nombre, $correo, $hash);
                $stmt_u->execute();
                $id_Usuario = $stmt_u->insert_id;

                $sql_p = "INSERT INTO persona (Persona_Nombre, Persona_Apellido) VALUES (?, ?)";
                $stmt_p = $conexion->prepare($sql_p);
                $stmt_p->bind_param("ss", $nombre, $apellido);
                $stmt_p->execute();
                $id_Persona = $stmt_p->insert_id;

                $sql_e = "INSERT INTO empleado (id_Usuario, id_Persona, id_Rol, Especialidad, CUIL) VALUES (?, ?, ?, ?, ?)";
                $stmt_e = $conexion->prepare($sql_e);
                $stmt_e->bind_param("iiiss", $id_Usuario, $id_Persona, $id_Rol, $especialidad, $cuil);
                $stmt_e->execute();

                $conexion->commit();
                header("Location: ../Views/admin_usuarios.php?msg=terapeuta_registrado");
            } catch (Exception $e) {
                $conexion->rollback();
                header("Location: ../Views/registro_terapeuta.php?error=" . urlencode($e->getMessage()));
            }
            exit();
        }
    }

    // puede editar cualquier usuario el admin

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
                header("Location: ../Views/Login.php");
                exit();
            }

            $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
            if ($conexion->connect_error) die("Error de conexión");

            require_once '../Models/Usuario.php';
            $modelo = new Usuario($conexion);

            $datos = [
                'id_Usuario'     => $_POST['id_Usuario'],
                'nombre'         => $_POST['nombre'],
                'apellido'       => $_POST['apellido'],
                'telefono'       => $_POST['telefono'],
                'domicilio'      => $_POST['domicilio'],
                'usuario_nombre' => $_POST['usuario_nombre'],
                'correo'         => $_POST['correo']
            ];

            if (isset($_POST['especialidad'])) {
                $datos['especialidad'] = $_POST['especialidad'];
                $datos['cuil']         = $_POST['cuil'];
            }

            if ($modelo->actualizarUsuarioCompleto($datos)) {
                header("Location: ../Controllers/admin_usuariosController.php?msg=editado");
            } else {
                header("Location: ../Controllers/admin_usuariosController.php?error=edicion");
            }
            exit();
        }
    }

    // admin elimina usuario (y datos relacionados)

    public function eliminar() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
            header("Location: ../Views/Login.php");
            exit();
        }

        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
            if (!$conexion->connect_error) {
                $conexion->begin_transaction();
                try {
                    $sql_get_p = "SELECT p.id_Persona FROM persona p
                                  LEFT JOIN empleado e ON p.id_Persona = e.id_Persona
                                  LEFT JOIN paciente pac ON p.id_Persona = pac.id_Persona
                                  WHERE e.id_Usuario = ? OR pac.id_Usuario = ?";
                    $stmt_get = $conexion->prepare($sql_get_p);
                    $stmt_get->bind_param("ii", $id, $id);
                    $stmt_get->execute();
                    $res_get = $stmt_get->get_result();
                    $personas = [];
                    while ($row = $res_get->fetch_assoc()) {
                        $personas[] = $row['id_Persona'];
                    }

                    $conexion->query("DELETE t FROM turno t JOIN paciente pac ON t.id_Paciente = pac.id_Paciente WHERE pac.id_Usuario = $id");
                    $conexion->query("DELETE t FROM turno t JOIN empleado e ON t.id_Empleado = e.id_Empleado WHERE e.id_Usuario = $id");
                    $conexion->query("DELETE FROM empleado WHERE id_Usuario = $id");
                    $conexion->query("DELETE FROM paciente WHERE id_Usuario = $id");
                    $conexion->query("DELETE FROM usuario WHERE id_Usuario = $id");

                    foreach ($personas as $id_p) {
                        $conexion->query("DELETE FROM persona WHERE id_Persona = $id_p");
                    }

                    $conexion->commit();
                } catch (Exception $e) {
                    $conexion->rollback();
                }
            }
        }
        header("Location: ../Controllers/admin_usuariosController.php");
        exit();
    }
    
    // PERFIL: ver perfil propio (todos los roles)
    public function perfil() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_Usuario'])) {
            header("Location: ../Views/Login.php");
            exit();
        }

        $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
        require_once '../Models/Usuario.php';
        $modelo = new Usuario($conexion);

        $id_Usuario = $_SESSION['id_Usuario'];
        $usuario    = $modelo->obtenerPorId($id_Usuario);

        // Si es paciente (Rol 0), cargar también sus turnos
        $turnos = null;
        if ($_SESSION['id_Rol'] == 0) {
            $turnos = $modelo->listarTurnosPorPaciente($id_Usuario);
        }

        // Si es terapeuta (Rol 2), cargar sus citas
        $citas = [];
        if ($_SESSION['id_Rol'] == 2) {
            $res = $modelo->listarCitasPorTerapeuta($id_Usuario);
            while ($row = $res->fetch_assoc()) $citas[] = $row;
        }

        require_once '../Views/perfil.php';
        exit();
    }

    // perfil: guardar cambios del perfil propio
    // Paciente y Terapeuta

    public function actualizarPerfil() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (!isset($_SESSION['id_Usuario'])) {
                header("Location: ../Views/Login.php");
                exit();
            }

            $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
            require_once '../Models/Usuario.php';
            $modelo = new Usuario($conexion);

            $datos = [
                'id_Usuario' => $_SESSION['id_Usuario'],
                'nombre'     => trim($_POST['nombre']),
                'apellido'   => trim($_POST['apellido']),
                'telefono'   => trim($_POST['telefono']),
                'domicilio'  => trim($_POST['domicilio']),
                'correo'     => trim($_POST['correo'])
            ];

            // Contraseña opcional
            if (!empty($_POST['nueva_contrasena'])) {
                $datos['nueva_contrasena'] = $_POST['nueva_contrasena'];
            }

            if ($modelo->actualizarPerfil($datos)) {
                // Actualizar datos en sesión
                $_SESSION['Correo_E']          = $datos['correo'];
                $_SESSION['persona_nombre']    = $datos['nombre'];
                $_SESSION['persona_apellido']  = $datos['apellido'];
                $_SESSION['persona_telefono']  = $datos['telefono'];
                $_SESSION['persona_domicilio'] = $datos['domicilio'];

                header("Location: ../Views/perfil.php?msg=perfil_actualizado");
            } else {
                header("Location: ../Views/perfil.php?error=No se pudo actualizar");
            }
            exit();
        }
    }

    // agregar turno 

    public function agregarTurno() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 0) {
                header("Location: ../Views/Login.php");
                exit();
            }

            $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
            require_once '../Models/Usuario.php';
            $modelo = new Usuario($conexion);

            $id_Usuario  = $_SESSION['id_Usuario'];
            $id_Empleado = intval($_POST['id_Empleado']);
            $id_Servicio = intval($_POST['id_Servicio']);
            $fecha       = $_POST['fecha'];
            $hora        = $_POST['hora'];

            if ($modelo->agregarTurno($id_Usuario, $id_Empleado, $id_Servicio, $fecha, $hora)) {
                header("Location: ../Views/perfil.php?msg=turno_agregado");
            } else {
                header("Location: ../Views/perfil.php?error=No se pudo agregar el turno");
            }
            exit();
        }
    }

    // eliminar turno (desde perfil paciente)   

    public function eliminarTurno() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 0) {
            header("Location: ../Views/Login.php");
            exit();
        }

        if (isset($_GET['id'])) {
            $id_Turno   = intval($_GET['id']);
            $id_Usuario = $_SESSION['id_Usuario'];

            $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
            require_once '../Models/Usuario.php';
            $modelo = new Usuario($conexion);

            $modelo->eliminarTurno($id_Turno, $id_Usuario);
        }

        header("Location: ../Views/perfil.php?msg=turno_eliminado");
        exit();
    }

    // terapeuta ve sus citas

    public function verCitas() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 2) {
            header("Location: ../Views/Login.php");
            exit();
        }

        $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
        require_once '../Models/Usuario.php';
        $modelo = new Usuario($conexion);

        $citas = $modelo->listarCitasPorTerapeuta($_SESSION['id_Usuario']);

        require_once '../Views/citas_terapeuta.php';
        exit();
    }
}

$controller = new UsuarioController();
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'login':               $controller->login();               break;
        case 'logout':              $controller->logout();              break;
        case 'listar':              $controller->listar();              break;
        case 'editar':              $controller->editar();              break;
        case 'registrar_terapeuta': $controller->registrar_terapeuta(); break;
        case 'eliminar':            $controller->eliminar();            break;
        case 'perfil':              $controller->perfil();              break;
        case 'actualizarPerfil':    $controller->actualizarPerfil();    break;
        case 'agregarTurno':        $controller->agregarTurno();        break;
        case 'eliminarTurno':       $controller->eliminarTurno();       break;
        case 'verCitas':            $controller->verCitas();            break;
    }
}
?>