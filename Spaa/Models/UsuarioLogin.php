<?php
class Usuario {

    public static function login($conn, $usuario_o_correo) {
        $sql = "SELECT * FROM Usuario 
                WHERE Usuario_Nombre = ? OR Correo_E = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $usuario_o_correo, $usuario_o_correo);
        $stmt->execute();

        return $stmt->get_result();
    }

}