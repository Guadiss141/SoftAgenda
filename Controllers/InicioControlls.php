<?php
if ($user_data) {
    session_start();
    $_SESSION['id_Usuario'] = $user_data['id_Usuario'];
    $_SESSION['nombre_usuario'] = $user_data['Usuario_Nombre'];
    $_SESSION['usuario'] = $user_data['Usuario_Nombre'];
    $_SESSION['Correo_E'] = $user_data['Correo_E'];
    
    // Incluir conexión para buscar id_Paciente e id_Empleado
    $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
    
    if (!$conexion->connect_error) {
        // Buscar en tabla Empleado
        $sql_emp = "SELECT e.id_Empleado, e.id_Rol FROM empleado e WHERE e.id_Usuario = ?";
        $stmt_emp = $conexion->prepare($sql_emp);
        $stmt_emp->bind_param("i", $user_data['id_Usuario']);
        $stmt_emp->execute();
        $res_emp = $stmt_emp->get_result();

        if ($res_emp->num_rows > 0) {
            $datos_emp = $res_emp->fetch_assoc();
            $_SESSION['id_Empleado'] = $datos_emp['id_Empleado'];
            $_SESSION['id_Rol'] = $datos_emp['id_Rol'];
        } else {
            // Buscar en tabla Paciente
            $sql_pac = "SELECT pac.id_Paciente FROM paciente pac WHERE pac.id_Usuario = ?";
            $stmt_pac = $conexion->prepare($sql_pac);
            $stmt_pac->bind_param("i", $user_data['id_Usuario']);
            $stmt_pac->execute();
            $res_pac = $stmt_pac->get_result();

            if ($res_pac->num_rows > 0) {
                $datos_pac = $res_pac->fetch_assoc();
                $_SESSION['id_Paciente'] = $datos_pac['id_Paciente'];
                $_SESSION['id_Rol'] = 0;
            }
        }
        
        $conexion->close();
    }
    
    header("Location: ../Views/Index.php");
    exit;
}
?>