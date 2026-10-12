<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignación de Módulos - SoftAgenda</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink: #e879a0;
            --pink-light: #f5b8d0;
            --pink-pale: #fde8f0;
            --red: #e05555;
            --red-pale: #fdeaea;
            --cream: #f7c6d0;
            --dark: #1a1a1a;
            --gray: #888;
            --gray-pale: #f5f5f5;
            --gray-mid: #ccc;
            --green: #5a9e6f;
            --green-pale: #eaf4ee;
        }

        body { 
            font-family: 'DM Sans', sans-serif; 
            padding: 0; 
            margin: 0; 
            background: var(--cream);
            color: var(--dark);
            min-height: 100vh;
            transition: background 0.3s, color 0.3s;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 40px;
            border-radius: 32px;
            box-shadow: 0 8px 40px rgba(232,121,160,0.12);
            transition: background 0.3s;
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            border-bottom: 2px solid var(--pink-pale);
            padding-bottom: 10px;
        }

        .header-flex h1 {
            font-family: 'Playfair Display', serif;
            color: var(--dark);
            font-weight: 900;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .badge-rol {
            display: inline-block;
            padding: 6px 14px;
            background: var(--pink-pale);
            color: var(--pink);
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 500;
            border: 1px solid var(--pink-light);
        }

        .table-container {
            overflow-x: auto;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            background: white;
            transition: background 0.3s;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.88rem;
        }

        .table th, .table td {
            padding: 14px 16px;
            text-align: center;
            border-bottom: 1px solid #f5f5f5;
        }
        
        .table td:first-child { text-align: left; }

        .table th {
            background: var(--pink-pale);
            color: var(--pink);
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .table tr:hover {
            background: var(--gray-pale);
        }

        .module-name { font-weight: 600; color: var(--dark); }
        .module-desc { font-size: 0.8em; color: var(--gray); }

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--pink);
        }

        .btn {
            display: inline-block;
            padding: 11px 24px;
            border-radius: 30px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-align: center;
        }

        .btn-primary { 
            background: var(--pink); 
            color: white; 
            box-shadow: 0 4px 6px rgba(232,121,160,0.3); 
        }
        
        .btn-secondary { 
            background: var(--gray-pale); 
            color: var(--dark); 
            border: 1.5px solid var(--gray-mid); 
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-success { background: var(--green-pale); color: var(--green); border-left: 4px solid var(--green); }
        .alert-danger  { background: var(--red-pale); color: var(--red); border-left: 4px solid var(--red); }

        /* ════════════════════════
           TEMA OSCURO
           Se activa agregando la clase .tema-oscuro al <html>
        ════════════════════════ */
        html.tema-oscuro body {
            background: #15151a;
            color: #eee;
        }

        html.tema-oscuro .container {
            background: #1e1e24;
            box-shadow: 0 8px 40px rgba(0,0,0,0.4);
        }

        html.tema-oscuro .header-flex {
            border-bottom-color: rgba(232,121,160,0.25);
        }

        html.tema-oscuro .header-flex h1 {
            color: #f2f2f2;
        }

        html.tema-oscuro .badge-rol {
            background: rgba(232,121,160,0.15);
            border-color: rgba(232,121,160,0.3);
        }

        html.tema-oscuro .table-container {
            background: #1e1e24;
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
        }

        html.tema-oscuro .table th {
            background: rgba(232,121,160,0.12);
        }

        html.tema-oscuro .table td {
            color: #eee;
            border-bottom-color: #2a2a31;
        }

        html.tema-oscuro .table tr:hover {
            background: #26262e;
        }

        html.tema-oscuro .module-name {
            color: #f2f2f2;
        }

        html.tema-oscuro .module-desc {
            color: #999;
        }

        html.tema-oscuro .btn-secondary {
            background: #2a2a31;
            color: #eee;
            border-color: #3a3a42;
        }

        html.tema-oscuro .alert-success {
            background: rgba(90,158,111,0.15);
            color: #8fc9a0;
        }

        html.tema-oscuro .alert-danger {
            background: rgba(224,85,85,0.15);
            color: #f19d9d;
        }
    </style>
    <?php include __DIR__ . '/../Tema/TemaHead.php'; ?>
</head>
<body>

<div class="container">
    <div class="header-flex">
        <h1>Asignación de Módulos</h1>
        <div class="badge-rol">Módulo Administrador</div>
    </div>

    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] === 'success'): ?>
            <div class="alert alert-success">Configuración guardada correctamente.</div>
        <?php elseif ($_GET['status'] === 'error'): ?>
            <div class="alert alert-danger">Error al guardar la configuración.</div>
        <?php endif; ?>
    <?php endif; ?>

    <form method="POST" action="ModulosController.php?action=guardar">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Módulo</th>
                        <?php foreach ($roles as $id_Rol => $nombreRol): ?>
                            <th><?= htmlspecialchars($nombreRol) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modulos as $modulo): ?>
                        <tr>
                            <td>
                                <div class="module-name"><?= htmlspecialchars($modulo['Nombre']) ?></div>
                                <div class="module-desc"><?= htmlspecialchars($modulo['Descripcion']) ?></div>
                            </td>
                            <?php foreach ($roles as $id_Rol => $nombreRol): ?>
                                <?php 
                                    $isChecked = isset($asignaciones[$id_Rol]) && in_array($modulo['id_Modulo'], $asignaciones[$id_Rol]); 
                                ?>
                                <td>
                                    <input type="checkbox" name="modulos[<?= $id_Rol ?>][<?= $modulo['id_Modulo'] ?>]" value="1" <?= $isChecked ? 'checked' : '' ?>>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="button-group">
            <button type="submit" class="btn btn-primary">Guardar Configuración</button>
            <a href="../Inicioo/Index.php" class="btn btn-secondary">Volver al Inicio</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/../Views/ayuda_footer.php'; ?>

</body>
</html>
