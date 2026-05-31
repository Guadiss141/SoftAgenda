<?php

class admin_usuariosModel {

    public static function conectar() {

        $conexion = new mysqli(
            "localhost",
            "root",
            "345756",
            "gestionspabd"
        );

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }

        $conexion->set_charset("utf8");

        return $conexion;
    }
}