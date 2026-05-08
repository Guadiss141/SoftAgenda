<?php

class UsuarioController {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $conexion = new mysqli("localhost", "root", "", "gestionspabd");
            
            if ($conexion->connect_error) {
                die("Error de conexión: " . $conexion->connect_error);
            }

            require_once '../Models/Usuario.php';
            $modelo = new Usuario($conexion);

            $usuario = $_POST['usuario_o_correo'];
            $password = $_POST['contraseña'];

            $user_data = $modelo->validarLogin($usuario, $password);

            if ($user_data) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                
                // Datos básicos
                $_SESSION['id_Usuario'] = $user_data['id_Usuario'];
                $_SESSION['nombre_usuario'] = $user_data['Usuario_Nombre'];
                $_SESSION['usuario'] = $user_data['Usuario_Nombre'];
                $_SESSION['Correo_E'] = $user_data['Correo_E'];
                $_SESSION['id_Rol'] = $user_data['id_Rol'];
                
                // 1. Buscar en tabla Empleado
                $sql_emp = "SELECT e.id_Empleado, e.id_Rol, p.Persona_Nombre, p.Persona_Apellido, p.Persona_Telefono, p.Persona_Domicilio
                            FROM empleado e
                            JOIN persona p ON e.id_Persona = p.id_Persona
                            WHERE e.id_Usuario = ?";
                
                $stmt_emp = $conexion->prepare($sql_emp);
                $stmt_emp->bind_param("i", $user_data['id_Usuario']);
                $stmt_emp->execute();
                $res_emp = $stmt_emp->get_result();

