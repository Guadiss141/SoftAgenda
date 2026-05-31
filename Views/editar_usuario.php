<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Editar Usuario - SoftAgenda</title>

    <style>

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 0;
            margin: 0;
            background: url('img/relax.png') no-repeat center center fixed;
            background-size: cover;
            background-color: #fcf9f2;
            color: #4a4a4a;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(253, 251, 247, 0.88);
            z-index: -1;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        h1 {
            color: #5c4a3d;
            font-weight: 300;
            letter-spacing: 1px;
            border-bottom: 2px solid #e9e3dc;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-group {
            flex: 1;
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: #5c4a3d;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid #dcd3cb;
            box-sizing: border-box;
            background: #fff;
            transition: 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #8da399;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-primary   { background: #8da399; color: white; }
        .btn-secondary { background: #dcd3cb; color: #5c4a3d; }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .button-group {
            margin-top: 25px;
            display: flex;
            gap: 10px;
            justify-content: space-between;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-success { background: #e9edc9; color: #4e5a37; border-left: 4px solid #a3b18a; }
        .alert-danger  { background: #f9ebea; color: #78281f; border-left: 4px solid #d98880; }

    </style>

</head>

<body>

<?php
//Seguridad: solo admin 
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
    header("Location: Login.php");
    exit();
}

//Cargar datos del usuario a editar 
require_once '../Models/Usuario.php';
$conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
if ($conexion->connect_error) die("Error de conexión");

$modelo  = new Usuario($conexion);
$mensaje = '';

if (!isset($_GET['id'])) {
    header("Location: admin_usuarios.php");
    exit();
}

$id = intval($_GET['id']);
$user = $modelo->obtenerPorId($id);

if (!$user) {
    header("Location: admin_usuarios.php?error=usuario_no_encontrado");
    exit();
}
?>

<div class="container">

    <h1>Editar Usuario</h1>

    <?php if (!empty($mensaje)) echo $mensaje; ?>

    <form action="../Controllers/UsuarioController.php?action=editar" method="POST">

        <input type="hidden" name="id_Usuario" value="<?= htmlspecialchars($user['id_Usuario']) ?>">

        <div class="form-row">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre"
                       value="<?= htmlspecialchars($user['Persona_Nombre'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Apellido:</label>
                <input type="text" name="apellido"
                       value="<?= htmlspecialchars($user['Persona_Apellido'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Teléfono:</label>
                <input type="text" name="telefono"
                       value="<?= htmlspecialchars($user['Persona_Telefono'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Domicilio:</label>
                <input type="text" name="domicilio"
                       value="<?= htmlspecialchars($user['Persona_Domicilio'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nombre de Usuario:</label>
                <input type="text" name="usuario_nombre"
                       value="<?= htmlspecialchars($user['Usuario_Nombre'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label>Correo Electrónico:</label>
                <input type="email" name="correo"
                       value="<?= htmlspecialchars($user['Correo_E'] ?? '') ?>" required>
            </div>
        </div>

        <?php if ($user['id_Rol'] == 2): ?>
        <div class="form-row">
            <div class="form-group">
                <label>Especialidad:</label>
                <input type="text" name="especialidad"
                       value="<?= htmlspecialchars($user['Especialidad'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>CUIL:</label>
                <input type="text" name="cuil"
                       value="<?= htmlspecialchars($user['CUIL'] ?? '') ?>">
            </div>
        </div>
        <?php endif; ?>

        <div class="button-group">
            <a href="../Controllers/admin_usuariosController.php" class="btn btn-secondary">Cancelar y Volver</a>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>

    </form>

</div>

</body>
</html>