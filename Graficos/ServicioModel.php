<?php
class ServicioModel {
    private $conn;

    public function __construct() {
        // Ajustá estos datos con los de tu base "Spaa"
        $host = "localhost";
        $dbname = "gestionspabd";
        $user = "root";
        $pass = "345756";

        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function serviciosMasSolicitados($mes, $anio) {
        $sql = "SELECT s.Nombre_servicio, COUNT(t.id_Turno) AS cantidad
                FROM turno t
                INNER JOIN servicio s ON t.id_Servicio = s.id_Servicio
                WHERE MONTH(t.Fecha_Turno) = :mes
                  AND YEAR(t.Fecha_Turno) = :anio
                GROUP BY s.Nombre_servicio
                ORDER BY cantidad DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['mes' => $mes, 'anio' => $anio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function turnosPorEmpleado($mes, $anio) {
        $sql = "SELECT CONCAT(p.Persona_Nombre, ' ', p.Persona_Apellido) AS nombre_empleado,
                       COUNT(t.id_Turno) AS cantidad
                FROM turno t
                INNER JOIN empleado e ON t.id_Empleado = e.id_Empleado
                INNER JOIN persona p ON e.id_Persona = p.id_Persona
                WHERE MONTH(t.Fecha_Turno) = :mes
                  AND YEAR(t.Fecha_Turno) = :anio
                GROUP BY p.id_Persona, nombre_empleado
                ORDER BY cantidad DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['mes' => $mes, 'anio' => $anio]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function turnosPorDiaSemana($mes, $anio) {
        // DAYOFWEEK: 1=Domingo, 2=Lunes ... 7=Sábado
        $sql = "SELECT DAYOFWEEK(t.Fecha_Turno) AS dia_num, COUNT(*) AS cantidad
                FROM turno t
                WHERE MONTH(t.Fecha_Turno) = :mes
                  AND YEAR(t.Fecha_Turno) = :anio
                GROUP BY dia_num";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['mes' => $mes, 'anio' => $anio]);
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Armamos un mapa dia_num => cantidad para poder ordenar Lunes a Domingo
        $cantidadPorDiaNum = [];
        foreach ($filas as $fila) {
            $cantidadPorDiaNum[(int)$fila['dia_num']] = (int)$fila['cantidad'];
        }

        // Orden deseado: Lunes(2), Martes(3), Miércoles(4), Jueves(5), Viernes(6), Sábado(7), Domingo(1)
        $ordenDias = [2 => 'Lunes', 3 => 'Martes', 4 => 'Miércoles', 5 => 'Jueves', 6 => 'Viernes', 7 => 'Sábado', 1 => 'Domingo'];

        $resultado = [];
        foreach ($ordenDias as $diaNum => $nombreDia) {
            $resultado[] = [
                'dia' => $nombreDia,
                'cantidad' => $cantidadPorDiaNum[$diaNum] ?? 0
            ];
        }
        return $resultado;
    }

    public function turnosPorFranjaHoraria($mes, $anio) {
        $sql = "SELECT
                    CASE
                        WHEN HOUR(t.Hora_Turno) >= 6 AND HOUR(t.Hora_Turno) < 12 THEN 'Mañana'
                        WHEN HOUR(t.Hora_Turno) >= 12 AND HOUR(t.Hora_Turno) < 18 THEN 'Tarde'
                        ELSE 'Noche'
                    END AS franja,
                    COUNT(*) AS cantidad
                FROM turno t
                WHERE MONTH(t.Fecha_Turno) = :mes
                  AND YEAR(t.Fecha_Turno) = :anio
                GROUP BY franja";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['mes' => $mes, 'anio' => $anio]);
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Aseguramos que las tres franjas aparezcan siempre, aunque estén en 0
        $cantidadPorFranja = ['Mañana' => 0, 'Tarde' => 0, 'Noche' => 0];
        foreach ($filas as $fila) {
            $cantidadPorFranja[$fila['franja']] = (int)$fila['cantidad'];
        }

        $resultado = [];
        foreach ($cantidadPorFranja as $franja => $cantidad) {
            $resultado[] = ['franja' => $franja, 'cantidad' => $cantidad];
        }
        return $resultado;
    }
}