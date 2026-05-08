<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/');
    session_start();
}

// 1. Verificar que el usuario esté logueado (Cualquier rol)
if (!isset($_SESSION['id_Usuario']) || !isset($_SESSION['id_Rol'])) {
    header("Location: Login.php");
    exit();
}

// Redirect Admin to the administration module
if ($_SESSION['id_Rol'] == 3) {
    header("Location: admin_usuarios.php");
    exit();
}

$conexion = new mysqli("localhost", "root", "", "gestionspabd");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$id_Usuario = $_SESSION['id_Usuario'];
$id_Rol = $_SESSION['id_Rol'];

// 2. Obtener id_Persona de forma dinámica según el tipo de usuario
if ($id_Rol == 0) {
    $sql_persona = "SELECT p.* FROM paciente pac JOIN persona p ON pac.id_Persona = p.id_Persona WHERE pac.id_Usuario = ?";
} else {
    $sql_persona = "SELECT p.* FROM empleado e JOIN persona p ON e.id_Persona = p.id_Persona WHERE e.id_Usuario = ?";
}

$stmt_p = $conexion->prepare($sql_persona);
$stmt_p->bind_param("i", $id_Usuario);
$stmt_p->execute();
$res_p = $stmt_p->get_result();
$datos_p = $res_p->fetch_assoc();
$id_persona = $datos_p['id_Persona'] ?? null;

// Obtener información desde la BD
$persona = array(
    'Persona_Nombre' => $datos_p['Persona_Nombre'] ?? '',
    'Persona_Apellido' => $datos_p['Persona_Apellido'] ?? '',
    'Persona_Telefono' => $datos_p['Persona_Telefono'] ?? '',
    'Persona_Domicilio' => $datos_p['Persona_Domicilio'] ?? '',
    'Persona_DNI' => $datos_p['Persona_DNI'] ?? '',
    'Fecha_Nac' => $datos_p['Fecha_Nac'] ?? '',
    'Persona_Descripcion' => $datos_p['Persona_Descripcion'] ?? ''
);

$usuario_data = array(
    'Correo_E' => $_SESSION['correo_e'] ?? ''
);

$mensaje = "";

// Manejar cancelación de turno
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'cancelar_turno') {
    $id_turno_cancelar = intval($_POST['id_turno'] ?? 0);
    if ($id_turno_cancelar > 0 && isset($_SESSION['id_Paciente'])) {
        $id_Paciente_cancelar = $_SESSION['id_Paciente'];
        
        $sql_delete_turno = "DELETE FROM turno WHERE id_Turno = ? AND id_Paciente = ?";
        $stmt_del = $conexion->prepare($sql_delete_turno);
        $stmt_del->bind_param("ii", $id_turno_cancelar, $id_Paciente_cancelar);
        
        if ($stmt_del->execute()) {
            $mensaje = "<div class='alert alert-success'>Turno cancelado con éxito!</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>Error al cancelar el turno.</div>";
        }
    }
}

// 3. Obtener turnos SOLO si es Paciente
$turnos = [];
if ($id_Rol == 0 && isset($_SESSION['id_Paciente'])) {
    $id_Paciente = $_SESSION['id_Paciente'];
    $sql_turnos = "SELECT t.id_Turno, t.Fecha_Turno, t.Hora_Turno, t.Estado_Turno, 
                          s.Nombre_servicio, s.Costo,
                          per_emp.Persona_Nombre as Terapeuta_Nombre, per_emp.Persona_Apellido as Terapeuta_Apellido
                   FROM turno t
                   JOIN servicio s ON t.id_Servicio = s.id_Servicio
                   JOIN empleado emp ON t.id_Empleado = emp.id_Empleado
                   JOIN persona per_emp ON emp.id_Persona = per_emp.id_Persona
                   WHERE t.id_Paciente = ? 
                   ORDER BY t.Fecha_Turno DESC, t.Hora_Turno DESC";

    $stmt_t = $conexion->prepare($sql_turnos);
    $stmt_t->bind_param("i", $id_Paciente);
    $stmt_t->execute();
    $result_turnos = $stmt_t->get_result();
    $turnos = $result_turnos->fetch_all(MYSQLI_ASSOC);
}

