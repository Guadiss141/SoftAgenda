<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. SEGURIDAD: Verificar que el usuario esté logueado
if (!isset($_SESSION['id_Usuario'])) {
    header("Location: Login.php");
    exit();
}

// 2. DEFINIR VARIABLES LOCALES
$id_Rol = $_SESSION['id_Rol'] ?? null; 
$id_Paciente = $_SESSION['id_Paciente'] ?? null;
$id_Empleado = $_SESSION['id_Empleado'] ?? null;

require_once '../Models/Turno.php'; 

// Conexión local (root sin contraseña)
$conexion = new mysqli("localhost", "root", "", "gestionspabd");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$modeloTurno = new Turno($conexion);

// 3. CARGAR DATOS SEGÚN EL ROL
$turnos_resultado = null;
$agendaHoy = null;

if ($id_Rol == 0 && $id_Paciente) { 
    
    $turnos_resultado = $modeloTurno->obtenerTurnosPorPaciente($id_Paciente);
} elseif ($id_Rol == 2 && $id_Empleado) {
   
    $agendaHoy = $modeloTurno->obtenerAgendaDelDia($id_Empleado);
} elseif ($id_Rol == 3) {
    
    $turnos_resultado = $conexion->query("SELECT t.*, s.Nombre_servicio FROM turno t JOIN servicio s ON t.id_Servicio = s.id_Servicio ORDER BY t.Fecha_Turno DESC");
}

