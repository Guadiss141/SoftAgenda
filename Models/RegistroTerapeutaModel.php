<?php

class TerapeutaModel {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    /*
     Verifica si ya existe un usuario con ese nombre o correo.
     Retorna true si ya está en uso.
     */
    public function usuarioExiste(string $usuario_nombre, string $correo): bool {
        $sql  = "SELECT id_Usuario FROM usuario WHERE Usuario_Nombre = ? OR Correo_E = ? LIMIT 1";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("ss", $usuario_nombre, $correo);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    /*
     Registra una persona, un usuario y un empleado (terapeuta)
     dentro de una transacción para garantizar consistencia.
     Retorna true si todo salió bien.
     */
    public function registrarTerapeuta(
        string $nombre,
        string $apellido,
        string $usuario_nombre,
        string $correo,
        string $contrasena_hash,
        string $especialidad,
        string $cuil
    ): bool {
        $this->conexion->begin_transaction();

        try {
            $sql_persona = "INSERT INTO persona (Persona_Nombre, Persona_Apellido) VALUES (?, ?)";
            $stmt = $this->conexion->prepare($sql_persona);
            $stmt->bind_param("ss", $nombre, $apellido);
            $stmt->execute();
            $id_Persona = $this->conexion->insert_id;

            $id_Rol = 2;
            $sql_usuario = "INSERT INTO usuario (Usuario_Nombre, Correo_E, Usuario_Contraseña, id_Rol) VALUES (?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql_usuario);
            $stmt->bind_param("sssi", $usuario_nombre, $correo, $contrasena_hash, $id_Rol);
            $stmt->execute();
            $id_Usuario = $this->conexion->insert_id;

            $sql_empleado = "INSERT INTO empleado (id_Persona, id_Usuario, id_Rol, Especialidad, CUIL) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql_empleado);
            $id_Rol = 2;
            $stmt->bind_param("iiiss", $id_Persona, $id_Usuario, $id_Rol, $especialidad, $cuil);
            $stmt->execute();

            $this->conexion->commit();
            return true;

        } catch (Exception $e) {
            $this->conexion->rollback();
            return false;
        }
    }
}