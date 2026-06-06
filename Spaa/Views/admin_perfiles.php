<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Perfiles y Módulos - SoftAgenda</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            padding: 0; 
            margin: 0; 
            background: #f6c8c7; 
            color: #4a4a4a; 
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        h1, h2 {
            color: #5c4a3d;
            font-weight: 300;
            letter-spacing: 1px;
            border-bottom: 2px solid #e9e3dc;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }

        .badge-rol {
            display: inline-block;
            padding: 6px 14px;
            background: #e9edc9;
            color: #5c4a3d;
            border-radius: 20px;
            font-size: 0.9em;
            margin-bottom: 20px;
            font-weight: 500;
            border: 1px solid #d4a373;
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

        .btn-primary { background: #8da399; color: white; }
        .btn-success { background: #a3b18a; color: white; }
        .btn-danger { background: #d98880; color: white; }
        .btn-secondary { background: #dcd3cb; color: #5c4a3d; }
        .btn-small { padding: 6px 12px; font-size: 12px; }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .grid-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid #f0e9e1;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #f0e9e1;
        }

        .table th {
            background: #fdfbf7;
            color: #5c4a3d;
            font-weight: 600;
        }

        .form-group {
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
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #dcd3cb;
            box-sizing: border-box;
            background: #fff;
            font-size: 14px;
        }

        .checkbox-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            text-align: left;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success { background: #e9edc9; color: #4e5a37; border-left: 4px solid #a3b18a; }
        .alert-danger  { background: #f9ebea; color: #78281f; border-left: 4px solid #d98880; }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .header-flex h1 {
            margin-bottom: 0;
            border-bottom: none;
            padding-bottom: 0;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-flex">
        <h1>Administración de Perfiles y Módulos</h1>
        <div class="badge-rol">Módulo Administrador</div>
    </div>

    <?php if (!empty($mensaje)): ?>
        <div class="alert alert-success">✓ <?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">✗ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="grid-container">
        
        <!-- List of Roles and Permissions Assignment -->
        <div class="card">
            <h2>Perfiles Registrados</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Descripción del Perfil</th>
                        <th>Módulos Permitidos (Permisos)</th>
                        <th style="width: 140px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $r): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($r['id_Rol']) ?></strong></td>
                            <td>
                                <form action="PerfilCrudController.php?action=editar_rol" method="POST" style="display: flex; gap: 5px;">
                                    <input type="hidden" name="id_Rol" value="<?= $r['id_Rol'] ?>">
                                    <input type="text" name="Rol_Descripcion" value="<?= htmlspecialchars($r['Rol_Descripcion']) ?>" required style="padding: 5px; border-radius: 5px; border: 1px solid #dcd3cb; font-size: 13px; width: 100%;">
                                    <button type="submit" class="btn btn-primary btn-small" title="Guardar cambios de nombre">✓</button>
                                </form>
                            </td>
                            <td>
                                <form action="PerfilCrudController.php?action=guardar_permisos" method="POST">
                                    <input type="hidden" name="id_Rol" value="<?= $r['id_Rol'] ?>">
                                    <div class="checkbox-list">
                                        <?php foreach ($modulos as $m): ?>
                                            <?php 
                                                $checked = isset($permisos[$r['id_Rol']]) && in_array($m['id_Modulo'], $permisos[$r['id_Rol']]) ? 'checked' : '';
                                            ?>
                                            <label class="checkbox-item">
                                                <input type="checkbox" name="modulos[]" value="<?= $m['id_Modulo'] ?>" <?= $checked ?>>
                                                <?= htmlspecialchars($m['Modulo_Nombre']) ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-small" style="margin-top: 8px;">Guardar Permisos</button>
                                </form>
                            </td>
                            <td>
                                <?php if (in_array($r['id_Rol'], [0, 2, 3])): ?>
                                    <span style="font-size: 11px; color: #aaa; font-style: italic;">Sistema (Bloqueado)</span>
                                <?php else: ?>
                                    <form action="PerfilCrudController.php?action=eliminar_rol" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este perfil? Esto podría afectar a los usuarios asociados.');">
                                        <input type="hidden" name="id_Rol" value="<?= $r['id_Rol'] ?>">
                                        <button type="submit" class="btn btn-danger btn-small">Eliminar</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Create Profile Card -->
        <div class="card" style="height: fit-content;">
            <h2>Crear Nuevo Perfil</h2>
            <form action="PerfilCrudController.php?action=crear_rol" method="POST">
                <div class="form-group">
                    <label for="id_Rol">ID del Rol (Número):</label>
                    <input type="number" name="id_Rol" id="id_Rol" required min="1" placeholder="Ej. 4">
                </div>
                <div class="form-group">
                    <label for="Rol_Descripcion">Nombre del Perfil:</label>
                    <input type="text" name="Rol_Descripcion" id="Rol_Descripcion" required placeholder="Ej. Supervisor">
                </div>
                <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 10px;">Crear Perfil</button>
            </form>
        </div>

    </div>

    <div style="text-align: center;">
        <a href="admin_usuariosController.php" class="btn btn-secondary">Volver a Gestión de Usuarios</a>
    </div>
</div>

</body>
</html>