// Cargar datos para los Selects del Modal 
$servicios_db = $conexion->query("SELECT id_Servicio, Nombre_servicio FROM servicio ORDER BY Nombre_servicio");
$terapeutas_db = $conexion->query("SELECT e.id_Empleado, p.Persona_Nombre FROM empleado e JOIN persona p ON e.id_Persona = p.id_Persona WHERE e.id_Rol = 2");
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
            background-color: #fcf9f2; /* Fallback */
            color: #4a4a4a; 
        }
        /* Add a warm overlay to the background */
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(253, 251, 247, 0.88); /* Warm white overlay */
            z-index: -1;
        }
        .content-wrapper { padding: 40px; }
        h1, h2 { color: #5c4a3d; font-weight: 300; letter-spacing: 1px; }
        #turnos-container, #agenda-container { margin-top: 20px; display: flex; flex-direction: column; align-items: center; }
        .turno {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            margin: 15px auto;
            width: 90%;
            max-width: 550px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            text-align: left;
            border-left: 6px solid #b5a397; /* Warm earthy border */
            transition: transform 0.3s ease;
        }
        .turno:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        }
        .turno p { margin: 8px 0; color: #555; }
        .turno b { color: #5c4a3d; }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #8da399; /* Calming sage green */
            color: white;
            border-radius: 30px; /* Pill shape for softer look */
            text-decoration: none;
            cursor: pointer;
            margin: 10px 5px;
            border: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(141, 163, 153, 0.3);
        }
        .btn-danger { background: #d98880; box-shadow: 0 4px 6px rgba(217, 136, 128, 0.3); } /* Softer red */
        .btn-primary { background: #7fb3d5; box-shadow: 0 4px 6px rgba(127, 179, 213, 0.3); } /* Softer blue */
        .btn:hover { opacity: 0.9; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); }
        
        /* ALERTAS */
        .alerta {
            padding: 15px;
            border-radius: 10px;
            margin: 20px auto;
            width: 80%;
            max-width: 500px;
            animation: fadeIn 0.5s;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

        /* MODAL */
        #modal {
            display: none; 
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(60, 50, 40, 0.6); /* Warmer dark overlay */
            justify-content: center;
            align-items: center;
            z-index: 2000;
            backdrop-filter: blur(3px);
        }
        #modal-content {
            background: #fdfbf7;
            padding: 40px;
            border-radius: 20px;
            width: 90%;
            max-width: 450px;
            text-align: left;
            position: relative;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .close { position: absolute; right: 25px; top: 20px; cursor: pointer; font-size: 28px; color: #a0938a; transition: 0.3s; }
        .close:hover { color: #5c4a3d; }
        label { display: block; margin-top: 20px; font-weight: 500; color: #5c4a3d; }
        select, input { 
            width: 100%; 
            padding: 12px 15px; 
            margin-top: 8px; 
            border-radius: 10px; 
            border: 1px solid #dcd3cb; 
            box-sizing: border-box; 
            background: #fff;
            color: #555;
            transition: border-color 0.3s;
        }
        select:focus, input:focus {
            outline: none;
            border-color: #8da399;
        }
    </style>
</head>
<body>

<?php include("navbar.php"); ?>

<div class="content-wrapper">
    <!-- BLOQUE DE MENSAJES -->
    <?php if (isset($_GET['status'])): ?>
        <div id="notificacion" class="alerta" style="<?= $_GET['status'] === 'success' ? 'background: #d4edda; color: #155724; border: 1px solid #c3e6cb;' : 'background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;' ?>">
            <?= $_GET['status'] === 'success' ? '¡Operación realizada con éxito!' : 'Hubo un error al procesar la solicitud.' ?>
        </div>
        <script>setTimeout(() => { document.getElementById('notificacion').style.display = 'none'; }, 4000);</script>
    <?php endif; ?>

    <?php if ($id_Rol != 2): // Si no es terapeuta, mostrar turnos programados ?>
        <h1>Turnos Programados</h1>
        <div id="turnos-container">
            <?php if ($turnos_resultado && $turnos_resultado->num_rows > 0): ?>
                <?php while ($t = $turnos_resultado->fetch_assoc()): ?>
                    <div class="turno">
                        <p><b>Servicio:</b> <?= htmlspecialchars($t['Nombre_servicio']) ?></p>
                        <p><b>Fecha:</b> <?= date("d/m/Y", strtotime($t['Fecha_Turno'])) ?></p>
                        <p><b>Horario:</b> <?= substr($t['Hora_Turno'], 0, 5) ?> hs</p>
                        <p><b>Estado:</b> <span style="color: <?= $t['Estado_Turno'] == 'Pendiente' ? '#f39c12' : '#27ae60' ?>"><?= $t['Estado_Turno'] ?></span></p>
                        
                        <?php if ($id_Rol == 3 || ($id_Rol == 0 && $t['Estado_Turno'] == 'Pendiente')): ?>
                            <form action="../Controllers/TurnoController.php?action=eliminar" method="POST" style="display:inline;" onsubmit="return confirmarCancelacion(event, this);">
                                <input type="hidden" name="id_turno" value="<?= $t['id_Turno'] ?>">
                                <button type="submit" class="btn btn-danger">Cancelar Turno</button>
                            </form>
                            <a href="editar_turno.php?id=<?= $t['id_Turno'] ?>" class="btn btn-primary">Modificar Turno</a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No tienes turnos registrados actualmente.</p>
            <?php endif; ?>
        </div>
        
        <?php if ($id_Rol == 0 || $id_Rol == 3): ?>
            <button class="btn" onclick="abrirModal()">+ Solicitar nuevo turno</button>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($id_Rol == 2): // Vista específica de Terapeuta ?>
        <h1>Agenda de Hoy</h1>
        <div id="agenda-container">
            <?php if ($agendaHoy && $agendaHoy->num_rows > 0): ?>
                <?php while ($cita = $agendaHoy->fetch_assoc()): ?>
                    <div class="turno" style="border-left: 5px solid #2ecc71;">
                        <p><b>Hora:</b> <?= substr($cita['Hora_Turno'], 0, 5) ?> hs</p>
                        <p><b>Cliente:</b> <?= htmlspecialchars($cita['Cliente_Nombre'] . " " . $cita['Cliente_Apellido']) ?></p>
                        <p><b>Servicio:</b> <?= htmlspecialchars($cita['Nombre_servicio']) ?></p>
                        <p><b>Teléfono:</b> <?= htmlspecialchars($cita['Cliente_Telefono']) ?></p>
                        <p><b>Estado:</b> <?= $cita['Estado_Turno'] ?></p>
                        
                        <form action="../Controllers/TurnoController.php?action=completar" method="POST">
                            <input type="hidden" name="id_turno" value="<?= $cita['id_Turno'] ?>">
                            <button type="submit" class="btn">Finalizar Servicio</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No tienes citas para hoy. ¡Día tranquilo!</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div style="margin-top: 30px;">
        <a href="index.php" class="btn" style="background: #666;">Volver al inicio</a>
    </div>
</div>

<!-- MODAL PARA NUEVO TURNO -->
<div id="modal">
    <div id="modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <h2 style="margin-top: 0;">Agendar Turno</h2>
        <p style="font-size: 13px; color: #666;">Completa los datos para reservar tu sesión.</p>

        <form action="../Controllers/TurnoController.php?action=crear" method="POST">
            <label>Servicio:</label>
            <select name="id_Servicio" required>
                <option value="">Seleccione un servicio...</option>
                <?php while($s = $servicios_db->fetch_assoc()): ?>
                    <option value="<?= $s['id_Servicio'] ?>"><?= htmlspecialchars($s['Nombre_servicio']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Terapeuta:</label>
            <select name="id_Empleado" required>
                <option value="">Seleccione un profesional...</option>
                <?php 
                $terapeutas_db->data_seek(0); 
                while($emp = $terapeutas_db->fetch_assoc()): ?>
                    <option value="<?= $emp['id_Empleado'] ?>"><?= htmlspecialchars($emp['Persona_Nombre']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Fecha:</label>
            <input type="date" name="Fecha_Turno" min="<?= date('Y-m-d') ?>" required>

            <label>Horario:</label>
            <input type="time" name="Hora_Turno" required>

            <button type="submit" class="btn" style="width: 100%; margin-top: 20px;">Confirmar y Guardar</button>
        </form>
    </div>
</div>

<!-- MODAL DE CONFIRMACIÓN DE CANCELACIÓN -->
<div id="modal-confirmacion" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(60, 50, 40, 0.6); justify-content: center; align-items: center; z-index: 2000; backdrop-filter: blur(3px);">
    <div style="background: #fdfbf7; padding: 40px; border-radius: 20px; width: 90%; max-width: 400px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
        <h3 style="color: #5c4a3d; margin-top: 0; font-weight: 400;">¿Cancelar Turno?</h3>
        <p style="color: #666; margin-bottom: 25px;">¿Estás seguro de que deseas cancelar este turno? Esta acción no se puede deshacer.</p>
        <div style="display: flex; gap: 10px; justify-content: center;">
            <button id="btn-confirmar-si" class="btn btn-danger" style="margin: 0;">Sí, Cancelar</button>
            <button type="button" onclick="cerrarConfirmacion()" class="btn btn-secondary" style="margin: 0; background: #dcd3cb; color: #5c4a3d;">No, Volver</button>
        </div>
    </div>
</div>

<script>
    function abrirModal() { document.getElementById("modal").style.display = "flex"; }
    function cerrarModal() { document.getElementById("modal").style.display = "none"; }
    
    let formToSubmit = null;
    function confirmarCancelacion(event, form) {
        event.preventDefault();
        formToSubmit = form;
        document.getElementById("modal-confirmacion").style.display = "flex";
        return false;
    }

    function cerrarConfirmacion() {
        document.getElementById("modal-confirmacion").style.display = "none";
        formToSubmit = null;
    }

    document.getElementById("btn-confirmar-si").addEventListener("click", function() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
    });

    // Cerrar modales si se hace clic fuera del contenido
    window.onclick = function(event) {
        let modal = document.getElementById("modal");
        let modalConfirm = document.getElementById("modal-confirmacion");
        if (event.target == modal) { cerrarModal(); }
        if (event.target == modalConfirm) { cerrarConfirmacion(); }
    }
</script>

</body>
</html>