                if ($res_emp->num_rows > 0) {
                    // ES UN EMPLEADO
                    $datos_emp = $res_emp->fetch_assoc();
                    $_SESSION['id_Empleado'] = $datos_emp['id_Empleado'];
                    $_SESSION['id_Rol'] = $datos_emp['id_Rol'];
                    
                    // Guardar datos personales
                    $_SESSION['persona_nombre'] = $datos_emp['Persona_Nombre'];
                    $_SESSION['persona_apellido'] = $datos_emp['Persona_Apellido'];
                    $_SESSION['persona_telefono'] = $datos_emp['Persona_Telefono'];
                    $_SESSION['persona_domicilio'] = $datos_emp['Persona_Domicilio'];

                } else {
                    // 2. Buscar en tabla Paciente
                    $sql_pac = "SELECT pac.id_Paciente, p.Persona_Nombre, p.Persona_Apellido, p.Persona_Telefono, p.Persona_Domicilio
                                FROM paciente pac
                                JOIN persona p ON pac.id_Persona = p.id_Persona
                                WHERE pac.id_Usuario = ?";
                    
                    $stmt_pac = $conexion->prepare($sql_pac);
                    $stmt_pac->bind_param("i", $user_data['id_Usuario']);
                    $stmt_pac->execute();
                    $res_pac = $stmt_pac->get_result();

                    if ($res_pac->num_rows > 0) {
                        // ES UN PACIENTE
                        $datos_pac = $res_pac->fetch_assoc();
                        $_SESSION['id_Paciente'] = $datos_pac['id_Paciente'];
                        $_SESSION['id_Rol'] = 0;  // Rol 0 = Paciente
                        
                        // Guardar datos personales
                        $_SESSION['persona_nombre'] = $datos_pac['Persona_Nombre'];
                        $_SESSION['persona_apellido'] = $datos_pac['Persona_Apellido'];
                        $_SESSION['persona_telefono'] = $datos_pac['Persona_Telefono'];
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

    public function editar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
                header("Location: ../Views/Login.php");
                exit();
            }

            $conexion = new mysqli("localhost", "root", "", "gestionspabd");
            if ($conexion->connect_error) {
                die("Error de conexión");
            }
            require_once '../Models/Usuario.php';
            $modelo = new Usuario($conexion);

            $datos = array(
                'id_Usuario' => $_POST['id_Usuario'],
                'nombre' => $_POST['nombre'],
                'apellido' => $_POST['apellido'],
                'telefono' => $_POST['telefono'],
                'domicilio' => $_POST['domicilio'],
                'usuario_nombre' => $_POST['usuario_nombre'],
                'correo' => $_POST['correo']
            );

            if (isset($_POST['especialidad'])) {
                $datos['especialidad'] = $_POST['especialidad'];
                $datos['cuil'] = $_POST['cuil'];
            }

            if ($modelo->actualizarUsuarioCompleto($datos)) {
                header("Location: ../Views/admin_usuarios.php?msg=editado");
            } else {
                header("Location: ../Views/admin_usuarios.php?error=edicion");
            }
            exit();
        }
    }

    public function registrar_terapeuta() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
                header("Location: ../Views/Login.php");
                exit();
            }

            $nombre = trim($_POST['nombre']);
            $apellido = trim($_POST['apellido']);
            $usuario_nombre = trim($_POST['usuario_nombre']);
            $correo = trim($_POST['correo']);
            $contrasena = $_POST['contrasena'];
            $confirmar_contrasena = $_POST['confirmar_contrasena'];
            $especialidad = trim($_POST['especialidad']);
            $cuil = trim($_POST['cuil']);

            if ($contrasena !== $confirmar_contrasena) {
                header("Location: ../Views/registro_terapeuta.php?error=Las contraseñas no coinciden");
                exit();
            }

            $conexion = new mysqli("localhost", "root", "", "gestionspabd");
            if ($conexion->connect_error) {
                die("Error de conexión");
            }

            // Verificar si el usuario ya existe
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
                // 1. Insertar en Usuario (Rol 2 = Terapeuta)
                $hash = password_hash($contrasena, PASSWORD_DEFAULT);
                $id_Rol = 2; 
                $sql_u = "INSERT INTO usuario (id_Rol, Usuario_Nombre, Correo_E, Usuario_Contraseña) VALUES (?, ?, ?, ?)";
                $stmt_u = $conexion->prepare($sql_u);
                $stmt_u->bind_param("isss", $id_Rol, $usuario_nombre, $correo, $hash);
                $stmt_u->execute();
                $id_Usuario = $stmt_u->insert_id;

                // 2. Insertar en Persona
                $sql_p = "INSERT INTO persona (Persona_Nombre, Persona_Apellido) VALUES (?, ?)";
                $stmt_p = $conexion->prepare($sql_p);
                $stmt_p->bind_param("ss", $nombre, $apellido);
                $stmt_p->execute();
                $id_Persona = $stmt_p->insert_id;

                // 3. Insertar en Empleado
                $sql_e = "INSERT INTO empleado (id_Usuario, id_Persona, id_Rol, Especialidad, CUIL) VALUES (?, ?, ?, ?, ?)";
                $stmt_e = $conexion->prepare($sql_e);
                $stmt_e->bind_param("iiiss", $id_Usuario, $id_Persona, $id_Rol, $especialidad, $cuil);
                $stmt_e->execute();

                $conexion->commit();
                header("Location: ../Views/admin_usuarios.php?msg=terapeuta_registrado");
            } catch (Exception $e) {
                $conexion->rollback();
                header("Location: ../Views/registro_terapeuta.php?error=Error al registrar: " . urlencode($e->getMessage()));
            }
            exit();
        }
    }
    public function eliminar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
            header("Location: ../Views/Login.php");
            exit();
        }

        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $conexion = new mysqli("localhost", "root", "", "gestionspabd");
            if (!$conexion->connect_error) {
                $conexion->begin_transaction();
                try {
                    // Obtener id_Persona asociado para eliminarlo también
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

                    // Eliminar de empleado y paciente
                    $conexion->query("DELETE FROM empleado WHERE id_Usuario = $id");
                    $conexion->query("DELETE FROM paciente WHERE id_Usuario = $id");
                    
                    // Eliminar de usuario
                    $conexion->query("DELETE FROM usuario WHERE id_Usuario = $id");

                    // Eliminar personas asociadas (opcional, pero mantiene la BD limpia)
                    foreach ($personas as $id_p) {
                        $conexion->query("DELETE FROM persona WHERE id_Persona = $id_p");
                    }

                    $conexion->commit();
                } catch (Exception $e) {
                    $conexion->rollback();
                }
            }
        }
        header("Location: ../Views/perfil.php");
        exit();
    }
}

// Lógica de ejecución
$controller = new UsuarioController();
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'login') {
        $controller->login();
    } elseif ($_GET['action'] == 'editar') {
        $controller->editar();
    } elseif ($_GET['action'] == 'registrar_terapeuta') {
        $controller->registrar_terapeuta();
    } elseif ($_GET['action'] == 'eliminar') {
        $controller->eliminar();
    }
}
?>