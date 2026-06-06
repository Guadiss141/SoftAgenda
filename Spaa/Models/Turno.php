<?php

class Turno {

    private $conn;
    private $table_name = "turno";

    public function __construct($db) {
        $this->conn = $db;
    }

    // listar todos los turnos
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

    public function crear($datos) {
        return $this->crearTurno(
            $datos['id_Paciente'],
            $datos['id_Servicio'],
            $datos['id_Empleado'],
            $datos['Fecha_Turno'],
            $datos['Hora_Turno']
        );
    }

    public function obtenerTurnoPorId($id_turno, $id_Paciente) {
        $sql = "SELECT t.id_Turno, t.Fecha_Turno, t.Hora_Turno, t.Estado_Turno,
                       t.id_Servicio, t.id_Empleado,
                       s.Nombre_servicio,
                       per_emp.Persona_Nombre   AS Terapeuta_Nombre,
                       per_emp.Persona_Apellido AS Terapeuta_Apellido
                FROM turno t
                JOIN servicio s      ON t.id_Servicio  = s.id_Servicio
                JOIN empleado emp    ON t.id_Empleado  = emp.id_Empleado
                JOIN persona per_emp ON emp.id_Persona = per_emp.id_Persona
                WHERE t.id_Turno = ? AND t.id_Paciente = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id_turno, $id_Paciente);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualizarTurno($fecha, $hora, $id_Servicio, $id_Empleado, $estado, $id_turno, $id_Paciente) {
        $sql = "UPDATE turno 
                SET Fecha_Turno   = ?,
                    Hora_Turno    = ?,
                    id_Servicio   = ?,
                    id_Empleado   = ?,
                    Estado_Turno  = ?
                WHERE id_Turno   = ?
                AND   id_Paciente = ?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("ssiisii", $fecha, $hora, $id_Servicio, $id_Empleado, $estado, $id_turno, $id_Paciente);
        return $stmt->execute();
    }

