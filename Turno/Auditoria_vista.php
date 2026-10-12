<?php
// Traduce nombres de campos técnicos a algo legible
function nombreAmigable($campo) {
    $mapa = [
        'id_Paciente' => 'Paciente',
        'id_Servicio' => 'Servicio',
        'id_Empleado' => 'Terapeuta',
        'Fecha' => 'Fecha',
        'Fecha_Turno' => 'Fecha',
        'Hora' => 'Hora',
        'Hora_Turno' => 'Hora',
        'Estado_Turno' => 'Estado',
        'id_Turno' => 'Turno',
    ];
    return $mapa[$campo] ?? $campo;
}

// Arma el detalle legible según haya solo "nuevo", solo "anterior", o ambos (diff)
function detalleLegible($jsonAnterior, $jsonNuevo) {
    $anterior = $jsonAnterior ? json_decode($jsonAnterior, true) : null;
    $nuevo    = $jsonNuevo    ? json_decode($jsonNuevo, true)    : null;

    if (!is_array($anterior) && !is_array($nuevo)) {
        return '<span style="color:#aaa;">Sin datos adicionales</span>';
    }

    $lineas = [];

    // Caso: hay ambos -> mostrar solo lo que cambió
    if (is_array($anterior) && is_array($nuevo)) {
        $campos = array_unique(array_merge(array_keys($anterior), array_keys($nuevo)));
        foreach ($campos as $campo) {
            $valorAnt = $anterior[$campo] ?? null;
            $valorNue = $nuevo[$campo] ?? null;
            if ($valorAnt != $valorNue) {
                $lineas[] = "<strong>" . htmlspecialchars(nombreAmigable($campo)) . ":</strong> "
                          . htmlspecialchars($valorAnt ?? '—') . " → " . htmlspecialchars($valorNue ?? '—');
            }
        }
        if (empty($lineas)) {
            return '<span style="color:#aaa;">Sin cambios detectados</span>';
        }
    // Caso: solo nuevo (creación)
    } elseif (is_array($nuevo)) {
        foreach ($nuevo as $campo => $valor) {
            $lineas[] = "<strong>" . htmlspecialchars(nombreAmigable($campo)) . ":</strong> " . htmlspecialchars($valor ?? '—');
        }
    // Caso: solo anterior (eliminación)
    } elseif (is_array($anterior)) {
        foreach ($anterior as $campo => $valor) {
            $lineas[] = "<strong>" . htmlspecialchars(nombreAmigable($campo)) . ":</strong> " . htmlspecialchars($valor ?? '—');
        }
    }

    return implode('<br>', $lineas);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoría - Beauty SPA</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --pink: #e879a0;
            --pink-light: #f5b8d0;
            --pink-pale: #fde8f0;
            --cream: #fdf2f6;
            --dark: #1a1a1a;
            --gray: #888;
            --green: #5a9e6f;
            --green-pale: #eaf4ee;
            --red: #e05555;
            --red-pale: #fdeaea;
            --orange: #d98c3f;
            --orange-pale: #fbf1e6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            padding: 40px 20px;
            color: var(--dark);
            transition: background 0.3s, color 0.3s;
        }

        .wrap {
            max-width: 1100px;
            margin: 0 auto;
        }

        .titulo {
            text-align: center;
            margin-bottom: 32px;
        }

        .titulo h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            font-size: 2.4rem;
            color: var(--dark);
            letter-spacing: -0.5px;
        }

        .titulo h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            font-size: 2.4rem;
            color: var(--pink);
            letter-spacing: -0.5px;
        }

        .card {
            background: #fff;
            border-radius: 28px;
            padding: 32px;
            box-shadow: 0 8px 40px rgba(232,121,160,0.12);
            margin-bottom: 24px;
            transition: background 0.3s;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
        }

        .card-header h3 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--dark);
        }

        .count-badge {
            background: var(--pink-pale);
            color: var(--pink);
            font-size: 0.8rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            text-align: left;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--gray);
            padding: 10px 14px;
            border-bottom: 1.5px solid var(--pink-pale);
        }

        tbody td {
            padding: 14px;
            font-size: 0.9rem;
            color: var(--dark);
            border-bottom: 1px solid #f5f5f5;
            vertical-align: top;
        }

        tbody tr:hover {
            background: #fffafc;
        }

        .accion-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .accion-CREAR { background: var(--green-pale); color: var(--green); }
        .accion-ELIMINAR { background: var(--red-pale); color: var(--red); }
        .accion-CANCELAR { background: var(--red-pale); color: var(--red); }
        .accion-MODIFICAR { background: var(--orange-pale); color: var(--orange); }

        .detalle-toggle {
            background: none;
            border: none;
            color: var(--pink);
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: underline;
            padding: 0;
        }

        .detalle-json {
            display: none;
            margin-top: 10px;
            background: #fafafa;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 0.82rem;
            color: var(--dark);
            line-height: 1.7;
            max-width: 340px;
        }

        .detalle-json.show { display: block; }

        .detalle-json strong {
            color: var(--pink);
            font-weight: 600;
        }

        .empty {
            text-align: center;
            color: var(--gray);
            padding: 30px;
            font-size: 0.9rem;
        }

        .volver {
            display: block;
            text-align: center;
            background: #eee;
            color: var(--dark);
            text-decoration: none;
            padding: 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            transition: background 0.3s;
        }

        .volver:hover {
            background: #e2e2e2;
        }

        @media (max-width: 700px) {
            .titulo h1, .titulo h2 { font-size: 1.7rem; }
            .card { padding: 20px; border-radius: 20px; }
        }

        /* ════════════════════════
           TEMA OSCURO
           Se activa agregando la clase .tema-oscuro al <html>
        ════════════════════════ */
        html.tema-oscuro body {
            background: #15151a;
            color: #eee;
        }

        html.tema-oscuro .titulo h1 {
            color: #f2f2f2;
        }

        html.tema-oscuro .card {
            background: #1e1e24;
            box-shadow: 0 8px 40px rgba(0,0,0,0.3);
        }

        html.tema-oscuro .card-header h3 {
            color: #f2f2f2;
        }

        html.tema-oscuro .count-badge {
            background: rgba(232,121,160,0.15);
        }

        html.tema-oscuro thead th {
            color: #999;
            border-bottom-color: #2a2a31;
        }

        html.tema-oscuro tbody td {
            color: #eee;
            border-bottom-color: #2a2a31;
        }

        html.tema-oscuro tbody tr:hover {
            background: #26262e;
        }

        html.tema-oscuro .accion-CREAR {
            background: rgba(90,158,111,0.15);
            color: #8fc9a0;
        }

        html.tema-oscuro .accion-ELIMINAR,
        html.tema-oscuro .accion-CANCELAR {
            background: rgba(224,85,85,0.15);
            color: #f19d9d;
        }

        html.tema-oscuro .accion-MODIFICAR {
            background: rgba(217,140,63,0.15);
            color: #e8b482;
        }

        html.tema-oscuro .detalle-json {
            background: #26262e;
            color: #ccc;
        }

        html.tema-oscuro .empty {
            color: #666;
        }

        html.tema-oscuro .volver {
            background: #1e1e24;
            color: #ccc;
        }

        html.tema-oscuro .volver:hover {
            background: #26262e;
        }
    </style>
    <?php include __DIR__ . '/../Tema/TemaHead.php'; ?>
    <script>console.log('USER_ID:', typeof USER_ID !== 'undefined' ? USER_ID : 'NO DEFINIDO');</script>
