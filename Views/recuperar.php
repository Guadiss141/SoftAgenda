<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

$conexion = new mysqli("localhost", "root", "345756", "gestionspabd");

$mensaje = "";
$mostrar_codigo = false;
$email = "";

if (isset($_POST['enviar_codigo'])) {
    $email = $_POST['email'];

    $sql = "SELECT * FROM usuario WHERE Correo_E = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {

        $codigo = str_pad(rand(0, 9999), 4, "0", STR_PAD_LEFT);
        $expira = date("Y-m-d H:i:s", strtotime('+10 minutes'));

        $update = "UPDATE usuario 
                   SET codigo_recuperacion=?, codigo_expira=? 
                   WHERE Correo_E=?";
        $stmt = $conexion->prepare($update);
        $stmt->bind_param("sss", $codigo, $expira, $email);
        $stmt->execute();

        // 🔥 ENVIAR MAIL
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
            $mail->Subject = 'Recuperación de contraseña';
            $mail->Body = "
                <h2>Beauty SPA</h2>
                <p>Tu código de recuperación es:</p>
                <h1>$codigo</h1>
                <p>Este código vence en 10 minutos.</p>
            ";

            $mail->send();

            $mensaje = "Código enviado al correo (Verifica Spam)";
            $mostrar_codigo = true;

        } catch (Exception $e) {
            $mensaje = "Error al enviar el correo";
        }

    } else {
        $mensaje = "Correo no encontrado";
    }
}

if (isset($_POST['verificar_codigo'])) {
    $email = $_POST['email'];
    $codigo = $_POST['codigo'];

    $sql = "SELECT * FROM usuario 
            WHERE Correo_E=? 
            AND codigo_recuperacion=? 
            AND codigo_expira > NOW()";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $email, $codigo);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        header("Location: nueva_contrasena.php?email=$email");
        exit;
    } else {
        $mensaje = "Código incorrecto o vencido";
        $mostrar_codigo = true;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recuperar contraseña</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
* {
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Poppins;
}

body {
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
    background:url('img/cascada.jpg') no-repeat center;
    background-size:cover;
}

body::before {
    content:"";
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
}

.box {
    position:relative;
    background: rgba(255,255,255,0.1);
    padding:30px;
    border-radius:15px;
    backdrop-filter: blur(20px);
    color:white;
    width:350px;
    z-index:1;
    text-align:center;
}

h2 {
    margin-bottom:20px;
}

input {
    width:100%;
    margin:10px 0;
    padding:12px;
    border-radius:30px;
    border:2px solid rgba(255,255,255,.3);
    background:transparent;
    color:white;
}

input::placeholder {
    color:#ddd;
}

input:focus {
    outline:none;
    border:2px solid white;
}

button {
    width:100%;
    padding:12px;
    border:none;
    border-radius:30px;
    background:white;
    cursor:pointer;
    font-weight:600;
    margin-top:5px;
}

button:hover {
    background:#ddd;
    transition:0.3s;
}

.msg {
    margin-bottom:10px;
    color:#ffdede;
}
</style>
</head>

<body>

<div class="box">
    <h2>Recuperar contraseña</h2>

    <?php if($mensaje) echo "<p class='msg'>$mensaje</p>"; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Tu correo" required value="<?php echo $email; ?>">
        <button name="enviar_codigo">Enviar código</button>
    </form>

    <?php if($mostrar_codigo){ ?>
    <form method="POST">
        <input type="hidden" name="email" value="<?php echo $email; ?>">
        <input type="text" name="codigo" placeholder="Código de 4 dígitos" required>
        <button name="verificar_codigo">Verificar código</button>
    </form>
    <?php } ?>

</div>

</body>
</html>