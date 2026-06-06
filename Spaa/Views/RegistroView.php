<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - Beauty SPA</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>

<style>

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "DM Sans", sans-serif;
}

body {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: url('img/spa_login_bg.png') no-repeat center center fixed;
  background-size: cover;
}

.header {
  position: absolute;
  top: 0;
  width: 100%;
  padding: 24px 40px;
  display: flex;
  justify-content: space-between;
  z-index: 1;
}

.logo {
  color: #1a1a1a;
  font-family: 'Playfair Display', serif;
  font-size: 1.6rem;
  text-decoration: none;
  font-weight: 900;
  letter-spacing: -0.5px;
}

.logo span {
  color: #e879a0;
}

.container {
  width: 400px;
  background: rgba(255, 255, 255, 0.85);
  border: 1px solid rgba(255, 255, 255, 0.4);
  backdrop-filter: blur(16px) saturate(120%);
  -webkit-backdrop-filter: blur(16px) saturate(120%);
  box-shadow: 0 20px 50px rgba(0, 0, 0, .08); 
  color: #333;
  border-radius: 24px;
  padding: 40px 32px;
  text-align: center;
  z-index: 1;
}

.container h1 {
  font-family: 'Playfair Display', serif;
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 24px;
  color: #1a1a1a;
}

.input-group {
    position: relative;
    margin: 14px 0;
}

.container input {
  width: 100%;
  height: 48px;
  background: rgba(255, 255, 255, 0.95);
  border-radius: 30px;
  outline: none;
  font-size: 0.95rem;
  color: #333;
  padding: 0 45px 0 20px;
  border: 1.5px solid rgba(232, 121, 160, 0.25);
  transition: all 0.3s ease;
}

.container input:focus {
  border-color: #e879a0;
  box-shadow: 0 0 10px rgba(232, 121, 160, 0.25);
}

.container input::placeholder {
  color: #999;
}

.input-group i {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.2rem;
    color: #e879a0;
}

/* 🔥 borde cuando hay error */
.container input.error-input {
  border: 1.5px solid #e05555;
  box-shadow: 0 0 8px rgba(224, 85, 85, 0.25);
}

.container .btn {
  width: 100%;
  height: 46px;
  background: #e879a0;
  border: none;
  outline: none;
  border-radius: 30px;
  box-shadow: 0 4px 15px rgba(232, 121, 160, 0.3);
  cursor: pointer;
  font-size: 0.95rem;
  color: #fff;
  font-weight: 500;
  margin-top: 10px;
  transition: all 0.3s ease;
}

.container .btn:hover {
  background: #d4638c;
  transform: translateY(-1px);
  box-shadow: 0 6px 20px rgba(232, 121, 160, 0.45);
}

a {
  text-decoration: none;
  color: #e879a0;
  font-size: 14px;
  display: block;
  margin-top: 14px;
  font-weight: 500;
  transition: color 0.2s;
}

a:hover {
  text-decoration: underline;
  color: #d4638c;
}

.mensaje {
  font-size: 0.85rem;
  background: #e9edc9;
  color: #4e5a37;
  padding: 10px;
  border-radius: 10px;
  margin-bottom: 15px;
  text-align: center;
}

/* 🔥 mensaje elegante */
.error-pass {
  font-size: 13px;
  margin-top: -8px;
  margin-bottom: 8px;
  color: #e05555;
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

<header class="header">
    <a href="index.php" class="logo">Beauty<span> SPA</span></a>
</header>

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