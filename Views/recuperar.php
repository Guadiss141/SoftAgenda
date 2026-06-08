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
$codigo_enviado = false; // Para activar la cuenta regresiva en JS

if (isset($_POST['enviar_codigo'])) {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $mensaje = "Por favor ingresá tu correo electrónico.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "El correo ingresado no tiene un formato válido (debe contener @ y dominio).";
    } else {
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

            // ENVIAR MAIL
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

                $mensaje = "Código enviado al correo (Revisá también la carpeta Spam).";
                $mostrar_codigo = true;
                $codigo_enviado = true;

            } catch (Exception $e) {
                $mensaje = "Error al enviar el correo. Intentá nuevamente.";
            }

        } else {
            $mensaje = "No encontramos ninguna cuenta con ese correo.";
        }
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
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $_SESSION['codigo_verificado'] = $email;
        header("Location: nueva_contrasena.php?email=$email");
        exit;
    } else {
        $mensaje = "Código incorrecto o vencido. Revisá el correo o solicitá uno nuevo.";
        $mostrar_codigo = true;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recuperar contraseña - Beauty SPA</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
* {
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: 'Poppins', sans-serif;
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
    background:rgba(0,0,0,0.45);
}

.box {
    position:relative;
    background: rgba(255,255,255,0.12);
    padding:36px 32px;
    border-radius:20px;
    backdrop-filter: blur(22px);
    -webkit-backdrop-filter: blur(22px);
    border: 1px solid rgba(255,255,255,0.25);
    color:white;
    width:360px;
    z-index:1;
    text-align:center;
    box-shadow: 0 16px 48px rgba(0,0,0,0.3);
}

h2 {
    margin-bottom:22px;
    font-size: 1.5rem;
    font-weight: 600;
}

input[type="email"],
input[type="text"] {
    width:100%;
    margin:10px 0;
    padding:13px 16px;
    border-radius:30px;
    border:2px solid rgba(255,255,255,.3);
    background:rgba(255,255,255,0.1);
    color:white;
    font-size: 0.9rem;
    transition: border 0.3s;
}

input[type="email"]:focus,
input[type="text"]:focus {
    outline:none;
    border-color: rgba(255,255,255,0.8);
}

input::placeholder {
    color: rgba(255,255,255,0.7);
}

input.input-error {
    border-color: #ff7675 !important;
    box-shadow: 0 0 8px rgba(255,118,117,0.4);
}

button.btn-main {
    width:100%;
    padding:13px;
    border:none;
    border-radius:30px;
    background:white;
    cursor:pointer;
    font-weight:600;
    font-size: 0.95rem;
    margin-top:8px;
    color: #333;
    transition: all 0.3s;
}

button.btn-main:hover:not(:disabled) {
    background:#f0f0f0;
    transform: translateY(-1px);
}

button.btn-main:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.msg {
    margin-bottom: 14px;
    padding: 10px 14px;
    border-radius: 10px;
    background: rgba(255,255,255,0.15);
    color: #fff;
    font-size: 0.85rem;
    line-height: 1.4;
}

.msg.msg-error {
    background: rgba(255,118,117,0.25);
    border-left: 3px solid #ff7675;
}

.msg.msg-ok {
    background: rgba(85,239,196,0.2);
    border-left: 3px solid #55efc4;
    color: #d4fff5;
}

.separador {
    margin: 18px 0 10px;
    border-top: 1px solid rgba(255,255,255,0.2);
}

/* Cuenta regresiva */
.countdown-wrap {
    margin-top: 12px;
    font-size: 0.82rem;
    color: rgba(255,255,255,0.75);
    min-height: 20px;
}

#btn-reenviar {
    display: none;
    margin-top: 10px;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.4);
    color: white;
    padding: 9px 20px;
    border-radius: 30px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: background 0.3s;
}

#btn-reenviar:hover {
    background: rgba(255,255,255,0.25);
}

/* Modal overlay */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.65);
    justify-content: center;
    align-items: center;
    z-index: 3000;
    backdrop-filter: blur(4px);
}

.modal-box {
    background: #fff;
    padding: 36px 30px;
    border-radius: 20px;
    width: 90%;
    max-width: 360px;
    text-align: center;
    animation: popIn 0.25s ease;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
}

@keyframes popIn {
    from { opacity: 0; transform: scale(0.9); }
    to   { opacity: 1; transform: scale(1); }
}

