<?php

class PerfilModel {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    /* Obtiene los datos de la persona según el rol del usuario. */
    public function getPersonaPorUsuario(int $id_Usuario, int $id_Rol): ?array {
        if ($id_Rol == 0) {
            $sql = "SELECT p.*, pac.id_Paciente, pac.Observaciones_Paciente
                    FROM paciente pac 
                    JOIN persona p ON pac.id_Persona = p.id_Persona 
                    WHERE pac.id_Usuario = ?";
        } else {
            $sql = "SELECT p.*, e.id_Empleado, e.Especialidad, e.CUIL, e.Empleado_CBU
                    FROM empleado e 
                    JOIN persona p ON e.id_Persona = p.id_Persona 
                    WHERE e.id_Usuario = ?";
        }

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_Usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    /* Obtiene todos los turnos de un paciente con información*/
    public function getTurnosPorPaciente(int $id_Paciente): array {
        $sql = "SELECT t.id_Turno, t.Fecha_Turno, t.Hora_Turno, t.Estado_Turno,
                       s.Nombre_servicio, s.Costo,
                       per_emp.Persona_Nombre  AS Terapeuta_Nombre,
                       per_emp.Persona_Apellido AS Terapeuta_Apellido
                FROM turno t
                JOIN servicio  s       ON t.id_Servicio  = s.id_Servicio
                JOIN empleado  emp     ON t.id_Empleado  = emp.id_Empleado
                JOIN persona   per_emp ON emp.id_Persona = per_emp.id_Persona
                WHERE t.id_Paciente = ?
                ORDER BY t.Fecha_Turno DESC, t.Hora_Turno DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_Paciente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /*Elimina un turno verificando que pertenezca al paciente.*/
    public function cancelarTurno(int $id_Turno, int $id_Paciente): bool {
        $sql  = "DELETE FROM turno WHERE id_Turno = ? AND id_Paciente = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $id_Turno, $id_Paciente);
        return $stmt->execute();
    }

    /*Actualiza los datos personales en la tabla persona.*/
    public function actualizarPersona(
        int    $id_Persona,
        string $nombre,
        string $apellido,
        string $telefono,
        string $domicilio,
        string $dni,
        ?string $fecha_nac,
        string $descripcion
    ): bool {
        $sql = "UPDATE persona 
                SET Persona_Nombre       = ?,
                    Persona_Apellido     = ?,
                    Persona_Telefono     = ?,
                    Persona_Domicilio    = ?,
                    Persona_DNI          = ?,
                    Fecha_Nac            = ?,
                    Persona_Descripcion  = ?
                WHERE id_Persona = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param(
            "sssssssi",
            $nombre, $apellido, $telefono, $domicilio,
            $dni, $fecha_nac, $descripcion, $id_Persona
        );
        return $stmt->execute();
    }

    /*Actualiza el correo electrónico en la tabla usuario.*/
    public function actualizarCorreo(int $id_Usuario, string $correo): bool {
        $sql  = "UPDATE usuario SET Correo_E = ? WHERE id_Usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $correo, $id_Usuario);
        return $stmt->execute();
    }

    // lo q sigue es lo del abm de cuenta, q se me había pasado agregar al model

    /*Verifica si la contraseña actual es correcta y retorna el hash almacenado.*/
    public function getContrasena(int $id_Usuario): ?string {
        $sql  = "SELECT Usuario_Contraseña FROM usuario WHERE id_Usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_Usuario);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return $row['Usuario_Contraseña'] ?? null;
    }

    /*Actualiza la contraseña del usuario con el nuevo hash.*/
    public function cambiarContrasena(int $id_Usuario, string $nueva_hash): bool {
        $sql  = "UPDATE usuario SET Usuario_Contraseña = ? WHERE id_Usuario = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("si", $nueva_hash, $id_Usuario);
        return $stmt->execute();
    }

    /*Elimina la cuenta de un paciente en cascada: turno → paciente → persona → usuario*/
    public function eliminarPaciente(int $id_Paciente, int $id_Persona, int $id_Usuario): bool {
        $this->conexion->begin_transaction();
        try {
            $stmt = $this->conexion->prepare("DELETE FROM turno WHERE id_Paciente = ?");
            $stmt->bind_param("i", $id_Paciente);
            $stmt->execute();

            $stmt = $this->conexion->prepare("DELETE FROM paciente WHERE id_Paciente = ?");
            $stmt->bind_param("i", $id_Paciente);
            $stmt->execute();

            $stmt = $this->conexion->prepare("DELETE FROM persona WHERE id_Persona = ?");
            $stmt->bind_param("i", $id_Persona);
            $stmt->execute();

            $stmt = $this->conexion->prepare("DELETE FROM usuario WHERE id_Usuario = ?");
            $stmt->bind_param("i", $id_Usuario);
            $stmt->execute();

            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollback();
            return false;
        }
    }

    /*Elimina la cuenta de un empleado en cascada: empleado → persona → usuario*/
    public function eliminarEmpleado(int $id_Empleado, int $id_Persona, int $id_Usuario): bool {
        $this->conexion->begin_transaction();
        try {
            $stmt = $this->conexion->prepare("DELETE FROM empleado WHERE id_Empleado = ?");
            $stmt->bind_param("i", $id_Empleado);
            $stmt->execute();

            $stmt = $this->conexion->prepare("DELETE FROM persona WHERE id_Persona = ?");
            $stmt->bind_param("i", $id_Persona);
            $stmt->execute();

            $stmt = $this->conexion->prepare("DELETE FROM usuario WHERE id_Usuario = ?");
            $stmt->bind_param("i", $id_Usuario);
            $stmt->execute();

            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollback();
            return false;
        }
    }
}