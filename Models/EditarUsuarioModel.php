<?php

class EditarUsuarioModel {

    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Obtener usuario por ID
    public function obtenerUsuarioPorId($id_usuario) {

        $sql = "SELECT u.*, p.*, e.Especialidad, e.CUIL 
                FROM usuario u 
                LEFT JOIN empleado e 
                ON u.id_Usuario = e.id_Usuario

                LEFT JOIN persona p 
                ON (
                    e.id_Persona = p.id_Persona 

                    OR p.id_Persona = (
                        SELECT id_Persona
                        FROM paciente pac
                        WHERE pac.id_Usuario = u.id_Usuario
                        LIMIT 1
                    )
                )

                WHERE u.id_Usuario = ?";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bind_param("i", $id_usuario);

        $stmt->execute();

        $resultado = $stmt->get_result();

        return $resultado->fetch_assoc();
    }

    // Actualizar usuario
    public function actualizarUsuario(
        $id_usuario,
        $nombre,
        $apellido,
        $telefono,
        $domicilio,
        $usuario_nombre,
        $correo,
        $especialidad = null,
        $cuil = null
    ) {

        // Actualizar tabla usuario
        $sql_usuario = "UPDATE usuario
                        SET Usuario_Nombre = ?,
                            Correo_E = ?
                        WHERE id_Usuario = ?";

        $stmt_usuario = $this->conexion->prepare($sql_usuario);

        $stmt_usuario->bind_param(
            "ssi",
            $usuario_nombre,
            $correo,
            $id_usuario
        );

        $stmt_usuario->execute();

        // Obtener id_persona
        $sql_persona = "SELECT p.id_Persona
                        FROM persona p

                        LEFT JOIN empleado e
                        ON p.id_Persona = e.id_Persona

                        LEFT JOIN paciente pac
                        ON p.id_Persona = pac.id_Persona

                        WHERE e.id_Usuario = ?
                        OR pac.id_Usuario = ?

                        LIMIT 1";

        $stmt_persona = $this->conexion->prepare($sql_persona);

        $stmt_persona->bind_param(
            "ii",
            $id_usuario,
            $id_usuario
        );

        $stmt_persona->execute();

        $resultado_persona = $stmt_persona->get_result();

        $persona = $resultado_persona->fetch_assoc();

        if (!$persona) {
            return false;
        }

        $id_persona = $persona['id_Persona'];

        // Actualizar persona
        $sql_update_persona = "UPDATE persona
                               SET Persona_Nombre = ?,
                                   Persona_Apellido = ?,
                                   Persona_Telefono = ?,
                                   Persona_Domicilio = ?
                               WHERE id_Persona = ?";

        $stmt_update_persona = $this->conexion->prepare($sql_update_persona);

        $stmt_update_persona->bind_param(
            "ssssi",
            $nombre,
            $apellido,
            $telefono,
            $domicilio,
            $id_persona
        );

        $stmt_update_persona->execute();

        // Actualizar terapeuta
        if ($especialidad !== null && $cuil !== null) {

            $sql_empleado = "UPDATE empleado
                             SET Especialidad = ?,
                                 CUIL = ?
                             WHERE id_Usuario = ?";

            $stmt_empleado = $this->conexion->prepare($sql_empleado);

            $stmt_empleado->bind_param(
                "ssi",
                $especialidad,
                $cuil,
                $id_usuario
            );

            $stmt_empleado->execute();
        }

        return true;
    }
}