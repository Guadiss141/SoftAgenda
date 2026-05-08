<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login - Beauty SPA</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
/* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

/* FONDO */
body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: url('img/cascada.jpg') no-repeat center;
    background-size: cover;
}

/* OSCURECER FONDO */
body::before {
    content: "";
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.4);
    z-index: 0;
}

/* HEADER */
.header {
    position: absolute;
    top: 0;
    width: 100%;
    padding: 20px 100px;
    display: flex;
    justify-content: space-between;
    z-index: 1;
}

.logo {
    color: #fff;
    font-size: 25px;
    text-decoration: none;
    font-weight: 600;
}

.nav a {
    color: #fff;
    text-decoration: none;
    margin-left: 20px;
}

/* CONTENEDOR LOGIN */
.wrapper {
    position: relative;
    width: 420px;
    background: rgba(255, 255, 255, 0.1);
    border: 2px solid rgba(255,255,255,.2);
    backdrop-filter: blur(20px);
    box-shadow: 0 0 30px rgba(0,0,0,.5);
    color: #fff;
    border-radius: 15px;
    padding: 30px 40px;
    z-index: 1;
}

/* TITULO */
.wrapper h1 {
    font-size: 32px;
    text-align: center;
}

/* INPUTS */
.input-box {
    position: relative;
    width: 100%;
    height: 50px;
    margin: 25px 0;
}

.input-box input {
    width: 100%;
    height: 100%;
    background: transparent;
    border: 2px solid rgba(255,255,255,.3);
    border-radius: 40px;
    outline: none;
    font-size: 16px;
    color: #fff;
    padding: 0 45px 0 20px;
}

.input-box input::placeholder {
    color: #ddd;
}

.input-box i {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
}

/* BOTON */
.btn {
    width: 100%;
    height: 45px;
    border: none;
    border-radius: 40px;
    background: #fff;
    color: #333;
    font-weight: 600;
    cursor: pointer;
}

/* LINK REGISTRO */
.register-link {
    text-align: center;
    margin-top: 15px;
}

.register-link a {
    color: #fff;
    text-decoration: none;
    font-weight: 600;
}

/* ERROR */
.error {
    color: #ff6b6b;
    text-align: center;
    margin-top: 10px;
}

</style>
</head>

<body>

<header class="header">
    <a href="index.php" class="logo">Beauty SPA</a>
</header>

<div class="wrapper">
    <form action="../Controllers/UsuarioController.php?action=login" method="POST">

        <h1>Login</h1>

        <?php if ($error) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <div class="input-box">
            <input type="text" name="usuario_o_correo" placeholder="Usuario o Correo" required>
            <i class='bx bx-user'></i>
        </div>

        <div class="input-box">
            <input type="password" name="contraseña" placeholder="Contraseña" required>
            <i class='bx bx-lock-alt'></i>
        </div>

        <button type="submit" class="btn">Iniciar sesión</button>

        <div class="register-link">
            <p>¿No tienes una cuenta? <a href="registro.php">Registrarse</a></p>
        </div>

        <div class="register-link">
            <p><a href="recuperar.php">¿Olvidaste tu contraseña?</a></p>
        </div>

    </form>
</div>

</body>
</html>