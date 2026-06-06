<?php
class Usuario {

    public static function existe($conn, $nombre, $correo) {
        $sql = "SELECT * FROM Usuario WHERE Usuario_Nombre = ? OR Correo_E = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nombre, $correo);
        $stmt->execute();

        return $stmt->get_result();
    }

    public static function registrar($conn, $nombre, $correo, $contraseña, $codigo, $expira) {
        $sql = "INSERT INTO Usuario 
                (Usuario_Nombre, Correo_E, Usuario_Contraseña, Fecha_Creacion, verificado, codigo_verificacion, verificacion_expira)
                VALUES (?, ?, ?, NOW(), 0, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nombre, $correo, $contraseña, $codigo, $expira);

        return $stmt;
    }
}