.modal-icon { font-size: 2rem; margin-bottom: 8px; }
.modal-title { font-size: 1.1rem; font-weight: 700; color: #1a1a1a; margin-bottom: 8px; }
.modal-msg { font-size: 0.87rem; color: #666; line-height: 1.5; margin-bottom: 20px; }

.btn-modal-ok {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 30px;
    background: #e879a0;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: opacity 0.3s;
}

.btn-modal-ok:hover { opacity: 0.88; }
</style>
</head>

<body>

<div class="box">
    <h2>🔐 Recuperar contraseña</h2>

    <?php if($mensaje): ?>
        <div class="msg <?= $codigo_enviado ? 'msg-ok' : 'msg-error' ?>">
            <?= htmlspecialchars($mensaje) ?>
        </div>
    <?php endif; ?>

    <!-- Formulario: enviar código -->
    <form method="POST" id="form-enviar" novalidate>
        <input type="email" name="email" id="email-input"
               placeholder="Tu correo electrónico"
               value="<?php echo htmlspecialchars($email); ?>">

        <button type="submit" name="enviar_codigo" class="btn-main" id="btn-enviar">
            <?= $codigo_enviado ? 'Código enviado ✓' : 'Enviar código' ?>
        </button>

        <!-- Cuenta regresiva -->
        <div class="countdown-wrap" id="countdown-wrap"></div>
        <button type="button" id="btn-reenviar" onclick="reenviarCodigo()">
            🔄 Reenviar código
        </button>
    </form>

    <?php if($mostrar_codigo){ ?>
    <div class="separador"></div>

    <form method="POST" id="form-verificar" novalidate>
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <input type="text" name="codigo" placeholder="Código de 4 dígitos" maxlength="4" id="codigo-input">
        <button type="submit" name="verificar_codigo" class="btn-main">Verificar código</button>
    </form>
    <?php } ?>

</div>

<!-- Modal de validación -->
<div id="modal-error" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-icon">⚠️</div>
        <div class="modal-title" id="modal-err-titulo">Error</div>
        <p class="modal-msg" id="modal-err-msg">Hay un problema con los datos ingresados.</p>
        <button class="btn-modal-ok" onclick="cerrarModal()">Entendido</button>
    </div>
</div>

<script>
// ==========================================
// Modal de error
// ==========================================
function mostrarError(titulo, msg) {
    document.getElementById('modal-err-titulo').textContent = titulo;
    document.getElementById('modal-err-msg').textContent = msg;
    document.getElementById('modal-error').style.display = 'flex';
}

function cerrarModal() {
    document.getElementById('modal-error').style.display = 'none';
}

document.getElementById('modal-error').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});

// ==========================================
// Validación del formulario de envío
// ==========================================
document.getElementById('form-enviar').addEventListener('submit', function(e) {
    const emailInput = document.getElementById('email-input');
    const val = emailInput.value.trim();

    emailInput.classList.remove('input-error');

    if (!val) {
        e.preventDefault();
        emailInput.classList.add('input-error');
        mostrarError('Campo requerido', 'Por favor ingresá tu correo electrónico antes de continuar.');
        return;
    }

    // Validación básica de formato
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(val)) {
        e.preventDefault();
        emailInput.classList.add('input-error');
        mostrarError('Correo inválido', 'El correo debe tener el formato correcto (ej: nombre@dominio.com).');
        return;
    }
});

// Validación formulario verificar código
const formVerif = document.getElementById('form-verificar');
if (formVerif) {
    formVerif.addEventListener('submit', function(e) {
        const codigoInput = document.getElementById('codigo-input');
        if (!codigoInput.value.trim()) {
            e.preventDefault();
            codigoInput.classList.add('input-error');
            mostrarError('Campo requerido', 'Ingresá el código de 4 dígitos que recibiste por correo.');
        }
    });
}

// ==========================================
// Cuenta regresiva (10 segundos)
// ==========================================
const COUNTDOWN_SECS = 10;
let countdownInterval = null;

function iniciarCuentaRegresiva() {
    const wrap = document.getElementById('countdown-wrap');
    const btnEnviar = document.getElementById('btn-enviar');
    const btnReenviar = document.getElementById('btn-reenviar');

    btnEnviar.disabled = true;
    btnReenviar.style.display = 'none';

    let segundos = COUNTDOWN_SECS;
    wrap.textContent = 'Podés reenviar el código en ' + segundos + 's...';

    countdownInterval = setInterval(function() {
        segundos--;
        if (segundos <= 0) {
            clearInterval(countdownInterval);
            wrap.textContent = '¿No recibiste el código?';
            btnReenviar.style.display = 'inline-block';
            btnEnviar.disabled = false;
        } else {
            wrap.textContent = 'Podés reenviar el código en ' + segundos + 's...';
        }
    }, 1000);
}

function reenviarCodigo() {
    const form = document.getElementById('form-enviar');
    // Crear input hidden para el submit
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'enviar_codigo';
    input.value = '1';
    form.appendChild(input);
    form.submit();
}

// Activar cuenta regresiva si se acaba de enviar el código
<?php if ($codigo_enviado): ?>
window.addEventListener('load', function() {
    iniciarCuentaRegresiva();
});
<?php endif; ?>
</script>

</body>
</html>