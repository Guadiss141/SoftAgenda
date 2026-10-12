<?php
/*
 * AyudaController.php — Pantalla del administrador para configurar el módulo de ayuda
 * (preguntas frecuentes, datos de contacto y redes sociales).
 */
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(0, '/');
    session_start();
}

// Solo el administrador (rol 3)
if (!isset($_SESSION['id_Rol']) || $_SESSION['id_Rol'] != 3) {
    header("Location: /Spaa/Login/LoginControlls.php");
    exit();
}

require_once __DIR__ . '/AyudaModel.php';

$conexion = new mysqli("localhost", "root", "345756", "gestionspabd");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$conexion->set_charset('utf8mb4');

$model = new AyudaModel($conexion);
$model->asegurarTablas();   // crea las tablas y carga los textos iniciales la primera vez

// ── Token CSRF ──
if (empty($_SESSION['csrf_ayuda'])) {
    $_SESSION['csrf_ayuda'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf_ayuda'];

function ayuda_flash($tipo, $mensaje) {
    $_SESSION['ayuda_flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}
function ayuda_volver() {
    header("Location: AyudaController.php");
    exit();
}
function ayuda_largo($texto) {
    return function_exists('mb_strlen') ? mb_strlen($texto, 'UTF-8') : strlen($texto);
}

// Valida pregunta / respuesta / orden de una FAQ. Devuelve [datos, errores].
function ayuda_validar_faq($post) {
    $pregunta  = trim($post['pregunta']  ?? '');
    $respuesta = trim($post['respuesta'] ?? '');
    $orden     = (int)($post['orden'] ?? 0);
    $activo    = isset($post['activo']) ? 1 : 0;
    $errores   = [];

    if ($pregunta === '' || ayuda_largo($pregunta) > 200) {
        $errores[] = 'La pregunta es obligatoria y puede tener hasta 200 caracteres.';
    }
    if ($respuesta === '' || ayuda_largo($respuesta) > 1000) {
        $errores[] = 'La respuesta es obligatoria y puede tener hasta 1000 caracteres.';
    }
    if ($orden < 0 || $orden > 9999) {
        $errores[] = 'El orden debe estar entre 0 y 9999.';
    }
    return [[$pregunta, $respuesta, $orden, $activo], $errores];
}

/* ═════════ Procesar formularios (POST → redirección) ═════════ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!hash_equals($csrf, $_POST['csrf'] ?? '')) {
        ayuda_flash('danger', 'La sesión del formulario expiró. Volvé a intentarlo.');
        ayuda_volver();
    }

    $accion = $_POST['accion'] ?? '';

    switch ($accion) {

        case 'guardar_config':
            $errores = [];
            $datos = [];
            foreach (AyudaModel::CAMPOS as $campo => $max) {
                $datos[$campo] = trim($_POST[$campo] ?? '');
                if (ayuda_largo($datos[$campo]) > $max) {
                    $errores[] = "El campo «$campo» supera los $max caracteres.";
                }
            }
            if ($datos['email'] !== '' && !filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'El email no tiene un formato válido.';
            }
            $datos['whatsapp'] = AyudaModel::soloDigitos($datos['whatsapp']);
            if ($datos['whatsapp'] !== '' && (strlen($datos['whatsapp']) < 8 || strlen($datos['whatsapp']) > 15)) {
                $errores[] = 'El WhatsApp debe tener entre 8 y 15 dígitos (con código de país, sin + ni espacios).';
            }
            foreach (['instagram', 'facebook'] as $red) {
                if ($datos[$red] !== '' && AyudaModel::urlSegura($datos[$red]) === '') {
                    $errores[] = "El enlace de " . ucfirst($red) . " debe empezar con http:// o https://";
                }
            }

            if ($errores) {
                ayuda_flash('danger', implode(' ', $errores));
            } elseif ($model->guardarConfig($datos)) {
                ayuda_flash('success', 'Contacto y redes guardados correctamente.');
            } else {
                ayuda_flash('danger', 'No se pudo guardar el contacto. Intentá de nuevo.');
            }
            ayuda_volver();
            break;

        case 'crear_faq':
            [$d, $errores] = ayuda_validar_faq($_POST);
            if ($errores) {
                ayuda_flash('danger', implode(' ', $errores));
            } elseif ($model->crearFaq($d[0], $d[1], $d[2], $d[3])) {
                ayuda_flash('success', 'Pregunta agregada.');
            } else {
                ayuda_flash('danger', 'No se pudo agregar la pregunta.');
            }
            ayuda_volver();
            break;

        case 'actualizar_faq':
            $id = (int)($_POST['id_Faq'] ?? 0);
            [$d, $errores] = ayuda_validar_faq($_POST);
            if ($id <= 0) {
                ayuda_flash('danger', 'Pregunta no válida.');
            } elseif ($errores) {
                ayuda_flash('danger', implode(' ', $errores));
            } elseif ($model->actualizarFaq($id, $d[0], $d[1], $d[2], $d[3])) {
                ayuda_flash('success', 'Pregunta actualizada.');
            } else {
                ayuda_flash('danger', 'No se pudo actualizar la pregunta.');
            }
            ayuda_volver();
            break;

        case 'eliminar_faq':
            $id = (int)($_POST['id_Faq'] ?? 0);
            if ($id > 0 && $model->eliminarFaq($id)) {
                ayuda_flash('success', 'Pregunta eliminada.');
            } else {
                ayuda_flash('danger', 'No se pudo eliminar la pregunta.');
            }
            ayuda_volver();
            break;

        default:
            ayuda_volver();
    }
}

/* ═════════ Mostrar ═════════ */
$flash = $_SESSION['ayuda_flash'] ?? null;
unset($_SESSION['ayuda_flash']);

$faqs   = $model->obtenerFaq(false);
$config = $model->obtenerConfig();
$conexion->close();

require_once __DIR__ . '/ayuda_admin_view.php';
