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
    background:url('img/cascada.jpg') no-repeat center;
    background-size:cover;
}

/* oscurecer fondo */
body::before {
    content:"";
    position:absolute;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.4);
}

/* caja */
.box {
    position:relative;
    background: rgba(255,255,255,0.1);
    padding:35px;
    border-radius:15px;
    backdrop-filter: blur(20px);
    color:white;
    width:370px;
    text-align:center;
    z-index:1;
}

/* titulo de la caja */
.box h2 {
    margin-bottom:20px;
    font-size:26px;
}

input {
    width:100%;
    margin:12px 0;
    padding:12px;
    border-radius:30px;
    border:2px solid rgba(255,255,255,.3);
    background:transparent;
    color:white;
    font-size:15px;
}

input::placeholder {
    color:#ddd;
}

input:focus {
    outline:none;
    border:2px solid white;
}

/* boton */
button {
    width:100%;
    padding:12px;
    border:none;
    border-radius:30px;
    background:white;
    cursor:pointer;
    font-weight:600;
    margin-top:10px;
    transition:0.3s;
}

button:hover {
    background:#ddd;
}

/* mensaje */
.msg {
    margin-bottom:15px;
    color:#ffdede;
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

</body>
</html>