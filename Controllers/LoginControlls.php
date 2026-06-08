<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/');
    session_start();
}
include("../Models/UsuarioLogin.php");

$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario_o_correo = trim($_POST['usuario_o_correo'] ?? '');
    $contraseña = trim($_POST['contraseña'] ?? '');

    // Configuraación local: root y sin contraseña
    $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");

    if ($conexion->connect_error) {
        die("Error de conexión");
    }

    $resultado = Usuario::login($conexion, $usuario_o_correo);

    if ($resultado && $resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($contraseña, $usuario['Usuario_Contraseña'])) {
            $_SESSION['id_Usuario'] = $usuario['id_Usuario'];
            $_SESSION['nombre_usuario'] = $usuario['Usuario_Nombre'];
            $_SESSION['usuario'] = $usuario['Usuario_Nombre'];
            $_SESSION['Correo_E'] = $usuario['Correo_E'];
            $_SESSION['id_Rol'] = $usuario['id_Rol'];

            $sql_emp = "SELECT e.id_Empleado, e.id_Rol, p.Persona_Nombre, p.Persona_Apellido, p.Persona_Telefono, p.Persona_Domicilio
                        FROM empleado e
                        JOIN persona p ON e.id_Persona = p.id_Persona
                        WHERE e.id_Usuario = ?";
            
            $stmt_emp = $conexion->prepare($sql_emp);
            $stmt_emp->bind_param("i", $usuario['id_Usuario']);
            $stmt_emp->execute();
            $res_emp = $stmt_emp->get_result();

            if ($res_emp->num_rows > 0) {
                $datos_emp = $res_emp->fetch_assoc();
                $_SESSION['id_Empleado'] = $datos_emp['id_Empleado'];
                $_SESSION['id_Rol'] = $datos_emp['id_Rol']; 
                
                // Guardar datos personales del empleado en sesión
                $_SESSION['persona_nombre'] = $datos_emp['Persona_Nombre'];
                $_SESSION['persona_apellido'] = $datos_emp['Persona_Apellido'];
                $_SESSION['persona_telefono'] = $datos_emp['Persona_Telefono'];
                $_SESSION['persona_domicilio'] = $datos_emp['Persona_Domicilio'];

            } else {
                // Si no es empleado, buscamos en la tabla Paciente
                $sql_pac = "SELECT pac.id_Paciente, p.Persona_Nombre, p.Persona_Apellido, p.Persona_Telefono, p.Persona_Domicilio
                            FROM paciente pac
                            JOIN persona p ON pac.id_Persona = p.id_Persona
                            WHERE pac.id_Usuario = ?";
                
                $stmt_pac = $conexion->prepare($sql_pac);
                $stmt_pac->bind_param("i", $usuario['id_Usuario']);
                $stmt_pac->execute();
                $res_pac = $stmt_pac->get_result();

                if ($res_pac->num_rows > 0) {
                    $datos_pac = $res_pac->fetch_assoc();
                    $_SESSION['id_Paciente'] = $datos_pac['id_Paciente'];
                    $_SESSION['id_Rol'] = 0;  // Rol 0 = Paciente
                    
                    // Guarda datos personales del paciente en sesión
                    $_SESSION['persona_nombre'] = $datos_pac['Persona_Nombre'];
                    $_SESSION['persona_apellido'] = $datos_pac['Persona_Apellido'];
                    $_SESSION['persona_telefono'] = $datos_pac['Persona_Telefono'];
                    $_SESSION['persona_domicilio'] = $datos_pac['Persona_Domicilio'];
                }
            }

            header("Location: Index.php");
            exit;

        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario o correo no encontrado";
    }
}

include("LoginViews.php");
?>