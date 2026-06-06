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

$conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
if ($conexion->connect_error) {
    die("Error de conexión");
}

$email = $_REQUEST['email'] ?? '';
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verificar'])) {
        $codigo = trim($_POST['codigo'] ?? '');

        $sql = "SELECT u.*, pac.id_Paciente, p.Persona_Nombre, p.Persona_Apellido, p.Persona_Telefono, p.Persona_Domicilio 
                FROM usuario u
                LEFT JOIN paciente pac ON u.id_Usuario = pac.id_Usuario
                LEFT JOIN persona p ON pac.id_Persona = p.id_Persona
                WHERE u.Correo_E = ? AND u.codigo_verificacion = ? AND u.verificacion_expira > NOW() AND u.activo = 1";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $email, $codigo);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $usuario = $res->fetch_assoc();
            
            // Confirm verification
            $update = "UPDATE usuario SET verificado = 1, codigo_verificacion = NULL, verificacion_expira = NULL WHERE id_Usuario = ?";
            $stmt_up = $conexion->prepare($update);
            $stmt_up->bind_param("i", $usuario['id_Usuario']);
            $stmt_up->execute();

            // Set login session variables
            $_SESSION['id_Usuario'] = $usuario['id_Usuario'];
            $_SESSION['nombre_usuario'] = $usuario['Usuario_Nombre'];
            $_SESSION['usuario'] = $usuario['Usuario_Nombre'];
            $_SESSION['Correo_E'] = $usuario['Correo_E'];
            $_SESSION['id_Rol'] = 0; // default Paciente
            $_SESSION['id_Paciente'] = $usuario['id_Paciente'];
            $_SESSION['persona_nombre'] = $usuario['Persona_Nombre'];
            $_SESSION['persona_apellido'] = $usuario['Persona_Apellido'];
            $_SESSION['persona_telefono'] = $usuario['Persona_Telefono'];
            $_SESSION['persona_domicilio'] = $usuario['Persona_Domicilio'];

            header("Location: ../Views/Index.php");
            exit();
        } else {
            $mensaje = "Código incorrecto o vencido.";
        }
    } elseif (isset($_POST['reenviar'])) {
        $nuevo_codigo = str_pad(rand(0, 9999), 4, "0", STR_PAD_LEFT);
        $nueva_expira = date("Y-m-d H:i:s", strtotime('+10 minutes'));

        $update = "UPDATE usuario SET codigo_verificacion = ?, verificacion_expira = ? WHERE Correo_E = ?";
        $stmt_up = $conexion->prepare($update);
        $stmt_up->bind_param("sss", $nuevo_codigo, $nueva_expira, $email);
        
        if ($stmt_up->execute()) {
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
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Nuevo Codigo de Verificacion - Beauty SPA';
                $mail->Body = "
                    <h2>¡Verifica tu cuenta!</h2>
                    <p>Tu nuevo código de verificación es:</p>
                    <h1 style='color: #e879a0; font-size: 32px; letter-spacing: 2px;'>$nuevo_codigo</h1>
                    <p>Este código vencerá en 10 minutos.</p>
                ";
                $mail->send();
                $mensaje = "Nuevo código enviado a tu correo.";
            } catch (Exception $e) {
                $mensaje = "Error al enviar el correo.";
            }
        } else {
            $mensaje = "Error al generar el código.";
        }
    }
}

include __DIR__ . '/../Views/verificar_registro.php';
