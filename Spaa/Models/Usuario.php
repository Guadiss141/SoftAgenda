<?php

class Usuario {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

public function validarLogin($usuario, $password) {

    $sql = "SELECT id_Usuario, id_Rol, Usuario_Nombre, Usuario_Contraseña FROM usuario 
            WHERE (Usuario_Nombre = ? OR Correo_E = ?) LIMIT 1";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("ss", $usuario, $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($user = $resultado->fetch_assoc()) {
       
        if (password_verify($password, $user['Usuario_Contraseña'])) {
            return $user; // Éxito: coinciden
        }
    }
    return false;
}

    // Función para listar (ABM)
    public function listarTodos() {
        $query = "SELECT u.id_Usuario, u.Usuario_Nombre, u.Correo_E, 
                         p.Persona_Nombre, p.Persona_Apellido, p.Persona_Telefono,
                         r.Rol_Descripcion,
                         CASE 
                            WHEN e.id_Empleado IS NOT NULL THEN 'Empleado'
                            WHEN pac.id_Paciente IS NOT NULL THEN 'Paciente'
                            ELSE 'Sin asignar'
                         END as Tipo_Usuario
                  FROM usuario u
                  LEFT JOIN empleado e ON u.id_Usuario = e.id_Usuario
                  LEFT JOIN paciente pac ON u.id_Usuario = pac.id_Usuario
                  LEFT JOIN persona p ON (e.id_Persona = p.id_Persona OR pac.id_Persona = p.id_Persona)
                  LEFT JOIN rol r ON u.id_Rol = r.id_Rol
                  ORDER BY u.id_Usuario DESC";

        return $this->db->query($query);
    }

    // Función para actualizar (ABM)
    public function actualizarUsuarioCompleto($datos) {
        try {
            $this->db->begin_transaction();

            $sqlP = "UPDATE persona p
                     JOIN (SELECT id_Persona FROM empleado WHERE id_Usuario = ? 
                           UNION 
                           SELECT id_Persona FROM paciente WHERE id_Usuario = ?) as ids
                     ON p.id_Persona = ids.id_Persona
                     SET p.Persona_Nombre = ?, p.Persona_Apellido = ?, p.Persona_Telefono = ?, p.Persona_Domicilio = ?";
            
            $stmtP = $this->db->prepare($sqlP);
            $stmtP->bind_param("iissss", $datos['id_Usuario'], $datos['id_Usuario'], 
                               $datos['nombre'], $datos['apellido'], $datos['telefono'], $datos['domicilio']);
            $stmtP->execute();

            $sqlU = "UPDATE usuario SET Correo_E = ?, Usuario_Nombre = ? WHERE id_Usuario = ?";
            $stmtU = $this->db->prepare($sqlU);
            $stmtU->bind_param("ssi", $datos['correo'], $datos['usuario_nombre'], $datos['id_Usuario']);
            $stmtU->execute();

            if (isset($datos['especialidad'])) {
                $sqlE = "UPDATE empleado SET Especialidad = ?, CUIL = ? WHERE id_Usuario = ?";
                $stmtE = $this->db->prepare($sqlE);
                $stmtE->bind_param("ssi", $datos['especialidad'], $datos['cuil'], $datos['id_Usuario']);
                $stmtE->execute();
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
} 
?>