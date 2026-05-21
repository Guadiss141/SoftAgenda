<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administración de Usuarios - SoftAgenda</title>

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
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%;
            background: rgba(253, 251, 247, 0.88); 
            z-index: -1;
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
        }

        .btn-primary {
            background: #8da399;
            color: white;
        }

        .btn-success {
            background: #a3b18a;
            color: white;
        }

        .btn-danger {
            background: #d98880;
            color: white;
        }

        .btn-secondary {
            background: #dcd3cb;
            color: #5c4a3d;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .button-group {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            margin-bottom: 30px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .table th,
        .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f0e9e1;
        }

        .table th {
            background: #fdfbf7;
            color: #5c4a3d;
            font-weight: 600;
            white-space: nowrap;
        }

        .table tr:hover {
            background: #faf8f5;
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
            background: #fdfbf7;
            border-radius: 15px;
            color: #888;
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
    </style>
</head>

<body>

<div class="container">

    <div class="header-flex">
        <h1>Gestión de Usuarios</h1>
        <div class="badge-rol">Módulo Administrador</div>
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

                                <div class="action-links">

                                    <a href="editar_usuario.php?id=<?= $row['id_Usuario'] ?>"
                                       class="btn btn-primary btn-small">
                                        Editar
                                    </a>

                                    <a href="#"
                                       class="btn btn-danger btn-small"
                                       onclick="return confirmarEliminacion(
                                       event,
                                       '../Controllers/UsuarioController.php?action=eliminar&id=<?= $row['id_Usuario'] ?>'
                                       )">
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

        <a href="registro_terapeuta.php" class="btn btn-success">
            Registrar Nuevo Terapeuta
        </a>

        <a href="index.php" class="btn btn-secondary">
            Volver al Inicio
        </a>

    </div>

</div>

<!-- MODAL -->

<div id="modal-confirmacion"
     style="display: none;
     position: fixed;
     top: 0;
     left: 0;
     width: 100%;
     height: 100%;
     background: rgba(60, 50, 40, 0.6);
     justify-content: center;
     align-items: center;
     z-index: 2000;">

    <div style="background: #fdfbf7;
                padding: 40px;
                border-radius: 20px;
                width: 90%;
                max-width: 400px;
                text-align: center;">

        <h3 style="color: #5c4a3d;">
            ¿Eliminar Usuario?
        </h3>

        <p style="color: #666; margin-bottom: 25px;">
            ¿Estás seguro de que deseas eliminar permanentemente a este usuario?
        </p>

        <div style="display: flex; gap: 10px; justify-content: center;">

            <a id="btn-confirmar-si"
               href="#"
               class="btn btn-danger">
                Sí, Eliminar
            </a>

            <button type="button"
                    onclick="cerrarConfirmacion()"
                    class="btn btn-secondary">

                No, Volver

            </button>

        </div>

    </div>

</div>

<script>

function confirmarEliminacion(event, url) {

    event.preventDefault();

    document.getElementById("btn-confirmar-si").href = url;

    document.getElementById("modal-confirmacion").style.display = "flex";

    return false;
}

function cerrarConfirmacion() {

    document.getElementById("modal-confirmacion").style.display = "none";
}

</script>

</body>
</html>