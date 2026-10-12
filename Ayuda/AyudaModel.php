<?php
/*
 * AyudaModel.php — Acceso a datos del módulo de ayuda (preguntas frecuentes, contacto y redes).
 *
 * Tablas (se crean solas la primera vez que el administrador entra a la pantalla de Ayuda):
 *   ayuda_faq     → preguntas frecuentes
 *   ayuda_config  → datos de contacto y redes (clave / valor)
 */
class AyudaModel {

    // Campos configurables y su límite de largo
    const CAMPOS = [
        'telefono'  => 40,
        'email'     => 120,
        'direccion' => 160,
        'horario'   => 120,
        'whatsapp'  => 20,
        'instagram' => 255,
        'facebook'  => 255,
    ];

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    /* ───────── Valores por defecto (se usan al crear las tablas y como respaldo del footer) ───────── */
    public static function defaults() {
        return [
            'faq' => [
                ['Pregunta' => '¿Cómo reservo un turno?',
                 'Respuesta' => 'Iniciá sesión y presioná “Reserva tu cita” en la página de inicio. Elegí el día y el horario disponibles, y confirmá.'],
                ['Pregunta' => '¿Puedo cancelar o cambiar mi turno?',
                 'Respuesta' => 'Sí. Entrá a la sección de tus citas, buscá el turno y cancelalo desde ahí. Si necesitás cambiarlo, cancelalo y reservá uno nuevo.'],
                ['Pregunta' => 'Olvidé mi contraseña, ¿qué hago?',
                 'Respuesta' => 'En la pantalla de inicio de sesión elegí la opción para recuperarla. Te enviaremos un correo con las instrucciones.'],
                ['Pregunta' => '¿Cuáles son los horarios de atención?',
                 'Respuesta' => 'Atendemos de lunes a viernes de 9:00 a 19:00. Los fines de semana no hay turnos disponibles.'],
            ],
            'config' => [
                'telefono'  => '+54 370 000-0000',
                'email'     => 'contacto@beautyspa.com',
                'direccion' => 'Calle Ejemplo 123, Formosa',
                'horario'   => 'Lun a Vie · 9:00 a 19:00',
                'whatsapp'  => '543700000000',
                'instagram' => 'https://www.instagram.com/',
                'facebook'  => 'https://www.facebook.com/',
            ],
        ];
    }

    /* ───────── Conexión propia (la usa el footer, que no recibe una conexión) ───────── */
    public static function conectar() {
        $c = new mysqli("localhost", "root", "345756", "gestionspabd");
        if ($c->connect_error) {
            return null;
        }
        $c->set_charset('utf8mb4');
        return $c;
    }

    /* ───────── Datos para el footer público: si algo falla, devuelve los valores por defecto ───────── */
    public static function cargarParaFooter() {
        $defecto = self::defaults();
        try {
            $c = self::conectar();
            if (!$c) {
                return $defecto;
            }
            $r = $c->query("SHOW TABLES LIKE 'ayuda_config'");
            $r2 = $c->query("SHOW TABLES LIKE 'ayuda_faq'");
            if (!$r || !$r2 || $r->num_rows === 0 || $r2->num_rows === 0) {
                $c->close();
                return $defecto;          // todavía no se configuró nada desde el panel
            }
            $m = new self($c);
            $datos = [
                'faq'    => $m->obtenerFaq(true),
                'config' => $m->obtenerConfig(),
            ];
            $c->close();
            return $datos;
        } catch (Throwable $e) {
            return $defecto;
        }
    }

