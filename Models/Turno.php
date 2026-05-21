<?php

class Turno {

    private $conn;
    private $table_name = "turno";

    public function __construct($db) {
        $this->conn = $db;
    }

    // LISTAR TODOS LOS TURNOS

    public function leer() {

        $query = "SELECT 
                        t.id_Turno,
                        t.Fecha_Turno,
                        DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno,
                        t.Estado_Turno,
                        s.Nombre_servicio,
                        p.Persona_Nombre AS Cliente_Nombre
                  FROM " . $this->table_name . " t
                  JOIN servicio s   ON t.id_Servicio = s.id_Servicio
                  JOIN paciente pac ON t.id_Paciente = pac.id_Paciente
                  JOIN persona p    ON pac.id_Persona = p.id_Persona";

        return $this->conn->query($query);
    }

    // CREAR TURNO
    // Alias para que el Controller pueda llamar crearTurno()

    public function crearTurno($id_Paciente, $id_Servicio, $id_Empleado, $fecha, $hora) {

        $sql = "INSERT INTO turno
                    (id_Paciente, id_Empleado, id_Servicio, Fecha_Turno, Hora_Turno, Estado_Turno)
                VALUES
                    (?, ?, ?, ?, ?, 'Pendiente')";

        $stmt = $this->conn->prepare($sql);

        if (!$stmt) return false;

        $id_Paciente = intval($id_Paciente);
        $id_Empleado = intval($id_Empleado);
        $id_Servicio = intval($id_Servicio);

        $stmt->bind_param("iiiss", $id_Paciente, $id_Empleado, $id_Servicio, $fecha, $hora);

        return $stmt->execute();
    }

    // Método original mantenido para compatibilidad
    public function crear($datos) {
        return $this->crearTurno(
            $datos['id_Paciente'],
            $datos['id_Servicio'],
            $datos['id_Empleado'],
            $datos['Fecha_Turno'],
            $datos['Hora_Turno']
        );
    }

    // LISTAR SEGÚN ROL

    public function leerConFiltros($id_Usuario, $id_Rol, $id_Paciente = null) {

        $query = "SELECT
                        t.id_Turno,
                        t.Fecha_Turno,
                        DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno,
                        t.Estado_Turno,
                        s.Nombre_servicio,
                        p.Persona_Nombre    AS Cliente_Nombre,
                        per_emp.Persona_Nombre AS Terapeuta_Nombre
                  FROM turno t
                  JOIN servicio s      ON t.id_Servicio  = s.id_Servicio
                  JOIN paciente pac    ON t.id_Paciente  = pac.id_Paciente
                  JOIN persona p       ON pac.id_Persona = p.id_Persona
                  JOIN empleado emp    ON t.id_Empleado  = emp.id_Empleado
                  JOIN persona per_emp ON emp.id_Persona = per_emp.id_Persona";

        if ($id_Rol == 2) {
            $query .= " WHERE emp.id_Usuario = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id_Usuario);
        } elseif ($id_Paciente !== null && $id_Rol != 3) {
            $query .= " WHERE t.id_Paciente = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id_Paciente);
        } else {
            $stmt = $this->conn->prepare($query);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    // TURNOS DEL PACIENTE

    public function obtenerTurnosPorPaciente($id_Paciente) {

        $sql = "SELECT
                    t.id_Turno,
                    s.Nombre_servicio,
                    t.Fecha_Turno,
                    DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno,
                    t.Estado_Turno
                FROM turno t
                JOIN servicio s ON t.id_Servicio = s.id_Servicio
                WHERE t.id_Paciente = ?
                ORDER BY t.Fecha_Turno DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_Paciente);
        $stmt->execute();
        return $stmt->get_result();
    }


    // TODOS LOS TURNOS (Admin)

    public function obtenerTodosLosTurnos() {
        return $this->leer();
    }


    // AGENDA DEL TERAPEUTA

    public function obtenerAgendaDelDia($id_empleado) {

        $query = "SELECT
                        t.id_Turno,
                        DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno,
                        t.Estado_Turno,
                        s.Nombre_servicio,
                        p.Persona_Nombre  AS Cliente_Nombre,
                        p.Persona_Apellido AS Cliente_Apellido,
                        p.Persona_Telefono AS Cliente_Telefono
                  FROM turno t
                  JOIN servicio s   ON t.id_Servicio  = s.id_Servicio
                  JOIN paciente pac ON t.id_Paciente  = pac.id_Paciente
                  JOIN persona p    ON pac.id_Persona = p.id_Persona
                  WHERE t.id_Empleado = ?
                  AND t.Fecha_Turno = CURDATE()
                  ORDER BY t.Hora_Turno ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_empleado);
        $stmt->execute();
        return $stmt->get_result();
    }


    // ELIMINAR TURNO
    // Alias para que el Controller pueda llamar eliminarTurno()

    public function eliminarTurno($id_turno) {
        $sql  = "DELETE FROM turno WHERE id_Turno = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_turno);
        return $stmt->execute();
    }

    // Método original mantenido para compatibilidad
    public function borrar($id_turno, $id_usuario, $id_rol) {

        if ($id_rol == 0) {
            $sql = "DELETE t FROM turno t
                    JOIN paciente p ON t.id_Paciente = p.id_Paciente
                    WHERE t.id_Turno = ?
                    AND p.id_Usuario = ?
                    AND t.Estado_Turno = 'Pendiente'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $id_turno, $id_usuario);
        } else {
            $sql  = "DELETE FROM turno WHERE id_Turno = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id_turno);
        }

        return $stmt->execute();
    }

    // COMPLETAR TURNO

    public function completarTurno($id_turno) {

        $sql  = "UPDATE turno SET Estado_Turno = 'Completado' WHERE id_Turno = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_turno);
        return $stmt->execute();
    }

  
    // OBTENER SERVICIOS
    public function obtenerServicios() {

        $sql = "SELECT id_Servicio, Nombre_servicio
                FROM servicio
                ORDER BY Nombre_servicio";

        return $this->conn->query($sql);
    }

    // OBTENER TERAPEUTAS

    public function obtenerTerapeutas() {

        $sql = "SELECT e.id_Empleado, p.Persona_Nombre
                FROM empleado e
                JOIN persona p  ON e.id_Persona = p.id_Persona
                JOIN usuario u  ON e.id_Usuario = u.id_Usuario
                WHERE u.id_Rol = 2";

        return $this->conn->query($sql);
    }
}