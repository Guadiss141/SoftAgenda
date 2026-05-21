<?php

class EditarTurnoModel {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Obtener turno
    public function obtenerTurnoPorId($id_turno, $id_paciente) {

        $sql = "SELECT t.*, s.Nombre_servicio, s.Costo, 
                       per_emp.Persona_Nombre as Terapeuta_Nombre,
                       per_emp.Persona_Apellido as Terapeuta_Apellido,
                       emp.id_Empleado
                FROM turno t
                JOIN servicio s ON t.id_Servicio = s.id_Servicio
                JOIN empleado emp ON t.id_Empleado = emp.id_Empleado
                JOIN persona per_emp ON emp.id_Persona = per_emp.id_Persona
                WHERE t.id_Turno = ?
                AND t.id_Paciente = ?";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param("ii", $id_turno, $id_paciente);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Listar servicios
    public function listarServicios() {

        $sql = "SELECT id_Servicio, Nombre_servicio, Costo
                FROM servicio
                ORDER BY Nombre_servicio";

        $result = $this->conexion->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Listar terapeutas
    public function listarEmpleados() {

        $sql = "SELECT e.id_Empleado,
                       p.Persona_Nombre,
                       p.Persona_Apellido
                FROM empleado e
                JOIN persona p
                ON e.id_Persona = p.id_Persona
                ORDER BY p.Persona_Nombre";

        $result = $this->conexion->query($sql);

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Actualizar turno
    public function actualizarTurno(
        $fecha,
        $hora,
        $servicio,
        $empleado,
        $estado,
        $id_turno,
        $id_paciente
    ) {

        $sql = "UPDATE turno
                SET Fecha_Turno = ?,
                    Hora_Turno = ?,
                    id_Servicio = ?,
                    id_Empleado = ?,
                    Estado_Turno = ?
                WHERE id_Turno = ?
                AND id_Paciente = ?";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param(
            "ssiiisi",
            $fecha,
            $hora,
            $servicio,
            $empleado,
            $estado,
            $id_turno,
            $id_paciente
        );

        return $stmt->execute();
    }
}