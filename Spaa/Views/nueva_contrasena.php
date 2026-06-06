<?php
$conexion = new mysqli("localhost", "root", "345756", "gestionspabd");

// VALIDAR QUE VENGA EMAIL
if (!isset($_GET['email'])) {
    die("Acceso inválido");
}

$email = $_GET['email'];
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $pass = $_POST['password'];
    $confirmar = $_POST['confirmar_password'];

    // VALIDAR COINCIDENCIA
    if ($pass !== $confirmar) {
        $mensaje = "Las contraseñas no coinciden";
    } else {

        // HASHEAR CONTRASEÑA
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $sql = "UPDATE usuario 
                SET Usuario_Contraseña=?, 
                    codigo_recuperacion=NULL, 
                    codigo_expira=NULL 
                WHERE Correo_E=?";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $hash, $email);
        $stmt->execute();

        $mensaje = "Contraseña actualizada correctamente";

        // REDIRECCIÓN
        header("refresh:2;url=login.php");
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nueva contraseña</title>
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
    background: #f6c8c7;
}

/* caja */
.box {
    position:relative;
    background: rgba(255,255,255,0.9);
    padding:35px;
    border-radius:20px;
    backdrop-filter: blur(20px);
    box-shadow: 0 10px 30px rgba(0,0,0,.1);
    color:#333;
    width:370px;
    text-align:center;
    z-index:1;
}

/* titulo de la caja */
.box h2 {
    margin-bottom:20px;
    font-size:26px;
    color: #1a1a1a;
}

input {
    width:100%;
    margin:12px 0;
    padding:12px;
    border-radius:30px;
    border:1.5px solid #eee;
    background:#fff;
    color:#333;
    font-size:15px;
}

input::placeholder {
    color:#999;
}

input:focus {
    outline:none;
    border-color:#f5b8d0;
}

/* boton */
button {
    width:100%;
    padding:12px;
    border:none;
    border-radius:30px;
    background:#e879a0;
    color: #fff;
    cursor:pointer;
    font-weight:600;
    margin-top:10px;
    transition: background 0.2s;
}

button:hover {
    background:#d4638c;
}

/* mensaje */
.msg {
    margin-bottom:15px;
    color:#ff6b6b;
    font-size:14px;
}
</style>
</head>

<body>

<div class="box">
    <h2>Nueva contraseña</h2>

    <?php if($mensaje) echo "<p class='msg'>$mensaje<br>Redirigiendo al login...</p>"; ?>

    <form method="POST" onsubmit="return validarPasswords()">
        <input type="password" id="pass" name="password" placeholder="Nueva contraseña" required>
        <input type="password" id="confirm" name="confirmar_password" placeholder="Confirmar contraseña" required>
        <button type="submit">Guardar</button>
    </form>
</div>

<script>
function mostrarAlertaPersonalizada(mensaje) {
    document.getElementById('custom-alert-message').textContent = mensaje;
    document.getElementById('custom-alert-modal').style.display = 'flex';
}
function cerrarAlertaPersonalizada() {
    document.getElementById('custom-alert-modal').style.display = 'none';
}
window.alert = mostrarAlertaPersonalizada;

function validarPasswords() {
    let p1 = document.getElementById("pass").value;
    let p2 = document.getElementById("confirm").value;

    if (p1 !== p2) {
        alert("Las contraseñas no coinciden");
        return false;
    }
    return true;
}
</script>

<!-- Modal de Alerta Personalizada -->
<div id="custom-alert-modal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 10000; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
    <div style="background: #fff; padding: 30px; border-radius: 20px; width: 90%; max-width: 360px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.2); animation: popIn 0.2s ease; color: #333;">
        <div style="font-size: 2.2rem; margin-bottom: 12px; color: #ff7675;">⚠️</div>
        <h3 style="margin-bottom: 10px; color: #333; font-weight: 600; font-size: 1.25rem;">Atención</h3>
        <p id="custom-alert-message" style="font-size: 0.9rem; color: #666; margin-bottom: 22px; line-height: 1.5;"></p>
        <button onclick="cerrarAlertaPersonalizada()" style="background: #333; color: #fff; border: none; padding: 10px 30px; border-radius: 30px; font-weight: 600; font-size: 0.9rem; cursor: pointer; transition: all 0.2s;">Aceptar</button>
    </div>
</div>

<style>
@keyframes popIn {
    from { opacity: 0; transform: scale(0.92); }
    to { opacity: 1; transform: scale(1); }
}
</style>

</body>
</html>