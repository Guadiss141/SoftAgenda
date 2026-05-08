<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../Models/UsuarioRegistro.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST['nombre_usuario'] ?? '');
    $correo = trim($_POST['correo_usuario'] ?? '');
    $contraseña = trim($_POST['contraseña'] ?? '');
    $confirmar = trim($_POST['confirmar_contraseña'] ?? '');

    if ($nombre == "" || $correo == "" || $contraseña == "" || $confirmar == "") {
        $mensaje = "Completar todos los campos";
    } elseif ($contraseña !== $confirmar) {
        $mensaje = "Las contraseñas no coinciden";
    } else {

        $conexion = new mysqli("localhost", "root", "", "gestionspabd");

        if ($conexion->connect_error) {
            die("Error de conexión");
        }

        $resultado = Usuario::existe($conexion, $nombre, $correo);

        if ($resultado->num_rows > 0) {
            $mensaje = "El usuario o correo ya están registrados.";
        } else {

            // Hashear contraseña
            $hash = password_hash($contraseña, PASSWORD_DEFAULT);

            $stmt = Usuario::registrar($conexion, $nombre, $correo, $hash);

            if ($stmt->execute()) {
                $id_Usuario = $stmt->insert_id;

                // Crear registro en persona
                $apellido_vacio = '';
                $sql_persona = "INSERT INTO persona (Persona_Nombre, Persona_Apellido) VALUES (?, ?)";
                $stmt_p = $conexion->prepare($sql_persona);
                $stmt_p->bind_param("ss", $nombre, $apellido_vacio);
                $stmt_p->execute();
                $id_Persona = $stmt_p->insert_id;

                // Crear registro en paciente vinculando usuario y persona
                $sql_paciente = "INSERT INTO paciente (id_Usuario, id_Persona) VALUES (?, ?)";
                $stmt_pac = $conexion->prepare($sql_paciente);
                $stmt_pac->bind_param("ii", $id_Usuario, $id_Persona);
                $stmt_pac->execute();

                $_SESSION['id_Usuario'] = $id_Usuario;
                $_SESSION['nombre_usuario'] = $nombre;
                $_SESSION['id_Rol'] = 0; // Rol Paciente por defecto
                $_SESSION['id_Paciente'] = $stmt_pac->insert_id;
                $_SESSION['persona_nombre'] = $nombre;
                $_SESSION['persona_apellido'] = '';

                header("Location: Index.php");
                exit;

            } else {
                $mensaje = "Error al registrar";
            }
        }
    }
}

// cargar vista
include("RegistroView.php");