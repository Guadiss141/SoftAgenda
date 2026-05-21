<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Terapeuta - SoftAgenda</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            padding: 0; margin: 0; 
            background: url('../img/relax.png') no-repeat center center fixed;
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
        h1 { color: #5c4a3d; font-weight: 300; letter-spacing: 1px; border-bottom: 2px solid #e9e3dc; padding-bottom: 10px; margin-bottom: 25px; }

        .form-row { display: flex; gap: 15px; }
        .form-group { flex: 1; margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 500; color: #5c4a3d; margin-bottom: 8px; }
        .form-group input { 
            width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid #dcd3cb;
            box-sizing: border-box; background: #fff; transition: 0.3s;
        }
        .form-group input:focus { outline: none; border-color: #8da399; }

        .btn {
            display: inline-block; padding: 10px 20px; border-radius: 30px; text-decoration: none;
            cursor: pointer; border: none; font-size: 14px; font-weight: 500;
            transition: all 0.3s ease; text-align: center;
        }
        .btn-primary   { background: #8da399; color: white; box-shadow: 0 4px 6px rgba(141,163,153,0.3); }
        .btn-secondary { background: #dcd3cb; color: #5c4a3d; }
        .btn:hover { opacity: 0.9; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); }
        .button-group { margin-top: 25px; display: flex; gap: 10px; justify-content: space-between; }

        .alert { padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        .alert-danger { background: #f9ebea; color: #78281f; border-left: 4px solid #d98880; }
    </style>
</head>
<body>
<div class="container">

    <h1>Registrar Nuevo Terapeuta</h1>

    <!-- Mensaje de error si lo hay -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form action="registro_terapeuta.php" method="POST">

        <div class="form-row">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label>Apellido:</label>
                <input type="text" name="apellido" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nombre de Usuario (Login):</label>
                <input type="text" name="usuario_nombre" required>
            </div>
            <div class="form-group">
                <label>Correo Electrónico:</label>
                <input type="email" name="correo" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="contrasena" required>
            </div>
            <div class="form-group">
                <label>Confirmar Contraseña:</label>
                <input type="password" name="confirmar_contrasena" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Especialidad:</label>
                <input type="text" name="especialidad">
            </div>
            <div class="form-group">
                <label>CUIL:</label>
                <input type="text" name="cuil">
            </div>
        </div>

        <div class="button-group">
            <a href="admin_usuarios.php" class="btn btn-secondary">Cancelar y Volver</a>
            <button type="submit" class="btn btn-primary">Registrar Terapeuta</button>
        </div>

    </form>
</div>
</body>
</html>