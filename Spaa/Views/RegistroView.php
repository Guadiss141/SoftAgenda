<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Beauty SPA</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<style>

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: url('img/cascada3.jpg') no-repeat;
  background-size: cover;
}

.container {
  width: 420px;
  background: transparent;
  border: 2px solid rgba(255, 255, 255, .2);
  backdrop-filter: blur(20px); 
  box-shadow: 0 0 10px rgba(0, 0, 0, .2); 
  color: #fff;
  border-radius: 10px;
  padding: 30px 40px;
  text-align: center;
}

.container h1 {
  font-size: 36px;
  margin-bottom: 20px;
}

.container input {
  width: 100%;
  height: 50px;
  background: transparent;
  border-radius: 40px;
  outline: none;
  font-size: 16px;
  color: #fff;
  padding: 20px;
  margin: 15px 0;
  border: 2px solid rgba(255, 255, 255, 0.3);
  transition: 0.3s;
}

.container input::placeholder {
  color: #ffffff;
}

/* 🔥 borde cuando hay error */
.container input.error-input {
  border: 2px solid #ffeaa7;
  box-shadow: 0 0 8px #ffeaa7;
}

.container .btn {
  width: 100%;
  height: 45px;
  background: #fff;
  border: none;
  outline: none;
  border-radius: 40px;
  box-shadow: 0 0 10px rgba(0, 0, 0, .1);
  cursor: pointer;
  font-size: 16px;
  color: #333;
  font-weight: 600;
  margin-top: 10px;
}

a {
  text-decoration: none;
  color: #fff;
  font-size: 14px;
  display: block;
  margin-top: 10px;
}

a:hover {
  text-decoration: underline;
}

.mensaje {
  font-size: 14px;
  margin-bottom: 10px;
  text-align: center;
  color: #ff7675;
}

.input-group {
    position: relative;
}

.input-group i {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
    color: #fff;
}

/* 🔥 mensaje elegante */
.error-pass {
  font-size: 14px;
  margin-top: -10px;
  margin-bottom: 10px;
  color: #ffeaa7;
  opacity: 0;
  transform: translateY(-5px);
  transition: all 0.3s ease;
}

.error-pass.show {
  opacity: 1;
  transform: translateY(0);
}

/* animación */
@keyframes shake {
  0% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  50% { transform: translateX(5px); }
  75% { transform: translateX(-5px); }
  100% { transform: translateX(0); }
}

.error-pass.shake {
  animation: shake 0.3s;
}

</style>

<body>

<div class="container">

    <h1>Crear Cuenta</h1>

    <?php if (!empty($mensaje)): ?>
        <p class="mensaje"><?= $mensaje ?></p>
    <?php endif; ?>

    <form action="Registro.php" method="POST" onsubmit="return validarPasswords()">

        <div class="input-group">
            <input type="text" name="nombre_usuario" placeholder="Nombre de usuario" required>
            <i class='bx bxs-user'></i>
        </div>

        <div class="input-group">
            <input type="email" name="correo_usuario" placeholder="Correo electrónico" required>
            <i class='bx bxs-envelope'></i>
        </div>

        <div class="input-group">
            <input type="password" id="password" name="contraseña" placeholder="Contraseña" required>
            <i class='bx bxs-lock-alt'></i>
        </div>

        <div class="input-group">
            <input type="password" id="confirmar" name="confirmar_contraseña" placeholder="Confirmar contraseña" required>
            <i class='bx bxs-lock'></i>
        </div>

        <!-- mensaje -->
        <p id="error-pass" class="error-pass">
            Las contraseñas no coinciden
        </p>

        <button type="submit" class="btn">Registrar</button>
    </form>

    <a href="Login.php">Ya tengo cuenta</a>
    <a href="Index.php">Volver al inicio</a>

</div>

<script>
let yaMostrado = false;

function validarPasswords() {
    let pass = document.getElementById("password");
    let confirm = document.getElementById("confirmar");
    let error = document.getElementById("error-pass");

    if (pass.value !== confirm.value) {
        error.classList.add("show");

        pass.classList.add("error-input");
        confirm.classList.add("error-input");

        if (!yaMostrado) {
            error.classList.add("shake");
            setTimeout(() => {
                error.classList.remove("shake");
            }, 300);
            yaMostrado = true;
        }

        return false;
    } else {
        error.classList.remove("show");

        pass.classList.remove("error-input");
        confirm.classList.remove("error-input");

        yaMostrado = false;
    }

    return true;
}
</script>

</body>
</html>