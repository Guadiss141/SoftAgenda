<?php
class Turno {
    private $conn;
    private $table_name = "turno";

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Listar turnos básicos con formato de hora
    public function leer() {
        $query = "SELECT t.id_Turno, t.Fecha_Turno, 
                         DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno, 
                         t.Estado_Turno, s.Nombre_servicio, p.Persona_Nombre as Cliente_Nombre 
                  FROM " . $this->table_name . " t
                  JOIN servicio s ON t.id_Servicio = s.id_Servicio
                  JOIN paciente pac ON t.id_Paciente = pac.id_Paciente
                  JOIN persona p ON pac.id_Persona = p.id_Persona";
        
        return $this->conn->query($query);
    }

    // 2. Crear turno
    public function crear($datos) {
        $sql = "INSERT INTO turno (id_Paciente, id_Empleado, id_Servicio, Fecha_Turno, Hora_Turno, Estado_Turno) 
                VALUES (?, ?, ?, ?, ?, 'Pendiente')";
        
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt) {
            error_log('Turno.crear: Error prepare() - ' . $this->conn->error);
            return false;
        }
        
        // Convertir a tipos correctos
        $id_Paciente = intval($datos['id_Paciente']);
        $id_Empleado = intval($datos['id_Empleado']);
        $id_Servicio = intval($datos['id_Servicio']);
        $Fecha_Turno = $datos['Fecha_Turno'];
        $Hora_Turno = $datos['Hora_Turno'];
        
        $stmt->bind_param("iiiss", 
            $id_Paciente, 
            $id_Empleado, 
            $id_Servicio, 
            $Fecha_Turno, 
            $Hora_Turno
        );

        $resultado = $stmt->execute();
        
        if (!$resultado) {
            error_log('Turno.crear: Error execute() - ' . $stmt->error);
        }
        
        return $resultado;
    }

    // 3. Leer con filtros (Corregido a MySQLi con formato de hora)
    public function leerConFiltros($id_Usuario, $id_Rol, $id_Paciente = null) {
        $query = "SELECT t.id_Turno, t.Fecha_Turno, 
                         DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno, 
                         t.Estado_Turno, s.Nombre_servicio, p.Persona_Nombre as Cliente_Nombre, 
                         per_emp.Persona_Nombre as Terapeuta_Nombre
                  FROM turno t
                  JOIN servicio s ON t.id_Servicio = s.id_Servicio
                  JOIN paciente pac ON t.id_Paciente = pac.id_Paciente
                  JOIN persona p ON pac.id_Persona = p.id_Persona
                  JOIN empleado emp ON t.id_Empleado = emp.id_Empleado
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

    // 4. Obtener turnos por paciente
    public function obtenerTurnosPorPaciente($id_Paciente) {
        $sql = "SELECT t.id_Turno, s.Nombre_servicio, t.Fecha_Turno, 
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

    // 5. Obtener agenda del día (Añadido formato de hora)
    public function obtenerAgendaDelDia($id_empleado) {
        $query = "SELECT t.id_Turno, DATE_FORMAT(t.Hora_Turno, '%H:%i') AS Hora_Turno, t.Estado_Turno, 
                         s.Nombre_servicio, 
                         p.Persona_Nombre as Cliente_Nombre, 
                         p.Persona_Apellido as Cliente_Apellido,
                         p.Persona_Telefono as Cliente_Telefono
                  FROM turno t
                  JOIN servicio s ON t.id_Servicio = s.id_Servicio
                  JOIN paciente pac ON t.id_Paciente = pac.id_Paciente
                  JOIN persona p ON pac.id_Persona = p.id_Persona
                  WHERE t.id_Empleado = ? 
                  AND t.Fecha_Turno = CURDATE() 
                  ORDER BY t.Hora_Turno ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_empleado);
        $stmt->execute();
        return $stmt->get_result();
    }

    // 6. Método para borrar/cancelar turno
    public function borrar($id_turno, $id_usuario, $id_rol) {
        // Si es paciente, solo puede borrar sus propios turnos pendientes
        if ($id_rol == 0) {
            $sql = "DELETE t FROM turno t 
                    JOIN paciente p ON t.id_Paciente = p.id_Paciente 
                    WHERE t.id_Turno = ? AND p.id_Usuario = ? AND t.Estado_Turno = 'Pendiente'";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $id_turno, $id_usuario);
        } else {
            // Si es admin, borra cualquier turno
            $sql = "DELETE FROM turno WHERE id_Turno = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id_turno);
        }
        return $stmt->execute();
    }
} 
?>