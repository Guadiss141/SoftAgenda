<?php
/*
 * ayuda_footer.php — Módulo de ayuda de Beauty SPA
 * --------------------------------------------------
 * Pie de página reutilizable con preguntas frecuentes, contacto y redes sociales.
 *
 * USO: incluirlo justo antes de </body> en cada vista:
 *     <?php include __DIR__ . '/../Views/ayuda_footer.php'; ?>
 *
 * Los textos, datos de contacto y redes se editan desde la pantalla del administrador
 * (módulo "Ayuda"). No hay que tocar este archivo.
 */

require_once __DIR__ . '/../Ayuda/AyudaModel.php';

// Los datos vienen de la base de datos (se editan desde Administrador → Ayuda).
// Si todavía no se configuró nada o la base falla, se usan los textos por defecto.
$ayuda_datos    = AyudaModel::cargarParaFooter();
$ayuda_faq      = $ayuda_datos['faq'];       // filas con Pregunta / Respuesta
$ayuda_contacto = $ayuda_datos['config'];    // telefono, email, direccion, horario, whatsapp, instagram, facebook

$ayuda_wa = AyudaModel::soloDigitos($ayuda_contacto['whatsapp'] ?? '');
$ayuda_redes = [
    'instagram' => AyudaModel::urlSegura($ayuda_contacto['instagram'] ?? ''),
    'facebook'  => AyudaModel::urlSegura($ayuda_contacto['facebook'] ?? ''),
    'whatsapp'  => $ayuda_wa !== '' ? 'https://wa.me/' . $ayuda_wa : '',
];

$ayuda_hay_faq      = !empty($ayuda_faq);
$ayuda_hay_contacto = !empty($ayuda_contacto['telefono']) || !empty($ayuda_contacto['email'])
                   || !empty($ayuda_contacto['direccion']) || !empty($ayuda_contacto['horario']);
$ayuda_hay_redes    = !empty(array_filter($ayuda_redes));

// Solo se muestran las columnas que tienen contenido
$ayuda_cols = [];
if ($ayuda_hay_faq)      $ayuda_cols[] = '1.6fr';
if ($ayuda_hay_contacto) $ayuda_cols[] = '1fr';
if ($ayuda_hay_redes)    $ayuda_cols[] = '0.8fr';
$ayuda_cols = $ayuda_cols ? implode(' ', $ayuda_cols) : '1fr';

$ayuda_h = function ($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); };

