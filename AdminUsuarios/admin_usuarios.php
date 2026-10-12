<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Usuarios - SoftAgenda</title>

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
            --orange: #e07830;
            --orange-pale: #fdf0e8;
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
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            padding: 40px;
            border-radius: 32px;
            box-shadow: 0 8px 40px rgba(232,121,160,0.12);
            transition: background 0.3s;
        }

        h1, h2 {
            font-family: 'Playfair Display', serif;
            color: var(--dark);
            font-weight: 900;
            letter-spacing: -0.5px;
            border-bottom: 2px solid var(--pink-pale);
            padding-bottom: 10px;
            margin-bottom: 25px;
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

        .btn-primary {
            background: var(--pink);
            color: white;
            box-shadow: 0 4px 6px rgba(232,121,160,0.3);
        }

        .btn-success {
            background: var(--green);
            color: white;
        }

        .btn-danger {
            background: var(--red);
            color: white;
        }

        .btn-secondary {
            background: var(--gray-pale);
            color: var(--dark);
            border: 1.5px solid var(--gray-mid);
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 14px;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .button-group {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            font-size: 0.88rem;
        }

        .table th,
        .table td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #f5f5f5;
        }

        .table th {
            background: var(--pink-pale);
            color: var(--pink);
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .table tr:hover {
            background: var(--gray-pale);
        }

        .table td {
            vertical-align: middle;
        }

        .action-links {
            display: flex;
            gap: 8px;
            white-space: nowrap;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            background: var(--pink-pale);
            border-radius: 15px;
            color: var(--pink);
        }

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

        .filtro-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filtro-label {
            font-weight: 500;
            color: var(--dark);
        }

        select {
            padding: 10px 14px;
            border: 1.5px solid #eee;
            border-radius: 12px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            color: var(--dark);
            outline: none;
            transition: border 0.2s, background 0.3s, color 0.3s;
            background: #fff;
        }

        select:focus {
            border-color: var(--pink-light);
        }

        /* Alertas propias */
        .alert-banner {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            border-radius: 14px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: var(--green-pale);
            color: var(--green);
            border-left: 4px solid var(--green);
        }

        .alert-error {
            background: var(--red-pale);
            color: var(--red);
            border-left: 4px solid var(--red);
        }

        .alert-icon { font-size: 1.3rem; }

        /* Modal overlay */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(30, 20, 20, 0.65);
            justify-content: center;
            align-items: center;
            z-index: 2000;
            backdrop-filter: blur(3px);
        }

        .modal-box {
            background: #fff;
            padding: 40px 36px;
            border-radius: 24px;
            width: 90%;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: popIn 0.25s ease;
        }

        @keyframes popIn {
            from { opacity: 0; transform: scale(0.9); }
            to   { opacity: 1; transform: scale(1); }
        }

        .modal-icon {
            font-size: 2.5rem;
            margin-bottom: 12px;
        }

        .modal-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 900;
            color: var(--dark);
            margin: 0 0 10px;
        }

        .modal-msg {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 28px;
            line-height: 1.5;
        }

        .modal-warn {
            background: var(--red-pale);
            color: var(--red);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 22px;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

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

        html.tema-oscuro h1,
        html.tema-oscuro h2 {
            color: #f2f2f2;
            border-bottom-color: rgba(232,121,160,0.25);
        }

        html.tema-oscuro .badge-rol {
            background: rgba(232,121,160,0.15);
            border-color: rgba(232,121,160,0.3);
        }

        html.tema-oscuro .filtro-label {
            color: #eee;
        }

        html.tema-oscuro .table-container {
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
        }

        html.tema-oscuro .table {
            background: #1e1e24;
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

        html.tema-oscuro .empty-state {
            background: rgba(232,121,160,0.12);
        }

        html.tema-oscuro select {
            background: #2a2a31;
            border-color: #3a3a42;
            color: #f2f2f2;
        }

        html.tema-oscuro .btn-secondary {
            background: #2a2a31;
            color: #eee;
            border-color: #3a3a42;
        }

        html.tema-oscuro .alert-success {
            background: rgba(90,158,111,0.15);
            color: #8fc9a0;
            border-left-color: #5a9e6f;
        }

        html.tema-oscuro .alert-error {
            background: rgba(224,85,85,0.15);
            color: #f19d9d;
            border-left-color: #e05555;
        }

        html.tema-oscuro .modal-overlay {
            background: rgba(0,0,0,0.8);
        }

        html.tema-oscuro .modal-box {
            background: #1e1e24;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }

        html.tema-oscuro .modal-title {
            color: #f2f2f2;
        }

        html.tema-oscuro .modal-msg {
            color: #bbb;
        }

        html.tema-oscuro .modal-warn {
            background: rgba(224,85,85,0.15);
            color: #f19d9d;
        }

        html.tema-oscuro label {
            color: #ddd;
        }
    </style>
    <?php include __DIR__ . '/../Tema/TemaHead.php'; ?>
    <script>console.log('USER_ID:', USER_ID);</script>
</head>

<body>

<div class="container">

    <div class="header-flex">
        <h1>Gestión de Usuarios</h1>
        <div class="badge-rol">Módulo Administrador</div>
    </div>

    <?php
    // Mensajes de retroalimentación
    if (isset($_GET['msg'])) {
        $msgs = [
            'editado'           => ['✅', 'Usuario editado correctamente.', 'success'],
            'terapeuta_registrado' => ['✅', 'Terapeuta registrado correctamente.', 'success'],
            'usuario_eliminado' => ['🗑️', 'Usuario eliminado permanentemente.', 'success'],
        ];
        if (isset($msgs[$_GET['msg']])) {
            [$icon, $texto, $tipo] = $msgs[$_GET['msg']];
            echo "<div class='alert-banner alert-{$tipo}'><span class='alert-icon'>{$icon}</span> {$texto}</div>";
        }
    }
    if (isset($_GET['error'])) {
        $errs = [
            'no_self'        => ['⛔', 'No podés realizar esta acción sobre tu propia cuenta.', 'error'],
            'no_admin'       => ['⛔', 'No se puede eliminar una cuenta de administrador.', 'error'],
            'error_eliminar' => ['❌', 'Ocurrió un error al eliminar el usuario. Intentalo de nuevo.', 'error'],
            'telefono_invalido' => ['❌', 'El teléfono ingresado no es válido (debe tener 10 dígitos).', 'error'],
            'edicion'        => ['❌', 'No se pudo guardar la edición. Intentalo de nuevo.', 'error'],
        ];
        if (isset($errs[$_GET['error']])) {
            [$icon, $texto, $tipo] = $errs[$_GET['error']];
            echo "<div class='alert-banner alert-{$tipo}'><span class='alert-icon'>{$icon}</span> {$texto}</div>";
        }
    }
    ?>

    <div style="margin-bottom: 20px;">
        <form method="GET" action="admin_usuariosController.php" class="filtro-form">
            <label class="filtro-label">Filtrar por estado:</label>
            <select name="filtro" onchange="this.form.submit()">
                <option value="todos" <?= (isset($_GET['filtro']) && $_GET['filtro'] == 'todos') ? 'selected' : '' ?>>Todos</option>
                <option value="activos" <?= (isset($_GET['filtro']) && $_GET['filtro'] == 'activos') ? 'selected' : '' ?>>Activos</option>
                <option value="inactivos" <?= (isset($_GET['filtro']) && $_GET['filtro'] == 'inactivos') ? 'selected' : '' ?>>Inactivos</option>
            </select>
        </form>
    </div>

    <?php if ($usuarios && $usuarios->num_rows > 0): ?>

        <div class="table-container">

            <table class="table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre Real</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Tipo</th>
                        <th>Rol / Especialidad</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <?php while($row = $usuarios->fetch_assoc()): ?>

                        <tr>

                            <td><?= htmlspecialchars($row['id_Usuario']) ?></td>

                            <td>
                                <?= htmlspecialchars($row['Persona_Nombre'] . " " . $row['Persona_Apellido']) ?>
                            </td>

                            <td><?= htmlspecialchars($row['Usuario_Nombre']) ?></td>

                            <td><?= htmlspecialchars($row['Correo_E']) ?></td>

                            <td><?= htmlspecialchars($row['Tipo_Usuario']) ?></td>

                            <td>
                                <?= htmlspecialchars($row['Rol_Descripcion'] ?? 'Paciente') ?>
                            </td>

                            <td>
                                <?php if ($row['Estado'] === 'Activo'): ?>
                                    <span style="color: #a3b18a; font-weight: bold;">● Activo</span>
                                <?php else: ?>
                                    <span style="color: #d98880; font-weight: bold;">● Inactivo</span>
                                <?php endif; ?>
                            </td>

                            <td>

                                <div class="action-links">

                                    <a href="editar_usuario.php?id=<?= $row['id_Usuario'] ?>"
                                       class="btn btn-primary btn-small">
                                        Editar
                                    </a>

                                    <?php if ($row['Estado'] === 'Activo'): ?>
                                        <a href="#"
                                           class="btn btn-danger btn-small"
                                           onclick="return abrirModal(event,
                                               '../Controllers/UsuarioController.php?action=cambiar_estado&id=<?= $row['id_Usuario'] ?>&estado=Inactivo',
                                               '⛔ ¿Desactivar Usuario?',
                                               'El usuario no podrá acceder al sistema.',
                                               false)">
                                            Desactivar
                                        </a>
                                    <?php else: ?>
                                        <a href="#"
                                           class="btn btn-success btn-small"
                                           onclick="return abrirModal(event,
                                               '../Controllers/UsuarioController.php?action=cambiar_estado&id=<?= $row['id_Usuario'] ?>&estado=Activo',
                                               '✅ ¿Activar Usuario?',
                                               'El usuario volverá a tener acceso al sistema.',
                                               false)">
                                            Activar
                                        </a>
                                    <?php endif; ?>

                                    <a href="#"
                                       class="btn btn-danger btn-small"
                                       style="background: #8b1a1a;"
                                       onclick="return abrirModal(event,
                                           '../Controllers/UsuarioController.php?action=eliminar_usuario&id=<?= $row['id_Usuario'] ?>',
                                           '🗑️ Eliminar Usuario',
                                           '<?= addslashes(htmlspecialchars($row['Usuario_Nombre'])) ?>',
                                           true)">
                                        Eliminar
                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    <?php else: ?>

        <div class="empty-state">
            <p>No se encontraron usuarios registrados en la base de datos.</p>
        </div>

    <?php endif; ?>

    <div class="button-group" style="justify-content: center;">

        <a href="../NuevoUsuario/NuevoUsuarioController.php" class="btn btn-success">
            Registrar Nuevo Usuario
        </a>

        <a href="../index.php" class="btn btn-secondary">Volver al Inicio</a>

    </div>