    /* ───────── Crea las tablas la primera vez y carga los textos iniciales ───────── */
    public function asegurarTablas() {
        $existiaFaq    = $this->tablaExiste('ayuda_faq');
        $existiaConfig = $this->tablaExiste('ayuda_config');

        $this->conexion->query(
            "CREATE TABLE IF NOT EXISTS ayuda_faq (
                id_Faq INT AUTO_INCREMENT PRIMARY KEY,
                Pregunta VARCHAR(200) NOT NULL,
                Respuesta TEXT NOT NULL,
                Orden INT NOT NULL DEFAULT 0,
                Activo TINYINT(1) NOT NULL DEFAULT 1
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );
        $this->conexion->query(
            "CREATE TABLE IF NOT EXISTS ayuda_config (
                clave VARCHAR(40) NOT NULL PRIMARY KEY,
                valor VARCHAR(255) NOT NULL DEFAULT ''
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );

        $def = self::defaults();
        if (!$existiaFaq) {
            $orden = 1;
            foreach ($def['faq'] as $f) {
                $this->crearFaq($f['Pregunta'], $f['Respuesta'], $orden++, 1);
            }
        }
        if (!$existiaConfig) {
            $this->guardarConfig($def['config']);
        }
    }

    private function tablaExiste($nombre) {
        $stmt = $this->conexion->prepare(
            "SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?"
        );
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        $existe = $stmt->get_result()->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    /* ───────── Preguntas frecuentes ───────── */
    public function obtenerFaq($soloActivas = false) {
        $sql = "SELECT id_Faq, Pregunta, Respuesta, Orden, Activo FROM ayuda_faq"
             . ($soloActivas ? " WHERE Activo = 1" : "")
             . " ORDER BY Orden ASC, id_Faq ASC";
        $result = $this->conexion->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function crearFaq($pregunta, $respuesta, $orden, $activo) {
        $stmt = $this->conexion->prepare("INSERT INTO ayuda_faq (Pregunta, Respuesta, Orden, Activo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssii", $pregunta, $respuesta, $orden, $activo);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function actualizarFaq($id, $pregunta, $respuesta, $orden, $activo) {
        $stmt = $this->conexion->prepare("UPDATE ayuda_faq SET Pregunta = ?, Respuesta = ?, Orden = ?, Activo = ? WHERE id_Faq = ?");
        $stmt->bind_param("ssiii", $pregunta, $respuesta, $orden, $activo, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function eliminarFaq($id) {
        $stmt = $this->conexion->prepare("DELETE FROM ayuda_faq WHERE id_Faq = ?");
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    /* ───────── Contacto y redes ───────── */
    public function obtenerConfig() {
        $config = array_fill_keys(array_keys(self::CAMPOS), '');
        $result = $this->conexion->query("SELECT clave, valor FROM ayuda_config");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                if (array_key_exists($row['clave'], $config)) {
                    $config[$row['clave']] = $row['valor'];
                }
            }
        }
        return $config;
    }

    public function guardarConfig(array $datos) {
        $this->conexion->begin_transaction();
        try {
            $stmt = $this->conexion->prepare(
                "INSERT INTO ayuda_config (clave, valor) VALUES (?, ?)
                 ON DUPLICATE KEY UPDATE valor = VALUES(valor)"
            );
            foreach (self::CAMPOS as $clave => $max) {
                $valor = (string)($datos[$clave] ?? '');
                $stmt->bind_param("ss", $clave, $valor);
                if (!$stmt->execute()) {
                    throw new Exception($stmt->error);
                }
            }
            $stmt->close();
            $this->conexion->commit();
            return true;
        } catch (Throwable $e) {
            $this->conexion->rollback();
            return false;
        }
    }

    /* ───────── Validaciones / limpieza ───────── */

    // Devuelve la URL solo si es http(s); si no, cadena vacía (evita javascript:, data:, etc.)
    public static function urlSegura($url) {
        $url = trim((string)$url);
        if ($url === '' || !preg_match('#^https?://#i', $url)) {
            return '';
        }
        return filter_var($url, FILTER_VALIDATE_URL) ? $url : '';
    }

    // Deja solo dígitos (para wa.me)
    public static function soloDigitos($valor) {
        return preg_replace('/\D+/', '', (string)$valor);
    }
}