// Íconos SVG (no dependen de librerías externas)
$ayuda_iconos = [
    'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5A4.5 4.5 0 1 1 7.5 12 4.5 4.5 0 0 1 12 7.5zm0 2A2.5 2.5 0 1 0 14.5 12 2.5 2.5 0 0 0 12 9.5zM17.2 5.8a1 1 0 1 1-1 1 1 1 0 0 1 1-1z"/></svg>',
    'facebook'  => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 22v-8.2h2.8l.5-3.3h-3.3V8.4c0-.9.3-1.6 1.7-1.6H17V3.9c-.3 0-1.3-.1-2.4-.1-2.5 0-4.1 1.5-4.1 4.2v2.5H7.7v3.3h2.8V22h3z"/></svg>',
    'whatsapp'  => '<svg class="trazo" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-12.300 7.400L3 20.500l1.600-5.500A8.500 8.500 0 1 1 21 11.500z"/><path d="M9 9.500c.5 2 2.500 4 5 5l1.500-1.500-2-1-1 .8c-.8-.4-1.500-1.100-1.900-1.900l.8-1-1-2z"/></svg>',
];
?>
<style>
    /* ══════════ MÓDULO DE AYUDA (todo con prefijo .spa-ayuda para no chocar con otros estilos) ══════════ */
    .spa-ayuda {
        position: relative;
        z-index: 1;                 /* queda por encima del overlay del inicio */
        width: 100%;
        max-width: 1100px;
        margin: 48px auto 0;
        padding: 0 16px 24px;
        font-family: 'DM Sans', sans-serif;
        color: #1a1a1a;
        text-align: left;
    }

    .spa-ayuda *,
    .spa-ayuda *::before,
    .spa-ayuda *::after { box-sizing: border-box; }

    .spa-ayuda__card {
        background: #fff;
        border-radius: 32px;
        padding: 36px 40px 28px;
        box-shadow: 0 8px 40px rgba(232, 121, 160, 0.18);
        transition: background 0.3s, color 0.3s;
    }

    .spa-ayuda__grid {
        display: grid;
        grid-template-columns: var(--ayuda-cols, 1.6fr 1fr 0.8fr);
        gap: 40px;
    }

    .spa-ayuda h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: #e879a0;
        margin: 0 0 16px;
    }

    /* ── Preguntas frecuentes (acordeón sin JS) ── */
    .spa-ayuda__faq details {
        border-bottom: 1px solid #f5d4e0;
        padding: 12px 0;
    }
    .spa-ayuda__faq details:first-of-type { padding-top: 0; }

    .spa-ayuda__faq summary {
        cursor: pointer;
        list-style: none;
        font-weight: 500;
        font-size: 0.95rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }
    .spa-ayuda__faq summary::-webkit-details-marker { display: none; }
    .spa-ayuda__faq summary::after {
        content: '+';
        font-size: 1.3rem;
        line-height: 1;
        color: #e879a0;
        transition: transform 0.25s;
        flex-shrink: 0;
    }
    .spa-ayuda__faq details[open] summary::after { transform: rotate(45deg); }
    .spa-ayuda__faq summary:hover { color: #e879a0; }
    .spa-ayuda__faq summary:focus-visible { outline: 2px solid #e879a0; outline-offset: 4px; border-radius: 4px; }

    .spa-ayuda__faq p {
        margin: 10px 0 0;
        font-size: 0.9rem;
        line-height: 1.6;
        color: #666;
    }

    /* ── Contacto ── */
    .spa-ayuda__contacto ul { list-style: none; margin: 0; padding: 0; }
    .spa-ayuda__contacto li {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        font-size: 0.9rem;
        line-height: 1.5;
        color: #555;
        margin-bottom: 12px;
    }
    .spa-ayuda__contacto li strong { display: block; color: #1a1a1a; font-weight: 500; font-size: 0.8rem; }
    .spa-ayuda__contacto a { color: inherit; text-decoration: none; }
    .spa-ayuda__contacto a:hover { color: #e879a0; }
    .spa-ayuda__contacto .spa-ayuda__ico { color: #e879a0; flex-shrink: 0; margin-top: 2px; }

    /* ── Redes ── */
    .spa-ayuda__redes p { font-size: 0.9rem; color: #666; line-height: 1.5; margin: 0 0 16px; }
    .spa-ayuda__redes-lista { display: flex; gap: 10px; flex-wrap: wrap; }
    .spa-ayuda__redes-lista a {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #fde8f0;
        color: #e879a0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s;
    }
    .spa-ayuda__redes-lista a:hover { background: #e879a0; color: #fff; transform: translateY(-2px); }
    .spa-ayuda__redes-lista svg { width: 20px; height: 20px; fill: currentColor; }
    .spa-ayuda__redes-lista svg.trazo { fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    .spa-ayuda__ico { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    .spa-ayuda__copy {
        text-align: center;
        font-size: 0.8rem;
        color: #999;
        margin-top: 28px;
        padding-top: 18px;
        border-top: 1px solid #f5d4e0;
    }

    /* ── Tema oscuro (usa la misma clase que el resto del proyecto) ── */
    html.tema-oscuro .spa-ayuda { color: #eee; }
    html.tema-oscuro .spa-ayuda__card { background: #1e1e26; box-shadow: 0 8px 40px rgba(0, 0, 0, 0.4); }
    html.tema-oscuro .spa-ayuda__faq details,
    html.tema-oscuro .spa-ayuda__copy { border-color: #33333d; }
    html.tema-oscuro .spa-ayuda__faq p,
    html.tema-oscuro .spa-ayuda__redes p,
    html.tema-oscuro .spa-ayuda__contacto li { color: #b5b5c0; }
    html.tema-oscuro .spa-ayuda__contacto li strong { color: #eee; }
    html.tema-oscuro .spa-ayuda__redes-lista a { background: #2c2c36; }
    html.tema-oscuro .spa-ayuda__redes-lista a:hover { background: #e879a0; }

    /* ── Responsive ── */
    @media (max-width: 900px) {
        .spa-ayuda__grid { grid-template-columns: 1fr; gap: 28px; }
        .spa-ayuda__card { padding: 28px 24px 22px; border-radius: 24px; }
    }
    @media (max-width: 600px) {
        .spa-ayuda { margin-top: 32px; }
    }
</style>

<footer class="spa-ayuda" id="ayuda" aria-label="Ayuda y contacto">
    <div class="spa-ayuda__card">
        <div class="spa-ayuda__grid" style="--ayuda-cols: <?= $ayuda_h($ayuda_cols) ?>;">

            <!-- Preguntas frecuentes -->
            <?php if ($ayuda_hay_faq): ?>
            <section class="spa-ayuda__faq">
                <h3>Preguntas frecuentes</h3>
                <?php foreach ($ayuda_faq as $item): ?>
                    <details>
                        <summary><?= $ayuda_h($item['Pregunta']) ?></summary>
                        <p><?= nl2br($ayuda_h($item['Respuesta'])) ?></p>
                    </details>
                <?php endforeach; ?>
            </section>
            <?php endif; ?>

            <!-- Contacto -->
            <?php if ($ayuda_hay_contacto): ?>
            <section class="spa-ayuda__contacto">
                <h3>Contacto</h3>
                <ul>
                    <?php if (!empty($ayuda_contacto['telefono'])): ?>
                        <li>
                            <svg class="spa-ayuda__ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <div><strong>Teléfono</strong>
                                <a href="tel:<?= $ayuda_h(preg_replace('/[^0-9+]/', '', $ayuda_contacto['telefono'])) ?>"><?= $ayuda_h($ayuda_contacto['telefono']) ?></a>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($ayuda_contacto['email'])): ?>
                        <li>
                            <svg class="spa-ayuda__ico" viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg>
                            <div><strong>Email</strong>
                                <a href="mailto:<?= $ayuda_h($ayuda_contacto['email']) ?>"><?= $ayuda_h($ayuda_contacto['email']) ?></a>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($ayuda_contacto['direccion'])): ?>
                        <li>
                            <svg class="spa-ayuda__ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <div><strong>Dirección</strong><?= $ayuda_h($ayuda_contacto['direccion']) ?></div>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($ayuda_contacto['horario'])): ?>
                        <li>
                            <svg class="spa-ayuda__ico" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            <div><strong>Horario</strong><?= $ayuda_h($ayuda_contacto['horario']) ?></div>
                        </li>
                    <?php endif; ?>
                </ul>
            </section>
            <?php endif; ?>

            <!-- Redes sociales -->
            <?php if ($ayuda_hay_redes): ?>
            <section class="spa-ayuda__redes">
                <h3>Seguinos</h3>
                <p>Novedades, promociones y mucho relax.</p>
                <div class="spa-ayuda__redes-lista">
                    <?php foreach ($ayuda_redes as $nombre => $url): ?>
                        <?php if (!empty($url) && isset($ayuda_iconos[$nombre])): ?>
                            <a href="<?= $ayuda_h($url) ?>" target="_blank" rel="noopener noreferrer"
                               aria-label="<?= $ayuda_h(ucfirst($nombre)) ?>" title="<?= $ayuda_h(ucfirst($nombre)) ?>">
                                <?= $ayuda_iconos[$nombre] ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

        </div>

        <div class="spa-ayuda__copy">© <?= date('Y') ?> Beauty SPA · Todos los derechos reservados</div>
    </div>
</footer>
