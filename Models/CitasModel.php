<?php

class CitasModel {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // citas del terapeuta

    public function getCitasPorEmpleado(int $id_Empleado): array {
        $sql = "SELECT t.id_Turno, t.Fecha_Turno,
                       DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno,
                       t.Estado_Turno, s.Nombre_servicio,
                       p.Persona_Nombre  AS Paciente_Nombre,
                       p.Persona_Apellido AS Paciente_Apellido
                FROM turno t
                JOIN servicio s   ON t.id_Servicio  = s.id_Servicio
                JOIN paciente pac ON t.id_Paciente  = pac.id_Paciente
                JOIN persona p    ON pac.id_Persona = p.id_Persona
                WHERE t.id_Empleado  = ?
                AND   t.Estado_Turno = 'Pendiente'
                ORDER BY t.Fecha_Turno ASC, t.Hora_Turno ASC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_Empleado);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // turnos del paciente (pendientes)
    public function getTurnosPorPaciente(int $id_Paciente): array {
        $sql = "SELECT t.id_Turno, t.Fecha_Turno,
                       DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno,
                       t.Estado_Turno, s.Nombre_servicio,
                       p.Persona_Nombre  AS Terapeuta_Nombre,
                       p.Persona_Apellido AS Terapeuta_Apellido
                FROM turno t
                JOIN servicio s      ON t.id_Servicio  = s.id_Servicio
                JOIN empleado emp    ON t.id_Empleado  = emp.id_Empleado
                JOIN persona p       ON emp.id_Persona = p.id_Persona
                WHERE t.id_Paciente  = ?
                AND   t.Estado_Turno = 'Pendiente'
                ORDER BY t.Fecha_Turno ASC, t.Hora_Turno ASC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_Paciente);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

   // cancelar turno (solo si está pendiente)
    public function cancelarTurno(int $id_Turno, int $id_Paciente): bool {
        $sql = "DELETE FROM turno
                WHERE id_Turno   = ?
                AND   id_Paciente = ?
                AND   Estado_Turno = 'Pendiente'";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $id_Turno, $id_Paciente);
        return $stmt->execute();
    }
}
?>