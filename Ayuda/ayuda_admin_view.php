<?php
$h = function ($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo de Ayuda - Beauty SPA</title>
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
        * { box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            margin: 0;
            padding: 0 16px 24px;
            background: var(--cream);
            color: var(--dark);
            min-height: 100vh;
            transition: background 0.3s, color 0.3s;
        }

        .container {
            max-width: 900px;
            margin: 40px auto 0;
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
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 25px;
            border-bottom: 2px solid var(--pink-pale);
            padding-bottom: 10px;
        }
        .header-flex h1 {
            font-family: 'Playfair Display', serif;
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

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            color: var(--pink);
            margin: 32px 0 6px;
        }
        .section-hint { color: var(--gray); font-size: 0.85rem; margin: 0 0 18px; line-height: 1.5; }

        .card {
            border: 1px solid #f5d4e0;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 16px;
            background: #fffafc;
            transition: background 0.3s, border-color 0.3s;
        }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 18px; }
        .form-group { display: flex; flex-direction: column; gap: 6px; }
        .form-group.full { grid-column: 1 / -1; }
        .form-group label { font-size: 0.78rem; font-weight: 600; color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px; }
        .form-group small { font-size: 0.75rem; color: var(--gray); }

        input[type="text"], input[type="email"], input[type="url"], input[type="number"], textarea {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--gray-mid);
            border-radius: 12px;
            font-family: inherit;
            font-size: 0.92rem;
            background: #fff;
            color: var(--dark);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        textarea { resize: vertical; min-height: 80px; }
        input:focus, textarea:focus { outline: none; border-color: var(--pink); box-shadow: 0 0 0 3px rgba(232,121,160,0.18); }

        .faq-row { display: grid; grid-template-columns: 1fr 90px; gap: 14px; }
        .faq-row .full { grid-column: 1 / -1; }
        .faq-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-top: 14px; }
        .check-inline { display: inline-flex; align-items: center; gap: 8px; font-size: 0.88rem; margin-right: auto; cursor: pointer; }
        input[type="checkbox"] { width: 18px; height: 18px; accent-color: var(--pink); cursor: pointer; }

        .faq-inactiva { opacity: 0.7; border-style: dashed; }
        .estado-tag { font-size: 0.7rem; background: var(--gray-pale); color: var(--gray); padding: 3px 10px; border-radius: 12px; margin-left: 8px; font-weight: 500; }

        .btn {
            display: inline-block;
            padding: 10px 22px;
            border-radius: 30px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            font-family: inherit;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-align: center;
        }
        .btn-primary { background: var(--pink); color: #fff; box-shadow: 0 4px 6px rgba(232,121,160,0.3); }
        .btn-secondary { background: var(--gray-pale); color: var(--dark); border: 1.5px solid var(--gray-mid); }
        .btn-danger { background: var(--red-pale); color: var(--red); border: 1.5px solid var(--red); }
        .btn:hover { opacity: 0.9; transform: translateY(-2px); }

        .button-group { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; margin-top: 30px; }

        .alert { padding: 15px; border-radius: 10px; margin-bottom: 20px; line-height: 1.5; }
        .alert-success { background: var(--green-pale); color: var(--green); border-left: 4px solid var(--green); }
        .alert-danger  { background: var(--red-pale); color: var(--red); border-left: 4px solid var(--red); }

        @media (max-width: 640px) {
            .container { padding: 24px 18px; border-radius: 24px; margin-top: 20px; }
            .form-grid { grid-template-columns: 1fr; }
            .faq-row { grid-template-columns: 1fr; }
        }

        /* ════════ TEMA OSCURO ════════ */
        html.tema-oscuro body { background: #15151a; color: #eee; }
        html.tema-oscuro .container { background: #1e1e24; box-shadow: 0 8px 40px rgba(0,0,0,0.4); }
        html.tema-oscuro .header-flex { border-bottom-color: rgba(232,121,160,0.25); }
        html.tema-oscuro .badge-rol { background: rgba(232,121,160,0.15); border-color: rgba(232,121,160,0.3); }
        html.tema-oscuro .card { background: #24242c; border-color: #33333d; }
        html.tema-oscuro input[type="text"], html.tema-oscuro input[type="email"],
        html.tema-oscuro input[type="url"], html.tema-oscuro input[type="number"],
        html.tema-oscuro textarea { background: #1a1a20; border-color: #3a3a42; color: #eee; }
        html.tema-oscuro .btn-secondary { background: #2a2a31; color: #eee; border-color: #3a3a42; }
        html.tema-oscuro .btn-danger { background: rgba(224,85,85,0.15); }
        html.tema-oscuro .estado-tag { background: #2a2a31; }
        html.tema-oscuro .alert-success { background: rgba(90,158,111,0.15); color: #8fc9a0; }
        html.tema-oscuro .alert-danger { background: rgba(224,85,85,0.15); color: #f19d9d; }
    </style>
    <?php include __DIR__ . '/../Tema/TemaHead.php'; ?>
</head>
<body>

<div class="container">
    <div class="header-flex">
        <h1>Módulo de Ayuda</h1>
        <div class="badge-rol">Módulo Administrador</div>
    </div>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= $flash['tipo'] === 'success' ? 'success' : 'danger' ?>">
            <?= $h($flash['mensaje']) ?>
        </div>
    <?php endif; ?>

    <!-- ══════════ CONTACTO Y REDES ══════════ -->
    <h2 class="section-title">Contacto y redes sociales</h2>
    <p class="section-hint">Se muestran en el pie de todas las páginas. Dejá un campo vacío para ocultarlo.</p>

    <form method="POST" action="AyudaController.php" class="card" autocomplete="off">
        <input type="hidden" name="csrf" value="<?= $h($csrf) ?>">
        <input type="hidden" name="accion" value="guardar_config">

        <div class="form-grid">
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" maxlength="40" value="<?= $h($config['telefono']) ?>" placeholder="+54 370 000-0000">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" maxlength="120" value="<?= $h($config['email']) ?>" placeholder="contacto@tuspa.com">
            </div>
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" maxlength="160" value="<?= $h($config['direccion']) ?>" placeholder="Calle 123, Ciudad">
            </div>
            <div class="form-group">
                <label for="horario">Horario</label>
                <input type="text" id="horario" name="horario" maxlength="120" value="<?= $h($config['horario']) ?>" placeholder="Lun a Vie · 9:00 a 19:00">
            </div>
            <div class="form-group">
                <label for="whatsapp">WhatsApp</label>
                <input type="text" id="whatsapp" name="whatsapp" maxlength="20" inputmode="numeric" value="<?= $h($config['whatsapp']) ?>" placeholder="543700000000">
                <small>Solo números, con código de país (sin + ni espacios).</small>
            </div>
            <div class="form-group">
                <label for="instagram">Instagram</label>
                <input type="url" id="instagram" name="instagram" maxlength="255" value="<?= $h($config['instagram']) ?>" placeholder="https://www.instagram.com/tu_cuenta">
            </div>
            <div class="form-group full">
                <label for="facebook">Facebook</label>
                <input type="url" id="facebook" name="facebook" maxlength="255" value="<?= $h($config['facebook']) ?>" placeholder="https://www.facebook.com/tu_pagina">
            </div>
        </div>

        <div class="faq-actions">
            <button type="submit" class="btn btn-primary" style="margin-left:auto;">Guardar contacto y redes</button>
        </div>
    </form>

    <!-- ══════════ PREGUNTAS FRECUENTES ══════════ -->
    <h2 class="section-title">Preguntas frecuentes</h2>
    <p class="section-hint">Se ordenan de menor a mayor según el número de orden. Las preguntas desactivadas no se muestran al público.</p>

    <?php if (empty($faqs)): ?>
        <p class="section-hint">Todavía no hay preguntas. Agregá la primera abajo.</p>
    <?php endif; ?>

    <?php foreach ($faqs as $f): ?>
        <form method="POST" action="AyudaController.php" class="card <?= $f['Activo'] ? '' : 'faq-inactiva' ?>">
            <input type="hidden" name="csrf" value="<?= $h($csrf) ?>">
            <input type="hidden" name="id_Faq" value="<?= (int)$f['id_Faq'] ?>">

            <div class="faq-row">
                <div class="form-group">
                    <label>Pregunta <?php if (!$f['Activo']): ?><span class="estado-tag">Oculta</span><?php endif; ?></label>
                    <input type="text" name="pregunta" maxlength="200" required value="<?= $h($f['Pregunta']) ?>">
                </div>
                <div class="form-group">
                    <label>Orden</label>
                    <input type="number" name="orden" min="0" max="9999" value="<?= (int)$f['Orden'] ?>">
                </div>
                <div class="form-group full">
                    <label>Respuesta</label>
                    <textarea name="respuesta" maxlength="1000" required><?= $h($f['Respuesta']) ?></textarea>
                </div>
            </div>

            <div class="faq-actions">
                <label class="check-inline" style="order:1;">
                    <input type="checkbox" name="activo" value="1" <?= $f['Activo'] ? 'checked' : '' ?>> Mostrar al público
                </label>
                <button type="submit" name="accion" value="actualizar_faq" class="btn btn-primary" style="order:3;">Guardar</button>
                <button type="submit" name="accion" value="eliminar_faq" class="btn btn-danger" style="order:2;" formnovalidate
                        onclick="return confirm('¿Eliminar esta pregunta? No se puede deshacer.');">Eliminar</button>
            </div>
        </form>
    <?php endforeach; ?>

    <!-- Agregar nueva -->
    <h3 class="section-title" style="font-size:1.05rem;margin-top:24px;">Agregar una pregunta</h3>
    <form method="POST" action="AyudaController.php" class="card" autocomplete="off">
        <input type="hidden" name="csrf" value="<?= $h($csrf) ?>">
        <input type="hidden" name="accion" value="crear_faq">

        <div class="faq-row">
            <div class="form-group">
                <label for="nueva_pregunta">Pregunta</label>
                <input type="text" id="nueva_pregunta" name="pregunta" maxlength="200" required placeholder="¿Cómo puedo…?">
            </div>
            <div class="form-group">
                <label for="nuevo_orden">Orden</label>
                <input type="number" id="nuevo_orden" name="orden" min="0" max="9999" value="<?= count($faqs) + 1 ?>">
            </div>
            <div class="form-group full">
                <label for="nueva_respuesta">Respuesta</label>
                <textarea id="nueva_respuesta" name="respuesta" maxlength="1000" required></textarea>
            </div>
        </div>

        <div class="faq-actions">
            <label class="check-inline">
                <input type="checkbox" name="activo" value="1" checked> Mostrar al público
            </label>
            <button type="submit" class="btn btn-primary">Agregar pregunta</button>
        </div>
    </form>

    <div class="button-group">
        <a href="/Spaa/Inicioo/Index.php#ayuda" class="btn btn-secondary">Ver cómo queda en el inicio</a>
        <a href="/Spaa/Inicioo/Index.php" class="btn btn-secondary">Volver al Inicio</a>
    </div>
</div>

<?php include __DIR__ . '/../Views/ayuda_footer.php'; ?>

</body>
</html>
