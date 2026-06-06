<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login - Beauty SPA</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;700&display=swap" rel="stylesheet">

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

.wrapper {
    position: relative;
    width: 400px;
    background: rgba(255, 255, 255, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.4);
    backdrop-filter: blur(16px) saturate(120%);
    -webkit-backdrop-filter: blur(16px) saturate(120%);
    box-shadow: 0 20px 50px rgba(0,0,0,0.08);
    color: #333;
    border-radius: 24px;
    padding: 40px 32px;
    z-index: 1;
}

.wrapper h1 {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 700;
    text-align: center;
    color: #1a1a1a;
    margin-bottom: 24px;
}

.input-box {
    position: relative;
    width: 100%;
    height: 48px;
    margin: 18px 0;
}

.input-box input {
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    border: 1.5px solid rgba(232, 121, 160, 0.25);
    border-radius: 30px;
    outline: none;
    font-size: 0.95rem;
    color: #1a1a1a;
    padding: 0 45px 0 20px;
    transition: all 0.3s ease;
}

.input-box input:focus {
    border-color: #e879a0;
    box-shadow: 0 0 10px rgba(232, 121, 160, 0.25);
}

.input-box input::placeholder {
    color: #999;
}

.input-box i {
    position: absolute;
    right: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #e879a0;
    font-size: 1.2rem;
}

.btn {
    width: 100%;
    height: 46px;
    border: none;
    border-radius: 30px;
    background: #e879a0;
    color: #fff;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(232, 121, 160, 0.3);
}

.btn:hover {
    background: #d4638c;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(232, 121, 160, 0.45);
}

.register-link {
    text-align: center;
    margin-top: 18px;
    font-size: 0.9rem;
}

.register-link p {
    color: #666;
}

.register-link a {
    color: #e879a0;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.register-link a:hover {
    color: #d4638c;
    text-decoration: underline;
}

.error {
    color: #e05555;
    background: #fdeaea;
    padding: 10px;
    border-radius: 10px;
    font-size: 0.85rem;
    text-align: center;
    margin-bottom: 15px;
}

</style>
</head>

<body>

<header class="header">
    <a href="index.php" class="logo">Beauty<span> SPA</span></a>
</header>

<div class="wrapper">
    <form action="../Controllers/UsuarioController.php?action=login" method="POST">

        <h1>Iniciar Sesión</h1>

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