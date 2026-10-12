<?php
// TemaHead.php
// Incluir este archivo DENTRO del <head>, antes de cerrarlo, en CADA vista del proyecto.
// Requiere que $_SESSION ya esté iniciada (session_start() en la vista o en un bootstrap).
$id_usuario_tema = $_SESSION['id_Usuario'] ?? null; // ajustar a la clave real de tu sesión
?>
<script>
    const USER_ID = <?= json_encode($id_usuario_tema) ?>;
</script>
<script src="/Spaa/Tema/TemaUsuario.js"></script>
<script>

    if (typeof TemaCache !== 'undefined') {
        TemaCache.aplicarTema(TemaCache.obtenerTemaGuardado());
    }
</script>