</div>

<!-- MODAL DE CONFIRMACIÓN -->
<div id="modal-overlay" class="modal-overlay">

    <div class="modal-box">

        <div class="modal-icon" id="modal-icon">⚠️</div>
        <h3 class="modal-title" id="modal-titulo">¿Confirmar acción?</h3>
        <p class="modal-msg" id="modal-mensaje">¿Estás seguro?</p>

        <div id="modal-warn-box" class="modal-warn" style="display:none;">
            ⚠️ Esta acción es <strong>permanente e irreversible</strong>.<br>
            Todos los datos del usuario serán eliminados.
        </div>

        <div class="modal-actions">
            <a id="btn-confirmar-si" href="#" class="btn btn-danger">Confirmar</a>
            <button type="button" onclick="cerrarModal()" class="btn btn-secondary">Cancelar</button>
        </div>

    </div>

</div>

<script>

function abrirModal(event, url, titulo, mensaje, esEliminar) {
    event.preventDefault();

    document.getElementById("btn-confirmar-si").href = url;
    document.getElementById("modal-titulo").innerText = titulo;

    const warnBox = document.getElementById("modal-warn-box");
    const msgEl   = document.getElementById("modal-mensaje");

    if (esEliminar) {
        msgEl.innerHTML = 'Vas a eliminar permanentemente al usuario <strong>' + mensaje + '</strong>.';
        warnBox.style.display = "block";
        document.getElementById("btn-confirmar-si").innerText = "Sí, Eliminar";
        document.getElementById("btn-confirmar-si").style.background = "#8b1a1a";
    } else {
        msgEl.innerText = mensaje;
        warnBox.style.display = "none";
        document.getElementById("btn-confirmar-si").innerText = "Confirmar";
        document.getElementById("btn-confirmar-si").style.background = "";
    }

    document.getElementById("modal-overlay").style.display = "flex";
    return false;
}

function cerrarModal() {
    document.getElementById("modal-overlay").style.display = "none";
}

// Cerrar al hacer click en el fondo
document.getElementById("modal-overlay").addEventListener("click", function(e) {
    if (e.target === this) cerrarModal();
});

</script>

<?php include __DIR__ . '/../Views/ayuda_footer.php'; ?>

</body>
</html>