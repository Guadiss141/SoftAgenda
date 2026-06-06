<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../Models/UsuarioRegistro.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = trim($_POST['nombre_usuario'] ?? '');
    $correo = trim($_POST['correo_usuario'] ?? '');
    $contraseña = trim($_POST['contraseña'] ?? '');
    $confirmar = trim($_POST['confirmar_contraseña'] ?? '');

    if ($nombre == "" || $correo == "" || $contraseña == "" || $confirmar == "") {
        $mensaje = "Completar todos los campos";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "El formato de correo no es válido";
    } elseif ($contraseña !== $confirmar) {
        $mensaje = "Las contraseñas no coinciden";
    } else {

        $conexion = new mysqli("localhost", "root", "345756", "gestionspabd");

        if ($conexion->connect_error) {
            die("Error de conexión");
        }

        $resultado = Usuario::existe($conexion, $nombre, $correo);

        if ($resultado->num_rows > 0) {
            $mensaje = "El usuario o correo ya están registrados.";
        } else {

            $codigo = str_pad(rand(0, 9999), 4, "0", STR_PAD_LEFT);
            $expira = date("Y-m-d H:i:s", strtotime('+10 minutes'));

            // Hashear contraseña
            $hash = password_hash($contraseña, PASSWORD_DEFAULT);

            $stmt = Usuario::registrar($conexion, $nombre, $correo, $hash, $codigo, $expira);

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

                // Enviar correo de verificación
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'villalbagarciaguadalupe@gmail.com';
                    $mail->Password = 'teli cujk piae xsuo';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('villalbagarciaguadalupe@gmail.com', 'Beauty SPA');
                    $mail->addAddress($correo);

                    $mail->isHTML(true);
                    $mail->Subject = 'Verificacion de correo - Beauty SPA';
                    $mail->Body = "
                        <h2>¡Bienvenido a Beauty SPA!</h2>
                        <p>Tu código de verificación de registro es:</p>
                        <h1 style='color: #e879a0; font-size: 32px; letter-spacing: 2px;'>$codigo</h1>
                        <p>Este código vence en 10 minutos.</p>
                    ";
                    $mail->send();
                } catch (Exception $e) {
                    // Ignorar silenciosamente
                }

                header("Location: VerificarRegistroController.php?email=" . urlencode($correo));
                exit;

            } else {
                $mensaje = "Error al registrar";
            }
        }
    }
}

// cargar vista
include __DIR__ . '/../Views/RegistroView.php';