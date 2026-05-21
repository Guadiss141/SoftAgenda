<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_Usuario'])) {
    header("Location: Login.php");
    exit();
}

$id_Rol          = $_SESSION['id_Rol'] ?? null;
$turnos_resultado = $turnos_resultado ?? null;
$agendaHoy       = $agendaHoy        ?? null;
$servicios_db    = $servicios_db     ?? null;
$terapeutas_db   = $terapeutas_db    ?? null;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Turnos - SoftAgenda</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            text-align: center; 
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
        .content-wrapper { padding: 40px; }
        h1, h2 { color: #5c4a3d; font-weight: 300; letter-spacing: 1px; }
        #turnos-container, #agenda-container {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .turno {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            margin: 15px auto;
            width: 90%;
            max-width: 550px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            text-align: left;
            border-left: 6px solid #b5a397;
            transition: transform 0.3s ease;
        }
        .turno:hover { transform: translateY(-3px); }
        .turno p { margin: 8px 0; }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #8da399;
            color: white;
            border-radius: 30px;
            text-decoration: none;
            cursor: pointer;
            margin: 10px 5px;
            border: none;
        }
        .btn-danger  { background: #d98880; }
        .btn-primary { background: #7fb3d5; }
        #modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(60, 50, 40, 0.6);
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }
        #modal-content {
            background: #fdfbf7;
            padding: 40px;
            border-radius: 20px;
            width: 90%;
            max-width: 450px;
            text-align: left;
            position: relative;
        }
        .close { position: absolute; right: 25px; top: 20px; cursor: pointer; font-size: 28px; }
        label { display: block; margin-top: 20px; }
        select, input {
            width: 100%; padding: 12px 15px; margin-top: 8px;
            border-radius: 10px; border: 1px solid #dcd3cb; box-sizing: border-box;
        }
    </style>
</head>
<body>

<?php include("navbar.php"); ?>

<div class="content-wrapper">

    <!-- MENSAJES -->
    <?php if (isset($_GET['status'])): ?>
        <div id="notificacion" style="
            padding:15px; border-radius:10px; margin:20px auto; width:80%; max-width:500px;
            <?= $_GET['status'] === 'success'
                ? 'background:#d4edda;color:#155724;'
                : 'background:#f8d7da;color:#721c24;' ?>">
            <?= $_GET['status'] === 'success'
                ? '¡Operación realizada con éxito!'
                : 'Hubo un error al procesar la solicitud.' ?>
        </div>
    <?php endif; ?>


    <!-- TURNOS (Paciente / Admin) -->
    <?php if ($id_Rol != 2): ?>

        <h1>Turnos Programados</h1>

        <div id="turnos-container">
            <?php if ($turnos_resultado && $turnos_resultado->num_rows > 0): ?>

                <?php while ($t = $turnos_resultado->fetch_assoc()): ?>
                    <div class="turno">
                        <p><b>Servicio:</b> <?= htmlspecialchars($t['Nombre_servicio']) ?></p>
                        <p><b>Fecha:</b>    <?= date("d/m/Y", strtotime($t['Fecha_Turno'])) ?></p>
                        <p><b>Horario:</b>  <?= substr($t['Hora_Turno'], 0, 5) ?> hs</p>
                        <p><b>Estado:</b>   <?= $t['Estado_Turno'] ?></p>

                        <?php if ($id_Rol == 3 || ($id_Rol == 0 && $t['Estado_Turno'] == 'Pendiente')): ?>
                            <form action="../Controllers/TurnoController.php?action=eliminar"
                                  method="POST" style="display:inline;">
                                <input type="hidden" name="id_turno" value="<?= $t['id_Turno'] ?>">
                                <button type="submit" class="btn btn-danger">Cancelar Turno</button>
                            </form>
                            <a href="editar_turno.php?id=<?= $t['id_Turno'] ?>" class="btn btn-primary">
                                Modificar Turno
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>

            <?php else: ?>
                <p>No tienes turnos registrados actualmente.</p>
            <?php endif; ?>
        </div>

        <?php if ($id_Rol == 0 || $id_Rol == 3): ?>
            <button type="button" id="btn-open-modal" class="btn">
                + Solicitar nuevo turno
            </button>
        <?php endif; ?>

    <?php endif; ?>


    <!-- AGENDA TERAPEUTA -->
    <?php if ($id_Rol == 2): ?>

        <h1>Agenda de Hoy</h1>

        <div id="agenda-container">
            <?php if ($agendaHoy && $agendaHoy->num_rows > 0): ?>

                <?php while ($cita = $agendaHoy->fetch_assoc()): ?>
                    <div class="turno">
                        <p><b>Hora:</b>     <?= substr($cita['Hora_Turno'], 0, 5) ?> hs</p>
                        <p><b>Cliente:</b>  <?= htmlspecialchars($cita['Cliente_Nombre'] . " " . $cita['Cliente_Apellido']) ?></p>
                        <p><b>Servicio:</b> <?= htmlspecialchars($cita['Nombre_servicio']) ?></p>
                        <p><b>Teléfono:</b> <?= htmlspecialchars($cita['Cliente_Telefono']) ?></p>
                        <form action="../Controllers/TurnoController.php?action=completar" method="POST">
                            <input type="hidden" name="id_turno" value="<?= $cita['id_Turno'] ?>">
                            <button type="submit" class="btn">Finalizar Servicio</button>
                        </form>
                    </div>
                <?php endwhile; ?>

            <?php else: ?>
                <p>No tienes citas para hoy.</p>
            <?php endif; ?>
        </div>

    <?php endif; ?>

    <div style="margin-top:30px;">
        <a href="../index.php" class="btn">Volver al inicio</a>
    </div>