// Manejar edición de información personal (Funciona para todos)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'editar_perfil') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $domicilio = trim($_POST['domicilio'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $fecha_nac = trim($_POST['fecha_nac'] ?? '');
    if ($fecha_nac === '') { $fecha_nac = null; }
    $descripcion = trim($_POST['descripcion'] ?? '');

    // Actualizar tabla persona
    $sql_update_persona = "UPDATE persona SET Persona_Nombre = ?, Persona_Apellido = ?, Persona_Telefono = ?, Persona_Domicilio = ?, Persona_DNI = ?, Fecha_Nac = ?, Persona_Descripcion = ?
                           WHERE id_Persona = ?";
    $stmt = $conexion->prepare($sql_update_persona);
    $stmt->bind_param("sssssssi", $nombre, $apellido, $telefono, $domicilio, $dni, $fecha_nac, $descripcion, $id_persona);
    
    if ($stmt->execute()) {
        // Actualizar correo en tabla usuario
        $sql_update_usuario = "UPDATE usuario SET Correo_E = ? WHERE id_Usuario = ?";
        $stmt_u = $conexion->prepare($sql_update_usuario);
        $stmt_u->bind_param("si", $correo, $id_Usuario);
        $stmt_u->execute();
        
        $mensaje = "<div class='alert alert-success'>Información actualizada correctamente.</div>";
        
        // Actualizar datos en SESIÓN para que se vean los cambios sin reloguear
        $_SESSION['persona_nombre'] = $nombre;
        $_SESSION['persona_apellido'] = $apellido;
        $_SESSION['persona_telefono'] = $telefono;
        $_SESSION['persona_domicilio'] = $domicilio;
        $_SESSION['correo_e'] = $correo;
        
        // Actualizar variables locales para la vista actual
        $persona['Persona_Nombre'] = $nombre;
        $persona['Persona_Apellido'] = $apellido;
        $persona['Persona_Telefono'] = $telefono;
        $persona['Persona_Domicilio'] = $domicilio;
        $persona['Persona_DNI'] = $dni;
        $persona['Fecha_Nac'] = $fecha_nac;
        $persona['Persona_Descripcion'] = $descripcion;
        $usuario_data['Correo_E'] = $correo;
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al actualizar la información.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Perfil - SoftAgenda</title>
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
            max-width: 900px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        h1, h2 { color: #5c4a3d; font-weight: 300; letter-spacing: 1px; border-bottom: 2px solid #e9e3dc; padding-bottom: 10px; margin-bottom: 25px; }
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
        .profile-info {
            background: #fdfbf7;
            padding: 25px;
            border-radius: 15px;
            border-left: 6px solid #b5a397;
            margin-bottom: 25px;
        }
        .info-row { display: flex; flex-wrap: wrap; margin-bottom: 15px; }
        .info-item { flex: 1; min-width: 200px; margin-bottom: 10px; }
        .info-label { font-weight: bold; color: #8da399; display: block; margin-bottom: 5px; }
        .info-value { color: #555; font-size: 1.1em; }
        
        .view-mode.hidden { display: none; }
        .edit-mode { display: none; background: #fdfbf7; padding: 25px; border-radius: 15px; border-left: 6px solid #8da399; margin-bottom: 25px; }
        .edit-mode.active { display: block; }
        
        .form-row { display: flex; gap: 20px; }
        .form-group { flex: 1; margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 500; color: #5c4a3d; margin-bottom: 8px; }
        .form-group input { 
            width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid #dcd3cb; box-sizing: border-box; background: #fff; transition: 0.3s;
        }
        .form-group input:focus { outline: none; border-color: #8da399; }
        
        .btn {
            display: inline-block; padding: 10px 20px; border-radius: 30px; text-decoration: none; cursor: pointer; border: none; font-size: 14px; font-weight: 500; transition: all 0.3s ease;
        }
        .btn-primary { background: #8da399; color: white; box-shadow: 0 4px 6px rgba(141, 163, 153, 0.3); }
        .btn-success { background: #a3b18a; color: white; box-shadow: 0 4px 6px rgba(163, 177, 138, 0.3); }
        .btn-danger { background: #d98880; color: white; box-shadow: 0 4px 6px rgba(217, 136, 128, 0.3); }
        .btn-secondary { background: #dcd3cb; color: #5c4a3d; }
        .btn:hover { opacity: 0.9; transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0,0,0,0.15); }
        .button-group { margin-top: 20px; display: flex; gap: 10px; }
        
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .table th, .table td { padding: 15px; text-align: left; border-bottom: 1px solid #f0e9e1; }
        .table th { background: #fdfbf7; color: #5c4a3d; font-weight: 600; }
        .table tr:hover { background: #faf8f5; }
        
        .alert { padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        .alert-success { background: #e9edc9; color: #4e5a37; border-left: 4px solid #a3b18a; }
        .alert-danger { background: #f9ebea; color: #78281f; border-left: 4px solid #d98880; }
        
        .empty-state { text-align: center; padding: 40px; background: #fdfbf7; border-radius: 15px; color: #888; }
        .back-button { margin-top: 30px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mi Perfil</h1>
        
        <!-- Mostrar Rol actual -->
        <div class="badge-rol">
            Rol: <?php 
                if($id_Rol == 0) echo "Paciente";
                elseif($id_Rol == 3) echo "Administrador";
                elseif($id_Rol == 2) echo "Terapeuta";
                else echo "Personal";
            ?>
        </div>

        <?php echo $mensaje; ?>
        
        <h2>Información Personal</h2>
        
        <div class="view-mode" id="view-mode">
            <div class="profile-info">
                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">Nombre:</span>
                        <span class="info-value"><?php echo htmlspecialchars($persona['Persona_Nombre']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Apellido:</span>
                        <span class="info-value"><?php echo htmlspecialchars($persona['Persona_Apellido']); ?></span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">Teléfono:</span>
                        <span class="info-value"><?php echo htmlspecialchars($persona['Persona_Telefono']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Correo Electrónico:</span>
                        <span class="info-value"><?php echo htmlspecialchars($usuario_data['Correo_E']); ?></span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">DNI:</span>
                        <span class="info-value"><?php echo htmlspecialchars($persona['Persona_DNI']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Fecha de Nacimiento:</span>
                        <span class="info-value"><?php echo !empty($persona['Fecha_Nac']) ? date('d/m/Y', strtotime($persona['Fecha_Nac'])) : 'No especificada'; ?></span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-item" style="flex: 100%;">
                        <span class="info-label">Descripción personal (dolencias, deportes, etc):</span>
                        <span class="info-value" style="white-space: pre-wrap;"><?php echo !empty($persona['Persona_Descripcion']) ? htmlspecialchars($persona['Persona_Descripcion']) : 'Sin descripción'; ?></span>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" onclick="toggleEditMode()">Editar Información</button>
        </div>

        <div class="edit-mode" id="edit-mode">
            <form method="POST" action="perfil.php">
                <input type="hidden" name="action" value="editar_perfil">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" name="nombre" value="<?php echo htmlspecialchars($persona['Persona_Nombre']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Apellido:</label>
                        <input type="text" name="apellido" value="<?php echo htmlspecialchars($persona['Persona_Apellido']); ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>DNI:</label>
                        <input type="text" name="dni" value="<?php echo htmlspecialchars($persona['Persona_DNI']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Fecha de Nacimiento:</label>
                        <input type="date" name="fecha_nac" value="<?php echo htmlspecialchars($persona['Fecha_Nac']); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Teléfono:</label>
                        <input type="number" name="telefono" value="<?php echo htmlspecialchars($persona['Persona_Telefono']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Correo Electrónico:</label>
                        <input type="email" name="correo" value="<?php echo htmlspecialchars($usuario_data['Correo_E']); ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Domicilio:</label>
                    <input type="text" name="domicilio" value="<?php echo htmlspecialchars($persona['Persona_Domicilio']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Descripción personal (dolencias, deportes, etc):</label>
                    <textarea name="descripcion" style="width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid #dcd3cb; box-sizing: border-box; background: #fff; transition: 0.3s; font-family: inherit; resize: vertical; min-height: 80px;"><?php echo htmlspecialchars($persona['Persona_Descripcion']); ?></textarea>
                </div>
                <div class="button-group">
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" onclick="toggleEditMode()">Cancelar</button>
                </div>
            </form>
        </div>

<!-- SECCIÓN DINÁMICA SEGÚN ROL -->
<?php if ($id_Rol == 0): ?>
    <h2>Mis Turnos</h2>
    <?php if (count($turnos) > 0): ?>
        <table class="table">      
            <thead>
                <tr>
                    <th>Fecha</th><th>Hora</th><th>Servicio</th><th>Estado</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($turnos as $turno): ?>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($turno['Fecha_Turno'])); ?></td>
                    <td><?php echo date('H:i', strtotime($turno['Hora_Turno'])); ?></td>
                    <td><?php echo htmlspecialchars($turno['Nombre_servicio']); ?></td>
                    <td><?php echo htmlspecialchars($turno['Estado_Turno']); ?></td>
                    <td>
                        <form method="POST" action="perfil.php" style="display:inline;" onsubmit="return confirmarCancelacion(event, this);">
                            <input type="hidden" name="action" value="cancelar_turno">
                            <input type="hidden" name="id_turno" value="<?php echo $turno['id_Turno']; ?>">
                            <button type="submit" class="btn btn-danger">Cancelar</button>
                        </form>
                        <a href="editar_turno.php?id=<?php echo $turno['id_Turno']; ?>" class="btn btn-primary" style="margin-left: 5px;">Modificar turno</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <a href="turnos.php" class="btn btn-success">+ Agendar Otro Turno</a>
        
    <?php else: ?>
        <div class="empty-state">
            <p>No tienes turnos programados.</p>
            <a href="turnos.php" class="btn btn-success">Agendar Turno</a>
        </div>
    <?php endif; ?>

        <?php else: ?>
            <!-- Vista para Empleados/Admin -->
            <div class="profile-info" style="margin-top: 30px; border-left-color: #28a745;">
                <h3>Panel de Trabajo</h3>
                <p>Como miembro del personal, puedes gestionar la agenda desde el panel principal.</p>
                <a href="index.php" class="btn btn-success" style="margin-top:10px;">Ir al Panel de Gestión</a>
            </div>
        <?php endif; ?>

        <div class="back-button">
            <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
        </div>
    </div>

    <!-- MODAL DE CONFIRMACIÓN -->
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
        function toggleEditMode() {
            document.getElementById('view-mode').classList.toggle('hidden');
            document.getElementById('edit-mode').classList.toggle('active');
        }

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
    </script>
</body>
</html>