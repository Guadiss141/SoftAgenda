<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Registro - Beauty SPA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
            background: #f6c8c7;
        }

        .container {
            position: relative;
            width: 400px;
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, .5);
            backdrop-filter: blur(20px); 
            box-shadow: 0 10px 30px rgba(0, 0, 0, .1); 
            color: #333;
            border-radius: 20px;
            padding: 45px 35px;
            text-align: center;
            z-index: 1;
        }

        .container h1 {
            font-size: 28px;
            margin-bottom: 15px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .container p.description {
            font-size: 14px;
            margin-bottom: 25px;
            color: #666;
            line-height: 1.4;
        }

        .container input {
            width: 100%;
            height: 50px;
            background: #fff;
            border-radius: 40px;
            outline: none;
            font-size: 18px;
            color: #333;
            padding: 0 20px;
            margin: 15px 0;
            border: 1.5px solid #eee;
            text-align: center;
            letter-spacing: 4px;
            transition: 0.3s;
        }

        .container input::placeholder {
            color: #999;
            letter-spacing: normal;
        }

        .container input:focus {
            border-color: #f5b8d0;
        }

        .container .btn {
            width: 100%;
            height: 45px;
            background: #e879a0;
            border: none;
            outline: none;
            border-radius: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .1);
            cursor: pointer;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
            margin-top: 10px;
            transition: background 0.2s;
        }

        .container .btn:hover {
            background: #d4638c;
        }

        .mensaje {
            font-size: 14px;
            margin-bottom: 10px;
            text-align: center;
            color: #ff7675;
        }
        .mensaje-success {
            color: #2ecc71;
        }

        a {
            text-decoration: none;
            color: #e879a0;
            font-size: 14px;
            display: block;
            margin-top: 20px;
        }

        a:hover {
            text-decoration: underline;
            color: #d4638c;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Verifica tu correo</h1>
    <p class="description">Ingresa el código de 4 dígitos enviado a <br><strong><?= htmlspecialchars($email) ?></strong></p>

    <?php if (!empty($mensaje)): ?>
        <p class="mensaje <?= (strpos($mensaje, 'enviado') !== false || strpos($mensaje, 'Correcto') !== false) ? 'mensaje-success' : '' ?>"><?= $mensaje ?></p>
    <?php endif; ?>

    <form action="VerificarRegistroController.php" method="POST">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
        <input type="text" name="codigo" placeholder="Código de 4 dígitos" required maxlength="4" pattern="\d{4}">
        <button type="submit" name="verificar" class="btn">Verificar Código</button>
    </form>

    <form action="VerificarRegistroController.php" method="POST" style="margin-top: 10px;">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
        <button type="submit" name="reenviar" style="background:none; border:none; color:#e879a0; text-decoration:underline; font-size:14px; cursor:pointer;">
            Reenviar código de verificación
        </button>
    </form>

    <a href="../Controllers/LoginControlls.php">Volver al Login</a>
</div>

</body>
</html>
