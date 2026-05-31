<?php

class EditarTurnoModel {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerTurnoPorId($id_turno, $id_paciente) {
        $sql = "SELECT t.*, s.Nombre_servicio, s.Costo,
                       per_emp.Persona_Nombre  AS Terapeuta_Nombre,
                       per_emp.Persona_Apellido AS Terapeuta_Apellido,
                       emp.id_Empleado
                FROM turno t
                JOIN servicio s      ON t.id_Servicio  = s.id_Servicio
                JOIN empleado emp    ON t.id_Empleado  = emp.id_Empleado
                JOIN persona per_emp ON emp.id_Persona = per_emp.id_Persona
                WHERE t.id_Turno    = ?
                AND   t.id_Paciente = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $id_turno, $id_paciente);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function listarServicios() {
        $sql = "SELECT id_Servicio, Nombre_servicio, Costo
                FROM servicio
                ORDER BY Nombre_servicio";

        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    // listar terapeutas disponibles excluyendo los bloqueados ese día

    public function listarTerapeutasDisponibles($fecha) {
        $sql = "SELECT e.id_Empleado, p.Persona_Nombre, p.Persona_Apellido
                FROM empleado e
                JOIN persona p ON e.id_Persona = p.id_Persona
                JOIN usuario u ON e.id_Usuario = u.id_Usuario
                WHERE u.id_Rol = 2
                AND e.id_Empleado NOT IN (
                    SELECT id_Empleado FROM dias_bloqueados
                    WHERE Fecha_Bloqueada = ?
                )
                ORDER BY p.Persona_Apellido ASC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("s", $fecha);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Todos los terapeutas (para prellenar el form sin fecha)
    public function listarEmpleados() {
        $sql = "SELECT e.id_Empleado, p.Persona_Nombre, p.Persona_Apellido
                FROM empleado e
                JOIN persona p ON e.id_Persona = p.id_Persona
                JOIN usuario u ON e.id_Usuario = u.id_Usuario
                WHERE u.id_Rol = 2
                ORDER BY p.Persona_Apellido ASC";

        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    //horas ocupadas del terapeuta en esa fecha (para bloquearlas en el form)

    public function obtenerHorasOcupadas($id_Empleado, $fecha, $id_turno_excluir = 0) {
        $sql = "SELECT DATE_FORMAT(Hora_Turno, '%H:%i') AS hora
                FROM turno
                WHERE id_Empleado  = ?
                AND   Fecha_Turno  = ?
                AND   id_Turno    != ?
                AND   Estado_Turno NOT IN ('Cancelado', 'Completado')";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("isi", $id_Empleado, $fecha, $id_turno_excluir);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return array_column($rows, 'hora');
    }

    public function esDiaBloqueado($id_Empleado, $fecha) {
        $sql = "SELECT id_Bloqueo FROM dias_bloqueados
                WHERE id_Empleado    = ?
                AND   Fecha_Bloqueada = ?
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("is", $id_Empleado, $fecha);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function actualizarTurno($fecha, $hora, $servicio, $empleado, $id_turno, $id_paciente) {
        $sql = "UPDATE turno
                SET Fecha_Turno  = ?,
                    Hora_Turno   = ?,
                    id_Servicio  = ?,
                    id_Empleado  = ?
                WHERE id_Turno   = ?
                AND   id_Paciente = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ssiiii", $fecha, $hora, $servicio, $empleado, $id_turno, $id_paciente);
        return $stmt->execute();
    }
}
?>