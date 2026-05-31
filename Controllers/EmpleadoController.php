<?php
require_once '../Models/Empleado.php';

class EmpleadoController {
    
    public function registrarTerapeuta($datosPost) {
        session_start();

        // Seguridad: Verificar que sea Admin
        if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
            return "Acceso denegado. Solo los administradores pueden crear terapeutas.";
        }

        $conexion = new mysqli("localhost", "root", "", "gestionspabd");
        $modeloEmpleado = new Empleado($conexion);

        if ($modeloEmpleado->crearTerapeuta($datosPost)) {
            return "Terapeuta creado exitosamente.";
        } else {
            return "Error al crear el perfil del terapeuta.";
        }
    }
}