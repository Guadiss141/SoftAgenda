<?php

class admin_usuariosModel {

    public static function conectar() {

        $conexion = new mysqli(
            "localhost",
            "root",
            "345756",
            "gestionspabd"
        );

        // Verificar conexión
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        // Opcional: evitar problemas con acentos y ñ
        $conexion->set_charset("utf8");

        return $conexion;
    }
}