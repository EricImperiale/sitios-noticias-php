<?php
use DaVinci\Auth\Auth;
use DaVinci\Models\Comentario;
require_once __DIR__ . '/../bootstrap/init.php';

$id = $_POST['id'];
$comentario = $_POST['comentario'];
$usuario_id = (new Auth)->getId();

$errores = [];

if (empty($comentario)) {
    $errores['comentario'] = "El comentario no puede estar vació.";
}

if (count($errores) > 0) {
    $_SESSION['data-post'] = $_POST;
    $_SESSION['errores'] = $errores;

    header("Location: ../index.php?s=noticias-leer&id=" . $id);
    exit;
}

try {
    (new Comentario)->crear($id, $comentario, $usuario_id);

    $_SESSION['mensajeFeedback'] = "Tu comentario fue publicado con éxito.";

    header("Location: ../index.php?s=noticias-leer&id=" . $id);
    exit;
} catch (Exception $e) {
    $_SESSION['mensajeError'] = "Ocurrió un error al publicar tu comentario. Intentá más tarde.";

    header("Location: ../index.php?s=noticias-leer&id=" . $id);
    exit;
}
