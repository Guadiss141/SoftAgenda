<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Nuevo Usuario - SoftAgenda</title>
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
            padding: 0; margin: 0; 
            background: var(--cream);
            color: var(--dark);
            min-height: 100vh;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 40px;
            border-radius: 32px;
            box-shadow: 0 8px 40px rgba(232,121,160,0.12);
        }

        h1 { 
            font-family: 'Playfair Display', serif;
            color: var(--dark); 
            font-weight: 900; 
            letter-spacing: -0.5px; 
            border-bottom: 2px solid var(--pink-pale); 
            padding-bottom: 10px; 
            margin-bottom: 25px; 
        }

        .form-row { display: flex; gap: 15px; }
        .form-group { flex: 1; margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 500; color: var(--dark); margin-bottom: 8px; }
        
        .form-group input, .form-group select { 
            width: 100%; 
            padding: 12px 15px; 
            border-radius: 12px; 
            border: 1.5px solid #eee;
            box-sizing: border-box; 
            background: #fff; 
            transition: 0.3s;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            color: var(--dark);
            outline: none;
        }
        
        .form-group input:focus, .form-group select:focus { 
            border-color: var(--pink-light); 
        }

        .form-group input.input-error {
            border-color: var(--red) !important;
        }

        /* Contador de caracteres */
        .char-counter {
            font-size: 0.75rem;
            margin-top: 5px;
            color: var(--gray);
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color 0.3s;
        }

        .char-counter .bar-track {
            flex: 1;
            height: 4px;
            background: #eee;
            border-radius: 4px;
            overflow: hidden;
        }

        .char-counter .bar-fill {
            height: 100%;
            border-radius: 4px;
            background: var(--red);
            transition: width 0.2s, background 0.3s;
        }

        .char-counter.ok { color: var(--green); }
        .char-counter.ok .bar-fill { background: var(--green); }

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
            margin-top: 25px; 
            display: flex; 
            gap: 10px; 
            justify-content: space-between; 
        }

        .alert { padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        .alert-danger { background: var(--red-pale); color: var(--red); border-left: 4px solid var(--red); }

        /* Modal de error */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(30,20,20,0.6);
            justify-content: center;
            align-items: center;
            z-index: 2000;
            backdrop-filter: blur(3px);
        }

        .modal-box {
            background: #fff;
            padding: 38px 34px;
            border-radius: 24px;
            width: 90%;
            max-width: 380px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: popIn 0.25s ease;
        }

        @keyframes popIn {
            from { opacity: 0; transform: scale(0.9); }
            to   { opacity: 1; transform: scale(1); }
        }

        .modal-icon { font-size: 2.2rem; margin-bottom: 10px; }

        .modal-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 900;
            color: var(--dark);
            margin: 0 0 10px;
        }

        .modal-msg {
            color: var(--gray);
            font-size: 0.88rem;
            line-height: 1.5;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
<div class="container">

    <h1>Registrar Nuevo Usuario</h1>

    <!-- Mensaje de error del servidor si lo hay -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

     <form action="NuevoUsuarioController.php" method="POST" id="registroForm" novalidate>

        <div class="form-row">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" id="nombre">
            </div>
            <div class="form-group">
                <label>Apellido:</label>
                <input type="text" name="apellido" id="apellido">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nombre de Usuario (Login):</label>
                <input type="text" name="usuario_nombre" id="usuario_nombre">
            </div>
            <div class="form-group">
                <label>Correo Electrónico:</label>
                <input type="email" name="correo" id="correo">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="contrasena" id="contrasena" autocomplete="new-password">
                <div class="char-counter" id="counter1">
                    <span id="counter1-text">0/8 · falta un número</span>
                    <div class="bar-track"><div class="bar-fill" id="bar1" style="width:0%"></div></div>
                </div>
            </div>
            <div class="form-group">
                <label>Confirmar Contraseña:</label>
                <input type="password" name="confirmar_contrasena" id="confirmar_contrasena" autocomplete="new-password">
                <div class="char-counter" id="counter2">
                    <span id="counter2-text">0/8 · falta un número</span>
                    <div class="bar-track"><div class="bar-fill" id="bar2" style="width:0%"></div></div>
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Tipo de Usuario:</label>
                <select name="tipo_usuario" id="tipo_usuario" style="width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid #dcd3cb; background: #fff; transition: 0.3s; box-sizing: border-box;">
                    <option value="cliente">Cliente</option>
                    <option value="empleado">Empleado</option>
                </select>
            </div>
            <div class="form-group" id="rol_group" style="display: none;">
                <label>Rol:</label>
                <select name="especialidad" id="especialidad" style="width: 100%; padding: 12px 15px; border-radius: 10px; border: 1px solid #dcd3cb; background: #fff; transition: 0.3s; box-sizing: border-box;">
                    <option value="">Seleccione un rol...</option>
                    <?php foreach ($roles_empleado as $rol): ?>
                        <option value="<?php echo htmlspecialchars($rol['nombre']); ?>"><?php echo htmlspecialchars($rol['nombre']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="button-group">
            <a href="../Controllers/admin_usuariosController.php" class="btn btn-secondary">Cancelar y Volver</a>
            <button type="submit" class="btn btn-primary">Registrar Usuario</button>
        </div>

    </form>
</div>

<!-- Modal de validación -->
<div id="modal-validacion" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-icon">⚠️</div>
        <h3 class="modal-title" id="modal-val-titulo">Atención</h3>
        <p class="modal-msg" id="modal-val-msg">Completá todos los campos.</p>
        <button onclick="cerrarModalVal()" class="btn btn-primary" style="width:100%;">Entendido</button>
    </div>
</div>

<script>
// === Tipo de usuario ===
document.getElementById('tipo_usuario').addEventListener('change', function() {
    const rolGroup = document.getElementById('rol_group');
    const especialidadSelect = document.getElementById('especialidad');
    if (this.value === 'empleado') {
        rolGroup.style.display = 'block';
        especialidadSelect.required = true;
    } else {
        rolGroup.style.display = 'none';
        especialidadSelect.required = false;
        especialidadSelect.value = '';
    }
});

// === Contador de caracteres ===
const MIN_CHARS = 8;

function actualizarContador(inputId, counterId, textId, barId) {
    const input = document.getElementById(inputId);
    const counter = document.getElementById(counterId);
    const text = document.getElementById(textId);
    const bar = document.getElementById(barId);

    input.addEventListener('input', function() {
        const len = this.value.length;
        const tieneNumero = /[0-9]/.test(this.value);
        const pct = Math.min((len / MIN_CHARS) * 100, 100);
        bar.style.width = pct + '%';

        if (len >= MIN_CHARS && tieneNumero) {
            text.textContent = '✓ Contraseña válida';
            counter.classList.add('ok');
        } else if (len < MIN_CHARS && !tieneNumero) {
            text.textContent = len + '/8 · falta un número';
            counter.classList.remove('ok');
        } else if (len < MIN_CHARS) {
            text.textContent = len + '/8 caracteres mínimos';
            counter.classList.remove('ok');
        } else {
            text.textContent = 'Falta al menos un número (0-9)';
            counter.classList.remove('ok');
        }
    });
}

actualizarContador('contrasena', 'counter1', 'counter1-text', 'bar1');
actualizarContador('confirmar_contrasena', 'counter2', 'counter2-text', 'bar2');

// === Modal de error ===
function mostrarError(titulo, mensaje) {
    document.getElementById('modal-val-titulo').textContent = titulo;
    document.getElementById('modal-val-msg').textContent = mensaje;
    document.getElementById('modal-validacion').style.display = 'flex';
}

function cerrarModalVal() {
    document.getElementById('modal-validacion').style.display = 'none';
}

// Cerrar al click fuera
document.getElementById('modal-validacion').addEventListener('click', function(e) {
    if (e.target === this) cerrarModalVal();
});

// === Validación del formulario ===
document.getElementById('registroForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const campos = [
        { id: 'nombre', label: 'Nombre' },
        { id: 'apellido', label: 'Apellido' },
        { id: 'usuario_nombre', label: 'Nombre de usuario' },
        { id: 'correo', label: 'Correo electrónico' },
        { id: 'contrasena', label: 'Contraseña' },
        { id: 'confirmar_contrasena', label: 'Confirmación de contraseña' },
    ];

    // Limpiar errores previos
    campos.forEach(c => document.getElementById(c.id).classList.remove('input-error'));

    // Verificar campos vacíos
    for (const campo of campos) {
        const el = document.getElementById(campo.id);
        if (!el.value.trim()) {
            el.classList.add('input-error');
            el.focus();
            mostrarError('Campo requerido', 'El campo "' + campo.label + '" es obligatorio.');
            return;
        }
    }

    // Verificar correo con @
    const correo = document.getElementById('correo').value;
    if (!correo.includes('@') || !correo.includes('.')) {
        document.getElementById('correo').classList.add('input-error');
        mostrarError('Correo inválido', 'Ingresá un correo electrónico válido (ej: nombre@dominio.com).');
        return;
    }

    // Verificar longitud mínima de contraseña
    const pass = document.getElementById('contrasena').value;
    if (pass.length < MIN_CHARS) {
        document.getElementById('contrasena').classList.add('input-error');
        mostrarError('Contraseña muy corta', 'La contraseña debe tener al menos ' + MIN_CHARS + ' caracteres y contener al menos un número.');
        return;
    }

    // Verificar que tenga al menos un número
    if (!/[0-9]/.test(pass)) {
        document.getElementById('contrasena').classList.add('input-error');
        mostrarError('Contraseña inválida', 'La contraseña debe contener al menos un número (0-9).');
        return;
    }

    // Verificar que coincidan
    const confirmar = document.getElementById('confirmar_contrasena').value;
    if (pass !== confirmar) {
        document.getElementById('confirmar_contrasena').classList.add('input-error');
        mostrarError('Contraseñas no coinciden', 'Las contraseñas ingresadas no son iguales. Verificalas.');
        return;
    }

    // Verificar formato alfanumérico
    const regex = /^[a-zA-Z0-9]+$/;
    if (!regex.test(pass)) {
        document.getElementById('contrasena').classList.add('input-error');
        mostrarError('Contraseña inválida', 'La contraseña solo puede contener letras y números (sin espacios ni caracteres especiales).');
        return;
    }

    // Si todo OK, enviar
    this.submit();
});
</script>
</body>
</html>