</div><!-- /.content-wrapper -->


<!-- MODAL -->
<div id="modal">
    <div id="modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <h2>Agendar Turno</h2>

        <form action="../Controllers/TurnoController.php?action=crear" method="POST">

            <label>Servicio:</label>
            <select name="id_Servicio" required>
                <option value="">Seleccione un servicio...</option>
                <?php if ($servicios_db): ?>
                    <?php while ($s = $servicios_db->fetch_assoc()): ?>
                        <option value="<?= $s['id_Servicio'] ?>">
                            <?= htmlspecialchars($s['Nombre_servicio']) ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>

            <label>Terapeuta:</label>
            <select name="id_Empleado" required>
                <option value="">Seleccione un profesional...</option>
                <?php if ($terapeutas_db): ?>
                    <?php $terapeutas_db->data_seek(0); ?>
                    <?php while ($emp = $terapeutas_db->fetch_assoc()): ?>
                        <option value="<?= $emp['id_Empleado'] ?>">
                            <?= htmlspecialchars($emp['Persona_Nombre']) ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>

            <label>Fecha:</label>
            <input type="date" name="Fecha_Turno" min="<?= date('Y-m-d') ?>" required>

            <label>Horario:</label>
            <input type="time" name="Hora_Turno" required>

            <button type="submit" class="btn" style="width:100%;margin-top:20px;">
                Confirmar y Guardar
            </button>

        </form>
    </div>
</div>

<!-- SCRIPT al final del body, antes de cerrar -->
<script>
    function abrirModal() {
        const modal = document.getElementById("modal");
        if (modal) modal.style.display = "flex";
    }

    function cerrarModal() {
        const modal = document.getElementById("modal");
        if (modal) modal.style.display = "none";
    }

    document.addEventListener("DOMContentLoaded", function () {
        const btn = document.getElementById("btn-open-modal");
        if (btn) btn.addEventListener("click", abrirModal);

        const modal = document.getElementById("modal");
        if (modal) {
            modal.addEventListener("click", function (e) {
                if (e.target === modal) cerrarModal();
            });
        }
    });
</script>

</body>
</html>