<?php

class Usuario {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function validarLogin($usuario, $password) {
        $sql = "SELECT id_Usuario, id_Rol, Usuario_Nombre, Correo_E, Usuario_Contraseña 
                FROM usuario 
                WHERE (Usuario_Nombre = ? OR Correo_E = ?) LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $usuario, $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($user = $resultado->fetch_assoc()) {
            if (password_verify($password, $user['Usuario_Contraseña'])) {
                return $user;
            }
        }
        return false;
    }

    // listar todos los usuarios (admin)

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

    // listar terapeutas admin

    public function listarTerapeutas() {
        $query = "SELECT u.id_Usuario, u.Usuario_Nombre, u.Correo_E,
                         p.Persona_Nombre, p.Persona_Apellido, p.Persona_Telefono,
                         e.Especialidad, e.CUIL, e.id_Empleado
                  FROM usuario u
                  JOIN empleado e ON u.id_Usuario = e.id_Usuario
                  JOIN persona p ON e.id_Persona = p.id_Persona
                  WHERE u.id_Rol = 2
                  ORDER BY p.Persona_Apellido ASC";

        return $this->db->query($query);
    }

    public function obtenerPorId($id_Usuario) {
        $sql = "SELECT u.id_Usuario, u.Usuario_Nombre, u.Correo_E, u.id_Rol,
                       p.Persona_Nombre, p.Persona_Apellido, p.Persona_Telefono,
                       p.Persona_Domicilio, p.Persona_DNI, p.Fecha_Nac, p.Persona_Descripcion,
                       e.Especialidad, e.CUIL, e.Empleado_CBU,
                       pac.Observaciones_Paciente,
                       CASE 
                          WHEN e.id_Empleado IS NOT NULL THEN 'Empleado'
                          WHEN pac.id_Paciente IS NOT NULL THEN 'Paciente'
                          ELSE 'Sin asignar'
                       END as Tipo_Usuario
                FROM usuario u
                LEFT JOIN empleado e  ON u.id_Usuario  = e.id_Usuario
                LEFT JOIN paciente pac ON u.id_Usuario = pac.id_Usuario
                LEFT JOIN persona p   ON (e.id_Persona = p.id_Persona OR pac.id_Persona = p.id_Persona)
                WHERE u.id_Usuario = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_Usuario);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualizarUsuarioCompleto($datos) {
        try {
            $this->db->begin_transaction();

            $sqlP = "UPDATE persona p
                     JOIN (SELECT id_Persona FROM empleado WHERE id_Usuario = ? 
                           UNION 
                           SELECT id_Persona FROM paciente WHERE id_Usuario = ?) as ids
                     ON p.id_Persona = ids.id_Persona
                     SET p.Persona_Nombre = ?, p.Persona_Apellido = ?, 
                         p.Persona_Telefono = ?, p.Persona_Domicilio = ?";

            $stmtP = $this->db->prepare($sqlP);
            $stmtP->bind_param("iissss",
                $datos['id_Usuario'], $datos['id_Usuario'],
                $datos['nombre'], $datos['apellido'],
                $datos['telefono'], $datos['domicilio']
            );
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

    public function actualizarPerfil($datos) {
        try {
            $this->db->begin_transaction();

            // Actualizar datos de persona
            $sqlP = "UPDATE persona p
                     JOIN (SELECT id_Persona FROM empleado WHERE id_Usuario = ? 
                           UNION 
                           SELECT id_Persona FROM paciente WHERE id_Usuario = ?) as ids
                     ON p.id_Persona = ids.id_Persona
                     SET p.Persona_Nombre = ?, p.Persona_Apellido = ?,
                         p.Persona_Telefono = ?, p.Persona_Domicilio = ?";

            $stmtP = $this->db->prepare($sqlP);
            $stmtP->bind_param("iissss",
                $datos['id_Usuario'], $datos['id_Usuario'],
                $datos['nombre'], $datos['apellido'],
                $datos['telefono'], $datos['domicilio']
            );
            $stmtP->execute();

            // Actualizar correo del usuario
            $sqlU = "UPDATE usuario SET Correo_E = ? WHERE id_Usuario = ?";
            $stmtU = $this->db->prepare($sqlU);
            $stmtU->bind_param("si", $datos['correo'], $datos['id_Usuario']);
            $stmtU->execute();

            // Si quiere cambiar contraseña (campo opcional)
            if (!empty($datos['nueva_contrasena'])) {
                $hash = password_hash($datos['nueva_contrasena'], PASSWORD_DEFAULT);
                $sqlC = "UPDATE usuario SET Usuario_Contraseña = ? WHERE id_Usuario = ?";
                $stmtC = $this->db->prepare($sqlC);
                $stmtC->bind_param("si", $hash, $datos['id_Usuario']);
                $stmtC->execute();
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    public function listarCitasPorTerapeuta($id_Usuario) {
        $sql = "SELECT t.id_Turno, t.Fecha_Turno, t.Hora_Turno, t.Estado_Turno,
                       s.Nombre_servicio,
                       p.Persona_Nombre  AS Paciente_Nombre,
                       p.Persona_Apellido AS Paciente_Apellido,
                       p.Persona_Telefono AS Cliente_Telefono,
                       pac.id_Paciente
                FROM turno t
                JOIN empleado e  ON t.id_Empleado  = e.id_Empleado
                JOIN paciente pac ON t.id_Paciente = pac.id_Paciente
                JOIN persona p   ON pac.id_Persona = p.id_Persona
                JOIN servicio s  ON t.id_Servicio  = s.id_Servicio
                WHERE e.id_Usuario = ?
                ORDER BY t.Fecha_Turno ASC, t.Hora_Turno ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_Usuario);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function listarTurnosPorPaciente($id_Usuario) {
        $sql = "SELECT t.id_Turno, t.Fecha_Turno, t.Hora_Turno, t.Estado_Turno,
                       s.Nombre_servicio, s.Costo,
                       p.Persona_Nombre  AS Terapeuta_Nombre,
                       p.Persona_Apellido AS Terapeuta_Apellido,
                       e.Especialidad
                FROM turno t
                JOIN paciente pac ON t.id_Paciente = pac.id_Paciente
                JOIN empleado e   ON t.id_Empleado  = e.id_Empleado
                JOIN persona p    ON e.id_Persona   = p.id_Persona
                JOIN servicio s   ON t.id_Servicio  = s.id_Servicio
                WHERE pac.id_Usuario = ?
                ORDER BY t.Fecha_Turno ASC, t.Hora_Turno ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_Usuario);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function eliminarTurno($id_Turno, $id_Usuario) {
        // Verificar que el turno pertenece al paciente que hace la petición
        $sqlCheck = "SELECT t.id_Turno 
                     FROM turno t
                     JOIN paciente pac ON t.id_Paciente = pac.id_Paciente
                     WHERE t.id_Turno = ? AND pac.id_Usuario = ?";

        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->bind_param("ii", $id_Turno, $id_Usuario);
        $stmtCheck->execute();

        if ($stmtCheck->get_result()->num_rows === 0) {
            // El turno no le pertenece o no existe
            return false;
        }

        $sql = "DELETE FROM turno WHERE id_Turno = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id_Turno);
        return $stmt->execute();
    }

    public function agregarTurno($id_Usuario, $id_Empleado, $id_Servicio, $fecha, $hora) {
        $sqlPac = "SELECT id_Paciente FROM paciente WHERE id_Usuario = ? LIMIT 1";
        $stmtPac = $this->db->prepare($sqlPac);
        $stmtPac->bind_param("i", $id_Usuario);
        $stmtPac->execute();
        $res = $stmtPac->get_result()->fetch_assoc();

        if (!$res) return false;

        $id_Paciente = $res['id_Paciente'];
        $estado = 'Pendiente';

        $sql = "INSERT INTO turno (id_Paciente, id_Empleado, id_Servicio, Fecha_Turno, Hora_Turno, Estado_Turno)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiisss", $id_Paciente, $id_Empleado, $id_Servicio, $fecha, $hora, $estado);
        return $stmt->execute();
    }
}
?>