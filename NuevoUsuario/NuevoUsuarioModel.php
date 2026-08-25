<?php

class NuevoUsuarioModel {

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

    public function obtenerEspecialidades(): array {
        $sql = "SELECT id_Especialidad, nombre FROM especialidad_empleado ORDER BY nombre ASC";
        $res = $this->conexion->query($sql);
        $roles = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $roles[] = $row;
            }
        }
        return $roles;
    }

    /*
     Registra una persona, un usuario y un rol especifico (paciente o empleado)
     dentro de una transacción para garantizar consistencia.
     Retorna true si todo salió bien.
     */
    public function registrarUsuario(
        string $tipo_usuario,
        string $nombre,
        string $apellido,
        string $usuario_nombre,
        string $correo,
        string $contrasena_hash,
        string $especialidad
    ): bool {
        $this->conexion->begin_transaction();

        try {
            $sql_persona = "INSERT INTO persona (Persona_Nombre, Persona_Apellido) VALUES (?, ?)";
            $stmt = $this->conexion->prepare($sql_persona);
            $stmt->bind_param("ss", $nombre, $apellido);
            $stmt->execute();
            $id_Persona = $this->conexion->insert_id;

            $id_Rol = ($tipo_usuario === 'empleado') ? 2 : 0;
            $sql_usuario = "INSERT INTO usuario (Usuario_Nombre, Correo_E, Usuario_Contraseña, id_Rol) VALUES (?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql_usuario);
            $stmt->bind_param("sssi", $usuario_nombre, $correo, $contrasena_hash, $id_Rol);
            $stmt->execute();
            $id_Usuario = $this->conexion->insert_id;

            if ($tipo_usuario === 'empleado') {
                $cuil = ''; // Valor por defecto
                $sql_empleado = "INSERT INTO empleado (id_Persona, id_Usuario, id_Rol, Especialidad, CUIL) VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->conexion->prepare($sql_empleado);
                $stmt->bind_param("iiiss", $id_Persona, $id_Usuario, $id_Rol, $especialidad, $cuil);
                $stmt->execute();
            } else {
                $sql_paciente = "INSERT INTO paciente (id_Persona, id_Usuario) VALUES (?, ?)";
                $stmt = $this->conexion->prepare($sql_paciente);
                $stmt->bind_param("ii", $id_Persona, $id_Usuario);
                $stmt->execute();
            }

            $this->conexion->commit();
            return true;

        } catch (Exception $e) {
            $this->conexion->rollback();
            return false;
        }
    }
}