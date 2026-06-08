<?php

class ModulosModel {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtenerModulos() {
        $sql = "SELECT * FROM modulo ORDER BY id_Modulo";
        $result = $this->conexion->query($sql);
        $modulos = [];
        while ($row = $result->fetch_assoc()) {
            $modulos[] = $row;
        }
        return $modulos;
    }

    public function obtenerModulosPorRol($id_Rol) {
        $sql = "SELECT m.* FROM modulo m 
                JOIN modulo_rol mr ON m.id_Modulo = mr.id_Modulo 
                WHERE mr.id_Rol = ? ORDER BY m.id_Modulo";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $id_Rol);
        $stmt->execute();
        $result = $stmt->get_result();
        $modulos = [];
        while ($row = $result->fetch_assoc()) {
            $modulos[] = $row;
        }
        return $modulos;
    }

    public function obtenerModulosAsignados() {
        $sql = "SELECT id_Modulo, id_Rol FROM modulo_rol";
        $result = $this->conexion->query($sql);
        $asignados = [];
        while ($row = $result->fetch_assoc()) {
            $asignados[$row['id_Rol']][] = $row['id_Modulo'];
        }
        return $asignados;
    }

    public function asignarModulo($id_Modulo, $id_Rol) {
        $sql = "INSERT IGNORE INTO modulo_rol (id_Modulo, id_Rol) VALUES (?, ?)";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $id_Modulo, $id_Rol);
        return $stmt->execute();
    }

    public function desasignarModulo($id_Modulo, $id_Rol) {
        $sql = "DELETE FROM modulo_rol WHERE id_Modulo = ? AND id_Rol = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ii", $id_Modulo, $id_Rol);
        return $stmt->execute();
    }

    public function reemplazarAsignacionesPorRol($id_Rol, $modulos_ids) {
        $this->conexion->begin_transaction();
        try {
            // Eliminar todos los modulos actuales para este rol
            $sql_del = "DELETE FROM modulo_rol WHERE id_Rol = ?";
            $stmt_del = $this->conexion->prepare($sql_del);
            $stmt_del->bind_param("i", $id_Rol);
            $stmt_del->execute();

            // Insertar los nuevos
            if (!empty($modulos_ids)) {
                $sql_ins = "INSERT INTO modulo_rol (id_Modulo, id_Rol) VALUES (?, ?)";
                $stmt_ins = $this->conexion->prepare($sql_ins);
                foreach ($modulos_ids as $id_Modulo) {
                    $stmt_ins->bind_param("ii", $id_Modulo, $id_Rol);
                    $stmt_ins->execute();
                }
            }

            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollback();
            return false;
        }
    }
}
?>