</head>
<body>

<div class="wrap">
    <div class="titulo">
        <h1>AUDITORÍA</h1>
        <h2>HISTORIAL DE CAMBIOS</h2>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Registros</h3>
            <span class="count-badge"><?= count($registros) ?></span>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Fecha / Hora</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Turno</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($registros)): ?>
                        <tr>
                            <td colspan="5" class="empty">Todavía no hay registros de auditoría.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($registros as $i => $r): ?>
                        <tr>
                            <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($r['fecha_hora']))) ?></td>
                            <td><?= htmlspecialchars($r['nombre_usuario'] ?? ('Usuario #' . $r['id_usuario'])) ?></td>
                            <td>
                                <span class="accion-badge accion-<?= htmlspecialchars($r['accion']) ?>">
                                    <?= htmlspecialchars($r['accion']) ?>
                                </span>
                            </td>
                            <td>#<?= htmlspecialchars($r['id_turno']) ?></td>
                            <td>
                                <button class="detalle-toggle" onclick="toggleDetalle(<?= $i ?>)">Ver detalle</button>
                                <div class="detalle-json" id="detalle-<?= $i ?>">
                                    <?= detalleLegible($r['dato_anterior'], $r['dato_nuevo']) ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <a href="../Inicioo/Index.php" class="volver">← Volver al inicio</a>
</div>

<script>
function toggleDetalle(i) {
    document.getElementById('detalle-' + i).classList.toggle('show');
}
</script>

<?php include __DIR__ . '/../Views/ayuda_footer.php'; ?>

</body>
</html>