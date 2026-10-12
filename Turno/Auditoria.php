<?php

class Auditoria {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function registrar($id_turno, $id_usuario, $modulo, $accion, $datoAnterior = null, $datoNuevo = null) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';

        $sql = "INSERT INTO auditoria_turnos 
                (id_turno, id_usuario, modulo, accion, dato_anterior, dato_nuevo, ip_origen) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param(
            "iisssss", 
            $id_turno, $id_usuario, $modulo, $accion, $datoAnterior, $datoNuevo, $ip
        );
        $stmt->execute();
        $stmt->close();
    }
}