    // listar segun rol
    public function leerConFiltros($id_Usuario, $id_Rol, $id_Paciente = null) {
        $query = "SELECT
                        t.id_Turno,
                        t.Fecha_Turno,
                        DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno,
                        t.Estado_Turno,
                        s.Nombre_servicio,
                        p.Persona_Nombre       AS Cliente_Nombre,
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

    public function obtenerTodosLosTurnos() {
        return $this->leer();
    }

    public function obtenerTurnosPendientesAdmin() {
        $sql = "SELECT
                    t.id_Turno,
                    t.Fecha_Turno,
                    DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno,
                    s.Nombre_servicio,
                    CONCAT(pp.Persona_Nombre, ' ', pp.Persona_Apellido) AS Cliente,
                    CONCAT(pe.Persona_Nombre, ' ', pe.Persona_Apellido) AS Terapeuta
                FROM turno t
                JOIN servicio s      ON t.id_Servicio  = s.id_Servicio
                JOIN paciente pac    ON t.id_Paciente  = pac.id_Paciente
                JOIN persona pp      ON pac.id_Persona = pp.id_Persona
                JOIN empleado emp    ON t.id_Empleado  = emp.id_Empleado
                JOIN persona pe      ON emp.id_Persona = pe.id_Persona
                WHERE t.Estado_Turno = 'Pendiente'
                ORDER BY t.Fecha_Turno ASC, t.Hora_Turno ASC";

        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerTurnosCanceladosAdmin() {
        $sql = "SELECT
                    t.id_Turno,
                    t.Fecha_Turno,
                    DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno,
                    s.Nombre_servicio,
                    CONCAT(pp.Persona_Nombre, ' ', pp.Persona_Apellido) AS Cliente,
                    CONCAT(pe.Persona_Nombre, ' ', pe.Persona_Apellido) AS Terapeuta
                FROM turno t
                JOIN servicio s      ON t.id_Servicio  = s.id_Servicio
                JOIN paciente pac    ON t.id_Paciente  = pac.id_Paciente
                JOIN persona pp      ON pac.id_Persona = pp.id_Persona
                JOIN empleado emp    ON t.id_Empleado  = emp.id_Empleado
                JOIN persona pe      ON emp.id_Persona = pe.id_Persona
                WHERE t.Estado_Turno = 'Cancelado'
                ORDER BY t.Fecha_Turno DESC, t.Hora_Turno DESC";

        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerAgendaDelDia($id_empleado) {
        $query = "SELECT
                        t.id_Turno,
                        DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno,
                        t.Estado_Turno,
                        s.Nombre_servicio,
                        p.Persona_Nombre   AS Cliente_Nombre,
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

    public function eliminarTurno($id_turno) {
        $sql  = "DELETE FROM turno WHERE id_Turno = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_turno);
        return $stmt->execute();
    }

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

    public function completarTurno($id_turno) {
        $sql  = "UPDATE turno SET Estado_Turno = 'Completado' WHERE id_Turno = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_turno);
        return $stmt->execute();
    }

    public function obtenerServicios() {
        $sql = "SELECT id_Servicio, Nombre_servicio, Costo
                FROM servicio
                ORDER BY Nombre_servicio";
        return $this->conn->query($sql);
    }

    public function listarServicios() {
        return $this->obtenerServicios()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerTerapeutas() {
        $sql = "SELECT e.id_Empleado, p.Persona_Nombre, p.Persona_Apellido
                FROM empleado e
                JOIN persona p ON e.id_Persona = p.id_Persona
                JOIN usuario u ON e.id_Usuario = u.id_Usuario
                WHERE u.id_Rol = 2
                ORDER BY p.Persona_Apellido ASC";
        return $this->conn->query($sql);
    }

    public function listarEmpleados() {
        return $this->obtenerTerapeutas()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerDiasBloqueados($id_Empleado) {
        $sql = "SELECT Fecha_Bloqueada, Motivo
                FROM dias_bloqueados
                WHERE id_Empleado = ?
                ORDER BY Fecha_Bloqueada ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_Empleado);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function esDiaBloqueado($id_Empleado, $fecha) {
        $sql = "SELECT id_Bloqueo FROM dias_bloqueados
                WHERE id_Empleado = ? AND Fecha_Bloqueada = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $id_Empleado, $fecha);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function esDiaBloqueadoParaTodos($fecha) {
        $sql = "SELECT COUNT(*) AS total_terapeutas
                FROM empleado e
                JOIN usuario u ON e.id_Usuario = u.id_Usuario
                WHERE u.id_Rol = 2";

        $res   = $this->conn->query($sql);
        $total = $res->fetch_assoc()['total_terapeutas'];
        if ($total == 0) return true;

        $sql2 = "SELECT COUNT(*) AS bloqueados
                 FROM dias_bloqueados db
                 JOIN empleado e ON db.id_Empleado = e.id_Empleado
                 JOIN usuario u  ON e.id_Usuario   = u.id_Usuario
                 WHERE u.id_Rol = 2 AND db.Fecha_Bloqueada = ?";

        $stmt = $this->conn->prepare($sql2);
        $stmt->bind_param("s", $fecha);
        $stmt->execute();
        $bloqueados = $stmt->get_result()->fetch_assoc()['bloqueados'];
        return intval($bloqueados) >= intval($total);
    }

    public function obtenerDiasBloqueadosParaPaciente() {
        $sql = "SELECT db.Fecha_Bloqueada, COUNT(*) AS bloqueados,
                       (SELECT COUNT(*) FROM empleado e2
                        JOIN usuario u2 ON e2.id_Usuario = u2.id_Usuario
                        WHERE u2.id_Rol = 2) AS total_terapeutas
                FROM dias_bloqueados db
                JOIN empleado e ON db.id_Empleado = e.id_Empleado
                JOIN usuario u  ON e.id_Usuario   = u.id_Usuario
                WHERE u.id_Rol = 2
                GROUP BY db.Fecha_Bloqueada
                HAVING bloqueados >= total_terapeutas";

        $res  = $this->conn->query($sql);
        $dias = [];
        while ($row = $res->fetch_assoc()) {
            $dias[] = $row['Fecha_Bloqueada'];
        }
        return $dias;
    }

    public function obtenerTerapeutasDisponibles($fecha) {
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

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $fecha);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerHorasOcupadas($id_Empleado, $fecha) {
        $sql = "SELECT DATE_FORMAT(Hora_Turno, '%H:%i') AS hora
                FROM turno
                WHERE id_Empleado = ?
                AND Fecha_Turno   = ?
                AND Estado_Turno NOT IN ('Cancelado', 'Completado')";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $id_Empleado, $fecha);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return array_column($rows, 'hora');
    }

    public function obtenerHorasOcupadasDelDia($fecha) {
        $sql = "SELECT 
                    DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno,
                    p.Persona_Nombre   AS Terapeuta_Nombre,
                    p.Persona_Apellido AS Terapeuta_Apellido
                FROM turno t
                JOIN empleado emp ON t.id_Empleado  = emp.id_Empleado
                JOIN persona p    ON emp.id_Persona = p.id_Persona
                WHERE t.Fecha_Turno = ?
                AND t.Estado_Turno NOT IN ('Cancelado', 'Completado')
                ORDER BY t.Hora_Turno ASC, p.Persona_Apellido ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $fecha);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Obtener turnos de un día específico para el terapeuta (AJAX panel lateral)
    public function turnosDelDiaTerapeuta($id_Empleado, $fecha) {
        $sql = "SELECT
                    t.id_Turno,
                    DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno,
                    t.Estado_Turno,
                    s.Nombre_servicio,
                    p.Persona_Nombre   AS Paciente_Nombre,
                    p.Persona_Apellido AS Paciente_Apellido
                FROM turno t
                JOIN servicio s   ON t.id_Servicio  = s.id_Servicio
                JOIN paciente pac ON t.id_Paciente  = pac.id_Paciente
                JOIN persona p    ON pac.id_Persona = p.id_Persona
                WHERE t.id_Empleado = ?
                AND t.Fecha_Turno   = ?
                AND t.Estado_Turno NOT IN ('Cancelado', 'Completado')
                ORDER BY t.Hora_Turno ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $id_Empleado, $fecha);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function bloquearDia($id_Empleado, $fecha, $motivo) {
        $this->conn->begin_transaction();
        try {
            $sql = "INSERT INTO dias_bloqueados (id_Empleado, Fecha_Bloqueada, Motivo)
                    VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iss", $id_Empleado, $fecha, $motivo);
            $stmt->execute();

            $sql_pac = "SELECT DISTINCT u.id_Usuario, p.Persona_Nombre, p.Persona_Apellido
                        FROM turno t
                        JOIN paciente pac ON t.id_Paciente = pac.id_Paciente
                        JOIN usuario u    ON pac.id_Usuario = u.id_Usuario
                        JOIN persona p    ON pac.id_Persona = p.id_Persona
                        WHERE t.id_Empleado = ? AND t.Fecha_Turno = ?
                        AND t.Estado_Turno != 'Cancelado'";
            $stmt_pac = $this->conn->prepare($sql_pac);
            $stmt_pac->bind_param("is", $id_Empleado, $fecha);
            $stmt_pac->execute();
            $pacientes_afectados = $stmt_pac->get_result()->fetch_all(MYSQLI_ASSOC);

            $sql_cancel = "UPDATE turno SET Estado_Turno = 'Cancelado'
                           WHERE id_Empleado = ? AND Fecha_Turno = ?
                           AND Estado_Turno != 'Cancelado'";
            $stmt_cancel = $this->conn->prepare($sql_cancel);
            $stmt_cancel->bind_param("is", $id_Empleado, $fecha);
            $stmt_cancel->execute();

            $this->conn->commit();
            return $pacientes_afectados;

        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function desbloquearDia($id_Empleado, $fecha) {
        $sql = "DELETE FROM dias_bloqueados
                WHERE id_Empleado = ? AND Fecha_Bloqueada = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("is", $id_Empleado, $fecha);
        return $stmt->execute();
    }

    // turnos del mes para el paciente

    public function obtenerTurnosMesPaciente($id_Paciente, $anio, $mes) {
        $sql = "SELECT t.id_Turno, t.Fecha_Turno,
                       DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno,
                       t.Estado_Turno, s.Nombre_servicio,
                       p.Persona_Nombre   AS Terapeuta_Nombre,
                       p.Persona_Apellido AS Terapeuta_Apellido
                FROM turno t
                JOIN servicio s      ON t.id_Servicio  = s.id_Servicio
                JOIN empleado emp    ON t.id_Empleado  = emp.id_Empleado
                JOIN persona p       ON emp.id_Persona = p.id_Persona
                WHERE t.id_Paciente = ?
                AND YEAR(t.Fecha_Turno)  = ?
                AND MONTH(t.Fecha_Turno) = ?
                ORDER BY t.Fecha_Turno ASC, t.Hora_Turno ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $id_Paciente, $anio, $mes);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerTurnosMesTerapeuta($id_Empleado, $anio, $mes) {
        $sql = "SELECT t.id_Turno, t.Fecha_Turno,
                       DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno,
                       t.Estado_Turno, s.Nombre_servicio,
                       p.Persona_Nombre   AS Paciente_Nombre,
                       p.Persona_Apellido AS Paciente_Apellido
                FROM turno t
                JOIN servicio s      ON t.id_Servicio  = s.id_Servicio
                JOIN paciente pac    ON t.id_Paciente  = pac.id_Paciente
                JOIN persona p       ON pac.id_Persona = p.id_Persona
                WHERE t.id_Empleado = ?
                AND YEAR(t.Fecha_Turno)  = ?
                AND MONTH(t.Fecha_Turno) = ?
                ORDER BY t.Fecha_Turno ASC, t.Hora_Turno ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $id_Empleado, $anio, $mes);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>