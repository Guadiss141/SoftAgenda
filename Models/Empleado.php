<?php
class Empleado {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crearTerapeuta($datos) {
        try {
            $this->conn->begin_transaction();

            //Insertar en la tabla 'persona'
            $sqlPersona = "INSERT INTO persona (Persona_Nombre, Persona_Apellido, Persona_Telefono, Persona_Domicilio) 
                           VALUES (?, ?, ?, ?)";
            $stmtP = $this->conn->prepare($sqlPersona);
            $stmtP->bind_param("ssis", $datos['nombre'], $datos['apellido'], $datos['telefono'], $datos['domicilio']);
            $stmtP->execute();
            $idPersona = $this->conn->insert_id;

            //Insertar en la tabla 'usuario'
            $passHash = password_hash($datos['password'], PASSWORD_DEFAULT);
            $sqlUsuario = "INSERT INTO usuario (Usuario_Nombre, Usuario_Contraseña, Correo_E, Fecha_Creacion) 
                           VALUES (?, ?, ?, NOW())";
            $stmtU = $this->conn->prepare($sqlUsuario);
            $stmtU->bind_param("sss", $datos['usuario_nombre'], $passHash, $datos['correo']);
            $stmtU->execute();
            $idUsuario = $this->conn->insert_id;

            //Insertar en la tabla 'empleado' con id_Rol = 2 (Terapeuta)
            $sqlEmpleado = "INSERT INTO empleado (id_Usuario, id_Rol, id_Persona, Especialidad, CUIL) 
                            VALUES (?, 2, ?, ?, ?)";
            $stmtE = $this->conn->prepare($sqlEmpleado);
            $stmtE->bind_param("iiss", $idUsuario, $idPersona, $datos['especialidad'], $datos['cuil']);
            $stmtE->execute();

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}